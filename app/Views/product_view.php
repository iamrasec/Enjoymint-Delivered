<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo view('templates/__navigation.php'); ?>

<style>
.lds-hourglass {
  display: inline-block;
  position: relative;
  width: 30px;
  height: 30px;
}
.lds-hourglass:after {
  content: " ";
  display: block;
  border-radius: 50%;
  width: 0;
  height: 0;
  margin: 8px;
  box-sizing: border-box;
  border: 10px solid #489989;
  border-color: #489989 transparent #489989 transparent;
  animation: lds-hourglass 1.2s infinite;
}
@keyframes lds-hourglass {
  0% {
    transform: rotate(0);
    animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
  }
  50% {
    transform: rotate(900deg);
    animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  }
  100% {
    transform: rotate(1800deg);
  }
}
</style>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="card mt-8">
            <div class="card-body">
             <h5 class="mb-4">Product Details</h5>
              <div class="row">

                <div class="col-xl-5 col-lg-6 text-center">
                  <?php if($images): ?>
                  <img class="w-100 border-radius-lg shadow-lg mx-auto" src="<?= base_url('products/images/'.$images[0]->filename); ?>" alt="">
                  <?php endif; ?>

                  <div class="my-gallery d-flex mt-4 pt-2" itemscope itemtype="http://schema.org/ImageGallery">
                    <?php if($images): ?>
                    <?php foreach($images as $image): ?>
                      <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                        <a href="<?= base_url('products/images/'.$image->filename); ?>" itemprop="contentUrl" data-size="500x600">
                          <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow" src="<?= base_url('products/images/'.$image->filename); ?>" alt="" />
                        </a>
                    </figure>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-lg-5 mx-auto">
                  <h3 class="mt-lg-0 mt-4"><?= $product->name; ?></h3>
                  <hr id="color">
                  <div class="text-sm mb-3"><span class="badge text-bg-warning me-3"><?= $product->strain_name; ?></span><span class="badge text-bg-dark ms-3">THC <?= $product->thc_value; ?><?= ($product->thc_unit == 'pct') ? '%' : $product->thc_unit;?></span></div>
                  <div class="rating">
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                  </div>
                  
                  <div class="row mb-5">
                    <div class="col-12 col-sm-12">
                      <h6 class="mb-0 mt-3">Price</h6>
                      <h5>$<span class="price d-inline"><?= $product->price; ?></span></h5>
                      <?php if($product->stocks > 0): ?>
                      <span class="badge text-bg-success">In Stock</span>
                      <?php else: ?>
                      <span class="badge text-bg-danger">Out Of Stock</span>
                      <?php endif; ?>
                    </div>
                  </div>
                        
                  <hr id="color">

                  <div class="row mb-5">
                    <div class="col-12 col-sm-12">
                    <h6 class="mb-2">Description</h6>
                    <?= $product->description; ?>
                    </div>
                  </div>
                  
                  <div class="row mt-4">
                    <div class="col-lg-2">
                      <label class="ms-0">Quantity</label>
                      <input type="number" min="1" max="100" value="1" name="qty">
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-lg-5 d-flex flex-row align-items-center">
                      <button class="btn add-to-cart bg-warning text-white mb-0 mt-lg-auto w-100" type="button" name="add-to-cart" data-pid="<?= $product->id; ?>">
                        <span class="material-icons">add_shopping_cart</span> Add to cart
                      </button>
                      <div class="[lds-hourglass d-none"></div>
                    </div>
                  </div>
                </div>
              </div>

              <?php if(!empty($rate) && $rate[0]->total_ratings != 0): ?>
                <pre><?php print_r($rate); ?></pre>
              <div class="row mt-5">
                <h6>Ratings</h5>
                
                <hr/>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="card">
                      <div class="card-body text-center" style="background-color: #f0f2f5 !important;">
                        <div class="rating-block">
                          <h6>Average user rating</h6>
                          <?php foreach($rate as $rt ): ?>
                            <?php if($rt->avg_r != null): ?>
                          <h2 class="bold padding-bottom-7"><?= bcadd(0, $rt->avg_r, 1); ?><small>/ 5</small></h2>
                          <?php 
                            for( $x = 0; $x < 5; $x++ )
                            {
                                if( floor( $rt->avg_r )-$x >= 1 )
                                { echo '<i style="color: gold;" class="fa fa-star">&nbsp;</i>'; }
                                elseif( $rt->avg_r-$x > 0 )
                                { echo '<i style="color: gold;" class="fa fa-star-half"></i>'; }
                                else
                                { echo '<i style="color: gold;" class="fa fa-star-o"></i>'; }
                            }
                          ?>                           
                              
                          <?php else: ?>
                            <h2 class="bold padding-bottom-7">0<small>/ 5</small></h2>
                            <?php endif; ?>
                          <?php endforeach; ?>
                          <!-- <div class="rating">
                            <i class="material-icons text-lg">grade</i>
                            <i class="material-icons text-lg">grade</i>
                            <i class="material-icons text-lg">grade</i>
                            <i class="material-icons text-lg">star_outline</i>
                          </div> -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="col-sm-6">
                    <div class="card">
                      <div class="card-body">
                        <?php for($x=5;$x>0;$x--): ?>
                        <div class="row">
                          <div class="col-md-4 text-start">
                            <?php for($y=$x;$y>0;$y--): ?>
                              <i class="material-icons text-lg">grade</i>
                            <?php endfor; ?>
                            <?php for($y=5-$x;$y>0;$y--): ?>
                              <i class="material-icons text-lg">star_outline</i>
                            <?php endfor; ?>
                          </div>
                          <div class="col-md-6 text-start">
                            <div class="progress" style="margin-top: 10px;">
                              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 100%">
                              <span class="sr-only">80% Complete (danger)</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 text-start">1</div>
                        </div>
                        <?php endfor; ?>
                      </div>
                    </div>
                  </div>			 -->
                </div>
                <!-- <div class="row">
                  <div class="col-sm-7">
                    <hr/>
                    <div class="review-block">
                      <div class="row">
                        <div class="col-sm-3">
                          <img src="http://dummyimage.com/60x60/666/ffffff&text=No+Image" class="img-rounded">
                          <div class="review-block-name"><a href="#">Unknown</a></div>
                          <div class="review-block-date">10 Aug 2022<br/><small><i>1 day ago</i></small></div>
                        </div>
                        <div class="col-sm-9">
                          <div class="review-block-rate">
                            <?php for($y=5;$y>0;$y--): ?>
                                <i class="material-icons text-lg">grade</i>
                              <?php endfor; ?>
                          </div>
                          <div class="review-block-description">Nice product.</div>
                        </div>
                      </div>
                      <hr/>
                    </div>
                  </div>
                </div> -->

                <div class="row">
                  <div class="col-sm-7">
                    <hr/>
                    <?php foreach($rate_data as $rate): ?>
                    <div class="review-block">
                      <div class="row">
                        <div class="col-sm-3">
                          <img src="http://dummyimage.com/60x60/666/ffffff&text=No+Image" class="img-rounded">
                          <div class="review-block-name"><a href="#"> <?= $rate->first_name ?> <?= $rate->last_name ?></a></div>
                            <div class="review-block-date">
                            <?php
                              $date = $rate->created;     
                              $timestamp = strtotime($date);
                              $display_date = date("F j, Y", $timestamp);
                              ?>
                              <p><?= $display_date; ?></hp>
                              <!-- <br/><small><i>1 day ago</i></small> -->
                            </div>
                        </div>
                        <div class="col-sm-9">
                          <div class="review-block-rate">
                          
                          <?php for($y=0;$y<5 ;$y++): ?>
                            <?php if(($y+1)<=$rate->star): ?>
                               <?= '<i style="color: gold;" class="material-icons text-lg">grade</i>' ?>
                            <?php else: ?>
                              <?= '<i style="color: gold;" class="material-icons text-lg">star_outline</i>' ?>
                            <?php endif; ?>
                          <?php endfor; ?>
                           
                          <div class="review-block-description"> 
                          <?= $rate->message ?>
                          </div>
                      </div>
                        </div>
                      </div>
                      <hr/>
                    </div>
                    <?php endforeach; ?> 
                  </div>
                </div>
                
              </div>
              <?php endif; ?>

              <!-- <div class="row mt-5">
                <div class="col-12">
                  <h5 class="ms-3">Other Products</h5>
                  <div class="table table-responsive">
                    
                  </div>
                </div>
              </div> -->

            </div>
          </div>
        </div>
      </div>
     
