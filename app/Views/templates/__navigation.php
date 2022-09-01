<!-- Navbar -->
<div class="container position-sticky z-index-sticky top-1">
  <div class="row"><div class="col-12">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-absolute bg-transparent shadow-none">
      <div class="container-fluid px-0">
        <a class="navbar-brand font-weight-bolder ms-sm-3" href="<?= base_url(); ?>" rel="tooltip" title="Enjoymint Delivered" data-placement="bottom">
          <img src="/assets/img/Enjoymint-Logo-Landscape-White-2.png" class="logo" />
        </a>
        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon mt-2">
            <span class="navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
          </span>
        </button>
        <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
          <ul class="navbar-nav navbar-nav-hover ms-auto d-flex">
            <li class="nav-item mx-2">
              <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" href="<?= base_url(); ?>">
                <i class="material-icons opacity-6 me-2 text-md">home</i> Home
              </a>
            </li>

            <li class="nav-item mx-2">
              <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" href="<?= base_url('shop'); ?>">
                <i class="material-icons opacity-6 me-2 text-md">shop</i> Shop
              </a>
            </li>

            <li class="nav-item mx-2">
              <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" id="dropdownMenuDocs" data-bs-toggle="dropdown" aria-expanded="false" href="/about">
              <i class="material-icons opacity-6 me-2 text-md">eco</i> About Us
              </a>
            </li>
            <li class="nav-item ms-lg-auto">
              <a class="nav-link nav-link-icon me-2" href="https://github.com/creativetimofficial/material-kit" target="_blank" href="/blog">
              <i class="material-icons opacity-6 me-2 text-md">rss_feed</i> Blog
              </a>
            </li>
  
            <li class="nav-item my-auto ms-3 ms-lg-0">
              <a href="tel:+14084844644" class="btn btn-sm bg-primary-green mb-0 me-1 mt-2 mt-md-0">Call Now (408) 484-4644</a>
            </li>

            <li class="nav-item mx-2">
              <a class="nav-link nav-link-icon me-2" href="<?= base_url('users'); ?>"><i class="material-icons opacity-6 me-2 text-md" id="user_login">person</i></a>
            </li>
            
            <li class="nav-item my-auto ms-3 ms-lg-0 d-flex flex-row">
              <div class="cart-box" >
                <div class="cart-icon">
                  <a href="<?= base_url('checkout'); ?>"><i class="material-icons opacity-6 text-xl" id="cart_icon">shopping_cart</i></a>
                </div> 
              </div>
              <div class="counter">
                <p class="count_cart count" id="count_cart">0</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<!-- End Navbar -->
    </div>
  </div>
</div>
<!-- <style>
.material-icons {
  position: relative;
  top: 4px;
  z-index: 1;
  font-size: 24px;
}
</style> -->
