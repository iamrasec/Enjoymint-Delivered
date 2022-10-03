<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-8">
  <section class="pt-3 pb-4" id="popular-products">
  <form method="post" action="<?= base_url('upload')?>" enctype="multipart/form-data">
    <div class="container">
      <div class="row"> 
        <div class="col-lg-6 col-sm-2 mt-5 text-center">
            <h4>Upload your Valid ID and Picture together with your ID to Verify your Account</h4>
            <label>Please Select 2 Files</label>
            <input type="file" name="file[]" class="form-control" id="file" multiple style="margin-left: 220px ;" class="form-control">
          <input type="submit" class="btn btn-primary" value="upload" /> 
          
          </div>
          <div class="col-lg-6 col-sm-2 mt-5 text-center">
          <h6>Sample Captured</h6>
            <img src="/assets/img/verification/id.jpg"/>
            <img src="/assets/img/verification/selfie.jpg" style="height: 200px; border: solid black 2px;"/>
          </div>
          
        </div>
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
const selected = document.querySelector(".selected");
const optionsContainer = document.querySelector(".options-container");

const optionsList = document.querySelectorAll(".option");

selected.addEventListener("click", () => {
  optionsContainer.classList.toggle("active");
});

optionsList.forEach(o => {
  o.addEventListener("click", () => {
    selected.innerHTML = o.querySelector("label").innerHTML;
    optionsContainer.classList.remove("active");
  });
});

 </script> 
 
<?php $this->endSection() ?>

<?php 
  $session = session();
  // $uguid = ($session->get('guid')) ? $session->get('guid') : '';
  $uid = ($session->get('id')) ? $session->get('id') : 0;
?>

<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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