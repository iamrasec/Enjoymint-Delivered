<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>
<style>
  .breaker {
    margin-top: 10px;
  }
</style>
<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <form id="add_blog" class="enjoymint-form" enctype="multipart/form-data">
      <div class="row">
        <div class="col-lg-6">
          <h4><?php echo $page_title; ?></h4>
        </div>
        <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
          <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-8 col-xs-12 mt-lg-0 mt-4">
          <div class="card">
            <div class="card-body">
              <h5 class="font-weight-bolder">Product Informationsssss</h5>
              <div class="row mt-4">
                <div class="col-8 col-md-8 col-xs-12 mb-3">
                  <label class="form-label" for="name">Title</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="title" class="form-control w-100 border px-2" name="title" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Description</label>
                  <div class="input-group input-group-dynamic">
                   <textarea id="description" class="form-control w-100 border px-2" name="description" required onfocus="focused(this)" onfocusout="defocused(this)" ></textarea>
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-md-4 col-xs-12">
                  <div class="row">
                    <div class="col-md-12 col-xs-12">
                      <label class="form-label">Author</label>
                      <div class="input-group input-group-dynamic">
                        <input type="text" class="form-control w-100 border px-2" id="author" name="author" required onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-xs-12 mt-lg-4 mt-4">
          
          <br><br><br>
        <div class="card" style="margin-top: -100px;">
         <div class="card-body">
          <h6>Images</h6>
          <div class="row" id='image_lists'>       
            <div class="row">
              <div class="col-lg-10">
                <input type="file" name="images[]" accept="image/png, image/jpeg, image/jpg" class="form-control">
              </div>
              <div class="col-lg-2">
                <button type="button" class="btn bg-gradient-danger btn-sm remove_image"><span class="material-icons">delete</span></button>
              </div>
            </div>
          </div>
          <button type="button" class="btn bg-gradient-success btn-sm" id='add_image'><span class="material-icons">add</span></button>
        </div>
      </div>
      </div>
      </div>
    </form>
  </div>
</main>

<?php $this->endSection(); ?>
<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/add_blogs.js"></script>
<?php $this->endSection() ?>