<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>

<?php if(isset($role) && $role != 4): ?>
<?php echo $this->include('templates/__dash_navigation.php'); ?>
<?php endif; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">

    <div class="row">
      <div class="col-lg-6">
        <h4><?php echo $page_title; ?></h4>
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-5 text-right d-flex flex-row justify-content-center">
        <!-- <a class="btn save-btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="#">Save</a> -->
      </div>
    </div>
    
    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">

            <div class="row">
              <div class="col-lg-12">
                <h3><?= $order_data->first_name ?> <?= $order_data->first_name ?></h3>
                <div class="mb-2">Order Key: <input id="order_key" type="text" value="<?= $order_data->order_key; ?>" disabled></div>
                <div>Order ID: <input id="order_id" type="text" value="<?= $order_data->id; ?>" disabled></div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-12 col-md-8 col-xs-12">
                <h5>Products</h5>
                <div class="input-group" style="float: right; margin-top:-52px; margin-right:30px;">
                    <div class="input-group-prepend">
                      <button type="button" id="toggle" class="input-group-text" style="width: 250px;">
                      <i class="fa fa-calendar-alt" style="color: black"></i>&nbsp;&nbsp; 
                      <input style="color: black;" type="text" id="picker" value="<?= $order_data->delivery_schedule; ?> @ <?= $order_data->delivery_time; ?>" placeholder="delivery schedule" name="delivery_schedule" class="form-control">
                      </button>
                      <input type="hidden" name="delivery_date" id="delivery_date" value="<?= $order_data->delivery_schedule; ?>">
                      <input type="hidden" name="delivery_time" id="delivery_time" value="<?= $order_data->delivery_time; ?>">
                    </div>  
                </div>

                <p class="no-products d-none">There are no products in the cart. Please add a product to cart to save changes.</p>

                <table id="cart_products" class="w-100">
                  <tbody>
                    <?php foreach($order_products as $product): ?>
                    <tr class="pid-<?= $product->product_id; ?> border">
                      <td>
                        <div class="row product-wrap d-flex py-3">
                          <div class="col-12 col-md-2 col-xs-12 product-img">
                            <?php if(!empty($product->product_data->images)): ?>
                            <img src="<?= base_url('products/images/'.$product->product_data->images[0]->filename); ?>" style="width: 100px;">
                            <?php endif; ?>
                          </div>
                          <div class="col-12 col-md-8 col-xs-12 product-details">
                            <h6 class="product-title">
                              <a href="<?= base_url('products/'. $product->product_data->url); ?>"><?= $product->product_data->name; ?></a>
                            </h6>
                            <div class="text-sm mb-3">
                              <span class="badge text-bg-warning me-3"><?= $product->product_data->strain_name; ?></span>
                              <span class="badge text-bg-dark ms-3">THC <?= $product->product_data->thc_value; ?><?= ($product->product_data->thc_unit == 'pct') ? '%' : $product->product_data->thc_unit;?></span>
                            </div>
                            <div class="product-qty">
                              <span>QTY: </span><input type="number" name="cart[<?= $product->product_id; ?>][qty]" class="product-<?= $product->product_id; ?>-qty" min="1" max="100" value="<?= $product->qty; ?>" data-pid="<?= $product->product_id; ?>" data-unit-price="<?= $product->product_data->price; ?>">
                            </div>
                          </div>
                          <div class="col-12 col-md-2 col-xs-12 price text-right pe-4">
                            <input type="hidden" class="product-total-price product-<?= $product->product_id; ?>-total-price" value="<?= number_format($product->product_data->price * $product->qty, 2, '.', ''); ?>">
                            <strong class="total-price-display">$<?= number_format($product->product_data->price * $product->qty, 2, '.', ','); ?></strong>
                            <div class="mt-3 d-flex align-items-end align-content-end"><a href="#" class="remove-item ms-auto" data-pid="<?= $product->product_id; ?>"><i class="fas fa-trash"></i></a></div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <div class="row mt-5 mb-3">
                  <div><strong>Add More Products to Cart</strong></div>
                  <div class="col-10 col-md-10 col-xs-10">
                    <div class="add-product-wrap w-100">
                      <select id="add-product-select" class="w-100">
                        <?php foreach($all_products as $add_product): ?>
                        <?php if($add_product->stocks > 0): ?>
                        <option value="<?= $add_product->id; ?>"><?= $add_product->name; ?> | (Remaining Stocks: <?= $add_product->stocks; ?>)</option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-2 col-md-2 col-xs-2">
                    <button class="add-more btn bg-warning text-white"><i class="fas fa-plus"></i> Add</button>
                  </div>
                </div>
                <!-- <a class="add-more btn btn-danger mt-3 py-3 px-5"><i class="far fa-plus-square"></i> Add Another Product</a> -->
                <button class="btn save-btn bg-gradient-primary mt-3 py-3 px-5 d-inline-block" style="float:right;">Save</button>
              </div>
              <div class="col-12 col-md-4 col-xs-12">
                <div class="order-user-data px-3 py-3 px-4 rounded-5">
                  <div class="row mt-2">
                    <div class="col-12 col-md-12">
                      <h5>Payment Method</h5>
                      <select id="payment_method" name="payment_method" class="w-100 px-2 py-2">
                        <option value="zelle" <?= ($order_data->payment_method == "zelle") ? "selected" : ""; ?>>Zelle</option>
                        <option value="paytender"<?= ($order_data->payment_method == "paytender") ? "selected" : ""; ?>>PayTender</option>
                        <option value="cash"<?= ($order_data->payment_method == "cash") ? "selected" : ""; ?>>Cash</option>
                        <option value="debit_card"<?= ($order_data->payment_method == "debit_card") ? "selected" : ""; ?>>Debit Card</option>
                      </select>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-12 col-md-12">
                      <h5>Delivery Address</h5>
                      <div class="input-group input-group-outline">
                        <input type="text" id="delivery_address" name="delivery_address" value="<?= $order_data->address; ?>" class="form-control bg-light">
                      </div>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-12 col-md-12">
                      <h5>Order Notes</h5>
                      <div class="input-group input-group-outline">
                        <textarea class="form-control bg-light w-100" id="order_notes" name="order_notes" style="height: 100px;"><?= $order_data->order_notes; ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-12 col-md-12">
                      <h5>Delivery Type</h5>
                      <select id="del_type" name="del_type" class="w-100 px-2 py-2 mb-4">
                        <option value="0" <?= ($order_data->payment_method == 0) ? "selected" : ""; ?>>Scheduled</option>
                        <option value="1" <?= ($order_data->payment_method == 1) ? "selected" : ""; ?>>Fast-tracked</option>
                      </select>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <pre>ORDER DATA: <?php print_r($order_data); ?></pre>
    <!-- <pre>ORDER PRODUCTS: <?php print_r($order_products); ?></pre> -->
    <!-- <pre>ALL PRODUCTS: <?php print_r($all_products); ?></pre> -->

  </div>
