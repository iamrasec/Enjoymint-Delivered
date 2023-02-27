<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<style>
:root {
	--white: #ffffff;
	--light: #f0eff3;
	--black: #000000;
	--dark-blue: #1f2029;
	--dark-light: #353746;
  --green: #489989;
  --dark-green: #266b5d;
	--red: #da2c4d;
	--yellow: #f8ab37;
	--grey: #ecedf3;
}
[type="checkbox"]:checked,
[type="checkbox"]:not(:checked),
[type="radio"]:checked,
[type="radio"]:not(:checked){
	position: absolute;
	left: -9999px;
	width: 0;
	height: 0;
	visibility: hidden;
}
.checkbox:checked + label,
.checkbox:not(:checked) + label{
	position: relative;
	width: 70px;
	display: inline-block;
	padding: 0;
	margin: 0 auto;
	text-align: center;
	margin: 17px 0;
	margin-top: 100px;
	height: 6px;
	border-radius: 4px;
	background-image: linear-gradient(298deg, var(--dark-green), var(--green));
	z-index: 100 !important;
}
.checkbox:checked + label:before,
.checkbox:not(:checked) + label:before {
	position: absolute;
	font-family: 'unicons';
	cursor: pointer;
	top: -17px;
	z-index: 2;
	font-size: 20px;
	line-height: 40px;
	text-align: center;
	width: 40px;
	height: 40px;
	border-radius: 50%;
	-webkit-transition: all 300ms linear;
	transition: all 300ms linear; 
}
.checkbox:not(:checked) + label:before {
	content: '\eac1';
	left: 0;
	color: var(--grey);
	background-color: var(--dark-light);
	box-shadow: 0 4px 4px rgba(0,0,0,0.15), 0 0 0 1px rgba(26,53,71,0.07);
}
.checkbox:checked + label:before {
	content: '\eb8f';
	left: 30px;
	color: var(--yellow);
	background-color: var(--dark-blue);
	box-shadow: 0 4px 4px rgba(26,53,71,0.25), 0 0 0 1px rgba(26,53,71,0.07);
}

.checkbox:checked ~ .section .container .row .col-12 p{
	color: var(--dark-blue);
}
.checkbox-tools:checked + label,
.checkbox-tools:not(:checked) + label{
	position: relative;
	/* display: inline-block; */
	display: inline-flex;
	width: 100%;
	font-size: 13px;
	line-height: 20px;
	letter-spacing: 1px;
	margin: 0 auto;
	margin-left: 5px;
	margin-right: 5px;
	margin-bottom: 10px;
	text-align: center;
	border-radius: 4px;
	overflow: hidden;
	cursor: pointer;
	text-transform: uppercase;
	color: var(--white);
	-webkit-transition: all 300ms linear;
	transition: all 300ms linear; 
	min-height: 39px;
	justify-content: center;
	align-content: center;
	flex-wrap: wrap;
}
.checkbox-tools:not(:checked) + label{
	background-color: var(--dark-light);
	box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}
