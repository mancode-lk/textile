<?php
    include '../backend/conn.php';
    $grm_id = $_SESSION['grm_ref'];

    $sql ="SELECT * FROM `tbl_order` WHERE grm_ref='$grm_id' ORDER BY id DESC";
    $rs=$conn->query($sql);

    if($rs->num_rows > 0){
      while($row = $rs->fetch_assoc()){
        $id = $row['id'];
        $p_id=$row['product_id'];
        $p_name = getDataBack($conn,'tbl_product','id',$p_id,'name');
        $p_price =getDataBack($conn,'tbl_product','id',$p_id,'price');

    ?>
<div class="list-group-item d-flex justify-content-between align-items-center">
  <div>
    <h6 class="mb-1"><?= $p_name ?></h6>
    <small class="text-muted">Price: LKR <?= $p_name ?></small>
  </div>
  <div class="d-flex align-items-center">
    <input type="number" value="<?= $row['quantity'] ?>" min="1" onkeyup="updateQnty(<?= $id ?>,this.value)" class="form-control form-control-sm me-2" style="width: 80px;" />
    <span class="fw-bold me-2">LKR <?= $p_price ?></span>
    <button class="btn btn-sm btn-danger" onclick="del_item_cart(<?= $id ?>)">
      <i class="fas fa-trash-alt"></i>
    </button>
  </div>
</div>
<?php } } ?>
