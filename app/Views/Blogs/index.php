<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
  <div class="col-lg-12 col-sm-12 mt-5 text-center">
  <h1>Blogs</h1>
  </div>
    <div class="row">
      <!--<span class="badge bg-primary mb-3">Get them while they're hot</span>-->     
        <?php foreach($blogs as $blog): ?>
          <div class="col-md-3 col-sm-12 pt-4 pb-4">
            <?php if(isset($blog->images[0])): ?>
            <div class="product-featured">
              <div class="img-wrap">
                <a href="<?= base_url('blogs/'.$blog->url); ?>">
                  <img src="<?= base_url('blogs/images/'.$blog->images[0]->filename); ?>" alt="No image" style="width:290px;" />
                </a>
              </div>   
            </div>
            <?php endif; ?>
          </div>            
          <div class="col-md-9 col-sm-12 pt-4 pb-4">
            <div class="product-featured">
            <div class="img-wrap">
                <a href="<?= base_url('blogs/'.$blog->url); ?>"><h5><?= $blog->title; ?></h5></a>
                <p id="p2"><?= $blog->description; ?></p>
                <p id="p3"><?= $blog->author; ?></p>
                <b class="news_feed_text">
                <a href="news/" class="anchor"><a href="<?= base_url('blogs/'.$blog->url); ?>">Read More</a></i></a>
                </b>
              </div>
            </div>
          </div> 
          <?php endforeach; ?>     
      </div>
    </div>
  </div>
</section>



<!-- -------   START PRE-FOOTER 2 - simple social line w/ title & 3 buttons    -------- -->
<div class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-5 ms-auto">
        <h4 class="mb-1">Thank you for your support!</h4>
        <p class="lead mb-0">We deliver only the best products</p>
      </div>
      <div class="col-lg-5 me-lg-auto my-lg-auto text-lg-end mt-5">
        <a href="#" class="btn btn-twitter mb-0 me-2" target="_blank">
          <i class="fab fa-twitter me-1"></i> Tweet
        </a>
        <a href="#" class="btn btn-facebook mb-0 me-2" target="_blank">
          <i class="fab fa-facebook-square me-1"></i> Share
        </a>
        <a href="#" class="btn btn-pinterest mb-0 me-2" target="_blank">
          <i class="fab fa-pinterest me-1"></i> Pin it
        </a>
      </div>
    </div>
  </div>
</div>
<!-- -------   END PRE-FOOTER 2 - simple social line w/ title & 3 buttons    -------- -->

</div>

 <script type="text/javascript">
  // let btnCounter = document.querySelector('#btn_pr');
  // let counter = 0;

  // btnCounter.addEventListener('click', function(){
  //   counter++;
  //   document.querySelector('.count_cart').innerHTML = counter;
  // });
  // var count = (function () {
  //   var counter = 0;
  //   return function () {return counter +=;}
  // })();

  // function display(){
  //   document.getElementById('count_cart').innerHTML = count();
  // };

 </script>  
<?php $this->endSection() ?>
