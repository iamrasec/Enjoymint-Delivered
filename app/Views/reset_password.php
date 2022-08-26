<?php $this->extend("templates/base_no_footer"); ?>

<?php $this->section("content") ?>

<?php // echo $this->include('templates/__navigation.php'); ?>

<main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('<?= base_url('assets/img/age-check/cannabis.jpg'); ?>'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header text-center">
                <a href="<?= base_url(); ?>"><img src="<?= base_url('assets/img/logo-oval-300x89.png'); ?>" style="width: 300px;"></a>
                  <h4 class="font-weight-bolder mt-5">Reset Password</h4>
                  <p class="mb-0">Enter your new password</p>
                </div>
                <?php 
                if(isset($validation)): ?>
                <div class="alert alert-danger">
                  <?= $validation->listErrors()?>
                </div>
                <?php endif; ?>

                <?php if(session()->getTempdata('error')):?>
                  <div class='alert alert-danger'><?= session()->getTempdata('error');?></div>
                  <?php endif; ?>

                  <?php if(session()->getTempdata('success')):?>
                  <div class='alert alert-success'><?= session()->getTempdata('success');?></div>
                  <?php endif; ?>

                <div class="card-body">
                  <form role="form" method="post" action="/users/updatePassword">
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">New Password</label>
                      <input type="password" name="new_password" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Confirm New Password</label>
                      <input type="password" name="confirm_password" class="form-control">
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Update</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php $this->endSection() ?>