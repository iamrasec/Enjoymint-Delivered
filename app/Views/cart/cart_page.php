<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<main class="main-content position-relative border-radius-lg mt-9">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="continue_shopping px-3"><a href="<?= base_url('shop'); ?>"><i class="fas fa-chevron-left"></i> Continue Shoppping</a></div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-8 col-xs-12">
        <div class="card card-body blur shadow-blur mx-3 mx-md-4">
          <h1 class="pagetitle">Your Cart</h1>

          <?php if(empty($cart_products)): ?>
          <p>There are no products in your cart.  <a class="text-primary text-gradient font-weight-bold" href="<?= base_url('shop'); ?>">Click here</a> to continue shopping.</p>
          <?php else: ?>
          <div class="row">
            <div class="col-12">
              <form name="update-cart-form">
                <input type="hidden" name="guid" value="<?= $guid; ?>">
                <h4>Products</h4>
                <table id="cart_products" class="w-100">
                  <tbody>
                    <?php foreach($cart_products as $product): ?>
                    <tr class="pid-<?= $product['pid']; ?> border">
                      <td>
                        <div class="row product-wrap d-flex py-3">
                          <div class="col-12 col-md-2 col-xs-12 product-img">
                            <?php if(!empty($product['images'])): ?>
                            <img src="<?= base_url('products/images/'.$product['images'][0]->filename); ?>" style="width: 100px;">
                            <?php endif; ?>
                          </div>
                          <div class="col-12 col-md-8 col-xs-12 product-details">
                            <h6 class="product-title">
                              <a href="<?= base_url('products/'. $product['product_data']->url); ?>"><?= $product['product_data']->name; ?></a>
                            </h6>
                            <div class="text-sm mb-3">
                              <span class="badge text-bg-warning me-3"><?= $product['product_data']->strain_name; ?></span>
                              <span class="badge text-bg-dark ms-3">THC <?= $product['product_data']->thc_value; ?><?= ($product['product_data']->thc_unit == 'pct') ? '%' : $product['product_data']->thc_unit;?></span>
                            </div>
                            <div class="product-qty">
                              <span>QTY: </span><input type="number" class="product-qty" min="1" max="100" value="<?= $product['qty']; ?>">
                            </div>
                          </div>
                          <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                            <input type="hidden" class="product-total-price" value="<?= number_format($product['product_data']->price * $product['qty'], 2, '.', ''); ?>">
                            <strong>$<?= number_format($product['product_data']->price * $product['qty'], 2, '.', ','); ?></strong>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </form>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php if(!empty($cart_products)): ?>
      <div class="col-12 col-md-4 col-xs-12">
        <div class="cart-summary px-3 py-3 px-4 rounded-5">
          <h4 class="text-white">Cart Summary</h4>
          <div class="cart-item-count"><?= count($cart_products); ?> Items</div>
          <div class="row mt-4">
            <div class="col-8 col-md-8 col-xs-8">Subtotal</div>
            <div class="col-4 col-md-4 col-xs-4 text-right"><span class="subtotal-cost">0</span></div>
          </div>
          <div class="row mt-3">
            <div class="col-8 col-md-8 col-xs-8">Tax (Estimated)</div>
            <div class="col-4 col-md-4 col-xs-4 text-right"><span class="tax-cost">0</span></div>
          </div>
          <div class="row mt-3">
            <div class="col-8 col-md-8 col-xs-8">Total</div>
            <div class="col-4 col-md-4 col-xs-4 text-right"><span class="total-cost">0</span></div>
          </div>
          <div class="row mt-5">
            <div class="col-12 col-md-12 col-xs-12 d-grid">
              <button class="btn bg-primary-green btn-lg checkout-btn" type="button">Proceed to Checkout</button>
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
  var tax_rate = 1.35;  // 35%

  // Create our number formatter.
  var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
  });
  
  $(document).ready(function() {
    // Compute for subtotal cost
    var subtotal = 0;
    $(".product-total-price").each(function() {
      subtotal += parseFloat($(this).val());
    });

    $(".subtotal-cost").html(formatter.format(subtotal));

    // Subtotal + tax
    var with_tax = subtotal.toFixed(2) * (tax_rate - 1);
    $(".tax-cost").html(formatter.format(with_tax));

    // Calculate Total
    var total_cost = 0;
    total_cost = subtotal.toFixed(2) * tax_rate;
    $(".total-cost").html(formatter.format(total_cost));
  });
  
  $(document).on("click", ".checkout-btn", function(e) {
    e.preventDefault();
    console.log($("input[name=guid]").val());

    if($("input[name=guid]").val() == '') {
      $("#loginRegisterModal").modal('show');
    }
    else {
      window.location.replace("<?= base_url('cart/checkout'); ?>");
    }
  });
</script>
<?php $this->endSection(); ?>