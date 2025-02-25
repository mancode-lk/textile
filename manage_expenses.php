<?php
include 'layouts/header.php';
include 'layouts/sidebar.php';

// Initialize variables
$total_daily = 0;
$total_monthly = 0;
$total_petty_cash = 0;

// Handle Filters
// $filter_from = isset($_GET['filter_from']) ? $_GET['filter_from'] : '';
// $filter_to = isset($_GET['filter_to']) ? $_GET['filter_to'] : '';
// $filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
// $filter_month = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';

// SQL for total expenses today
$sql_daily = "SELECT SUM(amount) AS total FROM tbl_expenses WHERE DATE(expense_date) = CURDATE()";
$rs_daily = $conn->query($sql_daily);
$total_daily = ($rs_daily->num_rows > 0) ? $rs_daily->fetch_assoc()['total'] : 0;

// SQL for total expenses this month
$sql_monthly = "SELECT SUM(amount) AS total FROM tbl_expenses WHERE MONTH(expense_date) = MONTH(CURDATE()) AND YEAR(expense_date) = YEAR(CURDATE())";
$rs_monthly = $conn->query($sql_monthly);
$total_monthly = ($rs_monthly->num_rows > 0) ? $rs_monthly->fetch_assoc()['total'] : 0;

// Fetch Expenses with Vendor Name
// Initialize filter variables
$filter_from = isset($_GET['filter_from']) ? $_GET['filter_from'] : '';
$filter_to = isset($_GET['filter_to']) ? $_GET['filter_to'] : '';
$filter_month = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';

// Build SQL query for fetching expenses
$sql_expenses = "SELECT e.*, v.vendor_name 
                 FROM tbl_expenses e 
                 LEFT JOIN tbl_vendors v ON e.vendor_id = v.vendor_id 
                 WHERE 1"; // Using WHERE 1 for easier filtering

// Apply date range filter if set
if ($filter_from && $filter_to) {
    $sql_expenses .= " AND e.expense_date BETWEEN '$filter_from' AND '$filter_to'";
} elseif ($filter_from) {
    $sql_expenses .= " AND e.expense_date >= '$filter_from'";
} elseif ($filter_to) {
    $sql_expenses .= " AND e.expense_date <= '$filter_to'";
}

// Apply month filter if set
if ($filter_month) {
    $month = date('m', strtotime($filter_month));
    $year = date('Y', strtotime($filter_month));
    $sql_expenses .= " AND MONTH(e.expense_date) = '$month' AND YEAR(e.expense_date) = '$year'";
}

$sql_expenses .= " ORDER BY e.expense_date DESC";
$rs_expenses = $conn->query($sql_expenses);
// Apply Filters
// $conditions = [];

// if ($filter_from && $filter_to) {
//     $conditions[] = "e.expense_date BETWEEN '$filter_from' AND '$filter_to'";
// } elseif ($filter_from) {
//     $conditions[] = "e.expense_date >= '$filter_from'";
// } elseif ($filter_to) {
//     $conditions[] = "e.expense_date <= '$filter_to'";
// }

// // Filter by specific date
// if ($filter_date) {
//     $conditions[] = "DATE(e.expense_date) = '$filter_date'";
// }

// // Filter by specific month
// if ($filter_month) {
//     $conditions[] = "MONTH(e.expense_date) = MONTH('$filter_month') AND YEAR(e.expense_date) = YEAR('$filter_month')";
// }

// // Append conditions to SQL query
// if (!empty($conditions)) {
//     $sql_expenses .= " WHERE " . implode(" AND ", $conditions);
// }

// $sql_expenses .= " ORDER BY e.expense_date DESC";
// $rs_expenses = $conn->query($sql_expenses);
?>

<div class="page-wrapper">
    <div class="content">
        <!-- Dashboard Summary -->
        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Daily Expenses</h5>
                        <h3>Rs. <?= number_format($total_daily, 2) ?>/-</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Monthly Expenses</h5>
                        <h3>Rs. <?= number_format($total_monthly, 2) ?>/-</h3>
                    </div>
                </div>
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


        <!-- Filter by Date or Month -->
        <div class="row mt-4">
    <div class="col-md-4">
        <label for="filter_from" class="form-label">From</label>
        <input type="date" id="filter_from" class="form-control" value="<?= $filter_from ?>">
    </div>
    <div class="col-md-4">
        <label for="filter_to" class="form-label">To</label>
        <input type="date" id="filter_to" class="form-control" value="<?= $filter_to ?>">
    </div>
    <div class="col-md-4">
        <label for="filter_month" class="form-label">Select Month</label>
        <input type="month" id="filter_month" class="form-control" value="<?= $filter_month ?>">
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button id="filterBtn" class="btn btn-primary me-2">Search</button>
        <button id="clearFilter" class="btn btn-secondary">Reset</button>
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
                                    <th>Vendor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="expenseTable">
                                <?php
                                if ($rs_expenses->num_rows > 0) {
                                    while ($rows = $rs_expenses->fetch_assoc()) { ?>
                                        <tr id="row-<?= $rows['expense_id'] ?>">
                                            <td>Rs. <?= number_format($rows['amount'], 2) ?></td>
                                            <td><?= $rows['description'] ?></td>
                                            <td><span class="badge bg-info"><?= ucfirst($rows['category']) ?></span></td>
                                            <td><?= date('Y-m-d', strtotime($rows['expense_date'])) ?></td>
                                            <td>
                                                <?= ($rows['category'] == 'vendor' && $rows['vendor_name']) ? $rows['vendor_name'] : "-" ?>
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
   document.getElementById("filterBtn").addEventListener("click", function () {
    var filterFrom = document.getElementById("filter_from").value;
    var filterTo = document.getElementById("filter_to").value;
    var filterMonth = document.getElementById("filter_month").value;
    
    var url = "?";
    if (filterFrom) url += "filter_from=" + filterFrom + "&";
    if (filterTo) url += "filter_to=" + filterTo + "&";
    if (filterMonth) url += "filter_month=" + filterMonth + "&";
    
    window.location.href = url.slice(0, -1); // Remove last '&'
});

document.getElementById("clearFilter").addEventListener("click", function () {
    window.location.href = "manage_expenses.php";
});
    //     // Filter button click event
    //     document.getElementById("filterBtn").addEventListener("click", function () {
    //     var filterDate = document.getElementById("filter_date").value;
    //     window.location.href = "?filter_date=" + filterDate;
    // });

    // // Clear filter button click event
    // document.getElementById("clearFilter").addEventListener("click", function () {
    //     window.location.href = "expenses.php";
    // });

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
