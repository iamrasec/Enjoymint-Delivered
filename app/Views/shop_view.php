<?php $this->extend("templates/base"); ?>

<?php $this->section("styles") ?>
<link id="pagestyle" href="<?php echo base_url('assets/css/shop-view.css'); ?>" rel="stylesheet" />
<?php $this->endSection() ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">
  <section class="pt-3 pb-4" id="popular-products">
    <div class="container">
      <div class="row">
        <button id="product-filter-toggle" class="d-block d-lg-none text-center">Filter</button>
        
        <?php echo $this->include('templates/_product_filter.php'); ?>

        <div class="col-lg-10 col-sm-12 mt-5 text-center">
          <?php if($fast_tracked == true): ?>
          <h1>Fast Tracked Shop</h1>
          <?php else: ?>
          <h1>Shop</h1>
          <?php endif; ?>
          <?php if(!empty($products)): ?>
          <div id="products-list-view" class="row">

            <div class="input-group" style="float: right; margin-top:-45px; margin-right:-45px;">
              <div class="input-group-prepend">
                <button type="button" id="toggle" class="input-group-text">
                <i class="fa fa-calendar-alt"></i>&nbsp;&nbsp; 
                <input style="color: white;" type="text" id="picker" placeholder="delivery schedule" name="delivery_schedule" class="form-control datetime_picker">
                </button>
              </div>
           </div>

            <?php foreach($products as $product): ?>
            <div class="col-lg-3 col-sm-6 pt-4 pb-4 reveal-fadein zoom">
              <div class="card product-featured">
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
                <div class="product-info d-flex flex-column px-2">
                  <a href="<?= base_url('products/'. $product['url']); ?>"><h5><?= $product['name']; ?></h5></a>
                  <div class="product-info-bottom d-flex flex-column mt-auto">
                    <p>
                      <span class="badge bg-dark"><span class="text-warning">THC</span> <?= $product['thc_value'] . (($product['thc_unit'] == 'pct') ? '%' : $product['thc_unit']); ?></span> 
                      <?php if($product['stocks'] > 0): ?>
                      <?php $btn_disabled = ''; ?>
                      <span class="badge text-bg-success">In Stock</span>
                      <?php else: ?>
                      <?php $btn_disabled = 'disabled'; ?>
                      <span class="badge text-bg-danger">Out Of Stock</span>
                      <?php endif; ?>
                    </p>
                    <hr id="color" class="mt-0">
                    <p class="price">$<span><?= $product['price']; ?></span></p>
                    <hr id="color" class="mt-0">
                    <?php if($product['stocks'] > 0): ?>
                    <button class="btn add-to-cart btn-md bg-warning text-white" name="add-to-cart" data-pid="<?= $product['id']; ?>" >
                      <span class="material-icons">add_shopping_cart</span> Add to Cart
                    </button>
                    <?php elseif($product['stocks'] <= 0): ?>
                      <button class="btn btn-md bg-warning text-white" name="add-to-cart" data-pid="<?= $product['id']; ?>" <?= $btn_disabled = 'disabled'; ?>>
                      <span class="material-icons">add_shopping_cart</span> Add to Cart
                    </button>
                    <?php endif; ?>
                    <div class="lds-hourglass d-none"></div>
                  </div>
                </div>
              </div>  
            </div>
            <?php endforeach; ?>
          </div>
          <div><?= $pager->links() ?></div>
          <?php else: ?>
          <div class="mt-5"><p>No Products Available.</p></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>7f[9'/r/7]
</div>

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

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
const selected = document.querySelector(".selected");
const optionsContainer = document.querySelector(".options-container");

const optionsList = document.querySelectorAll(".option");

selected.addEventListener("click", () => {
  // optionsContainer.classList.toggle("active");
});

optionsList.forEach(o => {
  o.addEventListener("click", () => {
    selected.innerHTML = o.querySelector("label").innerHTML;
    optionsContainer.classList.remove("active");
  });
});

</script>  -->

<div class="d-none">
  <button type="button" class="btn delivery-popup btn-block btn-light mb-3" data-bs-toggle="modal" data-bs-target="#delivery-modal">Show Calendar</button>
</div>
<?php echo $this->include('templates/_delivery_popup.php'); ?>

<?php $this->endSection() ?>

<?php 
  $session = session();
  // $uguid = ($session->get('guid')) ? $session->get('guid') : '';
  $uid = ($session->get('id')) ? $session->get('id') : 0;
?>

<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= base_url('assets/js/product-filter.js'); ?>"></script>

<script>
  console.log("scripts section");

  jQuery.datetimepicker.setDateFormatter('moment');

  var serverDate = '<?php echo $currDate; ?>';
  
  $('#picker').datetimepicker({
    timepicker: true,
    datepicker: true,
    format: 'YYYY-MM-DD h:mm a',
    minDate: serverDate,
    allowTimes: [
      '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'
    ],
    minTime: function setMinTime() {
      var today = new Date(serverDate);
      today.setHours(today.getHours() + 3);

      return today;
    }(),
    // hours12: true,
  });

  $('#toggle').on('click', function(){
    $('#picker').datetimepicker('toggle');
  });

  $('#inline_picker').datetimepicker({
    timepicker: true,
    datepicker: true,
    inline: true,
    format: 'YYYY-MM-DD h:mm a',
    minDate: serverDate,
    allowTimes: [
      '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'
    ],
    minTime: function setMinTime() {
      var today = new Date(serverDate);
      // today.setHours(today.getHours() + 3);

      console.log(today.getHours());
      console.log(today.getUTCHours());

      return today;
    }(),
    // hours12: true,
  });

  $(document).ready(function() {
    $(".delivery-popup").click();
    $(".save-delivery-schedule").click(function() {
      console.log($("#inline_picker").val());
    });
  });

  var cookie_cart = 'cart_data';

  $(document).on('click', '.add-to-cart', function(e) {
    e.preventDefault();

    $(this).prop('disabled', true);
    $(".lds-hourglass").removeClass('d-none');

    console.log("add to cart clicked");

    let pid = $(this).data('pid');
    let qty = 1;
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

</script>
<?php $this->endSection() ?>