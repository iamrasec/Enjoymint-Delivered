<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo view('templates/__navigation.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card mt-8">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-sm-12 mt-0 text-left">
              <img src="<?= base_url('blogs/images/'.$blog[0]->images[0]->filename); ?>">
              <h1 class="blog-title mt-3"><?= $blog[0]->title; ?></h1>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-sm-12 mt-3">
              <?= $blog[0]->content; ?>
            </div>
          </div>
                    
        </div> 
      </div>
    </div>
  </div>
</div>
<style>
  h1{
    margin-top: -40px;
  }
</style>      
     
<?php $this->endSection() ?>

<?php 
  $session = session();
  // $uguid = ($session->get('guid')) ? $session->get('guid') : '';
  $uid = ($session->get('id')) ? $session->get('id') : 0;
?>


<?php $this->section("script") ?>

</script>
<?php $this->endSection() ?>


