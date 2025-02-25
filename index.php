<?php
include 'layouts/header.php';
include 'layouts/sidebar.php';

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
$sql = "SELECT p.name AS product_name, p.price 
        FROM tbl_product p
        LEFT JOIN tbl_category c ON p.category_id = c.id
        ORDER BY p.id DESC LIMIT $offset, $limit";
$rs = $conn->query($sql);

// Calculate Total Stock Value
$stock_value = 0;
$sql_products = "SELECT * FROM tbl_product";
$rs_prod = $conn->query($sql_products);
if ($rs_prod->num_rows > 0) {
    while ($rowProd = $rs_prod->fetch_assoc()) {
        $prod_id = $rowProd['id'];
        $price = $rowProd['price'];
        $tot_qnty = currentStockCount($conn, $prod_id);
        $stock_value += $price * $tot_qnty;
    }
}

// Fetch Today's Sales Total
$tot_bill_dis_today = 0;
$today_date = date("Y-m-d");

$sql_today_sales = "SELECT * FROM tbl_order WHERE DATE(bill_date) = '$today_date'";
$rs_today_sales = $conn->query($sql_today_sales);
if ($rs_today_sales->num_rows > 0) {
    while ($row = $rs_today_sales->fetch_assoc()) {
        $discount = $row['discount'];
        $p_price = $row['m_price'];

        if ($discount != 0) {
            $d_type = $row['discount_type'];
            $dis_amount = ($d_type == "p") ? ($p_price * $discount) / 100 : $discount;
            $p_price -= floor($dis_amount);
        }

        $tot_bill_dis_today += $row['quantity'] * $p_price;
    }
}

// Fetch Today's Expenses
$tot_expenses_today = 0;
$sql_today_expenses = "SELECT * FROM tbl_expenses WHERE DATE(expense_date) = '$today_date'";
$rs_today_expenses = $conn->query($sql_today_expenses);
if ($rs_today_expenses->num_rows > 0) {
    while ($row_expense = $rs_today_expenses->fetch_assoc()) {
        $tot_expenses_today += $row_expense['amount'];
    }
}

// Fetch Total Sales Value
$tot_bill_dis = 0;
$sql_orders = "SELECT * FROM tbl_order";
$rs_orders = $conn->query($sql_orders);
if ($rs_orders->num_rows > 0) {
    while ($row_order = $rs_orders->fetch_assoc()) {
        $discount = $row_order['discount'];
        $p_price = $row_order['m_price'];

        if ($discount != 0) {
            $d_type = $row_order['discount_type'];
            $dis_amount = ($d_type == "p") ? ($p_price * $discount) / 100 : $discount;
            $p_price -= floor($dis_amount);
        }

        $tot_bill_dis += $row_order['quantity'] * $p_price;
    }
}

$till_balance_today = $tot_bill_dis_today - $tot_expenses_today;
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <!-- Total Stock Value -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="dash-widget">
                    <div class="dash-widgetcontent">
                        <h5>Rs.<?= number_format($stock_value) ?>/-</h5>
                        <h6>Total Stock Value</h6>
                    </div>
                </div>
            </div>

            <!-- Total Sales Value -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="dash-widget dash2">
                    <div class="dash-widgetcontent">
                        <h5>Rs.<?= number_format($tot_bill_dis) ?>/-</h5>
                        <h6>Total Sales Value</h6>
                    </div>
                </div>
            </div>

            <!-- Today's Sales -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="dash-widget dash3">
                    <div class="dash-widgetcontent">
                        <h5>Rs.<?= number_format($tot_bill_dis_today) ?>/-</h5>
                        <h6>Today's Sales</h6>
                    </div>
                </div>
            </div>

            <!-- Today's Expenses -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="dash-widget dash4">
                    <div class="dash-widgetcontent">
                        <h5>Rs.<?= number_format($tot_expenses_today) ?>/-</h5>
                        <h6>Today's Expenses</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-lg-12 text-center">
                <a href="productlist.php" class="btn btn-primary mx-2">View Product</a>
                <a href="vendorlist.php" class="btn btn-secondary mx-2">View Vendor</a>
                <a href="customer_management.php" class="btn btn-success mx-2">Customer Management</a>
                <a href="manage_expenses.php" class="btn btn-warning mx-2">Expenses</a>
            </div>
        </div>

        <!-- Recently Added Products -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Recently Added Products</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable" id="productTable">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($rs->num_rows > 0) {
                                        while ($row = $rs->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= !empty($row['product_name']) ? $row['product_name'] : 'No Category' ?></td>
                                                <td><?= $row['price'] !== null ? number_format($row['price'], 2) : 'N/A' ?></td>
                                            </tr>
                                    <?php }} ?>
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
