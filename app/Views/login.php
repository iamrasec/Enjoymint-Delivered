<?php $this->extend("templates/base_no_footer"); ?>

<?php $this->section("content") ?>

<?php // echo $this->include('templates/__navigation.php'); ?>


<main class="main-content mt-0 ps login-page">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('<?= base_url('assets/img/age-check/cannabis.jpg'); ?>'); background-size: cover;"></div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header text-center">
                  <a href="<?= base_url(); ?>"><img src="<?= base_url('assets/img/logo-oval-300x89.png'); ?>" style="width: 300px;"></a>
                  <h4 class="font-weight-bolder mt-5">Sign In</h4>
                  <p class="mb-0">Enter your email and password to sign in</p>
                </div>
                <div class="card-body mt-2">
                  <?php if (session()->getFlashdata('message') !== NULL) : ?>
                    <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                        <?php echo session()->getFlashdata('message'); ?>
                    </div>
                  <?php endif; ?>
                  <form role="form" method="post" action="/users">
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" name="password" class="form-control">
                    </div>
                    <!-- <div class="form-check form-switch d-flex align-items-center mb-3">
                      <input class="form-check-input" type="checkbox" id="rememberMe">
                      <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                    </div> -->
                    <div class="">
                      <a href="<?=base_url()?>/Users/forgot_password">Forgot Password?</a>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="login-btn btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Don't have an account?
                    <a href="<?php echo base_url(); ?>/users/register" class="text-primary text-gradient font-weight-bold">Sign up</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></main>

<?php $this->endSection() ?>