<?php $this->extend("templates/base"); ?>

<?php $this->section("styles") ?>
<link id="pagestyle" href="<?php echo base_url('assets/css/faqs.css'); ?>" rel="stylesheet" />
<?php $this->endSection() ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">
  <section class="pt-3 pb-4" id="faqs-header">
    <div id="intro" class="container p-6">
      <div class="row">
        <div class="col-12 col-md-12">
          <h3 class="page-title mb-5 text-center"><?= strtoupper($page_title); ?></h3>

          <?php echo $this->include('terms_content.php'); ?>
          
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->endSection() ?>

<?php $this->section("scripts") ?>

<?php $this->endSection() ?>