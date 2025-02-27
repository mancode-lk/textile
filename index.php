<?php
include 'layouts/header.php';
include 'layouts/sidebar.php';

$u_id=$_SESSION['u_id'];
// Pagination Variables
$limit = 5; // Products per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
// Get Total Products Count
$sql_count = "SELECT COUNT(*) AS total FROM tbl_product";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

// Fetch Products with Pagination
$sql = "SELECT name AS product_name, price FROM tbl_product ORDER BY id DESC LIMIT $offset, $limit";
$rs = $conn->query($sql);

// Calculate Total Stock Value
$stock_value = 0;
$sql_products = "SELECT id, price, stock FROM tbl_product";
$rs_prod = $conn->query($sql_products);
while ($rowProd = $rs_prod->fetch_assoc()) {
    $p_id=$rowProd['id'];
    $stock_value += $rowProd['price'] * currentStockCount($conn,$p_id);
}

// Fetch Today's Sales Total
$tot_bill_dis_today = 0;
$today_date = date("Y-m-d");

$sql_today_sales = "
    SELECT o.grm_ref, o.quantity, p.price, g.discount_price
    FROM tbl_order o
    JOIN tbl_product p ON o.product_id = p.id
    JOIN tbl_order_grm g ON o.grm_ref = g.id
    WHERE DATE(g.order_date) = '$today_date'";

$rs_today_sales = $conn->query($sql_today_sales);
$order_totals_today = [];

