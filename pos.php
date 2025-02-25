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
      <h1 class="text-center text-primary">
        <i class="fas fa-store me-2"></i> POS System
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
            <h5 class="mb-3">Items</h5>
            <div class="list-group scrollable mb-4">
              <!-- Cart Item 1 -->
              <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-1">Top</h6>
                  <small class="text-muted">Price: LKR 2200.00</small>
                </div>
                <div class="d-flex align-items-center">
                  <input type="number" value="1" min="1" class="form-control form-control-sm me-2" style="width: 80px;" />
                  <span class="fw-bold me-2">LKR 2200.00</span>
                  <button class="btn btn-sm btn-danger">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
              <!-- Additional cart items can be added here -->
            </div>

            <!-- Bill Details -->
            <h5 class="mb-3">Bill Details</h5>
            <div class="row g-3 align-items-center">
              <!-- Subtotal -->
              <div class="col-6">
                <p class="mb-0">Subtotal: <span class="fw-bold">LKR 2200.00</span></p>
              </div>
              <!-- Product Discount -->
              <div class="col-6">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                  <input type="text" class="form-control" placeholder="Prod Disc (10% or LKR100)" />
                </div>
              </div>
              <!-- Bill Discount -->
              <div class="col-6">
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-percent"></i></span>
                  <input type="text" class="form-control" placeholder="Bill Disc (5% or LKR200)" />
                </div>
              </div>
              <!-- Total -->
              <div class="col-6 text-end">
                <p class="h5 mb-0">Total: <span class="fw-bold">LKR 2200.00</span></p>
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
                    <input class="form-check-input" type="radio" name="payment" id="cash" value="cash" checked />
                    <label class="form-check-label" for="cash">Cash</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment" id="card" value="card" />
                    <label class="form-check-label" for="card">Card</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment" id="credit" value="credit" />
                    <label class="form-check-label" for="credit">Credit</label>
                  </div>
                </div>
              </div>
              <!-- Amount Paid -->
              <div class="col-md-4">
                <label class="form-label">
                  <i class="fas fa-hand-holding-usd me-1"></i>Amount Paid (LKR)
                </label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                  <input type="number" class="form-control" placeholder="Enter amount" />
                </div>
              </div>
              <!-- Change -->
              <div class="col-md-4 text-end">
                <label class="form-label d-block">
                  <i class="fas fa-exchange-alt me-1"></i>Change
                </label>
                <p class="h5 mb-0 fw-bold">LKR 0.00</p>
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
                <!-- Search Result Item 1 -->

                <!-- Additional search results can be appended here -->
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
$(document).ready(function () {
    $("#searchInput").keypress(function (event) {
        if (event.which === 13) { // 13 is the Enter key
            event.preventDefault(); // Prevent form submission if inside a form

            var searchInput = $("#searchInput").val(); // Get the input value

            if (searchInput.trim() !== "") { // Ensure input is not empty
                $('#list_item_search').load('ajax/list_item_search.php', { skey: searchInput });
                // alert('hello');
            }
        }
    });
});

</script>
