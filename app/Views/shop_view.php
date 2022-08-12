<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-sm-12 mt-5 text-center">
      <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->
        <h1>All Products</h1>
        <!-- <pre><?php print_r($products); ?></pre> -->
        <div class="row">
          <?php foreach($products as $product): ?>
          <div class="col-md-2 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
              <div class="img-wrap">
                <a href="#"><img class="prod_image" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" /></a>
              </div>
              <div class="product-info">
                <a href="<?= base_url('products/'. $product->url); ?>"><h5><?= $product->name; ?></h5></a>
                <p><?= $product->thc_value; ?><?= $product->thc_unit; ?> THC</p>
                <p class="price">$<span>33.50</span></p>
              </div>
            </div>  
          </div>
          <?php endforeach; ?>
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

 <script type="text/javascript">
  let btnCounter = document.querySelector('#btn_pr');
  let counter = 0;

  btnCounter.addEventListener('click', function(){
    counter++;
    document.querySelector('.count_cart').innerHTML = counter;
  });
  // var count = (function () {
  //   var counter = 0;
  //   return function () {return counter +=;}
  // })();

  // function display(){
  //   document.getElementById('count_cart').innerHTML = count();
  // };

 </script>  
<?php $this->endSection() ?>

