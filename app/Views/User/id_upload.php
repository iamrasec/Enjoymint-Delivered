<?php $this->extend("templates/base"); ?>


<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>
<style>
  .fc {
    border: 1px solid black; 
    width:auto !important; 
    display: inline !important; 
    text-align:center
  }
</style>
<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-8">
  <section class="pt-3 pb-4" id="popular-products">
  <form id="verify" class="enjoymint-form" enctype="multipart/form-data">
    <div class="container" id="image_lists">
      <div class="row"> 
      <?php if(isset($success)): ?>
        <div class="col-lg-6 col-sm-12  mt-5 text-center">
          <h4>Upload your Valid ID and Picture together with your ID to Verify your Account</h4>
            <div class="card card-plain">
              
                <p style="color: <?= $color ?>;"><?= $success; ?></p>
              
            <div style="display:<?= $upload; ?> ;">
            <label for="exampleFormControlInput1" class="form-label">Select Valid ID(*Driver License):</label>
          <div>
            <input type="file" name="file[]" id="file" accept="image/png, image/jpeg, image/jpg" class="form-control fc" id="exampleFormControlInput1" placeholder="name@example.com">
          </div>
          <label class="form-label">Select Selfie photo with your Valid ID:</label>
          <div>
            <input type="file" name="file[]" id="file" class="form-control fc" accept="image/png, image/jpeg, image/jpg">
          </div>
          <label class="form-label">Select Medical Marijuana Identification Card photo (optional):</label>
          <div>
            <input type="file" name="file[]" id="file" class="form-control fc" accept="image/png, image/jpeg, image/jpg">
          </div>
          <div>
            <br>
            <input type="submit" class="btn btn-primary" value="upload" />  
          </div>
          </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-12 mt-5 text-center"> 
        <h6>Your Uploaded Photo</h6>
          <?php foreach($image_data['images'] as $dt => $val ){ ?>
            <img src="<?= base_url('users/verification/'.$val->filename) ?>" style="height: 200px; border: solid black 2px;"/>
          <?php } ?>
        </div>
       
      <?php else: ?>
        <div class="col-lg-6 col-sm-12 mt-5 " style="display: block; text-align: center">
          <h4>Upload your Valid ID and Picture together with your ID to Verify your Account</h4>
          <label for="exampleFormControlInput1" class="form-label">Select Valid ID(*Driver License):</label>
          <div>
            <input type="file" name="file[]" id="file" accept="image/png, image/jpeg, image/jpg" class="form-control fc" id="exampleFormControlInput1" placeholder="name@example.com">
          </div>
          <label class="form-label">Select Selfie photo with your Valid ID:</label>
          <div>
            <input type="file" name="file[]" id="file" class="form-control fc" accept="image/png, image/jpeg, image/jpg">
          </div>
          <label class="form-label">Select Medical Marijuana Identification Card photo (optional):</label>
          <div>
            <input type="file" name="file[]" id="file" class="form-control fc" accept="image/png, image/jpeg, image/jpg">
          </div>
          <div class="mt-2">
            <input type="submit" class="btn btn-primary" value="upload" />  
          </div>
        </div>
        <div class="col-lg-6 col-sm-12 col-12 mt-5 text-center">
          <div class="row">
          <h6>Sample Captured</h6>
          <div class="col-lg-6 col-sm-6 col-6">
            <img src="/assets/img/verification/id.jpg"/ style=" max-width: 100%; height: auto; border: solid black 2px;">
          </div>
          <div class="col-lg-6 col-sm-6 col-6">
            <img src="/assets/img/verification/selfie.jpg" style=" max-width: 100%; height: auto; border: solid black 2px;"/>
          </div>
          </div>
        </div>
      <?php endif; ?>
    </div>  
    </form>
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
  
$("#verify").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.

    const formData = new FormData();
    const photos = document.querySelectorAll('input[type="file"]');
  
    photos.forEach(function (item, field) {
      formData.append('productImages[]', item.files[0]);

      
    });
   
    fetch('/Users/uploadID',  {
      method: 'POST',
      body: formData,
      headers : {
        'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
      }
    }) .then(response => response.json()).then(response => {
        var { message, success }  = response;
        success ? enjoymintAlert('Nice!', message, 'success', 0, '/users/customerVerification') : enjoymintAlert('Sorry!', message, 'error', 0);
    }).catch((error) => {
        console.log('Error:', error);
    });
  });

  function enjoymintAlert(title, text, icon, is_reload = 0, redirect)
    {
      swal({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: false,
        confirmButtonColor: '#32243d',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok'
      }).then((result) => {
          if(is_reload === 1){
            window.location.reload();
          }
          if(redirect){
            location.reload();
          }

      });
    }
 </script> 
 
<?php $this->endSection() ?>

<?php 
  $session = session();
  // $uguid = ($session->get('guid')) ? $session->get('guid') : '';
  $uid = ($session->get('id')) ? $session->get('id') : 0;
?>

<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- <script>



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

</script> -->
<?php $this->endSection() ?>