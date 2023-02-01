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
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/promotion/add_promo'); ?>">Create Promo</a>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsives text-xs">
              <table id="promo-table" class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Promo Title</th>
                    <th>Promo Type</th>
                    <th>Description</th>
                    <th>Number of Products</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<!-- Load Data Table JS -->
<script src="<?= base_url('assets/js/plugins/jquery.dataTables.min.js') ?>"></script>

<!-- Product List page js -->
<script>
  // $(document).ready(function () {
  //     $('#promo-table').DataTable({
  //       // Processing indicator
  //       "processing": true,
  //       // DataTables server-side processing mode
  //       "serverSide": true,
  //       // Initial no order.
  //       "order": [],
  //       // Load data from an Ajax source
  //       "ajax": {
  //           "url": "<?= base_url('admin/promotion/getPromoList'); ?>",
  //           "type": "POST"
  //       },
  //       //Set column definition initialisation properties
  //       "columnDefs": [{ 
  //           "targets": [0],
  //           "orderable": false
  //       }]
  //   });
  // });
</script>
<?php $this->endSection(); ?>