<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="card mt-8">
            <div class="card-body">
              <h5 class="mb-4">Product Details</h5>
              <div class="row">

                <div class="col-xl-5 col-lg-6 text-center">
                  <?php if($images): ?>
                  <img class="w-100 border-radius-lg shadow-lg mx-auto" src="<?= base_url('products/images/'.$images[0]->filename); ?>" alt="">
                  <?php endif; ?>

                  <div class="my-gallery d-flex mt-4 pt-2" itemscope itemtype="http://schema.org/ImageGallery">
                    <?php if($images): ?>
                    <?php foreach($images as $image): ?>
                      <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                        <a href="<?= base_url('products/images/'.$image->filename); ?>" itemprop="contentUrl" data-size="500x600">
                          <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow" src="<?= base_url('products/images/'.$image->filename); ?>" alt="" />
                        </a>
                    </figure>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="col-lg-5 mx-auto">
                  <h3 class="mt-lg-0 mt-4"><?= $product->name; ?></h3>
                  <div class="text-sm mb-3"><span class="badge text-bg-warning me-3"><?= $product->strain_name; ?></span><span class="badge text-bg-dark ms-3">THC <?= $product->thc_value; ?><?= ($product->thc_unit == 'pct') ? '%' : $product->thc_unit;?></span></div>
                  <div class="rating">
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">star_outline</i>
                  </div>
                  
                  <div class="row mb-5">
                    <div class="col-12 col-sm-12">
                      <h6 class="mb-0 mt-3">Price</h6>
                      <h5>$<span class="price d-inline"><?= $product->price; ?></span></h5>
                      <span class="badge text-bg-success">In Stock</span>
                    </div>
                  </div>

                  <div class="row mb-5">
                    <div class="col-12 col-sm-12">
                    <h6 class="mb-2">Description</h6>
                    <?= $product->description; ?>
                    </div>
                  </div>
                  
                  <div class="row mt-4">
                    <div class="col-lg-2">
                      <label class="ms-0">Quantity</label>
                      <input type="number" min="1" max="100" value="1" name="qty">
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-lg-5">
                      <button class="btn add-to-cart bg-gradient-primary mb-0 mt-lg-auto w-100" type="button" name="add-to-cart" data-pid="<?= $product->id; ?>">Add to cart</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-5">
                <h6>Ratings</h5>
                <div class="row">
                  <div class="col-sm-6">
                    <form role="form" method="post" action="/users">
                      <div class="input-group input-group-outline mb-3">
                        <?php for($y=5;$y>0;$y--): ?>
                          <i class="material-icons text-lg">star_outline</i>
                          <?php endfor; ?>
                      </div>
                      <div class="input-group input-group-outline mb-3">
                        <label class="form-label">Comment</label>
                        <input type="text" name="message" class="form-control">
                      </div>
                      <button type="submit" class="login-btn btn btn-lg bg-gradient-primary btn-sm mt-4 mb-0">Submit</button>
                    </form>
                  </div>
                </div>
                <hr/>
                <div class="row">
                  <div class="col-sm-3">
                    <div class="card">
                      <div class="card-body text-center" style="background-color: #f0f2f5 !important;">
                        <div class="rating-block">
                          <h6>Average user rating</h6>
                          <h2 class="bold padding-bottom-7">4.3 <small>/ 5</small></h2>
                          <div class="rating">
                            <i class="material-icons text-lg">grade</i>
                            <i class="material-icons text-lg">grade</i>
                            <i class="material-icons text-lg">grade</i>
                            <i class="material-icons text-lg">grade</i>
                            <i class="material-icons text-lg">star_outline</i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-5">
                    <div class="card">
                      <div class="card-body">
                        <?php for($x=5;$x>0;$x--): ?>
                        <div class="row">
                          <div class="col-md-4 text-start">
                            <?php for($y=$x;$y>0;$y--): ?>
                              <i class="material-icons text-lg">grade</i>
                            <?php endfor; ?>
                            <?php for($y=5-$x;$y>0;$y--): ?>
                              <i class="material-icons text-lg">star_outline</i>
                            <?php endfor; ?>
                          </div>
                          <div class="col-md-6 text-start">
                            <div class="progress" style="margin-top: 10px;">
                              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 100%">
                              <span class="sr-only">80% Complete (danger)</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 text-start">1</div>
                        </div>
                        <?php endfor; ?>
                      </div>
                    </div>
                  </div>			
                </div>
                <div class="row">
                  <div class="col-sm-7">
                    <hr/>
                    <div class="review-block">
                      <div class="row">
                        <div class="col-sm-3">
                          <img src="http://dummyimage.com/60x60/666/ffffff&text=No+Image" class="img-rounded">
                          <div class="review-block-name"><a href="#">Unknown</a></div>
                          <div class="review-block-date">10 Aug 2022<br/><small><i>1 day ago</i></small></div>
                        </div>
                        <div class="col-sm-9">
                          <div class="review-block-rate">
                            <?php for($y=5;$y>0;$y--): ?>
                                <i class="material-icons text-lg">grade</i>
                              <?php endfor; ?>
                          </div>
                          <div class="review-block-description">Nice product.</div>
                        </div>
                      </div>
                      <hr/>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="row mt-5">
                <div class="col-12">
                  <h5 class="ms-3">Other Products</h5>
                  <div class="table table-responsive">
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     
<?php $this->endSection() ?>
<style>
.rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

/* Modified from: https://github.com/mukulkant/Star-rating-using-pure-css */
  .card-header {
    background-color: #f0f2f5 !important;
  }
.btn-grey{
    background-color:#D8D8D8;
	color:#FFF;
}
.rating-block{
	background-color:#FAFAFA;
	border:1px solid #EFEFEF;
	padding:15px 15px 20px 15px;
	border-radius:3px;
}
.bold{
	font-weight:700;
}
.padding-bottom-7{
	padding-bottom:7px;
}

.review-block{
	background-color:#FAFAFA;
	border:1px solid #EFEFEF;
	padding:15px;
	border-radius:3px;
	margin-bottom:15px;
}
.review-block-name{
	font-size:12px;
	margin:10px 0;
}
.review-block-date{
	font-size:12px;
}
.review-block-rate{
	font-size:13px;
	margin-bottom:15px;
}
.review-block-title{
	font-size:15px;
	font-weight:700;
	margin-bottom:10px;
}
.review-block-description{
	font-size:13px;
}
</style>