<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">
  <section class="pt-3 pb-4" id="popular-products">
    <div class="container">
      <div class="row">
        <button id="product-filter-toggle" class="d-block d-lg-none text-center">Filter</button>
        <div class="product-filter-form card col-lg-2 mt-2 mt-lg-8 d-lg-flex">
    
          <h5>Filter By:</h5>
          <form method='get' action="<?= base_url('/shop/')?>" id="searchForm">
            <div class="row">

              <div class="select-box" >
                <select class="selected" name="category">
                  <option value="0">Select Category:</option>
                  <?php foreach($categories as $category): ?>
                  <?php  echo '<option value="'.$category->id.'">'.$category->name.'</option>' ?>
                  <?php endforeach; ?>
                </select>
          
                <select class="selected" id="strain" name="strain">
                  <option value="0">Select Strain:</option>
                  <?php foreach($strains as $str): ?>
                  <?php  echo '<option value="'.$str->id.'">'.$str->url_slug.'</option>' ?>
                  <?php endforeach; ?>
                </select>
            
                <select class="selected" name="brands">
                  <option value="0">Select Brand:</option>
                  <?php foreach($brands as $brand): ?>
                  <?php  echo '<option value="'.$brand->id.'">'.$brand->name.'</option>' ?>
                  <?php endforeach; ?>
                </select>
              
                <label>Price Range:</label>
                <div slider id="slider-distance" class="mt-1">
                  <div>
                    <div inverse-left style="width:60%;"></div>
                    <div inverse-right style="width:60%;"></div>
                    <div range style="left:0%;right:0%;"></div>
                    <span thumb style="left:0%;"></span>
                    <span thumb style="left:100%;"></span>
                    <div sign style="left:0%;">
                      <span id="value">0</span>
                    </div>
                    <div sign style="left:100%;">
                      <span id="value">300</span>
                    </div>
                  </div>
                  <input type="range" value="0" name="min_price" max="300" min="0" step="1" oninput="
                  this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
                  let value = (this.value/parseInt(this.max))*100
                  var children = this.parentNode.childNodes[1].childNodes;
                  children[1].style.width=value+'%';
                  children[5].style.left=value+'%';
                  children[7].style.left=value+'%';children[11].style.left=value+'%';
                  children[11].childNodes[1].innerHTML=this.value;" />

                  <input type="range" value="100" name="max_price" max="300" min="0" step="1" oninput="
                  this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
                  let value = (this.value/parseInt(this.max))*100
                  var children = this.parentNode.childNodes[1].childNodes;
                  children[3].style.width=(100-value)+'%';
                  children[5].style.right=(100-value)+'%';
                  children[9].style.left=value+'%';children[13].style.left=value+'%';
                  children[13].childNodes[1].innerHTML=this.value;" />
                </div>
          
                <label class="mt-2">THC Value:</label>
                <div slider id="slider-distance" class="mt-1">
                  <div>
                    <div inverse-left style="width:70%;"></div>
                    <div inverse-right style="width:70%;"></div>
                    <div range style="left:0%;right:0%;"></div>
                    <span thumb style="left:0%;"></span>
                    <span thumb style="left:100%;"></span>
                    <div sign style="left:0%;">
                      <span id="value">0</span>
                    </div>
                    <div sign style="left:100%;">
                      <span id="value">100</span>
                    </div>
                  </div>

                  <input type="range" value="0" name="min_thc" max="100" min="0" step="1" oninput="
                  this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
                  let value = (this.value/parseInt(this.max))*100
                  var children = this.parentNode.childNodes[1].childNodes;
                  children[1].style.width=value+'%';
                  children[5].style.left=value+'%';
                  children[7].style.left=value+'%';children[11].style.left=value+'%';
                  children[11].childNodes[1].innerHTML=this.value;" />

                  <input type="range" value="100" name="max_thc" max="100" min="0" step="1" oninput="
                  this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
                  let value = (this.value/parseInt(this.max))*100
                  var children = this.parentNode.childNodes[1].childNodes;
                  children[3].style.width=(100-value)+'%';
                  children[5].style.right=(100-value)+'%';
                  children[9].style.left=value+'%';children[13].style.left=value+'%';
                  children[13].childNodes[1].innerHTML=this.value;" />
                </div>

                <label class="mt-2">CBD Value:</label>
                <div slider id="slider-distance" class="mt-1">
                  <div>
                    <div inverse-left style="width:70%;"></div>
                    <div inverse-right style="width:70%;"></div>
                    <div range style="left:0%;right:0%;"></div>
                    <span thumb style="left:0%;"></span>
                    <span thumb style="left:100%;"></span>
                    <div sign style="left:0%;">
                      <span id="value">0</span>
                    </div>
                    <div sign style="left:100%;">
                      <span id="value">200</span>
                    </div>
                  </div>

                  <input type="range" value="0" name="min_cbd" max="200" min="0" step="1" oninput="
                  this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
                  let value = (this.value/parseInt(this.max))*100
                  var children = this.parentNode.childNodes[1].childNodes;
                  children[1].style.width=value+'%';
                  children[5].style.left=value+'%';
                  children[7].style.left=value+'%';children[11].style.left=value+'%';
                  children[11].childNodes[1].innerHTML=this.value;" />

                  <input type="range" value="100" name="max_cbd" max="200" min="0" step="1" oninput="
                  this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
                  let value = (this.value/parseInt(this.max))*100
                  var children = this.parentNode.childNodes[1].childNodes;
                  children[3].style.width=(100-value)+'%';
                  children[5].style.right=(100-value)+'%';
                  children[9].style.left=value+'%';children[13].style.left=value+'%';
                  children[13].childNodes[1].innerHTML=this.value;" />
                </div>

                <!-- <p>
                <label>CBD_value:</label>
                <input type="text" id="textInput1" value="0" style="border: 0;">
                </p>
                <input type="range" name="cbd_value" min="0" max="10" value="0" onchange="updateTextInput1(this.value);" >
                -->
                <!-- <input type="text" id="search" class="form-control w-20 border px-2" name="search" placeholder="Search here"> -->
                
                <button id="searchFormSubmit" class="btn bg-primary-green mt-5">Search</button> 
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-10 col-sm-12 mt-5 text-center">
          <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->
          <h1>All Products</h1>
          
          <div class="row">
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
                      <span class="badge text-bg-success">In Stock</span>
                      <?php else: ?>
                      <span class="badge text-bg-danger">Out Of Stock</span>
                      <?php endif; ?>
                    </p>
                    <hr id="color" class="mt-0">
                    <p class="price">$<span><?= $product['price']; ?></span></p>
                    <hr id="color" class="mt-0">
                    <button class="btn add-to-cart btn-md bg-danger text-white" name="add-to-cart" data-pid="<?= $product['id']; ?>">
                      <span class="material-icons">add_shopping_cart</span> Add to Cart
                    </button>
                    <div class="lds-hourglass d-none"></div>
                  </div>
                </div>
              </div>  
            </div>
            <?php endforeach; ?>
          </div>
          <div><?= $pager->links() ?></div>
        </div>
      </div>
    </div>
  </section>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
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

 </script> 
 <style>
  * {
  margin: 0;
  box-sizing: border-box;
}

