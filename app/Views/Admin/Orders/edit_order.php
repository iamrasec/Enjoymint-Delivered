<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__dash_navigation.php'); ?>

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
        <a class="btn save-btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="#">Save</a>
      </div>
    </div>
    
    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">

            <div class="row">
              <div class="col-lg-12">
                <h3><?= $order_data->first_name ?> <?= $order_data->first_name ?></h3>
                <div>Order Key: <strong><?= $order_data->order_key; ?></strong></div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <pre><?php print_r($order_data); ?></pre>
    <pre><?php print_r($order_products); ?></pre>

  </div>
</main>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>

<script>
var jwt = $("[name='atoken']").attr('content');

$(document).ready(function () {
    
});
</script>

<?php $this->endSection(); ?>