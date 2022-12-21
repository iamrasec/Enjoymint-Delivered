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
        <button type="button" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2 form-submit">Save</a>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">
            <form role="form" id="add_experience_form" method="post" action="<?php echo $submit_url; ?>" >
              <div class="row">
                <div class="col-12 col-md-6 col-xs-12">
                  <label class="form-label">Brand Name</label>
                  <div class="input-group input-group-dynamic">
                  <input type="text" class="form-control" name="id" value="<?= $brand_data['id'] ?>" id="id" hidden>
                    <input type="text" class="form-control" name="name" value="<?= $brand_data['name'] ?>" id="name" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xs-12">
                  <div class="row">
                      <label class="form-label">Brand URL</label>
                      <div class="col-4 col-sm-4 pe-0">
                        <p class="text-xs mt-3 float-end px-0"><?php echo base_url(); ?>/brand/</p>
                      </div>
                      <div class="col-8 col-md-8 col-xs-8 mb-3 ps-1">
                        <div class="input-group input-group-dynamic">
                          <input type="text" id="url" class="form-control w-100 border px-2" name="url" required onfocus="focused(this)" onfocusout="defocused(this)" value="<?= $brand_data['url'] ?>">
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- <footer class="footer py-4  ">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start">
            
          </div>
        </div>
        <div class="col-lg-6">
          <ul class="nav nav-footer justify-content-center justify-content-lg-end">
            <li class="nav-item">
              <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer> -->
</main>

<?php $this->endSection(); ?>
<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/edit_experience.js"></script>
<?php $this->endSection() ?>