body {
  font-family: "Roboto", sans-serif;
  background: #f7f6ff;
}

h2 {
  margin: 16px;
}

.select-box {
  display: flex;
  width: 400px;
  flex-direction: column;
}

.select-box .options-container {
  background: #2f3640;
  color: #f5f6fa;
  max-height: 0;
  width: 100%;
  opacity: 0;
  transition: all 0.4s;
  border-radius: 8px;
  overflow: hidden;

  order: 1;
}

.selected {
  background: #2f3640;
  border-radius: 8px;
  margin-bottom: 8px;
  color: #f5f6fa;
  position: relative;

  order: 0;
}

.selected::after {
  content: "";
  background: url("img/arrow-down.svg");
  background-size: contain;
  background-repeat: no-repeat;

  position: absolute;
  height: 100%;
  width: 32px;
  right: 10px;
  top: 5px;

  transition: all 0.4s;
}

.select-box .options-container.active {
  max-height: 240px;
  opacity: 1;
  overflow-y: scroll;
}

.select-box .options-container.active + .selected::after {
  transform: rotateX(180deg);
  top: -6px;
}

.select-box .options-container::-webkit-scrollbar {
  width: 8px;
  background: #0d141f;
  border-radius: 0 8px 8px 0;
}

.select-box .options-container::-webkit-scrollbar-thumb {
  background: #525861;
  border-radius: 0 8px 8px 0;
}

.select-box .option,
.selected {
  padding: 12px;
  cursor: pointer;
}

.select-box .option:hover {
  background: #414b57;
}

.select-box label {
  cursor: pointer;
}

.select-box .option .radio {
  display: none;
}
  [slider] {
  width: 100%;
  position: relative;
  height: 5px;
  margin: 45px 0 10px 0;
}

[slider] > div {
  position: absolute;
  left: 13px;
  right: 15px;
  height: 5px;
}
[slider] > div > [inverse-left] {
  position: absolute;
  left: 0;
  height: 5px;
  border-radius: 10px;
  background-color: #CCC;
  margin: 0 7px;
}

[slider] > div > [inverse-right] {
  position: absolute;
  right: 0;
  height: 5px;
  border-radius: 10px;
  background-color: #CCC;
  margin: 0 7px;
}


