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
          
          <div class="row">
            <div class="col-12 col-md-12 col-xs-12 text-justify">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vitae malesuada lectus. Cras tempus tincidunt mi auctor venenatis. Nullam in gravida mi, a semper orci. Aliquam erat volutpat. Nam urna turpis, ultricies sit amet turpis id, venenatis molestie elit. Proin at nibh vitae felis blandit bibendum ac quis elit. In porttitor nibh leo, ac convallis dui tristique porttitor. Maecenas placerat non quam ut ornare.</p>
              
              <p>Nunc in magna metus. In nisi lorem, vulputate non placerat id, dapibus sit amet quam. Nunc quis consectetur libero, vel sagittis lacus. Phasellus venenatis ex at nunc vestibulum pretium. Curabitur nec viverra urna, vitae euismod felis. Mauris elementum euismod justo nec efficitur. Morbi eget feugiat arcu. Etiam ut vehicula augue, a pellentesque nisl. Curabitur facilisis dignissim euismod. Etiam malesuada risus ante, nec sagittis erat faucibus at. Cras sed odio eu nisi varius posuere quis ut odio. Aenean arcu metus, sollicitudin non suscipit vitae, maximus at quam. In gravida erat nec sapien tempor facilisis. Sed hendrerit felis vitae nunc pellentesque, eget hendrerit ante efficitur. Aenean vitae tortor vestibulum, blandit libero vel, volutpat quam.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->endSection() ?>

<?php $this->section("scripts") ?>

<?php $this->endSection() ?>