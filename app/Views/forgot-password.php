<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>


<main class="main-content mt-0 ps">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../../../assets/img/illustrations/illustration-signin.jpg'); background-size: cover;"></div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header text-center">
                  <h4 class="font-weight-bolder">Forgot Password</h4>
                  <p class="mb-0">Enter your email to reset password</p>
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
                  
                <div class="card-body mt-2">
                  <form role="form" method="post" action="/users/forgot_password">
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control">
                    </div>
                    <div class="text-center">
                      <button type="submit" class="login-btn btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></main>

<?php $this->endSection() ?>