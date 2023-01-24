<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<style>
  .alert-primary {
    background-color: #fff3cd;
    background-image: none !important;
    color: #856404;
  }
  .btn:disabled {
    background-color: #cccccc !important;
  }
</style>

<?php $fast_tracked = true; ?>

<main class="main-content position-relative border-radius-lg mt-9">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="continue_shopping px-3"><a href="<?= base_url('shop'); ?>"><i class="fas fa-chevron-left"></i> Continue Shoppping</a></div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-8 col-xs-12">
        <div class="card card-body blur shadow-blur mx-3 mx-md-4">
          <h1 class="pagetitle">Your Cart</h1>

          <div class="subtotal_short_alert alert alert-primary d-none" role="alert">You're only $<span class="subtotal_short_amount">0</span> away from waiving the service fee!  Click <a href="<?= base_url('/shop'); ?>">HERE</a> to add another product.</div>
          <div class="subtotal_below_min alert alert-primary d-none" role="alert">A minimum of $25 subtotal is required.  Click <a href="<?= base_url('/shop'); ?>">HERE</a> to add another product.</div>
                
          <div class="subtotal_short_alert1 alert alert-primary d-none" role="alert">You're only $<span class="subtotal_short_amount1">0</span> away from waiving the service fee!  Click <a href="<?= base_url('/shop'); ?>">HERE</a> to add another product.</div>
          <div class="subtotal_below_min1 alert alert-primary d-none" role="alert">A minimum of $50 subtotal is required.  Click <a href="<?= base_url('/shop'); ?>">HERE</a> to add another product.</div>
                
          <?php if(empty($cart_products)): ?>
          <p>There are no products in your cart.  <a class="text-primary text-gradient font-weight-bold" href="<?= base_url('shop'); ?>">Click here</a> to continue shopping.</p>
          <?php else: ?>
          <div class="row">
            <div class="col-12">
              <form id="update-cart-form" method="POST" action="<?= base_url('cart/update_cart'); ?>">
                <input type="hidden" name="guid" value="<?= $guid; ?>">
              
                <h4>Products</h4><br>
                <table id="cart_products" class="w-100">
                  <tbody>
                  <?php foreach($cart_products as $product): ?>
                    <?php if($product['product_data']->delivery_type == 0 || $product['product_data']->delivery_type == 1) { $fast_tracked = false; } ?>
                    <tr class="pid-<?= $product['pid']; ?> border">
                      <td>
                        <div class="row product-wrap d-flex py-3">
                          <div class="col-12 col-md-2 col-xs-12 product-img">
                            <?php if(!empty($product['images'])): ?>
                            <img src="<?= base_url('products/images/'.$product['images'][0]->filename); ?>" style="width: 100px;">
                            <?php endif; ?>
                          </div>
                          <div class="col-12 col-md-8 col-xs-12 product-details">
                            <h6 class="product-title">
                              <a href="<?= base_url('products/'. $product['product_data']->url); ?>"><?= $product['product_data']->name; ?></a>
                            </h6>
                            <div class="text-sm mb-3">
                              <span class="badge text-bg-warning me-3"><?= $product['product_data']->strain_name; ?></span>
                              <span class="badge text-bg-dark ms-3">THC <?= $product['product_data']->thc_value; ?><?= ($product['product_data']->thc_unit == 'pct') ? '%' : $product['product_data']->thc_unit;?></span>
                            </div>
                            <div class="product-qty">
                              <span>QTY: </span><input type="number" name="cart[<?= $product['pid']; ?>][qty]" class="product-qty product-<?= $product['pid']; ?>-qty" min="1" max="100" value="<?= $product['qty']; ?>" data-pid="<?= $product['pid']; ?>" data-unit-price="<?= $product['product_data']->price; ?>">
                            </div><br>
                          </div>
                          <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                            <input type="hidden" class="product-total-price product-<?= $product['pid']; ?>-total-price" value="<?= number_format($product['product_data']->price * $product['qty'], 2, '.', ''); ?>" data-pid="<?= $product['pid']; ?>">
                            <strong class="total-price-display">$<?= number_format($product['product_data']->price * $product['qty'], 2, '.', ','); ?></strong>
                            <div class="mt-3 d-flex align-items-end align-content-end"><a href="#" class="remove-item ms-auto" data-pid="<?= $product['pid']; ?>"><i class="fas fa-trash"></i></a></div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-12 col-xs-12 mt-2 text-right">
              <button id="update-cart" class="btn border ms-auto">Update Cart</button>
            </div>
          </div>
          <?php endif; ?>
         
        </div>
      </div>
      <?php if(!empty($cart_products)): ?>
      <div class="col-12 col-md-4 col-xs-12">
        <div class="spinner-wrap position-absolute w-25 h-30 px-2 py-2 rounded-5 d-none">
          <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
          </div>
        </div>
        <form id="cart-checkout" method="post" action="<?= base_url('cart/checkout/'); ?>">
          <div class="cart-summary px-3 py-3 px-4 rounded-5">
            <h4 class="text-white">Cart Summary</h4>

            <div>
              <?php if($fast_tracked == false): ?>
              <label class="text-white" style="font-size: 16px;">Delivery Schedule</label>
              <button type="button" id="toggle" class="input-group-text w-100 border-0" style="margin-top: -15px;">
              <i class="fa fa-calendar-alt" style="color: white"></i>&nbsp;&nbsp; 
              <input style="color: white;" type="hidden" placeholder="delivery schedule" name="delivery_schedule" class="form-control datetime_picker">
              <span class="del_date_display" style="color: #aeb0b5">Delivery Schedule</span>
              </button>   
              <input style="color: white;" type="hidden" value="nfs" name="del_type" class="form-control">
              <?php else: ?>
              <input style="color: white;" type="hidden" value="<?= $fscurrDay; ?>" name="delivery_schedule" class="form-control datetime_picker">
              <input style="color: white;" type="hidden" value="<?= $fsDelTime; ?>" name="time_window" class="form-control time_window">
              <input style="color: white;" type="hidden" value="fs" name="del_type" class="form-control">
              <?php endif; ?>
            </div>

            <div class="cart-item-count"><?= count($cart_products); ?> <?= (count($cart_products) > 1) ? "Items" : "Item"; ?></div>

            <div class="row mt-4">
              <div class="col-8 col-md-8 col-xs-8">Subtotal</div>
              <div class="col-4 col-md-4 col-xs-4 text-right"><span class="subtotal-cost">0</span></div>
            </div>
            <div class="row mt-3">
              <div class="col-8 col-md-8 col-xs-8">Tax (Estimated)</div>
              <div class="col-4 col-md-4 col-xs-4 text-right"><span class="tax-cost">0</span></div>
            </div>
            <div class="row mt-3 service-charge d-none">
              <div class="col-8 col-md-8 col-xs-8">Service Charge*</div>
              <div class="col-4 col-md-4 col-xs-4 text-right"><span class="service-charge-cost">$<?= number_format($service_charge, 2); ?></span></div>
            </div>
            <div class="row mt-3">
              <div class="col-8 col-md-8 col-xs-8">Total</div>
              <div class="col-4 col-md-4 col-xs-4 text-right"><span class="total-cost">0</span></div>
            </div>
            <div class="row mt-5">
              <div class="col-12 col-md-12 col-xs-12 d-grid">
                <button class="btn bg-primary-green btn-lg checkout-btn" type="submit" disabled>Proceed to Checkout</button>
              </div>
            </div>
          </div>

          <?php if($fast_tracked == false): ?>
          <div class="d-none">
            <button type="button" class="btn delivery-popup btn-block btn-light mb-3" data-bs-toggle="modal" data-bs-target="#delivery-modal">Show Calendar</button>
          </div>
          <?php echo $this->include('templates/_delivery_popup.php'); ?>
          <?php endif; ?>
          
           
         
        </form>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php echo $this->include('cart/_login_register_modal.php'); ?>

