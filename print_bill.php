<?php include './backend/conn.php';


$billId = $_REQUEST['bill_id'];

$sqlGrm = "SELECT * FROM tbl_order_grm WHERE id='$billId'";
$rsGrm = $conn->query($sqlGrm);

$rowGrm = $rsGrm->fetch_assoc();

$payment_type_id = $rowGrm['payment_type'];

$order_ref = $rowGrm['order_ref'];
$order_date = $rowGrm['order_date'];
$payment_type =getPayment($payment_type_id);

$cus_id = $rowGrm['customer_id'];

$cus_name = getDataBack($conn,'tbl_customer','c_id',$cus_id,'c_name');
$cus_phone= getDataBack($conn,'tbl_customer','c_id',$cus_id,'c_phone');
$cus_email= getDataBack($conn,'tbl_customer','c_id',$cus_id,'c_email');
$cus_address= getDataBack($conn,'tbl_customer','c_id',$cus_id,'c_address');
$cus_city= getDataBack($conn,'tbl_customer','c_id',$cus_id,'c_city');
$tot_qnty=0;



$pay_st = $rowGrm['pay_st'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="robots" content="noindex, nofollow">
        <title>Pos Admin</title>

		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">

		<!-- animation CSS -->
        <link rel="stylesheet" href="assets/css/animate.css">

		<!-- Owl Carousel CSS -->
		<link rel="stylesheet" href="assets/plugins/owlcarousel/owl.carousel.min.css">
		<link rel="stylesheet" href="assets/plugins/owlcarousel/owl.theme.default.min.css">

		<!-- Select2 CSS -->
		<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">


        <!-- Fontawesome CSS -->
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
		<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/my.css">
    </head>
    <body>

		<div id="global-loader" >
			<div class="whirly-loader"> </div>
		</div>

		<div class="main-wrappers">
      <div class="container">

        <div class="invoice_table">
          <div class="row">
            <div class="col-12">
              <div class="text-center">
                <?php
                  // if($store_id == 1){
                  //   $logo = 'five_logo.png';
                  //   $address = "5th lane, Dematagoda place, Colombo 09";
                  //   $phone = "+94 704315112";
                  //   $Email = "Info@fivestories.com";
                  // }
                  // elseif ($store_id == 2) {
                  //   $logo = 'cardamum.png';
                  //   $address = "71/6 wekanda Road, Colombo 02";
                  //   $phone = "076 499 5958";
                  //   $Email = "Cardamummegaplex@gmail.com";
                  // }
                  // else {
                    $logo = 'logo.png';
                    $address = "123 Gampola";
                    $phone = "+9477777777";
                    $Email = "123@gmail.com";
                  // }
                 ?>
                <img src="assets/<?= $logo ?>" style="width:220px;margin-top:-40px;" alt="">
              </div>
              <div class="row" style="margin-top:-20px;">
                <div class="col-6">
                  <div class="box_01">
                    <h5>Address: <?= $address ?></h5>
                    <h4>Phone: <?= $phone ?></h4>
                    <h4>Email: <?= $Email ?></h4>
                  </div>
                </div>
                <div class="col-6">
                  <div class="box_01">
                    <h5 style="font-weight:bolder;">Date: <?= $order_date ?></h5>
                    <h5 style="font-weight:bolder;">Invoice Number: #<?= $order_ref ?></h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="container">
          <table class="table">
            <thead>
              <tr>
                <th>Qty</th>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody>
              <?php
              function addLineBreaks($str) {
                $words = explode(' ', $str);
                if (count($words) > 8) {
                  array_splice($words, 8, 0, '<br>');
                }
                return implode(' ', $words);
              }
                $sql_ord = "SELECT * FROM tbl_order WHERE grm_ref='$billId'";
                $rs_ord = $conn->query($sql_ord);
                $total = 0;
                if($rs_ord->num_rows > 0){
                  while($rowOrd = $rs_ord->fetch_assoc()){
                    $pid = $rowOrd['product_id'];
                    $p_name = getDataBack($conn,'tbl_product','id',$pid,'name');
                    $p_price = $rowOrd['m_price'];
                    $discount = $rowOrd['discount'];
                    $discount_type = $rowOrd['discount_type'];
                    $dis_amount =0;
               ?>
              <tr>
                <td style="font-size:16px;color:#000;text-align:center;"><?= $rowOrd['quantity'] ?></td>
                <td style="font-size:16px;color:#000;"><?= addLineBreaks($p_name) ?></td>

                                           <td style="font-size:20px;color:#000;">
                                             <?php
                                             if($discount > 0){
                                               if($discount_type == "p"){
                                                 $dis_amount = ($p_price * $discount) / 100;
                                                 $new_price = $p_price - $dis_amount;
                                               }
                                               else {
                                                 $new_price = $p_price - $discount;
                                               }
                                               ?>
                                               <span style="text-decoration:line-through;"> Rs <?= number_format($p_price) ?>/- </span> <br>
                                               Rs <?= number_format($new_price) ?>/-
                                               <?php
                                             }
                                             else {
                                               echo "Rs ".number_format($p_price)."/-";
                                             }
                                              ?>
                                           </td>
                <td style="font-size:20px;color:#000;"><?php
                            if($discount == 0){
                              echo 0;
                            }
                            else if($discount_type == "p"){
                              echo "$discount%";
                            }else{
                               echo "Rs.". $discount."/-";
                             }  ?>
                </td>

                <td style="font-size:20px;color:#000;">
                  <?php
                  $tot_qnty +=$rowOrd['quantity'];
                  if($discount > 0){
                    if($discount_type == "p"){
                      $dis_amount = ($p_price * $discount) / 100;
                      $new_price = $p_price - $dis_amount;
                    }
                    else if($discount_type == "a"){
                      $new_price = $p_price- $discount;
                    }
                    else {
                      $new_price = $p_price;
                    }
                    $new_price = $new_price * $rowOrd['quantity']
                    ?>
                    <span style="text-decoration:line-through;"> Rs <?= number_format($p_price * $rowOrd['quantity']) ?>/- </span> <br>
                    Rs <?= number_format($new_price) ?>/-
                    <?php
                  }
                  else {
                    $new_price = $p_price * $rowOrd['quantity'];
                    echo "Rs ".number_format($new_price)."/-";
                  }

                   ?>


                </td>
              </tr>
            <?php $total +=$new_price; } } ?>
            
            <tr>
                <td colspan="4" style="font-weight:bold;font-size:18px;">Total Quantity:</td>
                <td style="font-weight:bold;font-size:18px;color:#000;"><?= $tot_qnty ?></td>
              </tr>
              <tr>
                <td colspan="4" style="font-weight:bold;font-size:18px;">Subtotal:</td>
                <td style="font-weight:bold;font-size:18px;">Rs <?= number_format($total) ?>/-</td>
              </tr>
           

              <tr>
                <td colspan="4" style="font-weight:bold;font-size:18px;">Payment Method:</td>
                <td style="font-weight:bold;font-size:18px;"><?= $payment_type ?></td>
              </tr>
              <tr>
                <td colspan="4" style="font-weight:bold;font-size:18px;">Total:</td>
                <td style="font-weight:bold;font-size:18px;">Rs <?= number_format($total) ?>/-</td>
              </tr>
              
              
              <?php
                if($pay_st == 2){
                  $pay_st_text = "PAID";
                }
                elseif($pay_st == 1) {
                  $pay_st_text = "NOT PAID";
                }

                if($pay_st == 2 || $pay_st == 1){
               ?>
              <tr>
                <td colspan="5" style="font-weight:bold;font-size:18px;text-align:center;">  <?= $pay_st_text ?> </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <hr><hr>
        <div class="row">
          <div class="col-6">
            <h3 class="text-center" style="color:#3cb598;font-weight:bold;">Billing address</h3>
            <br>
            <div class="box_01">
              <h4><?= $cus_name ?></h4>
              <h4> <?= $cus_address ?></h4>
              <h4> <?= $cus_phone ?></h4>
              <h4><?= $cus_email ?></h4>
            </div>
            <br>
          </div>
         
        </div>
        </div>

 <br><br>
      </div>
		</div>






	<?php include 'layouts/footer.php' ?>


    </body>
</html>
