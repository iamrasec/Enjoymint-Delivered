<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg mt-9">
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

          <div class="row">
            <div class="col-12">
              <form name="update-cart-form">
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
                              <span>QTY: </span><input type="number" min="1" max="100" value="<?= $product['qty']; ?>" name="qty">
                            </div>
                          </div>
                          <div class="col-12 col-md-2 col-xs-12 price">
                            <strong>$<?= $product['product_data']->price; ?></strong>
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
          <!-- <pre><?php print_r($cart_products); ?></pre> -->
        </div>
      </div>
      <div class="col-12 col-md-4 col-xs-12 ">
        <div class="cart-summary px-3 py-3">
          <h4>Cart Summary</h4>
          <div><?= count($cart_products); ?> Items</div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php $this->endSection() ?>