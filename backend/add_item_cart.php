<?php
include 'conn.php';

$grm_id = $_SESSION['grm_ref'];

$p_id = $_REQUEST['p_id'];
$qty = $_REQUEST['qty'];

$sql= "INSERT INTO tbl_order (product_id,quantity,grm_ref) VALUES ('$p_id','$qty','$grm_id')";
$rs=$conn->query($sql);

if($rs > 0){
  echo 200;
}
else {
  echo 500;
}
