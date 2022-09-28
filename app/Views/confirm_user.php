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
                <div class="card-header">
                  <a href="<?= base_url(); ?>"><img src="<?= base_url('assets/img/logo-oval-300x89.png'); ?>" style="width: 300px;"></a>
                    <?php if(isset($status)): ?>
                      <h4 class="font-weight-bolder mt-5"><?= $status; ?></h4>
                    <?php else: ?>
                    <h4 class="font-weight-bolder mt-5">Account Activation Successful</h4>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                  <p>Please <a class="fw-bold text-danger" href="<?= base_url(); ?>">Click Here</a> to go back to the storefront.</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  </main>

<?php $this->endSection() ?>