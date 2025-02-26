<?php
  include 'backend/conn.php';

  if (!isset($_SESSION['grm_ref'])) {
      $order_ref = generateOrderRef($conn);
      $sqlCreate = "INSERT INTO tbl_order_grm (order_ref, order_st) VALUES ('$order_ref', 0)";
      $rsCreate = $conn->query($sqlCreate);

      $grm_id = $conn->insert_id;
      $_SESSION['grm_ref'] = $grm_id;
  } else {
      $grm_id = $_SESSION['grm_ref'];
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>POS System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    .scrollable {
      max-height: 300px;
      overflow-y: auto;
    }
    .scrollable-results {
      max-height: 200px;
      overflow-y: auto;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container my-4">
    <!-- Header -->
    <header class="mb-4">
      <a href="pos_grm.php" class="btn btn-outline-dark btn-lg px-4 py-2 fw-bold shadow-sm">
         <i class="fas fa-receipt me-2"></i> GO TO BILLS
      </a>
      <h1 class="text-center text-primary">
        <i class="fas fa-store me-2"></i> POS System BILL ID: 00<?= $grm_id ?>
      </h1>
    </header>

    <!-- Two-column layout: Left = Cart Summary & Bill, Right = Product Search -->
    <div class="row g-4">
      <!-- Left Column: Cart Summary & Bill -->
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Cart Summary & Bill</h4>
          </div>
          <div class="card-body">
            <!-- Cart Items List (Scrollable) -->
            <div class="row">
              <div class="col-lg-4">
                <h5 class="mb-3">Items</h5>
              </div>
              <div class="col-lg-8">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                  <input type="text" class="form-control" id="barcodeInput" placeholder="Add Items By Barcode Search" />
                </div>
              </div>
            </div>
            <div class="list-group scrollable mb-4" id="showCartItems">
              <!-- Cart items will be loaded here -->
            </div>

            <!-- Bill Details -->
            <h5 class="mb-3">Bill Details</h5>
            <div class="row g-3 align-items-center">
              <!-- Product Discount -->
              <div class="col-6">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                  <input type="text" id="discount_amount" onkeyup="discountBill(this.value)" class="form-control" placeholder="Bill Disc (LKR200)" />
                </div>
              </div>
              <!-- Total -->
              <div class="col-6 text-end" id="totalValue">
                <!-- Bill total will be loaded here -->
              </div>
            </div>
            <hr />
            <!-- Payment Options -->
            <div class="row g-3 align-items-center">
              <div class="col-md-4">
                <label class="form-label mb-0">
                  <i class="fas fa-money-bill-wave me-1"></i>Payment Method
                </label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment" id="payment_method" value="0" checked />
                    <label class="form-check-label" for="cash">Cash</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment" id="payment_method" value="1" />
                    <label class="form-check-label" for="card">Online Pay</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment" id="payment_method" value="2" />
                    <label class="form-check-label" for="credit">Bank Transfer</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment" id="payment_method" value="3" />
                    <label class="form-check-label" for="credit">Credit</label>
                  </div>
                </div>
              </div>
              <input type="hidden" id="totPrice" value="">
              <!-- Amount Paid -->
              <div class="col-md-4">
                <label class="form-label">
                  <i class="fas fa-hand-holding-usd me-1"></i>Amount Paid (LKR)
                </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                  <input type="number" id="paid_amount" onkeyup="showBalance()" class="form-control" placeholder="Enter amount" />
                </div>
              </div>
              <!-- Change -->
              <div class="col-md-4 text-end">
                <label class="form-label d-block">
                  <i class="fas fa-exchange-alt me-1"></i>Change
                </label>
                <p class="h5 mb-0 fw-bold">LKR <span id="balanceToGive"></span>.00</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-6">
                <button type="button" class="btn btn-primary btn-sm" id="complete_bill">Complete Bill</button>
              </div>
              <div class="col-lg-6">
                <button type="button" class="btn btn-secondary btn-sm" id="add_to_draft">Add to Draft</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Product Search & Filters -->
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-search me-2"></i>Product Search</h4>
          </div>
          <div class="card-body">
            <!-- Search Form -->
            <form>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="searchInput" class="form-label">Product Name or Barcode</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Enter search term" />
                  </div>
                </div>
                <div class="col-md-2">
                  <label for="sizeFilter" class="form-label">Size</label>
                  <select id="sizeFilter" class="form-select">
                    <option value="">All</option>
                    <option value="S">Small</option>
                    <option value="M">Medium</option>
                    <option value="L">Large</option>
                    <option value="XL">Extra Large</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="colorFilter" class="form-label">Color</label>
                  <select id="colorFilter" class="form-select">
                    <option value="">All</option>
                    <option value="red">Red</option>
                    <option value="blue">Blue</option>
                    <option value="green">Green</option>
                    <option value="black">Black</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="categoryFilter" class="form-label">Category</label>
                  <select id="categoryFilter" class="form-select">
                    <option value="">All</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="accessories">Accessories</option>
                    <option value="others">Others</option>
                  </select>
                </div>
              </div>
            </form>
            <!-- Search Results (Scrollable) -->
            <div class="mt-4 scrollable-results">
              <ul class="list-group" id="list_item_search">
                <!-- Search results will be loaded here -->
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.row -->
  </div><!-- /.container -->

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery (Required for AJAX) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>

<script type="text/javascript">
$(document).ready(function() {
    // Barcode scanning event
    $("#barcodeInput").on("keypress", function(event) {
        if (event.which === 13) {
            event.preventDefault();
            let barcode = $(this).val().trim();
            if (barcode !== "") {
                cartItemBarcode(barcode);
                setTimeout(() => {
                    $(this).val("").focus();
                }, 1000);
            }
        }
    });

    // Product search with debouncing
    let searchTimeout;
    let searchAjaxRequest;
    $("#searchInput").on("keyup", function() {
        clearTimeout(searchTimeout);
        let searchInput = $(this).val().trim();
        if (searchInput === "") {
            $("#list_item_search").html("");
            return;
        }
        searchTimeout = setTimeout(function() {
            if (searchAjaxRequest) {
                searchAjaxRequest.abort();
            }
            searchAjaxRequest = $.ajax({
                type: "POST",
                url: "ajax/list_item_search.php",
                data: { skey: searchInput },
                beforeSend: function() {
                    $("#list_item_search").html("<p>Loading...</p>");
                },
                success: function(response) {
                    $("#list_item_search").html(response);
                },
                error: function(xhr, status, error) {
                    if (status !== "abort") {
                        console.error("AJAX Error:", error);
                    }
                }
            });
        }, 300);
    });

    // Complete bill click event
    $("#complete_bill").click(function() {
        let discount_amount = $("#discount_amount").val() || 0;
        let payment_method = $("input[name='payment']:checked").val();
        $.ajax({
            url: "backend/save_bill.php",
            method: "POST",
            data: {
                discount_amount: discount_amount,
                payment_method: payment_method,
                action: "complete_bill"
            },
            beforeSend: function() {
                $("#complete_bill").prop("disabled", true).text("Processing...");
            },
            success: function(response) {
                if (response == 200) {
                    window.location.href = "pos_grm.php";
                } else {
                    alert("Error: " + response);
                    $("#complete_bill").prop("disabled", false).text("Complete Bill");
                }
            },
            error: function(xhr, status, error) {
                alert("Failed to complete bill. Try again.");
                $("#complete_bill").prop("disabled", false).text("Complete Bill");
                console.error(error);
            }
        });
    });

    // Add to draft click event
    $("#add_to_draft").click(function() {
        let discount_amount = $("#discount_amount").val() || 0;
        let payment_method = $("input[name='payment']:checked").val();
        $.ajax({
            url: "backend/save_bill.php",
            method: "POST",
            data: {
                discount_amount: discount_amount,
                payment_method: payment_method,
                action: "add_to_draft"
            },
            beforeSend: function() {
                $("#add_to_draft").prop("disabled", true).text("Saving...");
            },
            success: function(response) {
                if (response == 200) {
                    alert("Bill saved as draft!");
                    window.location.href = "pos_grm.php";
                } else {
                    alert("Error: " + response);
                    $("#add_to_draft").prop("disabled", false).text("Add to Draft");
                }
            },
            error: function(xhr, status, error) {
                alert("Failed to save draft.");
                $("#add_to_draft").prop("disabled", false).text("Add to Draft");
                console.error(error);
            }
        });
    });
});

// Function to update bill total and store value in hidden totPrice field
function calculateTotal(){
    $('#totalValue').load('ajax/bill_total.php', function(response, status, xhr) {
        $('#totPrice').val(response);
    });
}

// Function to update total with discount applied and update totPrice field
function discountBill(price){
    $('#totalValue').load('ajax/bill_total.php', {disc_price: price}, function(response, status, xhr) {
        $('#totPrice').val(response);
    });
}

// Function to update balance display based on paid amount and total price
function showBalance() {
    let totPrice = parseFloat(document.getElementById('totPrice').value) || 0;
    let paid_amount = parseFloat(document.getElementById('paid_amount').value) || 0;
    let balance = paid_amount - totPrice;
    document.getElementById('balanceToGive').innerHTML = balance.toFixed(2);
}

// Function to add an item to the cart using a barcode
function cartItemBarcode(barcode) {
    $.ajax({
        url: 'backend/add_item_cart_barcode.php',
        method: 'POST',
        data: { bcode: barcode },
        dataType: "json",
        success: function(resp) {
            if (resp.statusCode === 200) {
                alert("✔ Item Added Successfully!");
                let paid_amount = parseFloat(document.getElementById('paid_amount').value) || 0;
                if (paid_amount !== 0) {
                    showBalance();
                }
                let discountValue = document.getElementById('discount_amount').value;
                if (discountValue !== "") {
                    discountBill(discountValue);
                }
                else {
                  calculateTotal();
                }

                $('#showCartItems').load('ajax/cart_items.php');
            } else if (resp.statusCode === 400) {
                alert("⚠ " + resp.message);
            } else {
                alert("❌ Something went wrong! Please try again.");
            }
        },
        error: function() {
            alert("❌ Network Error! Failed to add item. Check your connection.");
        }
    });
}

// Function to add an item to the order with a specific quantity
function addToOrders(id, qnty) {
    $.ajax({
        url: 'backend/add_item_cart.php',
        method: 'POST',
        data: { p_id: id, qty: qnty },
        dataType: "json",
        success: function(resp) {
            if (resp.statusCode === 200) {
                alert("✔ Item Added Successfully!");
                let paid_amount = parseFloat(document.getElementById('paid_amount').value) || 0;
                if (paid_amount !== 0) {
                    showBalance();
                }
                let discountValue = document.getElementById('discount_amount').value;
                if (discountValue !== "") {
                    discountBill(discountValue);
                }
                else {
                  calculateTotal();
                }
                $('#showCartItems').load('ajax/cart_items.php');
            } else if (resp.statusCode === 400) {
                alert("⚠ " + resp.message);
            } else {
                alert("❌ Something went wrong! Please try again.");
            }
        },
        error: function() {
            alert("❌ Network Error! Failed to add item. Check your connection.");
        }
    });
}

// Function to delete an item from the cart
function del_item_cart(id) {
    $.ajax({
        url: 'backend/delete_cart_item.php',
        method: 'POST',
        data: { order_id: id },
        success: function(resp) {
            if (resp == 200) {
                let paid_amount = parseFloat(document.getElementById('paid_amount').value) || 0;
                if (paid_amount !== 0) {
                    showBalance();
                }
                let discountValue = document.getElementById('discount_amount').value;
                if (discountValue !== "") {
                    discountBill(discountValue);
                }
                else {
                  calculateTotal();
                }
                $('#showCartItems').load('ajax/cart_items.php');
            } else {
                alert('Something went wrong');
            }
        }
    });
}

let updateTimeout;
let updateAjaxRequest; // renamed to avoid conflicts

// Function to update item quantity with a debounce delay
function updateQnty(id, qnty) {
    clearTimeout(updateTimeout);
    updateTimeout = setTimeout(() => {
        if (updateAjaxRequest) {
            updateAjaxRequest.abort();
        }
        updateAjaxRequest = $.ajax({
            url: 'backend/update_qnty.php',
            method: 'POST',
            data: { order_id: id, qty: qnty },
            success: function(resp) {
                if (resp == 200) {
                    let paid_amount = parseFloat(document.getElementById('paid_amount').value) || 0;
                    if (paid_amount !== 0) {
                        showBalance();
                    }
                    let discountValue = document.getElementById('discount_amount').value;
                    if (discountValue !== "") {
                        discountBill(discountValue);
                    }
                    else {
                      calculateTotal();
                    }
                    $('#showCartItems').load('ajax/cart_items.php');
                } else {
                    console.error('Update failed:', resp);
                }
            },
            error: function(xhr, status) {
                if (status !== "abort") {
                    console.error('AJAX error:', xhr.responseText);
                }
            }
        });
    }, 200);
}

// Initial load of cart items and bill total (with totPrice update)
$(document).ready(function() {
    $('#showCartItems').load('ajax/cart_items.php');
    $('#totalValue').load('ajax/bill_total.php', function(response, status, xhr) {
        $('#totPrice').val(response);
    });
});
</script>
