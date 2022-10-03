<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-4 col-sm-12 mt-5">
      <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->
        <h1 class="page-title"><?= $page_title; ?></h1>
      </div>
      <div class="col-12 col-md-8 col-sm-12 mt-md-5 mt-xs-1">
          <ul class="d-flex flex-row">
            <li><a href="#" class="px-2 py-2 border">Orders</a></li>
            <li><a href="#" class="px-2 py-2 border">Personal Info</a></li>
            <li><a href="#" class="px-2 py-2 border">Address</a></li>
            <li><a href="<?= base_url('users/customerverification'); ?>" class="px-2 py-2 border">Verification Center</a></li>
            <li><a href="<?= base_url('users/logout'); ?>" class="px-2 py-2 border">Log Out</a></li>
          </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-12 col-xs-12">
        <?php echo view('customer_dashboard/'. $active_tab); ?>
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