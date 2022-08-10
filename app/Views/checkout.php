<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>


<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
  
    <div class="row">
      <div class="col-lg-12 col-sm-12 mt-5 text-center">
      <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->
      <img src="/assets/img/Enjoymint-Logo-Landscape-White-2.png" class="logo" />
      <form method="post" action="<?= base_url('save_data')?>">
        <div class="row">
          <div class="col-md-6 col-sm-6 pt-4 pb-4" id="product">
            <h3 class="cart-title">Your Cart</h3>
            <div class="product-featured">
            <img class="shadow" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="thumbnail" alt="Image description" style="width:130px;margin-right:390px;" /> 
              <div class="product-info pr">
              
                 
               <label> Product Name:
                <input type="text" name="product" value="Minntz Indoor Flowers - Big Apple (3.5g Indica) - Cookies" > 
                </label>
                <label>Price: 
                  <input type="number" name="price" value="33.50">
                  </label>
                <label>Quantity: 
                 <input type="number" name="qty" value="1"> 
                 </label>
                <label>Subtotal 
                <input type="number" name="total" value="100" > 
                </label>
                
              </div>
            </div>  
          </div>
          <div class="col-md-6 col-sm-6 pt-4 pb-4">
          <h3>Personal Information</h3>
            <div class="product-featured pf">
              <label>Full Name:
             <input type="text" name="full_name" value="" style="width: 500px;">
              </label>
              <label>Address:
             <input type="text" name="address" value="" style="width: 500px;">
              </label>
              <label> Contact Number:
             <input type="number" name="c_number" value="" style="width: 500px;">
              </label>
        
            </div>
            <button class="btn bg-gradient-primary mb-0 mt-lg-auto w-100" type="submit" id="out" style="margin-top:-50px;">Proceed to CheckOut</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</section>







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

<?php $this->endSection() ?>