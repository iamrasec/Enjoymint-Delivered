<div class="card my-3">
  <div class="card-header"><h6>Personal Information</h6></div>
  <div class="card-body">
    <?php if(isset($validation)): ?>
    <div class="alert alert-danger">
      <?= $validation->listErrors()?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error') !== NULL) : ?>
      <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
          <?php echo session()->getFlashdata('error'); ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message') !== NULL) : ?>
      <div class="alert alert-success text-white alert-dismissible fade show" role="alert">
          <?php echo session()->getFlashdata('message'); ?>
      </div>
    <?php endif; ?>

    <form id="personal_info_form" method="POST" action="<?= base_url('users/update_personal_info'); ?>">
      <div class="row">
        <div class="col-12 col-md-6 col-xs-12">
          <label class="form-label" for="name">First Name</label>
          <div class="input-group input-group-dynamic">
            <input type="text" class="form-control w-100 border px-2" id="first_name" value="<?= $user_data['first_name']; ?>" readonly>
          </div>
        </div>
        <div class="col-12 col-md-6 col-xs-12">
          <label class="form-label" for="name">Last Name</label>
          <div class="input-group input-group-dynamic">
            <input type="text" class="form-control w-100 border px-2" id="first_name" value="<?= $user_data['last_name']; ?>" readonly>
          </div>
        </div>
      </div>
      <div class="row mt-3 mb-4">
        <div class="col-12 col-md-6 col-xs-12">
          <label class="form-label" for="name">Email</label>
          <div class="input-group input-group-dynamic">
            <input type="text" class="form-control w-100 border px-2" name="email" id="email" value="<?= $user_data['email']; ?>">
          </div>
        </div>
        <div class="col-12 col-md-6 col-xs-12">
          <label class="form-label" for="name">Mobile Phone</label>
          <div class="input-group input-group-dynamic">
            <input type="text" class="form-control w-100 border px-2" name="mobile_phone" id="mobile_phone" value="<?= $user_data['mobile_phone']; ?>">
          </div>
        </div>
      </div>
      <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Update</button>
    </form>
  </div>
</div>