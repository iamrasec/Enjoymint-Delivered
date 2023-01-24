<!-- Navbar -->
<?php $this->section("content") ?>
<div class="container position-sticky z-index-sticky top-1">
  <div class="row"><div class="col-12">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-absolute bg-transparent shadow-none">
      <div class="container-fluid px-0">
        <a class="navbar-brand font-weight-bolder ms-sm-3" href="<?= base_url(); ?>" rel="tooltip" title="Enjoymint Delivered" data-placement="bottom">
          <img src="/assets/img/Enjoymint-Logo-Landscape-White-2.png" class="logo" />
        </a>
        <!-- <input type="text" class="location" id="location" name="location" placeholder="Enter Location"> -->
        <div class="searchbar" id="location-toggle">
        
<?php if($location_keyword == null): ?>
  <input class="search_input" type="text"  name="location" readonly placeholder="Enter Location...">          
            <a href="#" class="search_icon"><i class="fas fa-search fa-1x" id="fas"></i></a>
            <?php else: ?>
          <input class="search_input" type="text" value="<?= $location_keyword['address'] ;?>" name="location" readonly placeholder="Enter Location...">          
            <a href="#" class="search_icon"><i class="fas fa-search fa-1x" id="fas"></i></a>   
            
           <?php endif; ?>
           
          
          </div>
        <a class="d-flex d-md-none flex-row text-white opacity-6 me-2 py-4 py-md-2" href="<?= base_url('users'); ?>"><i class="material-icons me-2 text-xl" id="user_login">person</i></a>
        <a href="<?= base_url('cart'); ?>"><i class="d-flex d-md-none flex-row material-icons opacity-6 text-xl py-4 py-md-2" id="cart_icon">shopping_cart</i></a>
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
              <a class="nav-link ps-2 py-4 py-md-2 d-flex cursor-pointer align-items-center" href="<?= base_url(); ?>">
                <i class="material-icons opacity-6 me-2 text-md">home</i> Home
              </a>
            </li> 
                          
            <li class="nav-item mx-2 has-child">
              <a class="nav-link ps-2 py-4 py-md-2 d-flex cursor-pointer align-items-center" href="<?= base_url('shop'); ?>">
                <i class="material-icons opacity-6 me-2 text-md">shop</i> Shop
              </a>
              <ul class="d-none position-absolute bg-secondary list-unstyled">
                <li class="px-3 py-2 border-bottom">
                  <a href="<?= base_url('shop'); ?>" class="text-white">Scheduled</a>
                </li>
                <li class="px-3 py-2">
                  <a href="<?= base_url('shop/fast_tracked'); ?>" class="text-white">Fast-Tracked</a>
                </li>
               </ul>
            </li>

            <li class="nav-item mx-2">
              <a class="nav-link ps-2 py-4 py-md-2 d-flex cursor-pointer align-items-center" href="<?= base_url('about'); ?>">
              <i class="material-icons opacity-6 me-2 text-md">eco</i> About Us
              </a>
            </li>
            <li class="nav-item mx-2 mx-md-0 ms-lg-auto">
              <a class="nav-link nav-link-icon me-0 me-md-2 py-4 py-md-2" href="<?= base_url('blogs'); ?>">
              <i class="material-icons opacity-6 me-2 text-md">rss_feed</i> Blog
              </a>
            </li>       
            
            <li class="nav-item my-auto ms-3 ms-lg-0 py-4 py-md-2">
              <a href="tel:+14084844644" class="nav-cta btn btn-sm bg-primary-green mb-0 me-1 mt-2 mt-md-0">Call Now (408) 484-4644</a>
            </li>

            <li class="d-none d-md-flex nav-item mx-2">
              <a class="nav-link nav-link-icon me-2 py-4 py-md-2" href="<?= base_url('users'); ?>"><i class="material-icons opacity-6 me-2 text-md" id="user_login">person</i></a>
            </li>
            
            <li class="d-none d-md-flex nav-item my-auto ms-3 ms-lg-0 d-flex flex-row">
              <div class="cart-box" >
                <div class="cart-icon">
                  <a href="<?= base_url('cart'); ?>"><i class="material-icons opacity-6 text-xl py-4 py-md-2" id="cart_icon">shopping_cart</i></a>
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

<div class="d-none">
  <button type="button" class="btn location-popup btn-block btn-light mb-3" data-bs-toggle="modal" data-bs-target="#location-modal">Show Calendar</button>
</div>  

<?php include('_location_popup.php'); ?>

<style>

  ::placeholder {
   color: white;
   opacity: 1; /* Firefox */
  }

  .searchbar{
   margin-bottom: auto;
   margin-top: auto;
   height: 50px;
   background-color: #464a55;
   border-radius: 30px;
   padding: 10px;     
  }
  
  .search_input{
   color: white;
   border: 0;
   outline: 0;
   background: none;
   width: 250px;
   caret-color:transparent;
   line-height: 30px;
   transition: width 0.4s linear;
   padding: 0 10px;
  }

  .searchbar:hover > .search_input{ 
   padding-right: 0 15px;
   caret-color:red;
   transition: width 0.4s linear;
  }

  .searchbar:hover > .search_icon{
   background: white;
   color: #e74c3c;
  }

  .search_icon{
   height: 35px;
   width: 35px;
   margin-top: -32px;
   float: right;
   display: flex;
   justify-content: center;
   color:white;
   align-items: center;
   border-radius: 50%;
   text-decoration:none;
  }

  .pac-container {
   z-index: 1051 !important;
   box-sizing: content-box;
   padding-right: 10px;
   padding-left: 10px;
   margin-left: -10px;
   margin-top: 11px;
  }

  .pac-item {
   font-size:15px;
  }

  .pac-item-query {
   font-size:15px;
  }
  
</style>

<?php $this->endSection() ?>

<?php $this->section("scripts") ?>
 <script>
  $('#location-toggle').on('click', function(){
    $(".location-popup").click();
  });
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyD18INrxrjZ5J9w9LbBiN68thy_7fZn_jE&callback=initMap" ></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>

  var autocomplete;
        function initMap() {
            var input = document.getElementById('searchLocation');
            autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener("place_changed", fillInAddress);
            
        }
    function fillInAddress(){
        var place = autocomplete.getPlace();
        var street="";
        
        console.log(place);
    }
              
    window.document.addEventListener("DOMContentLoaded", () => {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
        const $section = document.querySelector(`[data-section=${params.section}]`);
        if ($section) {
            const $header = document.getElementById("topnav");
            const sectionRect = $section.getBoundingClientRect();
            const headerRect = $header.getBoundingClientRect();
            window.scroll({top: ((sectionRect.top + window.scrollY) - headerRect.height * 2), behavior: "smooth"});
            $section.classList.add("active-section");
        }
    });
 </script>
<?php $this->endSection() ?>