<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<!-- -------- START HEADER 1 w/ text and image on right ------- -->

<header>
  <?php echo $this->include("sections/hero_section"); ?>
</header>
<!-- -------- END HEADER 1 w/ text and image on right ------- -->

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">

<section class="pt-3 pb-4" id="shop-by-category">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-sm-12 mt-5 text-center">
        <h2>Shop by Category</h2>
        <div class="row mt-5 d-flex justify-content-center">
          <?php foreach($categories as $category): ?>
          <div class="col-md-3 col-sm-6 pt-2 pb-2">
            <?php 
            switch(strtolower($category->name)) {
              case 'flowers':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">filter_vintage</i>';
                break;
              case 'pre-rolls':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">vaping_rooms</i>';
                break;
              case 'edibles':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">cake</i>';
                break;
              case 'concentrates':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">local_drink</i>';
                break;
              case 'tinctures':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">vaccines</i>';
                break;
              case 'topical':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">spa</i>';
                break;
              case 'beverages':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">liquor</i>';
                break;
              case 'extracts':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">water_drop</i>';
                break;
              case 'pre-orders':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">payments</i>';
                break;
              case 'accessories':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">devices_other</i>';
                break;
              case 'cartridges':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">hive</i>';
                break;
              case 'capsules':
                $cat_icon = '<i class="material-icons opacity-6 me-2" style="font-size: 30px;">cookie</i>';
                break;
              default:
                $cat_icon = '';
                break;
            } 
            ?>
            <a class="home-category-link border btn btn-outline-secondary px-6 py-2" href="<?= base_url('categories/'.$category->url); ?>"><?= $cat_icon . $category->name; ?></a>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-sm-12 mt-5 text-center">
      <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->
        <h2>Popular Products</h2>
        <div class="row">
          <?php for($countp = 0; $countp <= 3; $countp++): ?>
          <!-- <pre><?php print_r($products[$countp]); ?></pre> -->
          <div class="col-md-3 col-sm-6 pt-4 pb-4 reveal-fadein zoom">
            <div class="card product-featured">
              <div class="img-wrap">
                <a href="<?= base_url('products/'.$products[$countp]['url']); ?>"><img src="<?= base_url('products/images/'.$products[$countp]['images'][0]->filename); ?>" /></a>
              </div>
              <div class="product-info d-flex flex-column px-2">
                <a href="<?= base_url('products/'. $products[$countp]['url']); ?>"><h5><?= $products[$countp]['name']; ?></h5></a>
                <div class="product-info-bottom d-flex flex-column mt-auto">
                  <p>
                    <span class="badge bg-dark"><span class="text-warning">THC</span> <?= $products[$countp]['thc_value'] . (($products[$countp]['thc_unit'] == 'pct') ? '%' : $products[$countp]['thc_unit']); ?></span> 
                    <?php if($products[$countp]['stocks'] > 0): ?>
                    <span class="badge text-bg-success">In Stock</span>
                    <?php else: ?>
                    <span class="badge text-bg-danger">Out Of Stock</span>
                    <?php endif; ?>
                  </p>
                  <hr id="color" class="mt-0">
                  <p class="price">$<span><?= $products[$countp]['price']; ?></span></p>
                  <hr id="color" class="mt-0">
                  <button class="btn add-to-cart btn-md bg-danger text-white" type="button" name="add-to-cart" data-pid="<?= $products[$countp]['id']; ?>">
                    <span class="material-icons">add_shopping_cart</span> Add to Cart
                  </button>
                  <div class="lds-hourglass d-none"></div>
                </div>
              </div>
            </div>
          </div>
          <?php endfor; ?>
        </div>
        <a class="btn btn-lg bg-primary-green" href="<?= base_url('/shop'); ?>">View all Products</a>
      </div>
    </div>
  </div>
</section>

