<?php
// Database connection
include 'backend/conn.php';

// Initialize connection error handling
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Sanitize and get search parameters
$search_name = isset($_GET['name']) ? trim($_GET['name']) : '';
$search_phone = isset($_GET['phone']) ? trim($_GET['phone']) : '';
$search_payment_method = isset($_GET['payment_method']) ? trim($_GET['payment_method']) : '';

// Build query dynamically based on filters
$base_query = "
    SELECT 
        c.c_id AS CustomerID,
        c.c_name AS CustomerName,
        c.c_phone AS Phone,
        c.c_email AS Email,
        c.c_address AS Address,
        c.c_city AS City,
        c.credit_balance AS CreditBalance,
        og.id AS OrderID,
        og.order_date AS OrderDate,
        CASE og.payment_type
            WHEN 0 THEN 'Cash'
            WHEN 1 THEN 'Online Payment'
            WHEN 2 THEN 'Bank Transfer'
            WHEN 3 THEN 'Credit'
        END AS PaymentMethod,
        o.product_id AS ProductID,
        o.quantity AS Quantity,
        o.discount AS Discount,
        o.discount_type AS DiscountType,
        o.bill_date AS BillDate,
        o.m_price AS Price
    FROM tbl_order_grm og
    LEFT JOIN tbl_customer c ON og.customer_id = c.c_id
    INNER JOIN tbl_order o ON og.id = o.grm_ref
";

$conditions = [];
$params = [];
$types = '';

if (!empty($search_name)) {
    $conditions[] = "c.c_name LIKE CONCAT('%', ?, '%')";
    $params[] = $search_name;
    $types .= 's';
}

if (!empty($search_phone)) {
    $conditions[] = "c.c_phone LIKE CONCAT('%', ?, '%')";
    $params[] = $search_phone;
    $types .= 's';
}

if (!empty($search_payment_method)) {
    $conditions[] = "og.payment_type = ?";
    $params[] = $search_payment_method;
    $types .= 's';
}

$query = $base_query;
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Prepare and execute query
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

$result = $stmt->get_result();
$results = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders Report</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --hover-color: #1d4ed8;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 25px;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e293b;
        }

        input, select {
            padding: 10px;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            align-self: flex-end;
        }

        button:hover {
            background: var(--hover-color);
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        tr:hover {
            background-color: #f1f5f9;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }

        .currency {
            text-align: right;
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Orders Report</h1>
        <a href="index.php" class="btn btn-warning mx-2">Dashboard</a>
        <form method="GET" class="filters">
            <div class="filter-group">
                <label for="name">Customer Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($search_name) ?>">
            </div>
            
            <div class="filter-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($search_phone) ?>">
            </div>
            
            <div class="filter-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method">
                    <option value="">All Methods</option>
                    <option value="0" <?= $search_payment_method === 'Cash' ? 'selected' : '' ?>>Cash</option>
                    <option value="1" <?= $search_payment_method === 'Online Payment' ? 'selected' : '' ?>>Online Payment</option>
                    <option value="2" <?= $search_payment_method === 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                    <option value="3" <?= $search_payment_method === 'Credit' ? 'selected' : '' ?>>Credit</option>
                </select>
            </div>
            
            <button type="submit">Apply Filters</button>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <!-- <th>Customer ID</th> -->
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Credit Balance</th>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Payment Method</th>
                        <!-- <th>Product ID</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Price</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($results)): ?>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <!-- <td><?= htmlspecialchars($row['CustomerID']) ?></td> -->
                                <td><?= !empty($row['CustomerName']) ? htmlspecialchars($row['CustomerName']) : 'Not Available' ?></td>
                                <td><?= !empty($row['Phone']) ? htmlspecialchars($row['Phone']) : 'Not Available' ?></td>
                                <td><?= !empty($row['Email']) ? htmlspecialchars($row['Email']) : 'Not Available' ?></td>
                                <td><?= !empty($row['City']) ? htmlspecialchars($row['City']) : 'Not Available' ?></td>
                                <td class="currency"><?= !empty($row['CreditBalance']) ? 'Rs.' . number_format($row['CreditBalance'], 2) : 'Not Available' ?></td>
                                <td><?= htmlspecialchars($row['OrderID']) ?></td>
                                <td><?= date('M d, Y', strtotime($row['OrderDate'])) ?></td>
                                <td><?= htmlspecialchars($row['PaymentMethod']) ?></td>
                                <!-- <td><?= htmlspecialchars($row['ProductID']) ?></td>
                                <td><?= htmlspecialchars($row['Quantity']) ?></td>
                                <td><?= htmlspecialchars($row['Discount']) ?></td>
                                <td class="currency"><?= '$' . number_format($row['Price'], 2) ?></td> -->
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="13" class="no-data">
                                No orders found matching your criteria
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
