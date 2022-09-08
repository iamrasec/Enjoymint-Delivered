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
	display: inline-block;
	padding: 20px;
	width: 150px;
	font-size: 14px;
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
</style>

<main class="main-content position-relative border-radius-lg mt-9">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <!-- <div class="continue_shopping px-3"><a href="<?= base_url('shop'); ?>"><i class="fas fa-chevron-left"></i> Continue Shoppping</a></div> -->
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-8 col-xs-12">
        <div class="card card-body blur shadow-blur mx-3 mx-md-4">
          <h1 class="pagetitle">Checkout</h1>
          <form id="checkout" action="<?= base_url('cart/place_order'); ?>" method="POST">
						<input type="hidden" name="guid" value="<?= $guid; ?>">
						<input type="hidden" name="cart_key" value="<?= $checkout_token; ?>">
            <div class="row">
              <div class="col-12 col-md-12 col-xs-12 mt-3">
                <h5>Payment Method</h5>

                <div class="row justify-content-center pb-5">
                  <div class="col-12 pb-1">
                    <input class="checkbox-tools" type="radio" name="payment_method" id="tool-1" value="zelle" checked>
                    <label class="for-checkbox-tools" for="tool-1">Zelle</label>
                    
                    <input class="checkbox-tools" type="radio" name="payment_method" id="tool-2" value="paytender">
                    <label class="for-checkbox-tools" for="tool-2">PayTender</label>
                    
                    <input class="checkbox-tools" type="radio" name="payment_method" id="tool-3" value="cash">
                    <label class="for-checkbox-tools" for="tool-3">Cash</label>
                    
                    <input class="checkbox-tools" type="radio" name="payment_method" id="tool-4" value="debit_card">
                    <label class="for-checkbox-tools" for="tool-4">Debit Card</label>
                  </div>
									<span class="text-sm">Payments will be collected upon delivery.</span>
                </div>

              </div>
            </div>
						
						<div class="row mb-4">
							<div class="col-12 col-md-12 col-xs-12 mt-3">
								<h5>Delivery Address</h5>
								<div class="row mb-4">
									<div class="col-4 col-md-4 col-xs-12">
										<div class="input-group input-group-outline">
											<label class="form-label">Apartment/Suite Number</label>
											<input type="text" name="apt_no" class="form-control">
										</div>
									</div>
									<div class="col-8 col-md-8 col-xs-12">
										<div class="input-group input-group-outline">
											<label class="form-label">Street Address</label>
											<input type="text" name="street" class="form-control">
										</div>
									</div>
								</div>
								<div class="row mb-4">
									<div class="col-4 col-md-4 col-xs-12">
										<div class="input-group input-group-outline">
											<label class="form-label">City</label>
											<input type="text" name="city" class="form-control">
										</div>
									</div>
									<div class="col-4 col-md-4 col-xs-12">
										<div class="input-group input-group-outline">
											<label class="form-label">State</label>
											<input type="text" name="state" class="form-control">
										</div>
									</div>
									<div class="col-4 col-md-4 col-xs-12">
										<div class="input-group input-group-outline">
											<label class="form-label">Zipcode</label>
											<input type="text" name="zipcode" class="form-control">
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
          </form>
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
			<div class="col-12 col-md-4 col-xs-12">
        <div class="cart-summary px-3 py-3 px-4 rounded-5">
          <h4 class="text-white">Cart Summary</h4>
          <div class="cart-item-count"><?= count($cart_products); ?> items</div>
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
  </div>
</main>

<?php echo $this->include('cart/_login_register_modal.php'); ?>

<?php $this->endSection(); ?>

<?php $this->section("script"); ?>
<script>
  $(document).on("click", ".place-order", function() {
		$("#checkout").submit();
	})
</script>
<?php $this->endSection(); ?>