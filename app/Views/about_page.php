<?php $this->extend("templates/base"); ?>

<?php $this->section("styles") ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,1,-25" />
<link id="pagestyle" href="<?php echo base_url('assets/css/about.css'); ?>" rel="stylesheet" />
<?php $this->endSection() ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div id="about-header" class="page-header min-vh-100" loading="lazy">
  <span class="mask bg-gradient-dark opacity-5"></span>
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-7 d-flex justify-content-center flex-column">
        <h1 class="text-white mb-4">What Makes us Different?</h1>
        <p class="text-white">The extensive choices and information when walking into a Cannabis dispensary can be very overwhelming especially to a new patient. Numerous of people are yet to understand the differences between various Cannabis strains, their effects and benefits. However, Enjoymint Delivered believes that every patient is entitled to an amazing experience when shifting to plant-based medicine, and offers in-depth reviews of each product to help you better understand them before consumption. Rest assured, the products delivered to you are only the best quality - created and stored in the cleanest environment with correct handling and storage. Call us now for free consultation.</p>
        <div class="buttons">
          <a href="<?= base_url('shop'); ?>" class="btn btn-white mt-4">View Scheduled Delivery Products</a> | 
          <a href="<?= base_url('shop/fast_tracked'); ?>" class="btn btn-white mt-4">View Fast-tracked Delivery Products</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="intro" class="container text-center p-6">
  <div class="row">
    <div class="col-12 col-md-12">
      <h3 class="mb-5">BEST CANNABIS DELIVERY SERVICE IN THE BAY</h3>
      <p>Getting your hands on medical Cannabis supply in the Bay are has never been easier. It is so accessible that you can have your goods legally delivered right to your 
        doorstep. If for some reason you could not make it to your local Cannabis dispensary, a few clicks on your phone or computers get you access to high-quality weed 
        delivery. Enjoymint Delivered is the best local Cannabis delivery service in the Bay area that gets you your greens in no time! Expect same-day delivery and high 
        standards of service, offering a wide variety of concentrates, high CBD products and edibles of only the finest quality</p>
    </div>
</div>
</div>

<div id= "vision-section" class="container-fluid text-white p-6">
  <div class="row text-center">
    <div class="col-6 col-md-6 col-xs-12">
      <h3 class="text-white mb-3">OUR VISION</h3>
      <p>It is Enjoymint Delivered's vision that every medical Cannabis patient has access to top-quality medicine with convenience and discretion through high-standard weed delivery. Since the medical Cannabis industry is fairly new, the members aim to make sure that the patients are well-informed and has a clear understanding of all the different strains and their significant health benefits before consumption. It is Enjoymint Delivered's vision to remove the stigma with Cannabis and provide excellent and honest health care through professional Cannabis delivery.</p>
    </div>
    <div class="col-6 col-md-6 col-xs-12">
      <h3 class="text-white mb-3">WHO WE ARE</h3>
      <p>Enjoymint Delivered, formerly known as Mint Xpress is a trusted weed delivery service made of a group of individuals that are passionate medical Cannabis patients themselves. These advocates have been helping connect other patients to professional medical Cannabis providers since 2012, making sure that other patients only get the best quality products - lab-tested for potency, pesticides, fungicides, mildew, and mold. Enjoymint Delivered also provides in-depth reviews for all the different strains, making sure that only the right products are given out to each patient for consumption.</p>
    </div>
  </div>
</div>

<div id="testimonials" class="container-fluid text-center p-6">
  <div class="row mb-5">
    <div class="col-12 col-md-12 col-xs-12">
      <h3>WHAT PATIENTS SAY ABOUT US</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-4 col-md-4 col-xs-12 testimonial-1 px-4">
      <div class="testimonial-name fs-4">Kiki B.</div>
      <div class="stars mb-4">
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
      </div>
      
      <p>TJ makes great suggestions. vaping with the palm is the best. Neptune OG was beyond belief at controlling pain. Discreet and effective. Good salesperson. Glad I tried it.</p>
    </div>

    <div class="col-4 col-md-4 col-xs-12 testimonial-1 px-4">
      <div class="testimonial-name fs-4">Mahalo Magpie.</div>
      <div class="stars mb-4">
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
      </div>
      
      <p>The best delivery of any club I've tried. Short wait times, correct orders, nice gifts, great prices, cool selection and perfect experience every time I have ordered! The best customer service, with helpful people who take time to help with grrrrreat selections!</p>
    </div>

    <div class="col-4 col-md-4 col-xs-12 testimonial-1 px-4">
      <div class="testimonial-name fs-4">Ogcalabaza94ers</div>
      <div class="stars mb-4">
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
        <span class="star material-symbols-outlined">star</span>
      </div>
      
      <p>They showed up at the elks lodge with more than enough joints for the old timers. Kid on the phone got me a great deal thanks a million enjoymint.</p>
    </div>
  </div>
</div>

<?php echo $this->include('sections/got_questions.php'); ?>

<?php $this->endSection() ?>