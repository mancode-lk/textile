<?php
include './backend/conn.php';

// Get the search query from the AJAX request
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to include a search filter for customer name or reference number
$sql = "SELECT g.*, c.c_name, c.c_phone
        FROM tbl_order_grm g
        LEFT JOIN tbl_customer c ON g.customer_id = c.c_id
        WHERE g.order_ref LIKE '%$search_query%' OR c.c_name LIKE '%$search_query%' OR c.c_phone LIKE '%$search_query%'
        ORDER BY g.id DESC";
    
$rs = $conn->query($sql);

// Output the table rows as HTML
if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $ref = intval($row['id']); // Ensure ID is always an integer
        $customer = htmlspecialchars($row['c_name'] ?? 'N/A');
        $customerPhone = htmlspecialchars($row['c_phone'] ?? 'N/A');
        ?>
        <tr>
            <td><?= htmlspecialchars($row['order_ref']) ?></td>
            <td><?= $customer ?></td>
            <td><?= $customerPhone ?></td>
            <td><?= htmlspecialchars($row['order_date']) ?></td>
            <td style="color: <?= ($row['payment_type'] == "3") ? 'red' : 'black' ?>;">
    <?= htmlspecialchars(getPayment($row['payment_type'])) ?>
</td>


            <?php
            // Calculate Discounted Total in a Single Query
            $sqlS = "SELECT 
                        COALESCE(SUM(
                            CASE 
                                WHEN discount_type = 'p' THEN (m_price * (1 - discount / 100) * quantity) 
                                WHEN discount_type = 'a' THEN ((m_price - discount) * quantity) 
                                ELSE 100
                            END
                        ), 0) AS total
                     FROM tbl_order 
                     WHERE grm_ref='$ref'";

            $rsS = $conn->query($sqlS);
            $total = ($rsS->num_rows > 0) ? number_format($rsS->fetch_assoc()['total'], 2) : '0.00';
            ?>
            <td><?= $total ?></td>

            <td>
                <a class="me-3" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create" onclick="loadValue(<?= $ref ?>)">
                    <img src="assets/img/icons/eye.svg" alt="View">
                </a>
                <a href="print_bill.php?bill_id=<?= $ref ?>" target="_blank">
                    <span style="color:#f74e05;font-weight:bold;">Print Bill</span>
                </a>
            </td>
            <td>
                <a onclick="del_order(<?= $ref ?>)" class="me-3 confirm-text" href="javascript:void(0);">
                    <img src="assets/img/icons/delete.svg" alt="Delete">
                </a>
            </td>
        </tr>
    <?php 
    }
}
?>
