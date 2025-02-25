<?php include './backend/conn.php'; ?>
<table class="table datanew">
  <thead>
    <tr>
      <th>Vendor Name</th>
      <th>Contact</th>
      <th>Total Amount Owed</th> <!-- Total Purchase Amount -->
      <th>Total Paid</th> <!-- Payments from tbl_expenses -->
      <th>Total Discount</th> <!-- Discounts Applied -->
      <th>Remaining Balance</th> <!-- Amount still to be paid -->
      <th>Action</th>
      <th>Apply Discount</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT * FROM tbl_vendors";
    $rs = $conn->query($sql);
    if ($rs->num_rows > 0) {
      while ($row = $rs->fetch_assoc()) {
        $vendor_id = $row['vendor_id'];

        // Get the total purchase amount for the vendor
        $total_purchase_query = "SELECT SUM(total_amount) AS total_purchase FROM tbl_purchases WHERE vendor_id = '$vendor_id'";
        $total_purchase_result = $conn->query($total_purchase_query);
        $total_purchase = $total_purchase_result->fetch_assoc()['total_purchase'] ?? 0;

        // Get the total amount paid from tbl_purchase_payments
        $total_paid_query = "SELECT SUM(amount) AS total_paid FROM tbl_purchase_payments 
                             WHERE purchase_id IN (SELECT purchase_id FROM tbl_purchases WHERE vendor_id = '$vendor_id')";
        $total_paid_result = $conn->query($total_paid_query);
        $total_paid = $total_paid_result->fetch_assoc()['total_paid'] ?? 0;

        // Get the total amount paid from tbl_expenses
        $total_expense_query = "SELECT SUM(amount) AS total_expense FROM tbl_expenses WHERE vendor_id = '$vendor_id'";
        $total_expense_result = $conn->query($total_expense_query);
        $total_expense = $total_expense_result->fetch_assoc()['total_expense'] ?? 0;

        // Get the total discount applied
        $total_discount_query = "SELECT SUM(discount_amount) AS total_discount FROM tbl_vendor_discounts WHERE vendor_id = '$vendor_id'";
        $total_discount_result = $conn->query($total_discount_query);
        $total_discount = $total_discount_result->fetch_assoc()['total_discount'] ?? 0;

        // Calculate the remaining balance after discounts
        $remaining_balance = $total_purchase - ($total_paid + $total_expense + $total_discount);
    ?>
      <tr>
        <td><?= htmlspecialchars($row['vendor_name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><?= number_format($total_purchase, 2) ?></td> <!-- Total Amount Owed -->
        <td><?= number_format($total_expense, 2) ?></td> <!-- Total Paid -->
        <td><?= number_format($total_discount, 2) ?></td> <!-- Discounts Applied -->
        <td><?= number_format($remaining_balance, 2) ?></td> <!-- Remaining Balance -->
        <td>
          <a class="me-3" href="vendor_details.php?vendor_id=<?= $vendor_id ?>"><img src="assets/img/icons/eye.svg" alt="View"></a>
          <a class="me-3" href="editvendor.php?vendor_id=<?= $vendor_id ?>">
            <img src="assets/img/icons/edit.svg" alt="img">
          </a>
          <a onclick="del_prod(<?= $vendor_id ?>)" class="me-3 confirm-text" href="javascript:void(0);">
            <img src="assets/img/icons/delete.svg" alt="Delete">
          </a>

        </td>
        <td>
          <input type="number" id="discount_amount_<?= $vendor_id ?>" class="form-control" placeholder="Enter discount amount">
          <button onclick="applyDiscount(<?= $vendor_id ?>)" class="btn btn-primary mt-2">Apply</button>
        </td>
      </tr>
    <?php }} else { ?>
      <tr>
        <td colspan="8">No vendors added</td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<!-- AJAX Script for Applying Discount -->
<script>
function applyDiscount(vendorId) {
    let discountAmount = document.getElementById('discount_amount_' + vendorId).value;

    if (discountAmount > 0) {
        fetch('backend/apply_discount.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `vendor_id=${vendorId}&discount_amount=${discountAmount}`
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload(); // Refresh the table to show new balance
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert("Enter a valid discount amount!");
    }
}
</script>
