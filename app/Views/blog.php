<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-6">

<section class="pt-3 pb-4" id="popular-products">
  <div class="container">
  <div class="col-lg-12 col-sm-12 mt-5 text-center">
    <h1>All Blogs</h1>
    </div>
    <div class="row">
    <?php foreach($blogs as $blg): ?>
          <div class="col-md-6 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
            <div class="img-wrap">
                <?php if(isset($blg->url)): ?>
                <img class="prod_image" src="<?=  base_url('blogs/') .$blg->url; ?>" alt="No image" style="width:290px;" />
                <?php else: ?>
                  
                <img class="prod_image" src=""/>
                <?php endif; ?>
                <!-- <img class="shadow" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg"
               itemprop="thumbnail" alt="Image description" style="width:290px;" />   -->
              </div>   
            </div>
          </div>  
          
          <div class="col-md-6 col-sm-6 pt-4 pb-4">
            <div class="product-featured">
            <div class="img-wrap">
                <h3 id="p1"><?= $blg->title; ?></h3>
                <p id="p2"><?= $blg->description; ?></p>
                <p id="p3">Author: <?= $blg->author; ?></p>
                <b class="news_feed_text">
                <a href="news/" class="anchor" target="_blank">Read More</i></a>
                </b>
              </div>
            </div>
          </div> 
          <?php endforeach; ?>     
      </div>

    </div>
  </div>
</section>

<!-- -------   END PRE-FOOTER 2 - simple social line w/ title & 3 buttons    -------- -->

</div>
<style>
  #p2{
    margin-top: 40px;
    font-size: 18px;
  }
  #p3{
    margin-top: 40px;
    font-size: 16px;
  }
</style>

 <script type="text/javascript">
  let btnCounter = document.querySelector('#btn_pr');
  let counter = 0;

  btnCounter.addEventListener('click', function(){
    counter++;
    document.querySelector('.count_cart').innerHTML = counter;
  });
  // var count = (function () {
  //   var counter = 0;
  //   return function () {return counter +=;}
  // })();

  // function display(){
  //   document.getElementById('count_cart').innerHTML = count();
  // };

 </script>  

<?php $this->endSection() ?>

