<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="atoken" content="<?php echo $user_jwt; ?>">
  <meta name="robots" content="<?= getenv('CRAWL_META'); ?>">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>/assets/img/favicon.png">
  <title>
    Enjoymint Delivered | Admin Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="<?php echo base_url(); ?>/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Select2 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
  <link id="pagestyle" href="<?php echo base_url(); ?>/assets/css/material-dashboard.css?v=3.0.1" rel="stylesheet" />
  <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <link id="pagestyle" href="<?php echo base_url(); ?>/assets/css/styles.css" rel="stylesheet" />

  <!-- initialize uri service -->
  <?php $uri = service('uri'); ?>

  <!-- load datatable css on specific pages -->
  <?php if($uri->getSegment(2) == 'products' or $uri->getSegment(2) == 'sale'): ?>
    <link href="<?= base_url('assets/css/jquery.dataTables.min.css') ?>" rel="stylesheet" />
  <?php endif; ?>

  <?php echo $this->renderSection("styles"); ?>
  <link href="<?php echo base_url(); ?>/assets/css/bootstrap-tagsinput.css" rel="stylesheet" type="text/css">

  
</head>

<body class="g-sidenav-show  bg-gray-200" <?php echo ( isset($page_body_id)) ? 'id="'.$page_body_id.'"' : ''; ?>>
  
  <?php echo $this->renderSection("content"); ?>

  <!--   Core JS Files   -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="<?php echo base_url(); ?>/assets/js/core/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>/assets/js/core/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?php echo base_url(); ?>/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="<?php echo base_url(); ?>/../assets/js/plugins/choices.min.js"></script>
  <script src="<?php echo base_url(); ?>/../assets/js/plugins/dropzone.min.js"></script>
  <script src="<?php echo base_url(); ?>/../assets/js/plugins/quill.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="<?php echo base_url(); ?>/../assets/js/dashboard.js"></script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo base_url(); ?>/assets/js/material-dashboard.min.js?v=3.0.1"></script>

  <script>
    function enjoymintAlert(title, text, icon, is_reload = 0, redirect)
    {
      swal({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: false,
        confirmButtonColor: '#32243d',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok'
      }).then((result) => {
          if(is_reload === 1){
            window.location.reload();
          }
          if(redirect){
            window.location.href = redirect;
          }

      });
    }

  </script>

  <?php echo $this->renderSection("scripts"); ?>

</body>

</html>