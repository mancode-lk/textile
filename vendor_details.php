<?php
include './layouts/header.php';
include './layouts/sidebar.php';

if (!isset($_GET['vendor_id'])) {
    echo "<div class='alert alert-danger'>Vendor ID is missing.</div>";
    exit;
}

$vendor_id = intval($_GET['vendor_id']); // Prevent SQL injection
$filter_day = $_GET['day'] ?? '';
$filter_month = $_GET['month'] ?? '';

// Validate date inputs
if ($filter_day && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filter_day)) {
    $filter_day = '';
}
if ($filter_month && !preg_match('/^\d{4}-\d{2}$/', $filter_month)) {
    $filter_month = '';
}

// Fetch vendor details
$sqlVendor = "SELECT vendor_name, phone, address FROM tbl_vendors WHERE vendor_id = ?";
$stmt = $conn->prepare($sqlVendor);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$stmt->bind_result($vendor_name, $phone, $address);
$stmt->fetch();
$stmt->close();
?>

<div class="page-wrapper">
    <div class="content">
        <div class="container">
            <div class="card p-4 shadow-lg">
                <h2 class="text-primary">Vendor Details</h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($vendor_name) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
            </div>

            <!-- Filters -->
            <form method="GET" class="mt-4">
                <input type="hidden" name="vendor_id" value="<?= $vendor_id ?>" />
                <label for="day">Filter by Day:</label>
                <input type="date" name="day" value="<?= htmlspecialchars($filter_day) ?>" />
                <label for="month">Filter by Month:</label>
                <input type="month" name="month" value="<?= htmlspecialchars($filter_month) ?>" />
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>

            <!-- Vendor Payments -->
            <div class="card p-4 shadow-lg mt-4">
                <h3 class="text-dark">Payments Made to Vendor</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlPayments = "SELECT expense_date, amount, description FROM tbl_expenses WHERE vendor_id = ?";
                        $params = ["i", $vendor_id];

                        if ($filter_day) {
                            $sqlPayments .= " AND expense_date = ?";
                            $params[0] .= "s";
                            $params[] = $filter_day;
                        } elseif ($filter_month) {
                            $sqlPayments .= " AND expense_date LIKE ?";
                            $params[0] .= "s";
                            $params[] = "$filter_month%";
                        }

                        $stmt = $conn->prepare($sqlPayments);
                        $stmt->bind_param(...$params);
                        $stmt->execute();
                        $stmt->bind_result($date, $amount, $description);

                        $hasPayments = false;
                        while ($stmt->fetch()) {
                            $hasPayments = true;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($date) ?></td>
                                <td><?= number_format($amount, 2) ?></td>
                                <td><?= htmlspecialchars($description) ?></td>
                            </tr>
                        <?php }
                        $stmt->close();

                        if (!$hasPayments) {
                            echo "<tr><td colspan='3' class='text-center text-danger'>No payments recorded.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Purchased Products -->
            <div class="card p-4 shadow-lg mt-4">
                <h3 class="text-dark">Purchased Products</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Category</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlProducts = "SELECT p.barcode, p.quantity, p.price, c.name AS category_name, p.name FROM tbl_product p LEFT JOIN tbl_category c ON p.category_id = c.id WHERE p.vendor_id = ?";
                        $stmt = $conn->prepare($sqlProducts);
                        $stmt->bind_param("i", $vendor_id);
                        $stmt->execute();
                        $stmt->bind_result($barcode, $quantity, $price, $category_name, $product_name);

                        $hasProducts = false;
                        while ($stmt->fetch()) {
                            $hasProducts = true;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($barcode) ?></td>
                                <td><?= htmlspecialchars($category_name) ?></td>
                                <td><?= htmlspecialchars($product_name) ?></td>
                                <td><?= htmlspecialchars($quantity) ?></td>
                                <td><?= number_format($price, 2) ?></td>
                                <td><?= number_format($price * $quantity, 2) ?></td>
                            </tr>
                        <?php }
                        $stmt->close();

                        if (!$hasProducts) {
                            echo "<tr><td colspan='6' class='text-center text-danger'>No products found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card p-4 shadow-lg mt-4">
                <h3 class="text-dark">Financial Summary</h3>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Total Purchases</th>
                            <th>Total Payments Made</th>
                            <th>Total Discounts</th>
                            <th>Net Amount Owed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        function fetchSum($conn, $sql, $vendor_id) {
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $vendor_id);
                            $stmt->execute();
                            $stmt->bind_result($sum);
                            $stmt->fetch();
                            $stmt->close();
                            return $sum ?? 0;
                        }

                        $total_purchases = fetchSum($conn, "SELECT SUM(total_amount) FROM tbl_purchases WHERE vendor_id = ?", $vendor_id);
                        $total_payments = fetchSum($conn, "SELECT SUM(amount) FROM tbl_expenses WHERE vendor_id = ? AND category = 'vendor'", $vendor_id);
                        $total_discounts = fetchSum($conn, "SELECT SUM(discount_amount) FROM tbl_vendor_discounts WHERE vendor_id = ?", $vendor_id);

                        $net_amount_owed = $total_purchases - $total_payments - $total_discounts;
                        ?>
                        <tr>
                            <td><?= number_format($total_purchases, 2) ?></td>
                            <td><?= number_format($total_payments, 2) ?></td>
                            <td><?= number_format($total_discounts, 2) ?></td>
                            <td><?= number_format($net_amount_owed, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include './layouts/footer.php'; ?>