<!-- <link type="text/css" href="<?php echo base_url(); ?>/assets/css/jquery.datetimepicker.css" rel="stylesheet" /> -->
<?php $this->endSection(); ?>

<?php $this->section("script"); ?>
<!-- <script src="<?php echo base_url(); ?>/assets/js/jquery.datetimepicker.full.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/jquery.js"></script> -->

<?php 
  $session = session();
  // $uguid = ($session->get('guid')) ? $session->get('guid') : '';
  $uid = ($session->get('id')) ? $session->get('id') : 0;
?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>  
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> 
<script>

jQuery.datetimepicker.setDateFormatter('moment');

var serverDate = '<?php echo $currDate; ?>';

var today = new Date(serverDate);

// var dateNow = today.toISOString().slice(0, 10);

var maxDate = moment().add(6, 'day');

var enabledDates = [
moment(today, serverDate)
];

for (var i = 1; i <= 6; i++) {
  var date = moment().add(i, 'day').format(serverDate);
  enabledDates.push(moment(date, serverDate));
}

<?php if($fast_tracked == false): ?>
$('#inline_picker').datetimepicker({
  timepicker: false,
  datepicker: true,
  inline: true,
  format: 'YYYY-MM-DD',
  minDate: serverDate,
  maxDate: maxDate,
  enabledDates: enabledDates,
  // defaultDate: dateNow,
  defaultSelect: true,
  onGenerate:function(ct) {
    // console.log("onGenerate");
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
    // console.log("onSelectDate");
    $("#time_window option").show();
    $("#time_window option:selected").prop("selected", false);
  },
});

