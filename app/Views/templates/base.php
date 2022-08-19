<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="atoken" content="<?php echo $user_jwt; ?>">
<meta name="robots" content="<?= getenv('CRAWL_META'); ?>">

<link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
<link rel="icon" type="image/png" href="./assets/img/favicon.png">

<title>Enjoymint Delivered</title>

<!--     Fonts and icons     -->
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

<!-- Nucleo Icons -->
<link href="<?php echo base_url(); ?>/assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/assets/css/nucleo-svg.css" rel="stylesheet" />

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- CSS Files -->
<link id="pagestyle" href="<?php echo base_url(); ?>/assets/css/material-kit.css?v=3.0.0" rel="stylesheet" />
<link id="pagestyle" href="<?php echo base_url(); ?>/assets/css/styles.css" rel="stylesheet" />
</head>

<?php if(isset($page_body_id)): ?>
<body class="<?php echo ($page_body_id == 'home') ? $page_body_id : $page_body_id . ' inner-page'; ?> bg-gray-200" <?php echo ( isset($page_body_id)) ? 'id="'.$page_body_id.'"' : ''; ?>>
<?php else: ?>
  <body class="inner-page bg-gray-200">
<?php endif; ?>

<?php echo $this->renderSection("content"); ?>

<footer class="footer pt-5 mt-5">
  <div class="container">
    <div class=" row">
      <div class="col-md-3 mb-4 ms-auto">
        <div>
          <img src="/assets/img/Enjoymint-Logo-Landscape-White-2.png" class="logo" />
        </div>
        <div>
          <ul class="d-flex flex-row ms-n3 nav">
            <li class="nav-item">
              <a class="nav-link pe-1" href="#" target="_blank">
                <i class="fab fa-facebook text-lg opacity-8"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link pe-1" href="#" target="_blank">
                <i class="fab fa-twitter text-lg opacity-8"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link pe-1" href="#" target="_blank">
                <i class="fab fa-dribbble text-lg opacity-8"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link pe-1" href="#" target="_blank">
                <i class="fab fa-github text-lg opacity-8"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link pe-1" href="#" target="_blank">
                <i class="fab fa-youtube text-lg opacity-8"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="col-md-2 col-sm-6 col-6 mb-4"></div>
      <div class="col-md-2 col-sm-6 col-6 mb-4"></div>
      <div class="col-md-2 col-sm-6 col-6 mb-4"></div>
      <div class="col-md-2 col-sm-6 col-6 mb-4"></div>

      <div class="col-12">
        <div class="text-center">
          <p class="my-4 text-sm font-weight-normal">
            All rights reserved. Copyright © <script>document.write(new Date().getFullYear())</script> Enjoymint Delivered</a>.
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php echo $this->include('templates/__age_check.php'); ?>

<!--   Core JS Files   -->
<script src="<?php echo base_url(); ?>/assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/assets/js/plugins/perfect-scrollbar.min.js"></script>

<!--  Plugin for TypedJS, full documentation here: https://github.com/inorganik/CountUp.js -->
<script src="<?php echo base_url(); ?>/assets/js/plugins/countup.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/plugins/choices.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/plugins/prism.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/plugins/highlight.min.js"></script>

<!--  Plugin for Parallax, full documentation here: https://github.com/dixonandmoe/rellax -->
<script src="<?php echo base_url(); ?>/assets/js/plugins/rellax.min.js"></script>
<!--  Plugin for TiltJS, full documentation here: https://gijsroge.github.io/tilt.js/ -->
<script src="<?php echo base_url(); ?>/assets/js/plugins/tilt.min.js"></script>
<!--  Plugin for Selectpicker - ChoicesJS, full documentation here: https://github.com/jshjohnson/Choices -->
<script src="<?php echo base_url(); ?>/assets/js/plugins/choices.min.js"></script>

<!--  Plugin for Parallax, full documentation here: https://github.com/wagerfield/parallax  -->
<script src="<?php echo base_url(); ?>/assets/js/plugins/parallax.min.js"></script>

<!-- Control Center for Material UI Kit: parallax effects, scripts for the example pages etc -->
<!--  Google Maps Plugin    -->

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
<script src="<?php echo base_url(); ?>/assets/js/material-kit.min.js?v=3.0.0" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/assets/js/age-check.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/assets/js/main.js" type="text/javascript"></script>

<?php echo $this->renderSection("scripts"); ?>

<script type="text/javascript">

  if (document.getElementById('state1')) {
    const countUp = new CountUp('state1', document.getElementById("state1").getAttribute("countTo"));
    if (!countUp.error) {
      countUp.start();
    } else {
      console.error(countUp.error);
    }
  }
  if (document.getElementById('state2')) {
    const countUp1 = new CountUp('state2', document.getElementById("state2").getAttribute("countTo"));
    if (!countUp1.error) {
      countUp1.start();
    } else {
      console.error(countUp1.error);
    }
  }
  if (document.getElementById('state3')) {
    const countUp2 = new CountUp('state3', document.getElementById("state3").getAttribute("countTo"));
    if (!countUp2.error) {
      countUp2.start();
    } else {
      console.error(countUp2.error);
    };
  }

  jQuery(document).on('click', '.login-btn', function() {
    console.log("clicked");
  });
</script>

</body>

</html>