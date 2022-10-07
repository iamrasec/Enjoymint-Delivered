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
    <form id="edit_user" class="enjoymint-form" enctype="multipart/form-data" >
    <?php foreach ($user_data as $user): ?>  
      <input type="hidden" value="<?= $user->id; ?>" name="id" id="id">
      <div class="row">
        <div class="col-lg-6">
          <h4><?php echo $page_title; ?></h4>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-6 col-xs-12 mt-lg-0 mt-4" style="margin-left: 250px;">
          <div class="card">
            <div class="card-body">
              <h5 class="font-weight-bolder">User Information</h5>

              <div class="row mt-4">
                <div class="col-6 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">First Name</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="first_name" name="first_name" value="<?= $user->first_name; ?>" class="form-control">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-6 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Last Name</label>
                  <div class="input-group input-group-dynamic">
                  <input type="text" id="last_name" name="last_name" value="<?= $user->last_name; ?>" class="form-control">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-6 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Email</label>
                  <div class="input-group input-group-dynamic">
                  <input type="email" id="email" name="email" value="<?= $user->email; ?>" class="form-control">
                  </div>
                </div>
              </div>
              
              <div class="row mt-4">
                <div class="col-6 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Select Role</label>
                  <div class="input-group input-group-dynamic">
                  <select name="role" id="role" class="form-control" value="<?= $user->role; ?>">
                        <option value="0">Select</option>
                        <option id="role" name="role" value="1">Admin</option>
                        <option id="role" name="role" value="3">Costumer</option>
                  </select>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-6 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Password</label>
                  <div class="input-group input-group-dynamic">
                  <input type="password" id="password" name="password"  value="<?= $user->password; ?>" class="form-control">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-6 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Confirm Password</label>
                  <div class="input-group input-group-dynamic">
                  <input type="password" id="password_confirm" name="password_confirm" value="<?= $user->password; ?>" class="form-control">
                  </div>
                </div>
              </div>

              <div class="row mt-3 mb-5">
                <div class="col-lg-12 text-right d-flex flex-column justify-content-end">
                  <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <?php endforeach ;?>
    </form>
  </div>
</main>

<?php $this->endSection(); ?>
<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/edit_user.js"></script>
<script>
  var toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
      ['blockquote', 'code-block'],

      [{ 'header': 1 }, { 'header': 2 }],               // custom button values
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
      [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
      [{ 'direction': 'rtl' }],                         // text direction
        
      [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      [ 'link', 'image', 'video', 'formula' ],          // add's image support
      [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
      [{ 'font': [] }],
      [{ 'align': [] }],

      ['clean']                                         // remove formatting button
  ];

  var quill = new Quill('#quill_editor', {
    modules: {
      'toolbar': toolbarOptions
    },
    theme: 'snow'
  });

  quill.on('text-change', function(delta, oldDelta, source) {
    document.getElementById("quill_content").value = quill.root.innerHTML;
  });
</script>
<?php $this->endSection() ?>