// Check if cookie exists
var delivery_cookie = getCookie("delivery_schedule");

$('#toggle').on('click', function(){
  $(".delivery-popup").click();
});

$(document).ready(function() {
  if(!delivery_cookie) {
    // Show delivery schedule popup if no cookie is found.
    $(".delivery-popup").click();
  }
  else {
    let delsched = JSON.parse(delivery_cookie);
    let delTime = delsched.t.split("-");
    let delFrom = tConvert(delTime[0]);
    let delTo = tConvert(delTime[1]);
    
    $("input.datetime_picker").val(delsched.d);
    $(".del_date_display").text(delsched.d + " @ " + delFrom + " - " + delTo);
  }

  // Save Delivery Schedule
  $(".save-delivery-schedule").click(function() {
    // console.log("save delivery schedule");
    // console.log($("#inline_picker").val());
    // console.log($("#time_window").find(":selected").val());

    let timePickerVal = $("#inline_picker").datetimepicker('getValue');
    timePickerVal = JSON.stringify(timePickerVal).split("T");

    let delsched = {};
    delsched.d = timePickerVal[0].substring(1);
    delsched.t = $("#time_window").find(":selected").val();

    // console.log(JSON.stringify(delsched));

    setCookie("delivery_schedule", JSON.stringify(delsched), '1');
    // $("input.datetime_picker").val(delsched.d + " @ " + delsched.t);
    $("input.datetime_picker").val(delsched.d);
    // console.log(delsched.d + " @ " + delsched.t);
    $(".btn-link").click();
  });
});
<?php endif; ?>

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

  $(".add-to-cart").removeAttr('disabled');
  $(".lds-hourglass").addClass('d-none');
}

