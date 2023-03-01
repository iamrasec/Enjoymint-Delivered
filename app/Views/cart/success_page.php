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
      <div class="col-12 col-md-12 col-xs-12">
        <div class="card card-body blur shadow-blur mx-3 mx-md-4">
          <?php if(isset($order_completed) && $order_completed == 1): ?>
          <h1 class="pagetitle">Order Summary</h1>
          <p>Order ID: <strong><?= $order_data[0]->id; ?></strong></p>
          <p>Payment Method: <strong><?= ucfirst($order_data[0]->payment_method); ?></strong></p>
          <?php if($order_data[0]->delivery_type == 0): ?>
          <?php
          $del_time = explode("-", $order_data[0]->delivery_time);
          $del_time_from = $del_time[0];
          $del_time_to = $del_time[1];

          if($del_time_from > 1200) {
            $del_time_from = ($del_time_from - 1200) . ' PM';
          }
          else {
            $del_time_from = $del_time_from . ' AM';
          }

          if($del_time_to > 1200) {
            $del_time_to = ($del_time_to - 1200) . ' PM';
          }
          else {
            $del_time_to = $del_time_to . ' AM';
          }

          $del_time_from = substr_replace($del_time_from, ':', -5, 0);
          $del_time_to = substr_replace($del_time_to, ':', -5, 0);
          ?>
          <p>Delivery Date: <strong style="font-size: 20px;"><?= $order_data[0]->delivery_schedule; ?> @ <?= $del_time_from; ?> - <?= $del_time_to; ?></strong></p>
          <?php endif; ?>
          <table id="order_products">
            <tbody>
              <?php foreach($order_products as $product): ?>
              <tr class="pid-<?= $product['pid']; ?> border">
                <td>
                  <div class="row product-wrap d-flex py-3">
                    <div class="col-12 col-md-1 col-xs-12 product-img">
                      <?php if(!empty($product['images'])): ?>
                      <img src="<?= base_url('products/images/'.$product['images'][0]->filename); ?>" style="width: 100px;">
                      <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-9 col-xs-12 product-details">
                      <h6 class="product-title">
                        <a href="<?= base_url('products/'. $product['product_data']->url); ?>"><?= $product['product_data']->name; ?></a>
                      </h6>
                      <div class="text-sm mb-3">
                        <span class="badge text-bg-warning me-3"><?= $product['product_data']->strain_name; ?></span>
                        <span class="badge text-bg-dark ms-3">THC <?= $product['product_data']->thc_value; ?><?= ($product['product_data']->thc_unit == 'pct') ? '%' : $product['product_data']->thc_unit;?></span>
                      </div>
                      <div class="product-qty">
                        <span>QTY: <?= $product['qty']; ?></span>
                      </div>
                    </div>
                    <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                      <strong>$<?= number_format($product['product_data']->unit_price * $product['qty'], 2, '.', ','); ?></strong>
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <tr>
                <td>
                  <div class="row my-2">
                    <div class="col-12 col-md-10 col-xs-10 text-right">
                      Subtotal
                    </div>
                    <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                      <strong>$<?= $order_data[0]->subtotal; ?></strong>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="row my-2">
                    <div class="col-12 col-md-10 col-xs-10 text-right">
                      Taxes
                    </div>
                    <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                      <strong>$<?= $order_data[0]->tax; ?></strong>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="row my-2">
                    <div class="col-12 col-md-10 col-xs-10 text-right">
                      <strong>TOTAL</strong>
                    </div>
                    <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                      <strong>$<?= $order_data[0]->total; ?></strong>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <?php else: ?>
          <p>Forbidden</p>
          <?php endif; ?>
        </div>
      </div>
      
    </div>
  </div>
</main>

<?php $this->endSection(); ?>

<?php $this->section("script"); ?>
<script>
  
</script>
<?php $this->endSection(); ?>