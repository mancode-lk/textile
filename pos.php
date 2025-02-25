<?php include './backend/conn.php'; ?>

<?php
	if(!isset($_SESSION['user_logged'])){
	header('location:./signin.php');
	exit();
	}

	if($_REQUEST['or_date']!=""){

		$order_date = $_REQUEST['or_date'];


	}else{
			$order_date = date("Y-m-d"); 
				}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="robots" content="noindex, nofollow">
        <title>POS Admin</title>

		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">

		<!-- animation CSS -->
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

    </head>
    <body>

		<div id="global-loader" >
			<div class="whirly-loader"> </div>
		</div>

		<div class="main-wrappers">
			<div class="header">
				<!-- Logo -->
				 <div class="header-left border-0 ">

					

				</div>
				<!-- /Logo -->

				<!-- Header Menu -->
				<ul class="nav user-menu">

					<!-- Search -->
					<li class="nav-item">
						<div class="top-nav-search">

							<a href="javascript:void(0);" class="responsive-search">
								<i class="fa fa-search"></i>
						</a>
							<form action="#">
								<div class="searchinputs">
									<input type="text" placeholder="Search Here ...">
									<div class="search-addon">
										<span><img src="assets/img/icons/closes.svg" alt="img"></span>
									</div>
								</div>
								<a class="btn" id="searchdiv"><img src="assets/img/icons/search.svg" alt="img"></a>
							</form>
						</div>
					</li>
					<!-- /Search -->

					<!-- Flag -->
					<!-- /Flag -->

					<!-- Notifications -->
					<!-- /Notifications -->

					<li class="nav-item dropdown has-arrow main-drop">
						<div class="dropdown-menu menu-drop-user">
							<div class="profilename">
								<div class="profileset">
									<span class="status online"></span></span>
									<div class="profilesets">
										
										<h5>Admin</h5>
									</div>
								</div>
								<hr class="m-0">
								<a class="dropdown-item" href="profile.html"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user me-2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> My Profile</a>
								<a class="dropdown-item" href="generalsettings.html"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings me-2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>Settings</a>
								<hr class="m-0">
								<a class="dropdown-item logout pb-0" href="backend/logout.php"><img src="assets/img/icons/log-out.svg" class="me-2" alt="img">Logout</a>
							</div>
						</div>
					</li>
				</ul>
				<!-- /Header Menu -->

				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="profile.html">My Profile</a>
						<a class="dropdown-item" href="generalsettings.html">Settings</a>
						<a class="dropdown-item" href="signin.html">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->
			</div>

			<div class="page-wrapper ms-0">
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-sm-12 tabs_wrapper">
                <div class="row">
                    <div class="col-md-4">
                        <div class="page-header">
                            <div class="page-title">
                                <a href="pos_grm.php" class="btn btn-adds" data-bs-target="#create">Go Back</a>
                                <h4>Categories</h4>
                                <h6>Manage your purchases</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="order-list">
                            <div class="orderid">
                                <h4>Order List</h4>
                                <h5>Order Date: <?= $order_date ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Scanner Section -->
                <div class="row form-group">
					<div class="col-md-5">
						<input id="search_name" type="text" class="form-control" 
							placeholder="Scan or Type Product Name/Barcode" 
							oninput="searchProd()" autofocus>
					</div>
					<!-- <div class="col-md-3">
						<button onclick="searchProd()" class="btn btn-primary w-100">Search Product</button>
					</div> -->
					<div class="col-md-4 text-end">
						<a onclick="searchProd()" class="btn btn-filters">
							<img src="assets/img/icons/search-whites.svg" alt="Search">
						</a>
					</div>
				</div>

                <!-- Categories Carousel -->
               

                <div class="tabs_container" id="product_view"></div>
            </div>

            <!-- Order Summary Section -->
            <div class="col-lg-9 col-sm-12">
    <form action="./backend/order.php" method="post">
        <input type="hidden" name="order_date" value="<?= $order_date ?>">

        <div class="card card-order">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="#" class="btn btn-adds mb-2" data-bs-toggle="modal" data-bs-target="#create">
                            <i class="fa fa-plus me-2"></i>Add Customer
                        </a>
                    </div>
                    <div class="col-lg-12">
                        <input disabled class="form-control mb-2" type="text" name="customer" id="customer" placeholder="Customer Name">
                        <input type="hidden" name="customer_id" id="customer_id">
                    </div>
                    <div class="col-lg-12 mb-2">
                        <select class="form-select" id="store_id" name='store_id'>
                            <option value="2">Textile</option>
                        </select>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <select class="form-select" id="discount_type_main" name='discount_type' onchange="setDiscount()">
                            <option value="percentage">Percentage</option>
                            <option value="fixed_amount">Fixed Amount</option>
                        </select>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <input class="form-control" type="text" name="discount_total" id="discount_total" onkeyup="setDiscount()" placeholder="Discount">
                    </div>
                    <div class="col-lg-4 mb-2">
                        <input class="form-control" type="text" id="delivery_charge" name="delivery_charge" onkeyup="totalValue()" value="0" placeholder="Delivery Charge">
                    </div>

                    <div class="col-12 mb-2">
                        <select name="pay_st" class="form-select">
                            <option value="2">PAID</option>
                            <option value="1">NOT PAID</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="split-card"></div>

            <div class="card-body pt-0">
                <div class="totalitem">
                    <h5>Total items: <span id="total_items">0</span></h5>
                    <a onclick="clearAll()" href="javascript:void(0);">Clear all</a>
                </div>
                <div id='orderlist' class="product-table"></div>
            </div>

            <div class="split-card"></div>

            <div class="card-body pt-0 pb-2">
                <div class="setvalue">
                    <ul>
                        <li class="total-value">
                            <h5>Sub Total</h5>
                            <h6>Rs <span id='sub_total'>00</span></h6>
                        </li>
                        <li class="total-value">
                            <h5>Total</h5>
                            <h6>Rs <span id='total'>00</span></h6>
                        </li>
                    </ul>
                </div>

                <div class="setvaluecash text-center">
                    <ul class="d-flex justify-content-around p-0">
                        <li>
                            <button type="submit" class="btn btn-outline-success w-100 py-2" name="button" value="0">Cash</button>
                        </li>
                        <li>
                            <button type="submit" class="btn btn-outline-success w-100 py-2" name="button" value="1">Online Payment</button>
                        </li>
                        <li>
                            <button type="submit" class="btn btn-outline-success w-100 py-2" name="button" value="2">Bank Transfer</button>
                        </li>
                        <li>
                            <button type="submit" class="btn btn-outline-success w-100 py-2" name="button" value="3">Credit</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End of Order Summary -->
        </div> <!-- End of Row -->
    </div> <!-- End of Content -->