// Update the cart counter
update_cart_count();
});
 
  var tax_rate = <?= $tax_rate; ?>;  // 35%
  var service_charge = <?= $service_charge; ?>;
  
  // Create our number formatter.
  var formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
  });
  
  $(document).ready(function() {
    // Compute for subtotal cost
    var subtotal = 0;
    <?php if($location_keyword == null): ?>
      var location = null;
    <?php else: ?>
    var location = "<?= $location_keyword['address'] ?>";
    <?php endif; ?>  
    $(".product-total-price").each(function() {
      subtotal += parseFloat($(this).val());
    });
    
  if(location.match("Hollister") || location.match("Half Moon Bay") || location.match("Moss Beach")) {
    if(subtotal <= 50 && subtotal > 0) {
      $(".subtotal_below_min1").removeClass("d-none");
      $(".checkout-btn").prop("disabled", true);
    }
    else if(subtotal >= 90 && subtotal < 100) {
      let subtotal_short_amount = 100 - subtotal;
      $(".subtotal_short_amount1").html(subtotal_short_amount.toFixed(2));
      $(".subtotal_short_alert1").removeClass("d-none");
      $(".subtotal_below_min1").addClass("d-none");
      $(".checkout-btn").prop("disabled", false);
    }
    else {
      $(".subtotal_short_alert1").addClass("d-none");
      $(".subtotal_below_min1").addClass("d-none");
      $(".checkout-btn").prop("disabled", false);
    }

    $(".subtotal-cost").html(formatter.format(subtotal));

// Subtotal + tax
var with_tax = subtotal.toFixed(2) * (tax_rate - 1);
$(".tax-cost").html(formatter.format(with_tax));

// Calculate Total
var total_cost = 0;

if(subtotal < 99) {
  total_cost = (subtotal.toFixed(2) * tax_rate) + service_charge;
  $('.service-charge').removeClass('d-none');
}
else{
  total_cost = subtotal.toFixed(2) * tax_rate;
  $('.service-charge').addClass('d-none');
}

$(".total-cost").html(formatter.format(total_cost));
  }
   else {
  
    if(subtotal <= 25 && subtotal > 0) {
      $(".subtotal_below_min").removeClass("d-none");
      $(".checkout-btn").prop("disabled", true);
    }
    else if(subtotal >= 40 && subtotal < 50) {
      let subtotal_short_amount = 50 - subtotal;
      $(".subtotal_short_amount").html(subtotal_short_amount.toFixed(2));
      $(".subtotal_short_alert").removeClass("d-none");
      $(".subtotal_below_min").addClass("d-none");
      $(".checkout-btn").prop("disabled", false);
    }
    else {
      $(".subtotal_short_alert").addClass("d-none");
      $(".subtotal_below_min").addClass("d-none");
      $(".checkout-btn").prop("disabled", false);
    }
  
    $(".subtotal-cost").html(formatter.format(subtotal));

    // Subtotal + tax
    var with_tax = subtotal.toFixed(2) * (tax_rate - 1);
    $(".tax-cost").html(formatter.format(with_tax));

    // Calculate Total
    var total_cost = 0;

    if(subtotal < 50) {
      total_cost = (subtotal.toFixed(2) * tax_rate) + service_charge;
      $('.service-charge').removeClass('d-none');
    }
    else{
      total_cost = subtotal.toFixed(2) * tax_rate;
      $('.service-charge').addClass('d-none');
    }
    
    $(".total-cost").html(formatter.format(total_cost));
   }
  });

  $(document).on("click", ".checkout-btn", function(e) {
    e.preventDefault();
    var sched = $('.datetime_picker').val();

    // console.log($("input[name=guid]").val());
    // console.log(sched);

    if($("input[name=guid]").val() == 0) {
      $("#loginRegisterModal").modal('show');
    }
    else if(sched == "") {
      $(".delivery-popup").click();
    }
    else {
    //   const fd = new FormData();
    //   fd.append('delivery_schedule', sched);
    //   fetch('<?= base_url('cart/checkout'); ?>',{
    //     method: 'POST',
    //     body: fd
    //   })
    //   .then()
    //   .catch((error) => {
    //     console.log('Error:', error);
    // });

      // window.location.replace("<?= base_url('cart/checkout/'); ?>");
      $("#cart-checkout").submit();
    }
  });

  $(document).on("click", ".remove-item", function(e) {
    e.preventDefault();
    let toRemove = $(this).data('pid');
    let guid = $("input[name=guid]").val();
    let delete_item = delete_cart_item(guid, toRemove);
    
    // console.log(delete_item);

    // if(delete_item == 0) {
    //   window.location.replace("<?= base_url('cart'); ?>");
    // }
  });

  $(document).on("click", "#update-cart", function(e) {
    e.preventDefault();
    if(jwt == '') {
      // let pid = $(this).data('pid');
      // let qty = $(this).val();

      // console.log("qty: "+qty);

      get_cookie = getCookie(cookie_cart);

      cookie_products = JSON.parse(get_cookie);

      cookie_products.forEach(function(product) {
        $(".product-qty").each(function() {
          if(product.pid == $(this).data('pid')) {
            product.qty = parseInt($(this).val());
          }
        });
      });

      // console.log(cookie_products);

      setCookie(cookie_cart, JSON.stringify(cookie_products), '1');
    }

    $("#update-cart-form").submit();
  });


</script>
<?php $this->endSection(); ?>