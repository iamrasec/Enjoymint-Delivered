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
        <div class="row mt-5">
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Flower</a>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Pre-Rolls</a>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Concentrates</a>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Tinctures</a>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Capsules</a>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Edibles</a>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Cartriges</a>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <a href="#">Strains</a>
          </div>
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
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" /></a>
              </div>
              <div class="product-info">
                <a href="#"><h5>Minntz Indoor Flowers - Big Apple (3.5g Indica) - Cookies</h5></a>
                <p>20.037%~21.401% THC</p>
                <p class="price">$<span>33.50</span></p>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" /></a>
              </div>
              <div class="product-info">
                <a href="#"><h5>Minntz Indoor Flowers - Big Apple (3.5g Indica) - Cookies</h5></a>
                <p>20.037%~21.401% THC</p>
                <p class="price">$<span>33.50</span></p>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" /></a>
              </div>
              <div class="product-info">
                <a href="#"><h5>Minntz Indoor Flowers - Big Apple (3.5g Indica) - Cookies</h5></a>
                <p>20.037%~21.401% THC</p>
                <p class="price">$<span>33.50</span></p>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" /></a>
              </div>
              <div class="product-info">
                <a href="#"><h5>Minntz Indoor Flowers - Big Apple (3.5g Indica) - Cookies</h5></a>
                <p>20.037%~21.401% THC</p>
                <p class="price">$<span>33.50</span></p>
              </div>
            </div>
          </div>
        </div>
        <a class="btn btn-lg btn-primary">View all Products</a>
      </div>
    </div>
  </div>
</section>

<section class="pt-3 pb-4" id="discover">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-xs-12 mt-5 text-center">
        <h2>Discover</h2>
        <p>We have a wide range of products and various strains.<br>Choose the effects and benefits that suits you.</p>
        <div class="row mt-5">
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-verification.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Energy</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-lock.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Creativity</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-reset.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Focus</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-signin.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Bliss</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-signup.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Calm</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-verification.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Sleep</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-lock.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Arouse</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="discover-benefit">
              <div class="img-wrap">
                <a href="#"><img src="/assets/img/illustrations/illustration-reset.jpg" /></a>
              </div>
              <div class="discover-benefit-info">
                <a href="#"><h5>Comfort</h5></a>
                <a class="btn" href="#">Shop Now</a>
              </div>
            </div>
          </div>
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
        <p class="lead mb-0">We deliver the best web products</p>
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