</div> <!-- End of Page Wrapper -->



			
		</div>



		<div class="modal fade" id="create" tabindex="-1" aria-labelledby="create"  aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						 <h5 class="modal-title" >Create</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-6 col-sm-12 col-12">
								<div class="form-group">
									<label>Customer Name</label>
									<input name="customer_name" id="customer_name" type="text">
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 col-12">
								<div class="form-group">
									<label>Email</label>
									<input name="email" id="email" type="text">
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 col-12">
								<div class="form-group">
									<label>Phone</label>
									<input name="phone" id="phone" type="text">
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 col-12">
								<div class="form-group">
									<label>City</label>
									<input name="city" id="city" type="text" >
								</div>
							</div>
							<div class="col-lg-6 col-sm-12 col-12">
								<div class="form-group">
									<label>Address</label>
									<input name="address" id="address" type="text" >
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<a onclick="addCustomer()" class="btn btn-submit me-2">Submit</a>
							<a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>


	<?php include './layouts/footer.php' ?>
	<script src="https://unpkg.com/quagga"></script>
	<script type="text/javascript">

	$('#product_view').load('pos_table.php');
	$('#orderlist').load('pos_order_table.php');

	function startScanner() {
    document.getElementById('scanner').style.display = 'block';

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector("#scanner"),
            constraints: {
                facingMode: "environment" // Use the rear camera
            }
        },
        decoder: {
            readers: ["ean_reader", "code_128_reader"] // Supports EAN, Code 128, etc.
        }
    }, function(err) {
        if (err) {
            console.error(err);
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(function(result) {
        let barcode = result.codeResult.code;
        document.getElementById('search_name').value = barcode;
        Quagga.stop();
        document.getElementById('scanner').style.display = 'none';
        searchProd(); // Automatically trigger search
    });
}

function searchProd(event) {
    var word = document.getElementById('search_name').value.trim();

    if (word.length > 0) {
        $('#product_view').load('pos_table.php', {
            word: word
        });

		setTimeout(() => {
            document.getElementById("search_name").value = "";
        }, 3000);
    }
	
}





	function addCustomer(){
		var name= document.getElementById("customer_name").value;
		var email= document.getElementById("email").value;
		var phone= document.getElementById("phone").value;
		var city= document.getElementById("city").value;
		var address= document.getElementById("address").value;

		$.ajax({
				method: "POST",
				url: "./backend/add_customer.php",
				data:{
					name: name,
					email: email,
					phone: phone,
					city: city,
					address: address
				},
				success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						document.getElementById("customer").value = name;
						document.getElementById("customer_id").value = dataResult.customer_id;
						$('#create').modal('hide');
					}
				}
				});


	}



	function clearAll(){
		cart.length =0;
		quantities.length =0;
		$('#orderlist').load('pos_order_table.php',{
			prod_ids : JSON.stringify(cart)
		});
		document.getElementById("total").textContent = '00';
		document.getElementById("sub_total").textContent = '00';
		document.getElementById("total_items").textContent = 0;
	}


	function updateView(cat_id, cat_name){
		const categories = document.querySelectorAll(".product_cat");
		categories.forEach(function(category) {
			  category.classList.remove("active");
			});
			document.getElementById(cat_name).classList.add("active");



		$('#product_view').load('pos_table.php',{
			cat_id : cat_id
		});
	}

	let cart = [];
	let quantities = [];


	function selectProduct(prodId,av_anty){
		if(av_anty == 0){
			alert('Out Of Stock');
			return;
		}
		
		if(cart.length > 0){
			cart.forEach(function(id) {
				var quantity_val = document.getElementById(`quantity${id}`).value;
				var discount_val = document.getElementById(`discount${id}`).value;
				var price_val = document.getElementById(`m_price${id}`).value;
				var dis_type = document.getElementById(`discount_type${id}`).value;
				var final_price = document.getElementById(`final_price${id}`).value;
				// console.log(final_price);

				exist= false;
				for (var i = 0; i < quantities.length; i++) {
					if (quantities[i].id === id) {
						exist = true;
						break;
					}
				}
				if(!exist){
					quantities.push({ 'id' : id, 'quantityValue': quantity_val,'discountValue': discount_val, 'm_price': price_val, 'final_price':final_price, 'dis_type':dis_type});

				}else{
					quantities[i].dis_type = dis_type;
					quantities[i].quantityValue = quantity_val;
					quantities[i].m_price = price_val;
					quantities[i].discountValue = discount_val;
					quantities[i].final_price = final_price;
				}

			});
			// console.log(quantities);
			if(!cart.includes(prodId)) {
		    cart.unshift(prodId);
			}else{

					for (var i = 0; i < quantities.length; i++) {
				    if (quantities[i].id === prodId) {
				      quantities[i].quantityValue++;
				      break;
				    }
				  }
			}
		}else{
			cart.push(prodId);
		}
		$('#orderlist').load('pos_order_table.php',{
			prod_ids : JSON.stringify(cart)
		}, function (){
			if(quantities.length > 0){

				quantities.forEach(function(quantity) {
					var id = quantity.id;
					var q_value = quantity.quantityValue;
					var m_price = quantity.m_price;
					var d_value = quantity.discountValue;
					var d_type = quantity.dis_type;
					var final_price = quantity.final_price;

					document.getElementById(`quantity${id}`).value = q_value;
					document.getElementById(`m_price${id}`).value = m_price;
					document.getElementById(`discount${id}`).value = d_value;
					document.getElementById(`discount_type${id}`).value = d_type;
					document.getElementById(`final_price${id}`).value = final_price;
				});

			}
			var items = document.querySelectorAll('.price').length;
			document.getElementById("total_items").textContent = items;
			setDiscount();
			totalValue();
		});


	}
