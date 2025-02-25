<?php
include 'conn.php';

$grm_id = $_SESSION['grm_ref'];

$bcode =$_REQUEST['bcode'];



$p_id = getDataBack($conn,'tbl_product','barcode',$bcode,'id');
$qty = 1;

$sql= "INSERT INTO tbl_order (product_id,quantity,grm_ref) VALUES ('$p_id','$qty','$grm_id')";
$rs=$conn->query($sql);

if($rs > 0){
  echo 200;
}
else {
  echo $sql;
}
