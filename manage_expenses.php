<?php
include 'layouts/header.php';
include 'layouts/sidebar.php';

// Initialize totals
$total_daily = 0;
$total_monthly = 0;
$total_petty_cash = 0;

// Handle Date Filter
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

// Fetch Expenses with Vendor Name
$sql_expenses = "SELECT e.*, v.vendor_name 
                 FROM tbl_expenses e 
                 LEFT JOIN tbl_vendors v ON e.vendor_id = v.vendor_id"; // LEFT JOIN to include vendor names
if ($filter_date) {
    $sql_expenses .= " WHERE e.expense_date = '$filter_date'";
}
$sql_expenses .= " ORDER BY e.expense_date DESC";
$rs_expenses = $conn->query($sql_expenses);

// Calculate Total Expenses by Category
while ($row_expense = $rs_expenses->fetch_assoc()) {
    if ($row_expense['category'] == 'daily') {
        $total_daily += $row_expense['amount'];
    } elseif ($row_expense['category'] == 'monthly') {
        $total_monthly += $row_expense['amount'];
    } elseif ($row_expense['category'] == 'petty_cash') {
        $total_petty_cash += $row_expense['amount'];
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <!-- Dashboard Summary -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Daily Expenses</h5>
                        <h3>Rs. <?= number_format($total_daily, 2) ?>/-</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Monthly Expenses</h5>
                        <h3>Rs. <?= number_format($total_monthly, 2) ?>/-</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Petty Cash Expenses</h5>
                        <h3>Rs. <?= number_format($total_petty_cash, 2) ?>/-</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter by Date -->
        <div class="row mt-4">
            <div class="col-md-4">
                <label for="filter_date" class="form-label">Filter by Date</label>
                <input type="date" id="filter_date" class="form-control" value="<?= $filter_date ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="filterBtn" class="btn btn-primary me-2">Search</button>
                <button id="clearFilter" class="btn btn-secondary">Reset</button>
            </div>
        </div>

        <!-- Add Expense Form -->
        <div class="row mt-4">
            <div class="col-lg-6 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Add New Expense</h4>
                    </div>
                    <div class="card-body">
                        <form action="backend/addExpence.php" method="POST">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" name="amount" class="form-control" required step="0.01">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="daily">Daily</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="petty_cash">Petty Cash</option>
                                    <option value="vendor">Vendor</option> <!-- Added Vendor option -->
                                </select>
                            </div>

                            <!-- Vendor Dropdown -->
                            <div class="mb-3" id="vendorDropdown" style="display:none;">
                                <label for="vendor_id" class="form-label">Select Vendor</label>
                                <select name="vendor_id" id="vendor_id" class="form-control">
                                    <?php
                                    // Fetch all vendors
                                    $sql_vendors = "SELECT * FROM tbl_vendors";
                                    $result_vendors = $conn->query($sql_vendors);
                                    if ($result_vendors->num_rows > 0) {
                                        while ($vendor = $result_vendors->fetch_assoc()) {
                                            echo "<option value='" . $vendor['vendor_id'] . "'>" . $vendor['vendor_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No Vendors Available</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="expense_date" class="form-label">Expense Date</label>
                                <input type="date" name="expense_date" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Add Expense</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense List -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">All Expenses</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Vendor</th> <!-- Added Vendor column -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="expenseTable">
                                <?php
                                $rs_expenses = $conn->query($sql_expenses);
                                if ($rs_expenses->num_rows > 0) {
                                    while ($rows = $rs_expenses->fetch_assoc()) { ?>
                                        <tr id="row-<?= $rows['expense_id'] ?>">
                                            <td>Rs. <?= number_format($rows['amount'], 2) ?></td>
                                            <td><?= $rows['description'] ?></td>
                                            <td><span class="badge bg-info"><?= ucfirst($rows['category']) ?></span></td>
                                            <td><?= date('Y-m-d', strtotime($rows['expense_date'])) ?></td>
                                            <td>
                                                <?php
                                                // Display vendor name if the expense category is 'vendor'
                                                if ($rows['category'] == 'vendor' && $rows['vendor_name']) {
                                                    echo $rows['vendor_name'];
                                                } else {
                                                    echo "-"; // Show empty if no vendor is associated
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm delete-btn" onclick="del_expense(<?= $rows['expense_id'] ?>)">Delete</button>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No records found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Filter button click event
    document.getElementById("filterBtn").addEventListener("click", function () {
        var filterDate = document.getElementById("filter_date").value;
        window.location.href = "?filter_date=" + filterDate;
    });

    // Clear filter button click event
    document.getElementById("clearFilter").addEventListener("click", function () {
        window.location.href = "expenses.php";
    });

    document.getElementById("category").addEventListener("change", function() {
        var category = this.value;
        var vendorDropdown = document.getElementById("vendorDropdown");

        if (category === "vendor") {
            vendorDropdown.style.display = "block"; // Show vendor dropdown
        } else {
            vendorDropdown.style.display = "none"; // Hide vendor dropdown
        }
    });

    function del_expense(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: "POST",
                url: "./backend/deleteExpense.php",
                data: { exp_id: id },
                success: function(response) {
                    var dataResult = JSON.parse(response);
                    if (dataResult.statusCode == 200) {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: "Your expense has been deleted.",
                            confirmButtonClass: "btn btn-success"
                        }).then(() => {
                            location.reload(); // Refresh the page
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Failed to delete expense.",
                            confirmButtonClass: "btn btn-danger"
                        });
                    }
                }
            });
        }
    });
}
</script>

<?php include 'layouts/footer.php'; ?>
