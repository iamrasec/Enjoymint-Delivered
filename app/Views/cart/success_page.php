<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

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
          <h1 class="pagetitle">Order Summary</h1>

          <p>Success Page</p>
        </div>
      </div>
      
    </div>
  </div>
</main>

<?php $this->endSection(); ?>

<?php $this->section("script"); ?>
<script>
  
</script>
<?php $this->endSection(); ?>