<?php $this->endSection() ?>
        
<?php 
  $session = session();
  // $uguid = ($session->get('guid')) ? $session->get('guid') : '';
  $uid = ($session->get('id')) ? $session->get('id') : 0;
?>

<!-- <pre><?php print_r($cookie_cart); ?></pre> -->

<?php $this->section("script") ?>
<script>
  console.log("scripts section");

  var cookie_cart = 'cart_data';

  $(document).on('click', '.add-to-cart', function(e) {
    e.preventDefault();

    $(this).prop('disabled', true);
    $(".lds-hourglass").removeClass('d-none');

    console.log("add to cart clicked");

    let pid = $(this).data('pid');  
    let qty = $("input[name=qty]").val();
    let get_cookie = '';
    let cookie_products = [];

    if($("[name='atoken']").attr('content') != "") {
      add_to_cart(<?= $uid; ?>, pid, qty);
    }
    else {
      // Current user is not logged in
      console.log("no JWT");

      //Check if cookie exists.  Get cookie value if any.
      get_cookie = getCookie(cookie_cart);

      // Cookie doesn't exist.  Create cookie
      if(!get_cookie) {
        console.log('cart_data cookie not set.');
    
        // Set value to add to the cookie
        cookie_products = [{"pid": pid, "qty": parseInt(qty),}];  // Create an array of the product data

        // Create cookie
        setCookie(cookie_cart, JSON.stringify(cookie_products), '1');
      }
      // Cookie exists.  Check if data is correct.  Add product data to the cart data.
      else {
        console.log('cart_data cookie found.');

        // Parse JSON data into readable array
        cookie_products = JSON.parse(get_cookie);

        // Check if product is already existing in the cookie
        let pid_exists = false;

        // Loop through each product in the cookie and match each product ids
        cookie_products.forEach(function(product) {
          console.log("products in cookie: ");
          console.log(product);

          // If a match is found, add the new qty to the existing qty.
          if(product.pid == pid) {
            console.log("product "+pid+" found");
            product.qty = parseInt(product.qty) + parseInt(qty);

            // Update the variable to indicate that the product id exists in the cookie
            pid_exists = true;
          }
        });

        // If product is not found after the loop, append the product
        if(pid_exists == false) {
          cookie_products.push({"pid": pid, "qty": parseInt(qty)});
        }

        console.log("New product array: ");
        console.log(cookie_products);

        // Save new products array to cookie
        setCookie(cookie_cart, JSON.stringify(cookie_products), '1');
      }

      $(".add-to-cart").removeAttr('disabled');
      $(".lds-hourglass").addClass('d-none');
    }

    // Update the cart counter
    update_cart_count();
  });

  $("body").delegate(".stars", "click", function(){
    let count = $(this).data('id');
    for(var x=1;x<=5;x++){
      count >= x ?  $('#star_'+x).html('grade') : $('#star_'+x).html('star_outline');
      
    }
    document.getElementById('ratings').value= count;
  });
</script>
<?php $this->endSection() ?>


<!-- <style>
.rate {
    float: left;
    height: 46px;   
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

/* Modified from: https://github.com/mukulkant/Star-rating-using-pure-css */
  .card-header {
    background-color: #f0f2f5 !important;
  }
.btn-grey{
    background-color:#D8D8D8;
	color:#FFF;
}
.rating-block{
	background-color:#FAFAFA;
	border:1px solid #EFEFEF;
	padding:15px 15px 20px 15px;
	border-radius:3px;
}
.bold{
	font-weight:700;
}
.padding-bottom-7{
	padding-bottom:7px;
}

.review-block{
	background-color:#FAFAFA;
	border:1px solid #EFEFEF;
	padding:15px;
	border-radius:3px;
	margin-bottom:15px;
}
.review-block-name{
	font-size:12px;
	margin:10px 0;
}
.review-block-date{
	font-size:12px;
}
.review-block-rate{
	font-size:13px;
	margin-bottom:15px;
}
.review-block-title{
	font-size:15px;
	font-weight:700;
	margin-bottom:10px;
}
.review-block-description{
	font-size:13px;
}
</style> -->