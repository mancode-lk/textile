<?php include './backend/conn.php'; 
include './layouts/sidebar.php'; 
?>
<?php
if (!isset($_SESSION['user_logged'])) {
    header('location:./signin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="robots" content="noindex, nofollow">
<title>Vendor Admin</title>

<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<!-- Animation CSS -->
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

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Arial', sans-serif;
    }

    .content {
        padding: 20px;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .card-body {
        padding: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .form-select, .form-control {
        border-radius: 8px;
    }

    .search-section .input-group {
        border-radius: 8px;
        overflow: hidden;
    }

    .search-results-container {
        position: absolute;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
        display: none;
    }

    .search-item {
        padding: 12px;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
        cursor: pointer;
    }

    .search-item:hover {
        background-color: #f1f3f5;
    }

    .table th {
        background-color: #007bff;
        color: white;
        text-align: center;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .product-image {
        width: 50px;
        height: 50px;
        object-fit: contain;
        border-radius: 8px;
    }

    #selectedItems input[type="number"] {
        width: 70px;
        text-align: center;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-success {
        font-size: 18px;
        padding: 12px;
        border-radius: 8px;
    }
</style>

</head>
<body>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <!-- Vendor Selection & Search Panel -->
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary mb-3">Vendor Selection</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Select Vendor</label>
                                <select class="form-select form-select-lg" id="vendor_id" required>
                                    <option value="">Choose Vendor</option>
                                    <?php
                                        $vendors = $conn->query("SELECT * FROM tbl_vendors");
                                        while($vendor = $vendors->fetch_assoc()): ?>
                                    <option value="<?= $vendor['vendor_id'] ?>">
                                        <?= htmlspecialchars($vendor['vendor_name']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" id="purchase_date" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <!-- Product Search Section -->
                        <h5 class="card-title fw-bold text-primary mt-4">Search Products</h5>
                        <div class="search-section">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="search_name" 
                                       placeholder="🔍 Search products..." autofocus oninput="searchProd()">
                            </div>
                            <div id="searchResults" class="search-results-container mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Items Table -->
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary mb-3">Purchase Items</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="selectedItems" class="fw-semibold">
                                    <!-- Selected items will appear here -->
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                                        <td id="grandTotal">Rs 0.00</td>
                                        <td><button onclick="printBarcode(<?= $row['id'] ?>)" class="btn btn-sm btn-primary">Print Barcode</button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-success btn-lg" onclick="submitPurchase()">
                                <i class="fas fa-check-circle me-2"></i> Finalize Purchase
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End row -->
    </div> <!-- End content -->
</div> <!-- End page-wrapper -->

<script>
let purchaseItems = [];

function searchProd() {
    const searchTerm = document.getElementById('search_name').value.trim();
    const resultsContainer = document.getElementById('searchResults');
    
    if (searchTerm.length > 2) {
        fetch(`select_vendor_table.php?word=${encodeURIComponent(searchTerm)}`)
            .then(response => response.text())
            .then(data => {
                resultsContainer.innerHTML = data;
                resultsContainer.style.display = 'block';
            });
    } else {
        resultsContainer.style.display = 'none';
    }
}

function addToPurchase(productId, productName, price,selling_price, vendorName, vendorId, image = '') {
    const selectedVendor = document.getElementById('vendor_id').value;
    
    if (!selectedVendor) {
        document.getElementById('vendor_id').value = vendorId;
    } else if (selectedVendor != vendorId) {
        alert('❗ This product belongs to another vendor. Please change vendor selection.');
        return;
    }

    const existingItem = purchaseItems.find(item => item.id === productId);
    
    if (!existingItem) {
        purchaseItems.push({
            id: productId,
            name: productName,
            price: parseFloat(price),
            quantity: 1,
            vendor: vendorName,
            image: image,
            selling_price:selling_price
        });
    } else {
        existingItem.quantity++;
    }
    
    updatePurchaseTable();
    document.getElementById('search_name').value = '';
    document.getElementById('searchResults').style.display = 'none';
}

function updatePurchaseTable() {
    const tbody = document.getElementById('selectedItems');
    tbody.innerHTML = '';
    let grandTotal = 0;

    purchaseItems.forEach((item, index) => {
        const total = item.price * item.quantity;
        grandTotal += total;

        tbody.innerHTML += `
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <div class="fw-bold">${item.name}</div>
                            <small class="text-muted">${item.vendor}</small>
                        </div>
                    </div>
                </td>
                <td>Rs ${item.price.toFixed(2)}</td>
                <td>Rs ${item.selling_price.toFixed(2)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm" 
                           value="${item.quantity}" min="1"
                           onchange="updateQuantity(${index}, this.value)">
                </td>
                <td>Rs ${total.toFixed(2)}</td>
                <td>
                    <button class="btn btn-outline-danger btn-sm" 
                            onclick="removeItem(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" 
                            onclick="printBarcode(${item.id})">Print Barcode</button>
                </td>
            </tr>
        `;
    });

    document.getElementById('grandTotal').textContent = `Rs ${grandTotal.toFixed(2)}`;
}

function printBarcode(productId) {
    var printWindow = window.open('print_barcode.php?id=' + productId, '_blank');
    printWindow.focus();
}

function updateQuantity(index, value) {
    const qty = Math.max(1, parseInt(value) || 1);
    purchaseItems[index].quantity = qty;
    updatePurchaseTable();
}

function removeItem(index) {
    purchaseItems.splice(index, 1);
    updatePurchaseTable();
}

function submitPurchase() {
    const vendorId = document.getElementById('vendor_id').value;
    const purchaseDate = document.getElementById('purchase_date').value;
    
    if (!vendorId) {
        alert('Please select a vendor');
        return;
    }

    if (purchaseItems.length === 0) {
        alert('Please add at least one item');
        return;
    }

    const formData = new FormData();
    formData.append('vendor_id', vendorId);
    formData.append('purchase_date', purchaseDate);
    formData.append('items', JSON.stringify(purchaseItems));

    fetch('./backend/save_purchase.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Purchase saved successfully!');
            purchaseItems = [];
            updatePurchaseTable();
            document.getElementById('vendor_id').value = '';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the purchase');
    });
}
</script>
</body>
</html>
