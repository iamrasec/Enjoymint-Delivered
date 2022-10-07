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
    </div>
    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsives">
              <table id="verification-table" class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Photo</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tfoot>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Photo</th>
                    <th>Action</th>
                </tfoot>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= base_url('assets/js/plugins/jquery.dataTables.min.js') ?>"></script>

<!-- Product List page js -->
<script>
  $(document).ready(function () {
      $('#verification-table').DataTable({
        // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?= base_url('admin/verification_email/getVerificationEmail'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }]

      });

      //verification_approve
        $("body").delegate(".approve", "click", function(){
      var verifyId = $(this).data('id');
      console.log(verifyId);
      fetch('/admin/verification_email/verification_approve/'+verifyId,  {
        method: 'POST',
        headers : {
          'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
        }
      }) .then(response => response.json()).then(response => {
          var { message, success }  = response;
          success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/verification_email') : enjoymintAlert('Sorry!', message, 'error', 0);
      }).catch((error) => {
          console.log('Error:', error);
      });
    });

    //verification_deny
    $("body").delegate(".deny", "click", function(){
      var verifyId = $(this).data('id');
      console.log(verifyId);
      fetch('/admin/verification_email/verification_deny/'+verifyId,  {
        method: 'POST',
        headers : {
          'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
        }
      }) .then(response => response.json()).then(response => {
          var { message, success }  = response;
          success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/verification_email') : enjoymintAlert('Sorry!', message, 'error', 0);
      }).catch((error) => {
          console.log('Error:', error);
      });
    });
   

 
    
  });
  
</script>
<?php $this->endSection(); ?>