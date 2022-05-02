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
      <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
        <button type="button" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-lg-8 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">
            <h5 class="font-weight-bolder">Product Information</h5>
            <form></form>
            <div class="row mt-4">
              <div class="col-12 col-sm-12">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Name</label>
                  <input type="text" class="form-control w-100" name="name" aria-describedby="name" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
              </div>
              <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Weight</label>
                  <input type="email" class="form-control w-100" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-3">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Collection</label>
                  <input type="email" class="form-control w-100" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
              </div>
              <div class="col-3">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Price</label>
                  <input type="email" class="form-control w-100" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
              </div>
              <div class="col-3">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Quantity</label>
                  <input type="email" class="form-control w-100" onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label class="mt-4">Description</label>
                <p class="form-text text-muted text-xs ms-1 d-inline">
                  (optional)
                </p>
                <div id="edit-deschiption-edit" class="h-50">
                  
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
</main>

<?php $this->endSection(); ?>