<section class="pt-3 pb-4" id="discover">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-xs-12 mt-5 text-center">
        <h2>Discover <span class="text-sm">(Coming Soon)</span></h2>
        <p>We have a wide range of products and various strains.<br>Choose the effects and benefits that suits you.</p>
        <div class="row mt-5">
          <?php foreach($experience as $exp) : ?> 
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit reveal-fadein zoom">
              <div class="img-wrap">
                <!-- <a href="#"><img src="/assets/img/illustrations/illustration-verification.jpg" /></a> -->
                <a href="<?= base_url('experience/'.$exp->url); ?>"><img src="<?= base_url('assets/img/experience/energy.jpg'); ?>" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="<?= base_url('experience/'.$exp->url); ?>"><h5><?= $exp->name ?></h5></a>
                <a class="btn" href="<?= base_url('experience/'.$exp->url); ?>">Shop Now</a>
              </div>
            </div>
          </div>
         <?php endforeach ; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pt-3 pb-4" id="inspiring-products">

</section>

<!--
<section class="my-5 py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-4 ms-auto me-auto p-lg-4 mt-lg-0 mt-4">
        <div class="rotating-card-container">
          <div class="card card-rotate card-background card-background-mask-primary shadow-primary mt-md-0 mt-5">
            <div class="front front-background" style="background-image: url(https://images.unsplash.com/photo-1569683795645-b62e50fbf103?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=987&q=80); background-size: cover;">
              <div class="card-body py-7 text-center">
                <i class="material-icons text-white text-4xl my-3">touch_app</i>
                <h3 class="text-white">Feel the <br /> Material Kit</h3>
                <p class="text-white opacity-8">All the Bootstrap components that you need in a development have been re-design with the new look.</p>
              </div>
            </div>
            <div class="back back-background" style="background-image: url(https://images.unsplash.com/photo-1498889444388-e67ea62c464b?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1365&q=80); background-size: cover;">
              <div class="card-body pt-7 text-center">
                <h3 class="text-white">Discover More</h3>
                <p class="text-white opacity-8"> You will save a lot of time going from prototyping to full-functional code because all elements are implemented.</p>
                <a href=".//sections/page-sections/hero-sections.html" target="_blank" class="btn btn-white btn-sm w-50 mx-auto mt-3">Start with Headers</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 ms-auto">
        <div class="row justify-content-start">
          <div class="col-md-6">
            <div class="info">
              <i class="material-icons text-gradient text-primary text-3xl">content_copy</i>
              <h5 class="font-weight-bolder mt-3">Full Documentation</h5>
              <p class="pe-5">Built by developers for developers. Check the foundation and you will find everything inside our documentation.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info">
              <i class="material-icons text-gradient text-primary text-3xl">flip_to_front</i>
              <h5 class="font-weight-bolder mt-3">Bootstrap 5 Ready</h5>
              <p class="pe-3">The world’s most popular front-end open source toolkit, featuring Sass variables and mixins.</p>
            </div>
          </div>
        </div>
        <div class="row justify-content-start mt-5">
          <div class="col-md-6 mt-3">
            <i class="material-icons text-gradient text-primary text-3xl">price_change</i>
            <h5 class="font-weight-bolder mt-3">Save Time & Money</h5>
            <p class="pe-5">Creating your design from scratch with dedicated designers can be very expensive. Start with our Design System.</p>
          </div>
          <div class="col-md-6 mt-3">
            <div class="info">
              <i class="material-icons text-gradient text-primary text-3xl">devices</i>
              <h5 class="font-weight-bolder mt-3">Fully Responsive</h5>
              <p class="pe-3">Regardless of the screen size, the website content will naturally fit the given resolution.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
-->





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

