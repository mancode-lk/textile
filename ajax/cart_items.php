<?php
include '../backend/conn.php';
$grm_id = $_SESSION['grm_ref'];

$sql = "SELECT * FROM tbl_order WHERE grm_ref='$grm_id' ORDER BY id DESC";
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
                $discount = $row['discount'] ?? 0; // Fetch stored discount, default to 0
                $currentStock = currentStockCount($conn, $p_id);
                $exchange_st = -1;
                $sqlReturn ="SELECT * FROM tbl_return_exchange WHERE or_id='$id'";
                $rsReturn = $conn->query($sqlReturn);
                if($rsReturn->num_rows > 0){
                  $rowReturn=$rsReturn->fetch_assoc();
                  $exchange_st = $rowReturn['ret_or_ex_st'];
                }

                // Stock Level Indicators
                $stockBadge = '<span class="badge bg-success">In Stock</span>';
                if ($currentStock <= 5) {
                    $stockBadge = '<span class="badge bg-warning">Low Stock</span>';
                }
                if ($currentStock == 0) {
                    $stockBadge = '<span class="badge bg-danger">Out of Stock</span>';
                }

                // Status Badge
                $statusBadge = '';
                if ($exchange_st == 0) {
                    $statusBadge = '<span class="badge bg-warning">Cash Returned</span>';
                } elseif ($exchange_st == 1) {
                    $statusBadge = '<span class="badge bg-primary">Exchanged</span>';
                }

                // Calculate total price after discount
                $totalPrice = ($p_price * $qty) - $discount;
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h6 class="mb-1"><?= htmlspecialchars($p_name) ?> <?= $statusBadge ?></h6>
                        <small class="text-muted">Price: LKR <?= number_format($p_price, 2) ?></small>
                        <div>Stock: <span class="fw-bold"><?= $currentStock ?></span> <?= $stockBadge ?></div>

                    </div>
                    <div class="d-flex align-items-center my-3">
                        <input type="number" value="<?= (int) $qty ?>" min="1"
                               oninput="updateQnty(<?= $id ?>, this.value)"
                               class="form-control form-control-sm me-2 quantity-input"
                               style="width: 80px;" />

                        <input type="number" value="<?= (int) $discount ?>" min="0"
                               oninput="applyDiscount(<?= $id ?>, this.value)"
                               class="form-control form-control-sm me-2 discount-input"
                               placeholder="Discount"
                               style="width: 90px;" />

                        <span class="fw-bold me-2">LKR <span id="total_price_<?= $id ?>"><?= number_format($totalPrice, 2) ?></span></span>

                        <!-- Cash Return Button -->
                        <?php if($exchange_st !=0){ ?>
                        <button class="btn btn-sm btn-warning me-2" id="cashReturnButton" style="font-size:10px;" onclick="cashReturn(<?= $id ?>)">
                            <i class="fas fa-money-bill-wave"></i> Cash Return
                        </button>
                      <?php } ?>

                        <!-- Exchange Button -->
                        <?php if($exchange_st !=1){ ?>
                        <button class="btn btn-sm btn-primary me-2" id="exhchangeButton" style="font-size:10px;" onclick="exchangeItem(<?= $id ?>)">
                            <i class="fas fa-exchange-alt"></i> Exchange
                        </button>
                      <?php } ?>

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
