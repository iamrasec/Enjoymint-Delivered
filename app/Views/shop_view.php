<?php $this->extend("templates/base"); ?>

<?php $this->section("styles") ?>
<link id="pagestyle" href="<?php echo base_url('assets/css/shop-view.css'); ?>" rel="stylesheet" />
<!-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.css" /> -->
  <!-- <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />   -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

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
  width: 60px;
  height: 60px;
  margin: 8px;
  box-sizing: border-box;
  border: 70px solid #489989;
  border-color: #489989 transparent #489989 transparent;
  animation: lds-hourglass 1.2s infinite;
}
.waiting-large  {
  width: 60px;
  height: 60px;
  position: fixed;
  top: 8%;
  left: 50%;
  z-index: 11111;
}
.loading-overlay {
  width: 100%;
  height: 100%;
  position: absolute;
  background: rgba(255,255,255,0.7);
  z-index: 1;
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
          <form method="GET" action="<?= base_url('/shop/')?>">
            <div class="search" style="text-align: right ;">
            <input type="text" name="inputdata" placeholder="Search">
            <button type="submit" class="btn bg-primary-green">Search</button>
            </div>
          </form>
          <br>
          <?php endif; ?>
          
          <div id="products-section">
            <?php echo $this->include('sections/products_list.php'); ?>
          </div>  

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
<!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<script>

  jQuery.datetimepicker.setDateFormatter('moment');

  var serverDate = '<?php echo $currDate; ?>';

  var today = new Date(serverDate);

  var dateNow = today.toISOString().slice(0, 10);

  $('#inline_picker').datetimepicker({
    timepicker: false,
    datepicker: true,
    inline: true,
    format: 'YYYY-MM-DD',
    minDate: serverDate,
    defaultDate: dateNow,
    defaultSelect: true,
    onGenerate:function(ct) {
      console.log("onGenerate");
      console.log(ct);
      // console.log("ct: " + ct.getDate());
      // console.log("today: " + today.getDate());

      if(ct.getDate() == today.getDate()) {
        // console.log("Same day");
        let currTime = today.getHours() + ":" + today.getMinutes();
        // console.log("today hour: " + currTime);

        $("#time_window option").each(function() {
          if($(this).val() < today.getHours() + ":" + today.getMinutes()) {
            $(this).hide();
          }
          else {
            $(this).prop("selected", true);
            return false;
          }
        });
      }
      else {
        $("#time_window option:first").prop("selected", "selected");
        // console.log("Different day");
      }
    },
    onSelectDate:function(ct,$i){
      $("#time_window option").show();
      $("#time_window option:selected").prop("selected", false);
    },
  });

  // Check if cookie exists
  var delivery_cookie = getCookie("delivery_schedule");

  $(document).on('click', '#toggle', function(){
    $(".delivery-popup").click();
  });

  $(document).ready(function() {

    <?php if($fast_tracked == false): ?>
    if(!delivery_cookie) {
      // Show delivery schedule popup if no cookie is found.
      $(".delivery-popup").click();
    }
    else {
      let delsched = JSON.parse(delivery_cookie);
      let delTime = delsched.t.split("-");
      let delFrom = tConvert(delTime[0]);
      let delTo = tConvert(delTime[1]);
      
      $("input.datetime_picker").val(delsched.d + " @ " + delFrom + " - " + delTo);
    }

    // Save Delivery Schedule
    $(".save-delivery-schedule").click(function() {
      let timePickerVal = $("#inline_picker").datetimepicker('getValue');
      timePickerVal = JSON.stringify(timePickerVal).split("T");

      let delsched = {};
      delsched.d = timePickerVal[0].substring(1);
      delsched.t = $("#time_window").find(":selected").val();

      setCookie("delivery_schedule", JSON.stringify(delsched), '1');

      let delTime = delsched.t.split("-");
      let delFrom = tConvert(delTime[0]);
      let delTo = tConvert(delTime[1]);

      $("input.datetime_picker").val(delsched.d + " @ " + delFrom + " - " + delTo);
      // console.log(delsched.d + " @ " + delsched.t);
      $(".btn-link").click();
    });
    <?php endif; ?>
  });

  var cookie_cart = 'cart_data';

  $(document).on('click', '.add-to-cart', function(e) {
    e.preventDefault();

    $(this).prop('disabled', true);
    $(".lds-hourglass").removeClass('d-none');

    // console.log("add to cart clicked");

    let pid = $(this).data('pid');
    let qty = 1;
    let get_cookie = '';
    let cookie_products = [];

    if($("[name='atoken']").attr('content') != "") {
      add_to_cart(<?= $uid; ?>, pid, qty);
    }
    else {
      // Current user is not logged in
      // console.log("no JWT");

      //Check if cookie exists.  Get cookie value if any.
      get_cookie = getCookie(cookie_cart);

      // Cookie doesn't exist.  Create cookie
      if(!get_cookie) {
        // console.log('cart_data cookie not set.');

        // Set value to add to the cookie
        cookie_products = [{"pid": pid, "qty": parseInt(qty),}];  // Create an array of the product data

        // Create cookie
        setCookie(cookie_cart, JSON.stringify(cookie_products), '1');
      }
      // Cookie exists.  Check if data is correct.  Add product data to the cart data.
      else {
        // console.log('cart_data cookie found.');

        // Parse JSON data into readable array
        cookie_products = JSON.parse(get_cookie);

        // Check if product is already existing in the cookie
        let pid_exists = false;

        // Loop through each product in the cookie and match each product ids
        cookie_products.forEach(function(product) {
          // console.log("products in cookie: ");
          // console.log(product);

          // If a match is found, add the new qty to the existing qty.
          if(product.pid == pid) {
            // console.log("product "+pid+" found");
            product.qty = parseInt(product.qty) + parseInt(qty);

            // Update the variable to indicate that the product id exists in the cookie
            pid_exists = true;
          }
        });

        // If product is not found after the loop, append the product
        if(pid_exists == false) {
          cookie_products.push({"pid": pid, "qty": parseInt(qty)});
        }

        // console.log("New product array: ");
        // console.log(cookie_products);

        // Save new products array to cookie
        setCookie(cookie_cart, JSON.stringify(cookie_products), '1');
      }

      $(this).removeAttr('disabled');
      $(".lds-hourglass").addClass('d-none');
      enjoymintAlert('', 'Product added to cart', 'success', 0);
    }

    // Update the cart counter
    update_cart_count();
  });

</script>
<?php $this->endSection() ?>