.checkbox-tools:checked + label{
	background-color: transparent;
	box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-tools:not(:checked) + label:hover{
	box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
.checkbox-tools:checked + label::before,
.checkbox-tools:not(:checked) + label::before{
	position: absolute;
	content: '';
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	border-radius: 4px;
	background-image: linear-gradient(298deg, var(--dark-green), var(--green));
	z-index: -1;
}
.checkbox-tools:checked + label .uil,
.checkbox-tools:not(:checked) + label .uil{
	font-size: 24px;
	line-height: 24px;
	display: block;
	padding-bottom: 10px;
}

.checkbox:checked ~ .section .container .row .col-12 .checkbox-tools:not(:checked) + label{
	background-color: var(--light);
	color: var(--dark-blue);
	box-shadow: 0 1x 4px 0 rgba(0, 0, 0, 0.05);
}
.prom{
	font-size: 15px;
	font-weight: 500;
	color:#000000;
}
</style>

<main class="main-content position-relative border-radius-lg mt-9">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <!-- <div class="continue_shopping px-3"><a href="<?= base_url('shop'); ?>"><i class="fas fa-chevron-left"></i> Continue Shoppping</a></div> -->
      </div>
    </div>
	
    <div class="row">
      <div class="col-12 col-md-8 col-xs-7">
        <div class="card card-body blur shadow-blur mx-3 mx-md-4">
          <h1 class="pagetitle">Checkout</h1>
						

            <div class="row mb-4">
              <div class="col-12 col-md-12 col-xs-12 mt-3">
			<form id="pro_code" method="POST" action="<?= base_url('cart/promo_add'); ?>">
			  <label class="for-checkbox-tools prom" for="tool-2">Promo Code</label>
			  <input type="text" name="promo_code" id="promo_code" class="border px-2" style="width: 31%;" placeholder="Promo Code">
			  <button type="submit">Submit</button>		
			  
			</form>
			
			  <h5>Payment Method</h5>
				<form id="checkout" action="<?= base_url('cart/place_order'); ?>" method="POST">
					<input type="hidden" name="guid" value="<?= $guid; ?>">
					<input type="hidden" name="cart_key" value="<?= $checkout_token; ?>">

                <!-- <div class="row justify-content-center pb-5">
                  <div class="col-12 pb-1 d-flex">
					<input class="checkbox-tools w-100 border px-2" type="radio" name="payment_method" id="tool-1" value="ledgergreen" checked>
                    <label class="for-checkbox-tools" for="tool-1">Ledger Green</label>
                    
                    <input class="checkbox-tools  w-100 border px-2" type="radio" name="payment_method" id="tool-2" value="paytender">
                    <label class="for-checkbox-tools" for="tool-2">PayTender</label>
                    
                    <input class="checkbox-tools  w-100 border px-2" type="radio" name="payment_method" id="tool-3" value="cash">
                    <label class="for-checkbox-tools" for="tool-3">Cash</label>
                    
                    <input class="checkbox-tools  w-100 border px-2" type="radio" name="payment_method" id="tool-4" value="debit_card">
                    <label class="for-checkbox-tools" for="tool-4">Debit Card</label>
                  </div>
					<span class="text-sm">Payments will be collected upon delivery.</span>
                </div> -->
				<div class="row mb-4">
					<div class="col-6 col-md-6 col-xs-12">
					<input class="checkbox-tools w-100 border px-2" type="radio" name="payment_method" id="tool-1" value="ledgergreen" checked>
                    <label class="for-checkbox-tools" for="tool-1">Ledger Green</label>
					</div>
					<div class="col-6 col-md-6 col-xs-12">
					<input class="checkbox-tools  w-100 border px-2" type="radio" name="payment_method" id="tool-2" value="paytender">
                    <label class="for-checkbox-tools" for="tool-2">PayTender</label>
					</div>
				</div>	
				<div class="row mb-4">
					<div class="col-6 col-md-6 col-xs-12">
					<input class="checkbox-tools  w-100 border px-2" type="radio" name="payment_method" id="tool-3" value="cash">
                    <label class="for-checkbox-tools" for="tool-3">Cash</label>
					</div>
					<div class="col-6 col-md-6 col-xs-12">
					<input class="checkbox-tools  w-100 border px-2" type="radio" name="payment_method" id="tool-4" value="debit_card">
                    <label class="for-checkbox-tools" for="tool-4">Debit Card</label>
					</div>
				</div>						
              </div>
            </div>
						<div class="row mb-4">
							<div class="col-12 col-md-12 col-xs-12 mt-3">
								<h5>Delivery Address</h5>
								<div class="row mb-4">
									<div class="col-5 col-md-5 col-xs-12">
										<div class="input-group input-group-dynamic">
											<!-- <label class="form-label">Apartment/Suite Number</label> -->
											<input type="text" name="apt_no" class="form-control  w-100 border px-2" placeholder="Apartment/Suite Number">
										</div>
									</div>
									<div class="col-7 col-md-7 col-xs-12">
										<div class="input-group input-group-dynamic">
											<!-- <label class="form-label">Street Address</label> -->
											<input type="text" name="street" class="form-control  w-100 border px-2" placeholder="Street Address">
										</div>
									</div>
								</div>
								<div class="row mb-4">
									<div class="col-4 col-md-4 col-xs-12">
										<div class="input-group input-group-dynamic">
											<!-- <label class="form-label">City</label> -->
											<input type="text" name="city" class="form-control w-100 border px-2" placeholder="City">
										</div>
									</div>
									<div class="col-4 col-md-4 col-xs-12">
										<div class="input-group input-group-dynamic">
											<!-- <label class="form-label">State</label> -->
											<input type="text" name="state" class="form-control w-100 border px-2" placeholder="State">
										</div>
									</div>
									<div class="col-4 col-md-4 col-xs-12">
										<div class="input-group input-group-dynamic">
											<!-- <label class="form-label">Zipcode</label> -->
											<input type="text" name="zipcode" class="form-control w-100 border px-2" placeholder="Zipcode">
										</div>
									</div>
								</div>
								<div class="row mb-4">							
									<div class="col-12 col-md-12 col-xs-12">
										<div class="input-group input-group-dynamic">
											<!-- <label class="form-label">Phone Number</label> -->
											<input type="number" name="phone" class="form-control w-100 border px-2" placeholder="Phone Number">
										</div>
									</div>
								</div>
							</div>
						</div>
						

						<div class="row">
							<div class="col-12 col-md-12 col-xs-12 mt-3">
								<h5>Order Notes</h5>
								<div class="input-group input-group-outline">
									<textarea name="order_notes" class="form-control w-100" style="height: 100px;"></textarea>
								</div>
							</div>
						</div>
			
          
        </div>
      </div>

			<?php if(!empty($cart_products)): ?>
				
			<?php 
				$subtotal = 0;	
				foreach($cart_products as $product) {
					// echo "<pre>".print_r($product, 1)."</pre>";
					$subtotal += ($product['product_data']->price * $product['qty']);
				}
				$tax_cost = $subtotal * ($tax_rate - 1);
				$total_cost = $subtotal * $tax_rate;
			?>
			<div class="col-12 col-md-4 col-xs-5">
        <div class="cart-summary px-3 py-3 px-4 rounded-5">
          <h4 class="text-white">Cart Summary</h4>   

					<div>
						<?php if($del_type == "nfs"): ?>
						<label class="text-white" style="font-size: 16px;">Delivery Schedule</label>
						<button type="button" id="toggle" class="input-group-text w-100 border-0" style="margin-top: -15px;">
						<i class="fa fa-calendar-alt" style="color: white"></i>&nbsp;&nbsp; 
						<input style="color: white;" type="hidden" placeholder="delivery schedule" name="delivery_schedule" class="form-control datetime_picker">
						<span class="del_date_display" style="color: #aeb0b5">Delivery Schedule</span>
						</button>
						<input style="color: white;" type="hidden" value="nfs" name="del_type" class="form-control">
						<?php else: ?>
						<input style="color: white;" type="hidden" value="<?= $fscurrDay; ?>" name="delivery_schedule" class="form-control datetime_picker">
						<input style="color: white;" type="hidden" value="<?= $fsDelTime; ?>" name="time_window" class="form-control time_window">
						<?php endif; ?>
						<input style="color: white;" type="hidden" value="<?= $del_type; ?>" name="del_type" class="form-control">
					</div>
							
          <div class="cart-item-count"><?= count($cart_products); ?> <?= (count($cart_products) > 1) ? "Items" : "Item"; ?></div>
			
          <div class="row mt-4">
            <div class="col-8 col-md-8 col-xs-8">Subtotal</div>
            <div class="col-4 col-md-4 col-xs-4 text-right"><span class="subtotal-cost">$<?= number_format($subtotal, 2, '.', ','); ?></span></div>
          </div>
          <div class="row mt-3">
            <div class="col-8 col-md-8 col-xs-8">Tax (Estimated)</div>
            <div class="col-4 col-md-4 col-xs-4 text-right"><span class="tax-cost">$<?= number_format($tax_cost, 2, '.', ','); ?></span></div>
          </div>
          <div class="row mt-3">
            <div class="col-8 col-md-8 col-xs-8">Total</div>
            <div class="col-4 col-md-4 col-xs-4 text-right"><span class="total-cost">$<?= number_format($total_cost, 2, '.', ','); ?></span></div>
          </div>
          <div class="row mt-5">
            <div class="col-12 col-md-12 col-xs-12 d-grid">
              <button class="btn bg-primary-green btn-lg place-order" type="button">Place Order</button>
						<a href="<?= base_url('cart'); ?>" class="text-center text-gradient text-primary">Cancel</a>
            </div>
          </div>
        </div>
      </div>
			<?php endif; ?>
    </div>
		
		<?php if($del_type == 'nfs'): ?>
		<div class="d-none">
			<button type="button" class="btn delivery-popup btn-block btn-light mb-3" data-bs-toggle="modal" data-bs-target="#delivery-modal">Show Calendar</button>
		</div>
		<?php echo $this->include('templates/_delivery_popup.php'); ?>
		<?php endif; ?>
	</form>
  </div>
</main>

<?php echo $this->include('cart/_login_register_modal.php'); ?>

<?php $this->endSection(); ?>

<?php $this->section("script"); ?>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>  
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> 
<script>
$(document).on("click", ".place-order", function() {
	$("#checkout").submit();
})

jQuery.datetimepicker.setDateFormatter('moment')

var serverDate = '<?php echo $currDate; ?>';

var today = new Date(serverDate);

// var dateNow = today.toISOString().slice(0, 10);

var maxDate = moment().add(6, 'day');

var enabledDates = [
moment(today, serverDate)
];
	
for (var i = 1; i <= 6; i++) {
  var date = moment().add(i, 'day').format(serverDate);
  enabledDates.push(moment(date, serverDate));
}


<?php if($del_type == 'nfs'): ?>
$('#inline_picker').datetimepicker({
  timepicker: false,
  datepicker: true,
  inline: true,
  format: 'YYYY-MM-DD',
  minDate: serverDate,
  maxDate: maxDate,
  enabledDates: enabledDates,
//   defaultDate: dateNow,
  defaultSelect: true,
	onGenerate:function(ct) {
		console.log("onGenerate");
		console.log("ct: " + ct.getDate());
		console.log("today: " + today.getDate());

		if(ct.getDate() == today.getDate()) {
			console.log("Same day");
			let currTime = today.getHours() + ":" + today.getMinutes();
			console.log("today hour: " + currTime);

			$("#time_window option").each(function() {
				if($(this).val() < today.getHours() + ":" + today.getMinutes()) {
					$(this).hide();
				}
				else {
					$(this).prop("selected", true);
					return false;
				}
			});
		}
		else {
			$("#time_window option:first").prop("selected", "selected");
			console.log("Different day");
		}
	},
	onSelectDate:function(ct,$i){
		console.log("onSelectDate");
		$("#time_window option").show();
		$("#time_window option:selected").prop("selected", false);
	},
});

// Check if cookie exists
var delivery_cookie = getCookie("delivery_schedule");

$('#toggle').on('click', function(){
  $(".delivery-popup").click();
});

$(document).ready(function() {
  if(!delivery_cookie) {
    // Show delivery schedule popup if no cookie is found.
    $(".delivery-popup").click();
  }
	else {
    let delsched = JSON.parse(delivery_cookie);
    let delTime = delsched.t.split("-");
    let delFrom = tConvert(delTime[0]);
    let delTo = tConvert(delTime[1]);
    
    $("input.datetime_picker").val(delsched.d);
    $(".del_date_display").text(delsched.d + " @ " + delFrom + " - " + delTo);
  }

  // Save Delivery Schedule
  $(".save-delivery-schedule").click(function() {
    console.log("save delivery schedule");
    console.log($("#inline_picker").val());
    console.log($("#time_window").find(":selected").val());

    let delsched = {};
    delsched.d = $("#inline_picker").val();
    delsched.t = $("#time_window").find(":selected").val();

    console.log(JSON.stringify(delsched));

    setCookie("delivery_schedule", JSON.stringify(delsched), '1');
    $("input.datetime_picker").val(delsched.d + " @ " + delsched.t);
        console.log(delsched.d + " @ " + delsched.t);
    $(".btn-link").click();
  });
});
<?php endif; ?>

// const form = document.getElementById('pro_code');
//   const submitBtn = document.getElementById('submitProm');
//   const message = document.getElementById('message');

//   form.addEventListener('submit', function(event) {
//     event.preventDefault();

//     const promo_code = document.getElementById('promo_code').value;

//     fetch('/submit-promo', {
//       method: 'POST',
//       body: JSON.stringify({ promo_code: promo_code }),
//       headers: {
//         'Content-Type': 'application/json'
//       }
//     })
//     .then(response => response.json())
//     .then(data => {
//       message.innerHTML = data.message;
//       form.reset();
//     });
//   }); 

// Get the form element
// $(document).on("click", "#submit_promo", function(e) {
//     e.preventDefault();
//     // if(jwt == '') {
//       // let pid = $(this).data('pid');
//       // let qty = $(this).val();

//       // console.log("qty: "+qty);

//       get_cookie = getCookie(cookie_cart);

//       cookie_products = JSON.parse(get_cookie);

//       cookie_products.forEach(function(product) {
//         $(".product-qty").each(function() {
//           if(product.pid == $(this).data('pid')) {
//             product.qty = parseInt($(this).val());
//           }
//         });
//       });

//       // console.log(cookie_products);

//       setCookie(cookie_cart, JSON.stringify(cookie_products), '1');
//     // }

//     $("#update-cart-form").submit();
//   });

<?php $this->endSection(); ?>