<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-sm-12 mt-5 text-center">
      <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->
        <h1><?= $page_title; ?></h1>
        <div class="row">
          <?php if($products == null): ?>
          <div class="col-12 col-md-12 pt-4 pb-4">
            <p>No Products available for this Category.</p>
          </div>
          <?php else: ?>
          <?php foreach($products as $product): ?>
          <div class="col-md-2 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
              <div class="img-wrap">
                <?php if(isset($product->images[0])): ?>
                <a href="<?= base_url('products/'. $product->url); ?>"><img class="prod_image" src="<?= base_url('products/images/'.$product->images[0]->filename); ?>" /></a>
                <?php else: ?>
                <a href="<?= base_url('products/'. $product->url); ?>"><img class="prod_image" src="" /></a>
                <?php endif; ?>
              </div>
              <div class="product-info">
                <a href="<?= base_url('products/'. $product->url); ?>"><h5><?= $product->name; ?></h5></a>
                <p><?= $product->thc_value . (($product->thc_unit == 'pct') ? '%' : $product->thc_unit); ?> THC</p>
                <p class="price">$<span><?= $product->price; ?></span></p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- -------   START PRE-FOOTER 2 - simple social line w/ title & 3 buttons    -------- -->
<div class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 ms-auto">
        <h4 class="mb-1">Thank you for your support!</h4>
        <p class="lead mb-0">We deliver only the best products</p>
      </div>
      <div class="col-lg-5 me-lg-auto my-lg-auto text-lg-end mt-5">
        <a href="#" class="btn btn-twitter mb-0 me-2" target="_blank">
          <i class="fab fa-twitter me-1"></i> Tweet
        </a>
        <a href="#" class="btn btn-facebook mb-0 me-2" target="_blank">
          <i class="fab fa-facebook-square me-1"></i> Share
        </a>
        <a href="#" class="btn btn-pinterest mb-0 me-2" target="_blank">
          <i class="fab fa-pinterest me-1"></i> Pin it
        </a>
      </div>
    </div>
  </div>
</div>
<!-- -------   END PRE-FOOTER 2 - simple social line w/ title & 3 buttons    -------- -->

</div>

<?php $this->endSection() ?>