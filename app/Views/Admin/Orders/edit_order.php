<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">

    <div class="row">
      <div class="col-lg-6">
        <h4><?php echo $page_title; ?></h4>
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-5 text-right d-flex flex-row justify-content-center">
        <!-- <a class="btn save-btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="#">Save</a> -->
      </div>
    </div>
    
    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">

            <div class="row">
              <div class="col-lg-12">
                <h3><?= $order_data->first_name ?> <?= $order_data->first_name ?></h3>
                <div>Order Key: <strong><?= $order_data->order_key; ?></strong></div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-12 col-md-8 col-xs-12">
                <h5>Products</h5>
                <table id="cart_products" class="w-100">
                  <tbody>
                    <?php foreach($order_products as $product): ?>
                    <tr class="pid-<?= $product->product_id; ?> border">
                      <td>
                        <div class="row product-wrap d-flex py-3">
                          <div class="col-12 col-md-2 col-xs-12 product-img">
                            <?php if(!empty($product->product_data->images)): ?>
                            <img src="<?= base_url('products/images/'.$product->product_data->images[0]->filename); ?>" style="width: 100px;">
                            <?php endif; ?>
                          </div>
                          <div class="col-12 col-md-8 col-xs-12 product-details">
                            <h6 class="product-title">
                              <a href="<?= base_url('products/'. $product->product_data->url); ?>"><?= $product->product_data->name; ?></a>
                            </h6>
                            <div class="text-sm mb-3">
                              <span class="badge text-bg-warning me-3"><?= $product->product_data->strain_name; ?></span>
                              <span class="badge text-bg-dark ms-3">THC <?= $product->product_data->thc_value; ?><?= ($product->product_data->thc_unit == 'pct') ? '%' : $product->product_data->thc_unit;?></span>
                            </div>
                            <div class="product-qty">
                              <span>QTY: </span><input type="number" name="cart[<?= $product->product_id; ?>][qty]" class="product-<?= $product->product_id; ?>-qty" min="1" max="100" value="<?= $product->qty; ?>" data-pid="<?= $product->product_id; ?>" data-unit-price="<?= $product->product_data->price; ?>">
                            </div>
                          </div>
                          <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                            <input type="hidden" class="product-total-price product-<?= $product->product_id; ?>-total-price" value="<?= number_format($product->product_data->price * $product->qty, 2, '.', ''); ?>">
                            <strong class="total-price-display">$<?= number_format($product->product_data->price * $product->qty, 2, '.', ','); ?></strong>
                            <div class="mt-3 d-flex align-items-end align-content-end"><a href="#" class="remove-item ms-auto" data-pid="<?= $product->product_id; ?>"><i class="fas fa-trash"></i></a></div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-8 col-md-8 col-xs-12">
                    <div class="add-product-wrap w-100">
                      <select id="add-product-select">
                        <?php foreach($all_products as $add_product): ?>
                        <option value="<?= $add_product->id; ?>"><?= $add_product->name; ?> | (Remaining Stocks: <?= $add_product->stocks; ?>)</option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <a class="btn btn-danger mt-3 py-3 px-5"><i class="far fa-plus-square"></i> Add Another Product</a>
                <a class="btn save-btn bg-gradient-primary mt-3 py-3 px-5 d-inline-block" href="#" style="float:right;">Save</a>
              </div>
              <div class="col-12 col-md-4 col-xs-12">
                <div class="order-user-data px-3 py-3 px-4 rounded-5">
                  <div class="row mt-2">
                    <div class="col-12 col-md-12">
                      <h5>Payment Method</h5>
                      <select name="payment_method" class="w-100 px-2 py-2">
                        <option value="zelle" <?= ($order_data->payment_method == "zelle") ? "selected" : ""; ?>>Zelle</option>
                        <option value="paytender"<?= ($order_data->payment_method == "paytender") ? "selected" : ""; ?>>PayTender</option>
                        <option value="cash"<?= ($order_data->payment_method == "cash") ? "selected" : ""; ?>>Cash</option>
                        <option value="debit_card"<?= ($order_data->payment_method == "debit_card") ? "selected" : ""; ?>>Debit Card</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-12 col-md-12">
                      <h5>Delivery Address</h5>
                      <div class="input-group input-group-outline">
                        <input type="text" name="delivery_address" value="<?= $order_data->address; ?>" class="form-control bg-light">
                      </div>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-12 col-md-12">
                      <h5>Order Notes</h5>
                      <div class="input-group input-group-outline">
                        <textarea class="form-control bg-light w-100 mb-4" name="order_notes" style="height: 100px;"><?= $order_data->order_notes; ?></textarea>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <pre>ORDER DATA: <?php print_r($order_data); ?></pre>
    <pre>ORDER PRODUCTS: <?php print_r($order_products); ?></pre>
    <pre>ALL PRODUCTS: <?php print_r($all_products); ?></pre>

  </div>
</main>

<style>
  .order-user-data {
    background-color: rgba(38,50,48,0.95) !important;
    border-radius: 2rem !important;
    color: #ffffff;
  }

  .order-user-data h5 {
    color: #ffffff;
  }
</style>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>

<script>
var jwt = $("[name='atoken']").attr('content');

$(document).ready(function () {
    
});
</script>

<?php $this->endSection(); ?>