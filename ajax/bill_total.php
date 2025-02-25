<?php
    include '../backend/conn.php';
    $grm_id = $_SESSION['grm_ref'];
    $total_price = 0;
    $sql ="SELECT * FROM `tbl_order` WHERE grm_ref='$grm_id' ORDER BY id DESC";
    $rs=$conn->query($sql);

    if($rs->num_rows > 0){
      while($row = $rs->fetch_assoc()){
        $id = $row['id'];
        $p_id=$row['product_id'];
        $qty =$row['quantity'];
        $p_name = getDataBack($conn,'tbl_product','id',$p_id,'name');
        $p_price =getDataBack($conn,'tbl_product','id',$p_id,'price') * $qty;
        $total_price +=$p_price;
    ?>

  <?php }
?>
<?php
if (isset($_REQUEST['disc_price'])) {
    $discount_amount = $_REQUEST['disc_price'];
    $original_price = $total_price; // Store original price
    $total_price -= $discount_amount; // Apply discount
} else {
    $discount_amount = 0;
    $original_price = $total_price;
}
?>

<div class="border p-3 rounded bg-light">
    <p class="h5 mb-0">
        <span class="text-muted me-2">Total:</span>
        <span class="fw-bold text-primary" style="font-size: 1.3rem;">
            LKR <?= number_format($total_price, 2) ?>
        </span>
    </p>

    <?php if ($discount_amount > 0): ?>
        <p class="mb-0 text-muted">
            <small>
                <s class="text-secondary">LKR <?= number_format($original_price, 2) ?></s>
                <span class="text-success ms-2">Discount: -LKR <?= number_format($discount_amount, 2) ?></span>
            </small>
        </p>
    <?php endif; ?>
</div>
<?php
}else{ ?>
  <p class="h5 mb-0">Total: <span class="fw-bold">LKR 0</span></p>
<?php } ?>
<script type="text/javascript">
  document.getElementById('totPrice').value=<?= $total_price ?>;
</script>
