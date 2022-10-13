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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form id="verification_submit" class="enjoymint-form" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Deny Information</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
        <input id="verification_id" type="hidden" ><br>
        <label>Denial Message to user:</label><br>
        <textarea class="form-control" cols="60" rows="2" id="denial_msg" name="denial_message"></textarea>
    
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="valid_id" name="image_validID">
        <label class="form-check-label" for="check1">Valid ID</label>
      </div>
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="profile" name="image_profile">
        <label class="form-check-label" for="check2">Profile</label>
      </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
      </form>
    </div>
  </div>
</div>
</main>

<!-- Modal -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Image preview</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img src="" id="imagepreview" style="max-width: 400px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="open-in-tab btn bg-gradient-primary">Open Image in New Tab</button>
      </div>
    </div>
  </div>
</div>

<style>
  .id_verification_image {
    cursor: pointer;
  }
</style>

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

      // Show image modal
      $(document).on('click', '.id_verification_image', function() {
        $('#imagepreview').attr('src', $(this).attr('src'));
        $('#imagemodal').modal('show');
      });

      $(document).on('click', '.open-in-tab', function() {
        window.open($('#imagepreview').attr('src'), '_blank');
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
      
        // show modal
        $("#exampleModal").modal('show');
        $("#verification_id").val(verifyId);
    });
   
    $("#verification_submit").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        const fd = new FormData();

        fd.append('verification_id',  $('#verification_id').val());
        fd.append('denial_message',  $('#denial_msg').val());
        if($('#profile').is(':checked')){
          fd.append('image_profile',  'checked');
        }
        if($('#valid_id').is(':checked')){
          fd.append('image_validID',  'checked');
        }
        fetch('/admin/verification_email/verification_deny',  {
          method: 'POST',
          body: fd,
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