while ($row = $rs_today_sales->fetch_assoc()) {
    $grm_ref = $row['grm_ref'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $discount = $row['discount_price'];

    // Track total purchase amount per order reference
    if (!isset($order_totals_today[$grm_ref])) {
        $order_totals_today[$grm_ref] = ['total_price' => 0, 'discount' => $discount];
    }
    $order_totals_today[$grm_ref]['total_price'] += ($quantity * $price);
}

foreach ($order_totals_today as $order) {
    $total_price = $order['total_price'];
    $discount = $order['discount'];

    // Apply discount proportionally across the order
    $tot_bill_dis_today += ($total_price - $discount);
}

// Calculate Total Cost of All Products
$total_cost_price = 0;
$sql_total_cost = "
    SELECT p.id, p.cost_price, 
           COALESCE(SUM(e.quantity), 0) AS total_quantity
    FROM tbl_product p
    LEFT JOIN tbl_expiry_date e ON p.id = e.product_id
    GROUP BY p.id, p.cost_price";

$rs_total_cost = $conn->query($sql_total_cost);
while ($row = $rs_total_cost->fetch_assoc()) {
    $total_cost_price += $row['cost_price'] * $row['total_quantity'];
}

// Fetch Today's Expenses
$sql_today_expenses = "SELECT SUM(amount) AS total FROM tbl_expenses WHERE DATE(expense_date) = '$today_date'";
$rs_today_expenses = $conn->query($sql_today_expenses);
$row_expense = $rs_today_expenses->fetch_assoc();
$tot_expenses_today = $row_expense['total'] ?? 0;

// Fetch Total Sales Value
$tot_bill_dis = 0;
$sql_orders = "
    SELECT o.grm_ref, o.quantity, p.price, g.discount_price
    FROM tbl_order o
    JOIN tbl_product p ON o.product_id = p.id
    JOIN tbl_order_grm g ON o.grm_ref = g.id";

$rs_orders = $conn->query($sql_orders);
$order_totals = [];



$sqlTotalValue = "SELECT 
            COALESCE(SUM(amount), 0) AS total_payments,
            (SELECT COALESCE(SUM(discount_amount), 0) FROM tbl_vendor_discounts) AS total_discounts,
            (COALESCE(SUM(amount), 0) - (SELECT COALESCE(SUM(discount_amount), 0) FROM tbl_vendor_discounts)) AS total_due
        FROM tbl_vendor_payments";

$rs_TotalValue = $conn->query($sqlTotalValue);
$row_total_value = $rs_TotalValue->fetch_assoc();

$total_payments = $row_total_value['total_payments'] ?? 0;
$total_discounts = $row_total_value['total_discounts'] ?? 0;
$total_value_price = $row_total_value['total_due'] ?? 0;

while ($row_order = $rs_orders->fetch_assoc()) {
    $grm_ref = $row_order['grm_ref'];
    $quantity = $row_order['quantity'];
    $price = $row_order['price'];
    $discount = $row_order['discount_price'];

    // Track total purchase amount per order reference
    if (!isset($order_totals[$grm_ref])) {
        $order_totals[$grm_ref] = ['total_price' => 0, 'discount' => $discount];
    }
    $order_totals[$grm_ref]['total_price'] += ($quantity * $price);
}

$tot_bill_dis = 0;

foreach ($order_totals as $order) {
    $total_price = $order['total_price'];
    $discount = $order['discount'];

    // Apply discount proportionally across the order
    $tot_bill_dis += ($total_price - $discount);
}

$till_balance_today = $tot_bill_dis_today - $tot_expenses_today;

// Calculate Payment Breakdown for Today
$total_payments_today = [
    'cash' => 0,
    'online' => 0,
    'bank' => 0,
    'credit' => 0
];

$sql_payment_today = "
    SELECT 
        g.payment_type, 
        SUM((p.price * o.quantity) - (g.discount_price * (p.price * o.quantity) / sub.total_purchase)) AS total_received
    FROM tbl_order o
    JOIN tbl_order_grm g ON g.id = o.grm_ref
    JOIN tbl_product p ON o.product_id = p.id
    JOIN (
        -- Get the total purchase amount per bill before applying discount
        SELECT o.grm_ref, SUM(p.price * o.quantity) AS total_purchase
        FROM tbl_order o
        JOIN tbl_product p ON o.product_id = p.id
        GROUP BY o.grm_ref
    ) sub ON sub.grm_ref = g.id
    WHERE DATE(g.order_date) = CURDATE()
    GROUP BY g.payment_type
";

$rs_payment_today = $conn->query($sql_payment_today);
$total_payments_today = ['cash' => 0, 'online' => 0, 'bank' => 0, 'credit' => 0];

while ($row = $rs_payment_today->fetch_assoc()) {
    switch ($row['payment_type']) {
        case 0: $total_payments_today['cash'] = $row['total_received']; break;
        case 1: $total_payments_today['online'] = $row['total_received']; break;
        case 2: $total_payments_today['bank'] = $row['total_received']; break;
        case 3: $total_payments_today['credit'] = $row['total_received']; break;
    }
}


// Fetch total expenses without a vendor
$sql_no_vendor_expenses = "SELECT SUM(amount) AS total FROM tbl_expenses WHERE DATE(expense_date) = '$today_date' AND category='petty_cash'";
$rs_no_vendor_expenses = $conn->query($sql_no_vendor_expenses);
$row_no_vendor_expenses = $rs_no_vendor_expenses->fetch_assoc();
$tot_no_vendor_expenses = $row_no_vendor_expenses['total'] ?? 0;

// Fetch total expenses with a vendor where payment method is 'Cash'
$sql_vendor_cash_expenses = "SELECT SUM(amount) AS total FROM tbl_vendor_payments 
                             WHERE DATE(payment_date) = '$today_date' 
                             AND payment_method = 'Cash'";
$rs_vendor_cash_expenses = $conn->query($sql_vendor_cash_expenses);
$row_vendor_cash_expenses = $rs_vendor_cash_expenses->fetch_assoc();
$tot_vendor_cash_expenses = $row_vendor_cash_expenses['total'] ?? 0;

// Calculate total expenses for today
$tot_expenses_today_cash = $tot_no_vendor_expenses + $tot_vendor_cash_expenses;

$till_balance = $total_payments_today['cash'] - $tot_expenses_today_cash;

?>
<style>
    .dashboard-card {
        transition: transform 0.3s ease;
        border: none;
        border-radius: 15px;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    .card-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 24px;
    }
    .metric-title {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: 600;
    }
    .section-title {
        font-weight: 600;
        color: #2c3e50;
        border-left: 4px solid #007bff;
        padding-left: 1rem;
        margin: 1.5rem 0;
    }
</style>

<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Dashboard Metrics Grid -->
        <div class="row g-4">
            <?php if($u_id == 1): ?>
            <!-- Financial Overview Section -->
            <div class="col-12">
                <h4 class="section-title">Financial Overview</h4>
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-primary text-white me-3">
                                        <i class="ri-archive-line"></i>
                                    </div>
                                    <div>
                                        <div class="metric-title">Total Stock Value</div>
                                        <div class="metric-value text-primary">
                                            Rs.<?= number_format($stock_value) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-success text-white me-3">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                    <div>
                                        <div class="metric-title">Total Cost</div>
                                        <div class="metric-value text-success">
                                            Rs.<?= number_format($total_cost_price) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-info text-white me-3">
                                        <i class="ri-line-chart-line"></i>
                                    </div>
                                    <div>
                                        <div class="metric-title">Total Sales Value</div>
                                        <div class="metric-value text-info">
                                            Rs.<?= number_format($tot_bill_dis) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Overview Section -->
            <div class="col-12">
                <h4 class="section-title">Payments Overview</h4>
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Cash Flow</h6>
                            </div>
                            <div class="card-body">
                                <div class="metric-title">Received via Cash</div>
                                <div class="metric-value">Rs.<?= number_format($total_payments_today['cash']) ?></div>
                                <div class="metric-title mt-2">Till Balance</div>
                                <div class="metric-value text-success">
                                    Rs.<?= number_format($till_balance) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-warning text-white me-3">
                                        <i class="ri-global-line"></i>
                                    </div>
                                    <div>
                                        <div class="metric-title">Online Payments</div>
                                        <div class="metric-value text-warning">
                                            Rs.<?= number_format($total_payments_today['online']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-danger text-white me-3">
                                        <i class="ri-bank-line"></i>
                                    </div>
                                    <div>
                                        <div class="metric-title">Bank Transfers</div>
                                        <div class="metric-value text-danger">
                                            Rs.<?= number_format($total_payments_today['bank']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Daily Summary Section -->
            <div class="col-12">
                <h4 class="section-title">Daily Summary</h4>
                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-primary text-white me-3">
                                        <i class="ri-shopping-bag-line"></i>
                                    </div>
                                    <div>
                                        <div class="metric-title">Today's Sales</div>
                                        <div class="metric-value text-primary">
                                            Rs.<?= number_format($tot_bill_dis_today) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card dashboard-card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-success text-white me-3">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                    <div>
                                        <div class="metric-title">Today's Expenses</div>
                                        <div class="metric-value text-success">
                                            Rs.<?= number_format($tot_expenses_today) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="productlist.php" class="btn btn-primary btn-lg px-4">
                                <i class="ri-list-check me-2"></i>View Products
                            </a>
                            <a href="vendorlist.php" class="btn btn-secondary btn-lg px-4">
                                <i class="ri-store-line me-2"></i>View Vendors
                            </a>
                            <a href="manage_expenses.php" class="btn btn-warning btn-lg px-4">
                                <i class="ri-money-dollar-circle-line me-2"></i>Manage Expenses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Recently Added Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Product Name</th>
                                        <th class="text-end">Price (Rs)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rs->num_rows > 0): ?>
                                        <?php while ($row = $rs->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                                <td class="text-end"><?= number_format($row['price'], 2) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No products found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>