<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo view('templates/__navigation.php'); ?>

    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="card mt-8">
            <div class="card-body">
            <div class="col-lg-12 col-sm-12 mt-5 text-center">
              <h1>Blog Details</h1>
            </div>
                <form  role="form" method="post" action="/blogs">
                 <div class="row"> 
                  <?php foreach($blogs as $blg):?> 
                    <div class="col-md-6 col-sm-6 pt-4 pb-4">
                    <div class="product-featured">
                    <div class="img-wrap">
                      <img class="prod_image" src="<?= base_url($blg->url); ?>" alt="No image" style="width:290px;" />
                  </div>   
                </div>
                </div>
                <div class="col-md-6 col-sm-6 pt-4 pb-4">
                  <div class="product-featured">
                  <div class="img-wrap">
                    <h3><?= $blg->title; ?></h3>                                   
                    <div class="row mb-5">
                      <div class="col-12 col-sm-12">
                      <?= $blg->content; ?>
                      </div>
                   </div>
                   <div class="row mb-5">
                      <div class="col-12 col-sm-12">
                        <h7><span class="price d-inline"><?= $blg->author; ?></span></h7>
                      </div>
                    </div>   
                </div>
                   </div>   
                </div>
                <?php endforeach; ?>     
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


