<?php
include '../backend/conn.php';

if(isset($_POST['customer_id'])){
    $customer_id = $_POST['customer_id'];

    $sql_orders = "SELECT og.id, og.order_ref, og.order_date, og.payment_type, og.discount_price
                   FROM tbl_order_grm og
                   WHERE og.customer_id = '$customer_id'";
    $result_orders = mysqli_query($conn, $sql_orders);

    if(mysqli_num_rows($result_orders) > 0){
        echo '<table class="table table-bordered">';
        echo '<thead class="thead-light"><tr><th>Order Ref</th><th>Date</th><th>Payment Type</th><th>Total Bill (LKR)</th></tr></thead>';
        echo '<tbody>';

        while($order = mysqli_fetch_assoc($result_orders)){
            $order_id = $order['id'];
            $total = 0;

            $sql_items = "SELECT o.quantity, o.discount, p.price
                          FROM tbl_order o
                          JOIN tbl_product p ON o.product_id = p.id
                          WHERE o.grm_ref = '$order_id'";
            $result_items = mysqli_query($conn, $sql_items);

            if(mysqli_num_rows($result_items) > 0){
                while($item = mysqli_fetch_assoc($result_items)){
                    $line_total = ($item['quantity'] * $item['price']) - $item['discount'];
                    $total += $line_total;
                }
            }
            $total -= $order['discount_price'];

            $payment_types = ["Cash", "Online Pay", "Bank Transfer", "Credit"];
            $payment_type_text = $payment_types[$order['payment_type']];

            echo "<tr>
                    <td>{$order['order_ref']}</td>
                    <td>{$order['order_date']}</td>
                    <td>{$payment_type_text}</td>
                    <td>" . number_format($total, 2) . "</td>
                  </tr>";
        }

        echo '</tbody></table>';
    } else {
        echo '<p class="text-center text-muted">No orders found.</p>';
    }
}
?>