function changePrice(price,p_id){

	document.getElementById('m_price_'+p_id).innerHTML = price;
}

	function totalValue(qnty,ent_value,res_id){
		if(ent_value > qnty){
			document.getElementById('quantity'+res_id).value=qnty;
			alert('Quantity cannot exceed with available stock');
		}
		// var discount_type = document.getElementById('discount_type').value;

		total = 0.00;
		var prices = document.querySelectorAll('.price');

		var quantity_vals = document.querySelectorAll('.quantity_val');
		var discounts = document.querySelectorAll('.discount');
		var discount_types = document.querySelectorAll('.discount_type');

		var ori_prices = document.querySelectorAll('.original_price');
		var final_prices = document.querySelectorAll('.final_price');
		var del_charge = parseInt(document.getElementById('delivery_charge').value);

		// alert(discounts);
		// alert(discount_types);
		// alert(ori_prices);
		// alert(final_prices);
		// alert(del_charge);
		// exit();

		// for(i=0; i <prices.length; i++){
		// 	price = parseInt(prices[i].value);
		// 	// ori_price = parseInt(ori_prices[i].innerText);
		// 	if(quantity_vals[i].value){
		// 		quantity = parseInt(quantity_vals[i].value);
		// 	}else{
		// 		quantity = 0;
		// 	}
		// 	if(discounts[i].value){
		// 		discount = parseInt(discounts[i].value);
		// 	}else{
		// 		discount = 0.00;
		// 	}
		// 	if(discount[i].value){
		// 		if(discount_types[i].value == "percentage"){
		// 			newPrice = price*(1-discount/100)
		// 			total = total + price*quantity*(1-discount/100);
		// 		}else{
		// 			newPrice = (price-discount)
		// 			total = total + quantity*price - discount;
		// 		}
		// 	}else{
		// 		total = total + price;
		// 	}
		//
		// 	if(price != newPrice){
		// 		let newPriceElem = ori_prices[i].nextElementSibling;
		// 		if (!newPriceElem || newPriceElem.id !== 'new-price') {
		// 		  // Create a new price element if it doesn't already exist
		// 		  newPriceElem = document.createElement('span');
		// 		  newPriceElem.id = 'new-price';
		// 		  ori_prices[i].after(newPriceElem);
		// 		}
		//
		// 		newPriceElem.innerText = ` Rs ${newPrice.toFixed(2)}`;
		// 		prices[i].value = newPrice.toFixed(2);
		// 		ori_prices[i].style.textDecoration = 'line-through';
		// 		ori_prices[i].after(newPriceElem);
		// 	}
		// }
		for(i=0; i <prices.length; i++){
			price = parseInt(prices[i].value);
			ori_price = parseInt(ori_prices[i].innerText);
			if(quantity_vals[i].value){
				quantity = parseInt(quantity_vals[i].value);
			}else{
				quantity = 0;
			}
			if(discounts[i].value){
				discount = parseInt(discounts[i].value);
			}else{
				discount = 0.00;
			}

			if (discount_types[i].value == "percentage") {
			newPrice = Math.round(price * (1 - discount / 100));
			total += Math.round(price * quantity * (1 - discount / 100));
		} else {
			// Ensure discount does not exceed price
			let discountAmount = Math.min(discount, price);
			newPrice = Math.round(price - discountAmount);
			total += Math.round(quantity * (price - discountAmount));
		}


			// console.log(ori_price);
			if(ori_price != newPrice){
				let newPriceElem = ori_prices[i].nextElementSibling;
				if (!newPriceElem || newPriceElem.id !== 'new-price') {
				  // Create a new price element if it doesn't already exist
				  newPriceElem = document.createElement('span');
				  newPriceElem.id = 'new-price';
				  ori_prices[i].after(newPriceElem);
				}

				newPriceElem.innerText = ` Rs ${newPrice.toFixed(2)}`;
				final_prices[i].value = newPrice.toFixed(2);
				ori_prices[i].style.textDecoration = 'line-through';
				ori_prices[i].after(newPriceElem);
			}
		}
		
		document.getElementById("total").textContent = total;
		document.getElementById("sub_total").textContent = total;
	}

	function del_prod(id) {
		if(cart.length > 0){
			cart.forEach(function(sub_id) {
				var quantity_val = document.getElementById(`quantity${sub_id}`).value;
				var discount_val = document.getElementById(`discount${sub_id}`).value;
				var price_val = document.getElementById(`m_price${sub_id}`).value;
				var dis_type = document.getElementById(`discount_type${sub_id}`).value;
				var final_price = document.getElementById(`final_price${sub_id}`).value;
				// console.log(final_price);

				exist= false;
				for (var i = 0; i < quantities.length; i++) {
					if (quantities[i].id === sub_id) {
						exist = true;
						break;
					}
				}
				if(exist){
					quantities[i].dis_type = dis_type;
					quantities[i].quantityValue = quantity_val;
					quantities[i].m_price = price_val;
					quantities[i].discountValue = discount_val;
					quantities[i].final_price = final_price;
				}

			});
		}
			// console.log(quantities);


		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			type: "warning",
			showCancelButton: !0,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, delete it!",
			confirmButtonClass: "btn btn-primary",
			cancelButtonClass: "btn btn-danger ml-1",
			buttonsStyling: !1,
		}).then(function (t) {

			if(t.value){
				const index = cart.indexOf(id);
				if (index > -1) { // only splice array when item is found
				  cart.splice(index, 1); // 2nd parameter means remove one item only
				}
				for (var i = quantities.length - 1; i >= 0; --i) {
				    if (quantities[i].id == id) {
				        quantities.splice(i,1);
				    }
				}

				$('#orderlist').load('pos_order_table.php',{
					prod_ids : JSON.stringify(cart)
				}, function (){
					// console.log(quantities);
					if(quantities.length > 0){
						quantities.forEach(function(quantity) {
							var id = quantity.id;
							var m_price = quantity.m_price;
							var q_value = quantity.quantityValue;
							var d_value = quantity.discountValue;
							var final_price = quantity.final_price;
							var dis_type = quantity.dis_type;


							document.getElementById(`quantity${id}`).value = q_value;
							document.getElementById(`m_price${id}`).value = m_price;
							document.getElementById(`discount${id}`).value = d_value;
							document.getElementById(`final_price${id}`).value = final_price;
							document.getElementById(`discount_type${id}`).value = dis_type;


						});
					}
					setDiscount();
					totalValue();

					var items = document.querySelectorAll('.price').length;
					document.getElementById("total_items").textContent = items;
				});

			}
		});
	}
	// function del_order_grm(id) {
	// 	Swal.fire({
	// 		title: "Are you sure?",
	// 		text: "You won't be able to revert this!",
	// 		type: "warning",
	// 		showCancelButton: !0,
	// 		confirmButtonColor: "#3085d6",
	// 		cancelButtonColor: "#d33",
	// 		confirmButtonText: "Yes, delete it!",
	// 		confirmButtonClass: "btn btn-primary",
	// 		cancelButtonClass: "btn btn-danger ml-1",
	// 		buttonsStyling: !1,
	// 	}).then(function (t) {
	//
	// 		if(t.value){
	// 			const index = cart.indexOf(id);
	// 			if (index > -1) { // only splice array when item is found
	// 			  cart.splice(index, 1); // 2nd parameter means remove one item only
	// 			}
	// 			for (var i = quantities.length - 1; i >= 0; --i) {
	// 			    if (quantities[i].id == id) {
	// 			        quantities.splice(i,1);
	// 			    }
	// 			}
	//
	// 			$('#orderlist').load('pos_order_table.php',{
	// 				prod_ids : JSON.stringify(cart)
	// 			}, function (){
	// 				if(quantities.length > 0){
	// 					quantities.forEach(function(quantity) {
	// 						var id = quantity.id;
	// 						var q_value = quantity.quantityValue;
	// 						var d_value = quantity.discountValue;
	//
	// 						document.getElementById(`quantity${id}`).value = q_value;
	// 						document.getElementById(`discount${id}`).value = d_value;
	//
	// 					});
	// 				}
	// 				setDiscount();
	// 				totalValue();
	//
	// 				var items = document.querySelectorAll('.price').length;
	// 				document.getElementById("total_items").textContent = items;
	// 			});
	//
	// 		}
	// 	});
	// }

	function setDiscount() {
    var discount = parseFloat(document.getElementById('discount_total').value) || 0;
    var discount_type = document.getElementById('discount_type_main').value;

    if (discount) {
        document.querySelectorAll('.discount').forEach((item) => {
            item.value = discount;
        });

        document.querySelectorAll('.discount_type').forEach((item) => {
            for (var i = 0; i < item.options.length; i++) {
                if (item.options[i].value == discount_type) {
                    item.selectedIndex = i;
                    break;
                }
            }
        });
    }
    totalValue();
}

// barcode
let code = "";
let reading = false;

document.addEventListener('keypress', e => {
  //usually scanners throw an 'Enter' key at the end of read
   if (e.keyCode === 13) {
          if(code.length > 10) {
            document.getElementById('search_name').value=code;
						searchProd();
            /// code ready to use
            code = "";
         }
    } else {
        code += e.key; //while this is not an 'enter' it stores the every key
    }

    //run a timeout of 200ms at the first read and clear everything
    if(!reading) {
        reading = true;
        setTimeout(() => {
            code = "";
            reading = false;
        }, 200);  //200 works fine for me but you can adjust it
    }
});
	</script>

    </body>
</html>
