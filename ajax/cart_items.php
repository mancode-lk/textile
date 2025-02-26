<?php
include '../backend/conn.php';
$grm_id = $_SESSION['grm_ref'];

$sql = "SELECT * FROM `tbl_order` WHERE grm_ref='$grm_id' ORDER BY id DESC";
$rs = $conn->query($sql);

if ($rs->num_rows > 0) {
    ?>
    <div class="card shadow-sm border rounded p-3 mb-3">
        <h5 class="fw-bold mb-3"><i class="fas fa-boxes"></i> Current Stock</h5>
        <div class="list-group">
            <?php
            while ($row = $rs->fetch_assoc()) {
                $id = $row['id'];
                $p_id = $row['product_id'];
                $p_name = getDataBack($conn, 'tbl_product', 'id', $p_id, 'name');
                $p_price = getDataBack($conn, 'tbl_product', 'id', $p_id, 'price');
                $qty = $row['quantity'];
                $currentStock = currentStockCount($conn, $p_id);

                // Stock Level Indicators
                $stockBadge = '<span class="badge bg-success">In Stock</span>';
                if ($currentStock <= 5) {
                    $stockBadge = '<span class="badge bg-warning">Low Stock</span>';
                }
                if ($currentStock == 0) {
                    $stockBadge = '<span class="badge bg-danger">Out of Stock</span>';
                }
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><?= htmlspecialchars($p_name) ?></h6>
                        <small class="text-muted">Price: LKR <?= number_format($p_price, 2) ?></small>
                        <div>Stock: <span class="fw-bold"><?= $currentStock ?></span> <?= $stockBadge ?></div>
                    </div>
                    <div class="d-flex align-items-center">
                      <input type="number" value="<?= (int) $qty ?>" min="1"
     oninput="updateQnty(<?= $id ?>, this.value)"
     class="form-control form-control-sm me-2 quantity-input"
     style="width: 80px;" />

                        <span class="fw-bold me-2">LKR <?= number_format($p_price * $qty, 2) ?></span>
                        <button class="btn btn-sm btn-danger" onclick="del_item_cart(<?= $id ?>)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
}
?>
