<?php
include './backend/conn.php';

$billId = $_REQUEST['bill_id'];
$sqlGrm = "SELECT * FROM tbl_order_grm WHERE id='$billId'";
$rsGrm = $conn->query($sqlGrm);
$rowGrm = $rsGrm->fetch_assoc();

$payment_type_id = $rowGrm['payment_type'];
$order_ref      = $rowGrm['order_ref'];
$discount_price = $rowGrm['discount_price'];
$order_date     = $rowGrm['order_date'];
$payment_type   = getPayment($payment_type_id);
$cus_id         = $rowGrm['customer_id'];

if ($cus_id != 0) {
    $cus_name    = getDataBack($conn, 'tbl_customer', 'c_id', $cus_id, 'c_name');
    $cus_phone   = getDataBack($conn, 'tbl_customer', 'c_id', $cus_id, 'c_phone');
    $cus_email   = getDataBack($conn, 'tbl_customer', 'c_id', $cus_id, 'c_email');
    $cus_address = getDataBack($conn, 'tbl_customer', 'c_id', $cus_id, 'c_address');
    $cus_city    = getDataBack($conn, 'tbl_customer', 'c_id', $cus_id, 'c_city');
} else {
    $cus_name    = "Walk-in Customer";
    $cus_phone   = "";
    $cus_email   = "";
    $cus_address = "";
    $cus_city    = "";
}

$tot_qnty = 0;
$pay_st   = $rowGrm['pay_st'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=78mm, initial-scale=1.0">
  <title>Invoice #<?= $order_ref ?></title>
  <style>
  @media print {
    body {
      width: 78mm;
      font-size: 12px;
      margin: 0;
      padding: 0;
      font-family: 'Courier New', Courier, monospace; /* Monospace font for better readability */
    }
    .logo-container img {
      width: 60mm;
      height: auto;
      max-height: 30mm;
    }
    .header, .store-details, .invoice-details, .totals, .customer-details, .footer {
      text-align: center;
    }
    .items-table {
      width: 100%;
      border-collapse: collapse;
    }
    .items-table th, .items-table td {
      padding: 2px;
      border-bottom: 1px dashed #000;
      text-align: left;
    }
    @page {
      size: auto;
      margin: 0;
    }
  }
  </style>
</head>
<body>
  <div class="header">
    <?php
      $address = "No.115 Nuwara eliya road Gampola";
      $phone   = "077 9003566";
    ?>
    <div class="logo-container">
      <img src="logo/b_k_logo.png" alt="Store Logo">
    </div>
  </div>
  <div class="store-details">
    <div><?= $address ?></div>
    <div>Phone: <?= $phone ?></div>
  </div>
  <div class="invoice-details">
    <div>Date: <?= $order_date ?></div>
    <div>Invoice #: <?= $order_ref ?></div>
    <div>Payment: <?= $payment_type ?></div>
  </div>
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:10%;">Qty</th>
        <th style="width:40%;">Product</th>
        <th style="width:25%;">Unit Price</th>
        <th style="width:25%;">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql_ord = "SELECT * FROM tbl_order WHERE grm_ref='$billId'";
      $rs_ord = $conn->query($sql_ord);
      $total = 0;
      if ($rs_ord->num_rows > 0) {
        while ($rowOrd = $rs_ord->fetch_assoc()) {
          $pid      = $rowOrd['product_id'];
          $p_name   = getDataBack($conn, 'tbl_product', 'id', $pid, 'name');
          $p_price  = getDataBack($conn, 'tbl_product', 'id', $pid, 'price');
          $quantity = $rowOrd['quantity'];
          $line_total = $p_price * $quantity;
          $total += $line_total;
          $tot_qnty += $quantity;
      ?>
      <tr>
        <td><?= $quantity ?></td>
        <td><?= $p_name ?></td>
        <td>Rs <?= number_format($p_price) ?>/-</td>
        <td>Rs <?= number_format($line_total) ?>/-</td>
      </tr>
      <?php } } ?>
    </tbody>
  </table>
  <div class="totals">
    <div>Total Quantity: <?= $tot_qnty ?></div>
    <div>Subtotal: Rs <?= number_format($total) ?>/-</div>
    <?php if ($discount_price > 0) { ?>
      <div>Discount: Rs <?= number_format($discount_price) ?>/-</div>
    <?php } ?>
    <div><strong>Total: Rs <?= number_format($total - $discount_price) ?>/-</strong></div>
  </div>
  <div class="customer-details">
    <div><strong>Billing Details</strong></div>
    <?php if ($cus_id != 0) { ?>
      <div><?= $cus_name ?></div>
      <div><?= $cus_address ?></div>
      <div><?= $cus_phone ?></div>
      <div><?= $cus_email ?></div>
    <?php } else { ?>
      <div>Walk-in Customer</div>
    <?php } ?>
  </div>
  <div class="footer">
    <div>Thank you for your purchase!</div>
  </div>
  <script>
    window.onload = function() {
      setTimeout(function() {
        window.print();
        window.onafterprint = function() {
          window.close();
        };
      }, 500);
    };
  </script>
</body>
</html>