<div class="container-fluid bg-dark home-contact-form-wrap">
  <div class="container">
    <div class="home-contact-form">
      <div class="row">
        <div class="col-12 col-md-6">
          <h3>Got Questions?</h3>
          <div class="sub-head">Call <a href="tel:4084844644">(408) 484-4644</a></div>
          <p>If you have any questions or concerns, please don't hesitate to call the number above.<br>
            One of our professional staff will gladly attend to any inquiries.<br>
            Thank you and welcome to Enjoymint Delivered!</p>
        </div>
        <div class="col-12 col-md-6">
          <form id="contact_form" method="post" action="<?php echo base_url('api/contact/save'); ?>">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group input-group-dynamic mb-4">
                    <label class="form-label">First Name</label>
                    <input class="form-control" aria-label="First Name" name="first_name" type="text" >
                  </div>
                </div>
                <div class="col-md-6 ps-2">
                  <div class="input-group input-group-dynamic">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" placeholder="" name="last_name" aria-label="Last Name" >
                  </div>
                </div>
              </div>
              <div class="mb-4">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Phone Number</label>
                  <input type="text" class="form-control" placeholder="" name="phone_number" aria-label="Phone Number" >
                </div>
              </div>
              <div class="mb-4">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Email Address</label>
                  <input type="email" class="form-control" name="email">
                </div>
              </div>
              <div class="input-group mb-4 input-group-static">
                <label>Your message</label>
                <textarea name="message" class="form-control" id="message" name="message" rows="4"></textarea>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" class="btn bg-gradient-dark w-100">Send Message</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes slideup {
  0% {
      transform: translateY(50px);
      opacity: .5;
  }
  50% {
    opacity: .5;
    transform: translateY(-40px);
  }
  100%{
    transform: translateY(0px);
    opacity: 1;
  }
}

.card-new {
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 2px solid #e0e0e0;
  border-radius: 0.75rem;
}

#color {
  margin: 1rem 0;
  color: inherit;
  background-color: var(--bs-primary);
  border: 0;
  opacity: .4;
}

.sale {
  border: 1px solid black;
  background: var(--bs-primary);
  width: 10%;
  height: 10%;
  position: relative;
  top: 0;
  z-index: 5;
}

.reveal-fadein {
  transition: all 300ms ease;
  animation-name: slideup;
  animation-duration: 3s;
}

.reveal{
  position: relative;
  transform: translateY(150px);
  opacity: 0;
  transition: 1s all ease;
}

.reveal.active{
  transform: translateY(0);
  opacity: 1;
}

.sale-badge {
  width: 150px;
  height: 150px;
  position: absolute;
  top: -10px;
  right: -10px;
  overflow: hidden;
}

.sale-badge span {
  width: 225px;
  padding: 10px 0px;
  background: var(--bs-primary);
  display: block;
  position: absolute;
  top: 30px;
  left: -25px;
  transform: rotate(45deg);
  text-align: center;
  color: #fff;
  text-transform: uppercase;
  font-weight: bold;
}

.sale-badge:after, .sale-badge:before {
  content: '';
  border-top: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid var(--bs-primary-100);
  border-left: 5px solid var(--bs-primary-100);
  position: absolute;
}

.sale-badge:before {
  bottom: 2px;
  right: 0;
}

.sale-badge:after {
  top: 0;
  left: 2px;
}

.zoom {
  position: relative;
}
.zoom:hover {
  transform: scale(1.1);
  transition: all 300ms ease;
  cursor: pointer;
  z-index: 5;
}
.product-featured {
  box-shadow: 5px 4px 15px 9px rgb(0 0 0 / 10%), 0 2px 4px -1px rgb(0 0 0 / 6%);
  min-height: 550px;
}

.product-info {
  min-height: 290px;
}

.product-info h5 {
  font-size: 1rem;
  line-height: 1.625;
}
</style>

<?php $this->endSection() ?>

<?php 
  $session = session();
  // $uguid = ($session->get('guid')) ? $session->get('guid') : '';
  $uid = ($session->get('id')) ? $session->get('id') : 0;
?>

<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/contact.js"></script>

<script>
  console.log("scripts section");

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