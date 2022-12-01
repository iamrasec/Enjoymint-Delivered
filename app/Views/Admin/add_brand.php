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
      <div class="col-lg-6 text-right d-flex flex-column justify-content-center"></div>
    </div>

    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">
            <form role="form" method="post" action="<?php echo $submit_url; ?>">
              <div class="row mt-4">
                <div class="col-12 col-sm-6">
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control w-100" aria-describedby="emailHelp" onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">URL</label>
                    <input type="text" name="url" class="form-control w-100" onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
              </div>
            <div class="row">
              <div class="col-12 col-md-2 col-sm-12 text-center">
                <button type="submit" class="btn btn-lg bg-gradient-primary w-100 mt-4 mb-0">Save</button>
              </div>
              <div class="col-12 col-md-2 col-sm-12 text-left pt-4" style="display:flex; align-items:center;">
                <a href="<?php echo base_url('/admin/strains'); ?>">Cancel</a>
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

<?php $this->section('scripts'); ?>
<script src="<?php echo base_url(); ?>/assets/js/plugins/datatables.js"></script>
<?php $this->endSection(); ?>