[slider] > div > [range] {
  position: absolute;
  left: 0;
  height: 5px;
  border-radius: 14px;
  background-color: #d02128;
}

[slider] > div > [thumb] {
  position: absolute;
  top: -7px;
  z-index: 2;
  height: 20px;
  width: 20px;
  text-align: left;
  margin-left: -11px;
  cursor: pointer;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
  background-color: #FFF;
  border-radius: 50%;
  outline: none;
}

[slider] > input[type=range] {
  position: absolute;
  pointer-events: none;
  -webkit-appearance: none;
  z-index: 3;
  height: 14px;
  top: -2px;
  width: 100%;
  opacity: 0;
}

div[slider] > input[type=range]:focus::-webkit-slider-runnable-track {
  background: transparent;
  border: transparent;
}

div[slider] > input[type=range]:focus {
  outline: none;
}

div[slider] > input[type=range]::-webkit-slider-thumb {
  pointer-events: all;
  width: 28px;
  height: 28px;
  border-radius: 0px;
  border: 0 none;
  background: red;
  -webkit-appearance: none;
}

div[slider] > input[type=range]::-ms-fill-lower {
  background: transparent;
  border: 0 none;
}

div[slider] > input[type=range]::-ms-fill-upper {
  background: transparent;
  border: 0 none;
}

div[slider] > input[type=range]::-ms-tooltip {
  display: none;
}

[slider] > div > [sign] {
  opacity: 0;
  position: absolute;
  margin-left: -11px;
  top: -39px;
  z-index:3;
  background-color: #d02128;
  color: #fff;
  width: 28px;
  height: 28px;
  border-radius: 28px;
  -webkit-border-radius: 28px;
  align-items: center;
  -webkit-justify-content: center;
  justify-content: center;
  text-align: center;
}

[slider] > div > [sign]:after {
  position: absolute;
  content: '';
  left: 0;
  border-radius: 16px;
  top: 19px;
  border-left: 14px solid transparent;
  border-right: 14px solid transparent;
  border-top-width: 16px;
  border-top-style: solid;
  border-top-color: #d02128;
}

[slider] > div > [sign] > span {
  font-size: 12px;
  font-weight: 700;
  line-height: 28px;
}

[slider]:hover > div > [sign] {
  opacity: 1;
}

:root {
  --bs-blue: #63B3ED;
  --bs-indigo: #596CFF;
  --bs-purple: #6f42c1;
  --bs-pink: #d63384;
  --bs-red: #F56565;
  --bs-orange: #fd7e14;
  --bs-yellow: #FBD38D;
  --bs-green: #81E6D9;
  --bs-teal: #20c997;
  --bs-cyan: #0dcaf0;
  --bs-white: #fff;
  --bs-gray: #6c757d;
  --bs-gray-dark: #343a40;
  --bs-gray-100: #f8f9fa;
  --bs-gray-200: #f0f2f5;
  --bs-gray-300: #dee2e6;
  --bs-gray-400: #ced4da;
  --bs-gray-500: #adb5bd;
  --bs-gray-600: #6c757d;
  --bs-gray-700: #495057;
  --bs-gray-800: #343a40;
  --bs-gray-900: #212529;
  --bs-primary: #e91e63;
  --bs-primary-100: #640324;
  --bs-secondary: #7b809a;
  --bs-success: #4CAF50;
  --bs-info: #1A73E8;
  --bs-warning: #fb8c00;
  --bs-danger: #F44335;
  --bs-light: #f0f2f5;
  --bs-dark: #344767;
  --bs-white: #fff;
  --bs-primary-rgb: 233, 30, 99;
  --bs-secondary-rgb: , 128, 154;
  --bs-success-rgb: 76, 175, 80;
  --bs-info-rgb: 26, 115, 232;
  --bs-warning-rgb: 251, 140, 0;
  --bs-danger-rgb: 244, 67, 53;
  --bs-light-rgb: 240, 242, 245;
  --bs-dark-rgb: 52, 71, 103;
  --bs-white-rgb: 255, 255, 255;
  --bs-white-rgb: 255, 255, 255;
  --bs-black-rgb: 0, 0, 0;
  --bs-body-color-rgb: , 128, 154;
  --bs-body-bg-rgb: 255, 255, 255;
  --bs-font-sans-serif: "Roboto", Helvetica, Arial, sans-serif;
  --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
  --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
  --bs-body-font-family: var(--bs-font-sans-serif);
  --bs-body-font-size: 1rem;
  --bs-body-font-weight: 400;
  --bs-body-line-height: 1.5;
  --bs-body-color: #7b809a;
  --bs-body-bg: #fff;
}

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
<script src="<?= base_url('assets/js/product-filter.js'); ?>"></script>

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