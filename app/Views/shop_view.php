<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-xs-0 mt-5">
        <?php echo $this->include('templates/_product_filter.php'); ?>
      </div>
      <div class="col-lg-9 col-xs-12 mt-5 text-center">
      <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->
        <h1>All Products</h1>
        <form method='post' action="<?= base_url('/shop/index')?>" id="searchForm">
        <div class="row">
          
        <select name="category" style="width:180px ;">
        <option value="0">Select Category:</option>
        <?php foreach($categories as $category): ?>
          <?php  echo '<option value="'.$category->id.'">'.$category->name.'</option>' ?>
        <?php endforeach; ?>
        </select>
        <select name="price" style="width:180px ;" >
                    <option value="0">Select Price Range:</option>
                    <option value="15-20">$15 - $20</option>
                    <option value="20-25">$20 - $25</option>
                    <option value="25-30">$25 - $30</option>
                    <option value="30-35">$30 - $35</option>
        </select>
        <select id="strain" name="strain" style="width:140px ;">
                    <option value="0">Select Strain:</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
        </select>
        <select name="brands" style="width:140px ;">
          <option value="0">Select Brand:</option>
        <?php foreach($brands as $brand): ?>
          <?php  echo '<option value="'.$brand->id.'">'.$brand->name.'</option>' ?>
        <?php endforeach; ?>
        </select>
        <input type="range" name="thc_value" min="0" max="10" value="0" onchange="updateTextInput(this.value);" style="width:160px ;">
        <input type="text" id="textInput" value="0" style="width:60px ;">
        <input type="range" name="cbd_value" min="0" max="10" value="0" onchange="updateTextInput1(this.value);" style="width:160px ;">
        <input type="text" id="textInput1" value="0" style="width:60px ;">
        <!-- <input type="text" id="search" class="form-control w-20 border px-2" name="search" placeholder="Search here"> -->
        <button type="submit" class="btn btn-primary w-10">Search</button> 
        
      </div>
      </form>
        <div class="row">
          <?php foreach($products as $product): ?>
          <div class="col-md-2 col-sm-6 pt-4 pb-4">
            <form method="post" action="<?= base_url('counter')?>">
            <div class="product-featured">
              <div class="img-wrap">
                <?php 
                  $url = !empty($searchData) ? $product['url'] : $product['url'];
                
                if(isset($product['images'][0])):
                  ?>
                  <a href="<?= base_url('products/'. $url); ?>"><img class="prod_image" src="<?= base_url('products/images/'.$product['images'][0]->filename); ?>" /></a>
                <?php else: ?>
                <a href="<?= base_url('products/'. $url); ?>"><img class="prod_image" src="" /></a>
                <?php endif; ?>
              </div>
              <div class="product-info">
                <a href="<?= base_url('products/'. $url); ?>"><h5><?= !empty($searchData) ? $product['name'] : $product['name']; ?></h5></a>
  
                <p class="price">$<span><?= $product['price']; ?></span></p>
              </div>
            </div>  
          </div>
          <?php endforeach; ?>
        </div>
        <?= $pager->links() ?>
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
  // let btnCounter = document.querySelector('#btn_pr');
  // let counter = 0;

  // btnCounter.addEventListener('click', function(){
  //   counter++;
  //   document.querySelector('.count_cart').innerHTML = counter;
  // });
  // var count = (function () {
  //   var counter = 0;
  //   return function () {return counter +=;}
  // })();

  // function display(){
  //   document.getElementById('count_cart').innerHTML = count();
  // };
  function updateTextInput(val) {
          document.getElementById('textInput').value=val; 
        }
  function updateTextInput1(val) {
          document.getElementById('textInput1').value=val; 
        }
 </script> 
 
<?php $this->endSection() ?>