</main>

<div class="d-none">
  <button type="button" class="btn delivery-popup btn-block btn-light mb-3" data-bs-toggle="modal" data-bs-target="#delivery-modal">Show Calendar</button>
</div>
<?php echo $this->include('templates/_delivery_popup.php'); ?>

<style>
  .order-user-data {
    background-color: rgba(38,50,48,0.95) !important;
    border-radius: 2rem !important;
    color: #ffffff;
  }

  .order-user-data h5 {
    color: #ffffff;
  }

  #add-product-select {
    width: 100%;
    overflow: hidden;
    white-space: pre;
    text-overflow: ellipsis;
    -webkit-appearance: none;
  }

  #add-product-select option {
    border: solid 1px #DDDDDD;
  }
</style>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
   <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>  
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> 

<script>
// var jwt = $("[name='atoken']").attr('content');

const order_pids = [<?= $order_pids; ?>];

$(document).ready(function () {
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

  $('#toggle').on('click', function(){
    console.log("calendar button clicked!");
    $(".delivery-popup").click();
  }); 

  $(document).ready(function() {
    let delDate = "<?= $order_data->delivery_schedule; ?>";
    let delTime = "<?= $order_data->delivery_time; ?>";
    delTime = delTime.toString().split("-");
    let delFrom = tConvert(delTime[0]);
    let delTo = tConvert(delTime[1]);

    console.log("delTime: " + delTime);
    console.log("delFrom: " + delFrom);
    console.log("delTo: " + delTo);

    $("#picker").val(delDate + " @ " + delFrom + " - " + delTo);
  });

  // Save Delivery Schedule
  $(".save-delivery-schedule").click(function() {
    let timePickerVal = $("#inline_picker").datetimepicker('getValue');
    timePickerVal = JSON.stringify(timePickerVal).split("T");

    let delsched = {};
    delsched.d = timePickerVal[0].substring(1);
    delsched.t = $("#time_window").find(":selected").val();

    $("#delivery_date").val(delsched.d);
    $("#delivery_time").val(delsched.t);

    // setCookie("delivery_schedule", JSON.stringify(delsched), '1');

    let delTime = delsched.t.split("-");
    let delFrom = tConvert(delTime[0]);
    let delTo = tConvert(delTime[1]);

    $("#picker").val(delsched.d + " @ " + delFrom + " - " + delTo);
    // console.log(delsched.d + " @ " + delsched.t);
    $(".btn-link").click();
  });

  $(document).on('click', '.add-more', function(e) {
    e.preventDefault();

    $(this).prop('disabled', true);
    $(".save-btn").attr('disabled', true);
    
    let data = {};
    data.order_key = $("#order_key").val();
    data.oid = $("#order_id").val();
    data.pid = $("#add-product-select").find(":selected").val();
    data.order_pids = order_pids;

    $.ajax({
      type: "POST",
      url: '<?= base_url('/api/orders/add_product'); ?>',
      data: data,
      dataType: "json",
      success: function(json) {
        console.log(json);

        if(json.success == true) {
          console.log(json.data.append_data);

          order_pids.push(json.data.id);

          console.log(order_pids);
          $("#cart_products tbody").append(json.append_data);
          $(".no-products").addClass("d-none");
          $(".save-btn").removeAttr('disabled');

          enjoymintAlert('', json.message, 'success', 0);
        }
        else {
          enjoymintAlert('', json.message, 'error', 0);
        }

        if(order_pids.length > 0) {
          $(".save-btn").removeAttr('disabled');
        }

        $('.add-more').removeAttr('disabled');
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
        enjoymintAlert('Error', 'Error adding product.', 'error', 0);
        $('.add-more').removeAttr('disabled');
      },
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
      }
    });
    
    return false;
  });

  $(document).on("click", ".remove-item", function(e) {
    e.preventDefault();
    
    console.log("remove-item clicked");
    let toRemove = $(this).data('pid');

    $("tr.pid-"+toRemove).fadeOut(300, function() { 
      $(this).remove();
      order_pids.splice( $.inArray(toRemove, order_pids), 1 );
      if(order_pids.length == 0) {
        $(".no-products").removeClass("d-none");
        $(".save-btn").attr('disabled', true);
      }
      console.log(order_pids);
    });

    return false;
  });

  $(document).on('click', ".save-btn", function(e) {
    e.preventDefault();

    console.log("save button clicked");

    let data = {};
    data.order_key = $("#order_key").val();
    data.oid = $("#order_id").val();
    data.order_pids = order_pids;
    data.pay_method = $("#payment_method").find(":selected").val();
    data.del_address = $("#delivery_address").val();
    data.notes = $("#order_notes").val();
    data.del_date = $("#delivery_date").val();
    data.del_time = $("#delivery_time").val();
    data.del_type = $("#del_type").find(":selected").val();

    // data.products = $("#edit_order_form");

    let order_data = [];

    $(".product-qty").each(function() {
      let product_id = $(this).find("input").data("pid");
      let cart_product = {
        'id' : product_id,
        'name' : $("tr.pid-"+product_id).find(".product-title a").text(),
        'qty' : $(this).find("input").val(),
        'price' : $(this).find("input").data("unit-price"),
      };

      order_data.push(cart_product);
    });

    data.order_data = order_data;

    $.ajax({
      type: "POST",
      url: '<?= base_url('/api/orders/save_edit'); ?>',
      data: data,
      dataType: "json",
      success: function(json) {
        console.log(json);

        enjoymintAlert('', json.message, 'success', 1);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
      },
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
      }
    });

    return false;
  });
});
      
</script>

<?php $this->endSection(); ?>