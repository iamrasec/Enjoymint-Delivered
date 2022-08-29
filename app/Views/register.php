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
            <?php if(isset($success)): ?>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <a href="<?= base_url(); ?>"><img src="<?= base_url('assets/img/logo-oval-300x89.png'); ?>" style="width: 300px;"></a>
                    <h4 class="font-weight-bolder mt-5">Sign Up</h4>
                </div>
                <div class="card-body">
                  <p><?= $success; ?></p>
                </div>
              </div>
            </div>
            <?php else: ?>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                <a href="<?= base_url(); ?>"><img src="<?= base_url('assets/img/logo-oval-300x89.png'); ?>" style="width: 300px;"></a>
                  <h4 class="font-weight-bolder mt-5">Sign Up</h4>
                  <p class="mb-0">Enter your name, email and password to register</p>
                </div>
                <?php if(isset($validation)): ?>
                <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                  <?= $validation->listErrors(); ?>
                </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('email_sent') !== NULL) : ?>
                <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                    <?php echo session()->getFlashdata('email_sent'); ?>
                </div>
                <?php endif; ?>
                <div class="card-body">
                  <form role="form" method="post" action="<?= base_url('/users/register'); ?>">
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">First Name</label>
                      <input type="text" name="first_name" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Last Name</label>
                      <input type="text" name="last_name" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" name="password" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Confirm Password</label>
                      <input type="password" name="password_confirm" class="form-control">
                    </div>
                    <div class="form-check form-check-info text-left">
                      <input class="form-check-input" type="checkbox" value="1" name="accept_terms" id="flexCheckDefault" checked>
                      <label class="form-check-label" for="flexCheckDefault">
                        I agree the <a href="javascript:;" class="text-dark font-weight-bolder" data-bs-toggle="modal" data-bs-target="#modal-notification">Terms and Conditions</a>
                      </label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Sign Up</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Already have an account?
                    <a href="<?= base_url('users') ?>" class="text-primary text-gradient font-weight-bold">Sign in</a>
                  </p>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
	  <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h6 class="modal-title font-weight-normal" id="modal-title-notification">Terms and Conditions</h6>
			<button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="py-3 text-center">
			  <h4 class="text-gradient text-danger mt-4">EnjoymintDelivered Terms and Conditions</h4>
			  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla molestie laoreet mauris, vitae sodales elit luctus quis. Suspendisse quis nunc lectus. Vestibulum feugiat malesuada nibh, et elementum lorem tincidunt sed. Quisque tincidunt sagittis velit, at pulvinar dolor. Donec placerat nec dolor in varius. Sed vitae orci aliquam, interdum felis a, auctor ex. Quisque eget lorem sem. Nullam volutpat iaculis dolor ut cursus. Donec ultrices eu nisi at condimentum. Proin non sollicitudin turpis, id cursus est. Aenean posuere lacinia tempus. Suspendisse potenti. Cras ut orci augue. Pellentesque ut finibus ante. Aenean ac nisl mi. Vivamus vehicula sapien at lectus cursus, vel aliquam lorem mattis.</p>
			</div>
		  </div>
		  <div class="modal-footer">
			<!-- <button type="button" class="btn btn-white">Ok, Got it</button> -->
			<button type="button" class="btn btn-link ml-auto" data-bs-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>

<?php $this->endSection() ?>