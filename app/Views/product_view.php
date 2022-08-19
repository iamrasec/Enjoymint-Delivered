<?php $this->extend("templates/base"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__navigation.php'); ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="mb-4">Product Details</h5>
              <div class="row">
                <div class="col-xl-5 col-lg-6 text-center">
                  <img class="w-100 border-radius-lg shadow-lg mx-auto" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" alt="chair">
                  <div class="my-gallery d-flex mt-4 pt-2" itemscope itemtype="http://schema.org/ImageGallery">
                    <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                      <a href="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="contentUrl" data-size="500x600">
                        <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" alt="Image description" />
                      </a>
                    </figure>
                    <figure class="ms-3" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                      <a href="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="contentUrl" data-size="500x600">
                        <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="thumbnail" alt="Image description" />
                      </a>
                    </figure>
                    <figure class="ms-3" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                      <a href="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="contentUrl" data-size="500x600">
                        <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="thumbnail" alt="Image description" />
                      </a>
                    </figure>
                    <figure class="ms-3" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                      <a href="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="contentUrl" data-size="500x600">
                        <img class="w-100 min-height-100 max-height-100 border-radius-lg shadow" src="/assets/img/products/5621dd21-4801-41e7-bf08-df382aa81e79.jpeg" itemprop="thumbnail" alt="Image description" />
                      </a>
                    </figure>
                  </div>
                  <!-- Root element of PhotoSwipe. Must have class pswp. -->
                  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                    <!-- Background of PhotoSwipe.
It's a separate element, as animating opacity is faster than rgba(). -->
                    <div class="pswp__bg"></div>
                    <!-- Slides wrapper with overflow:hidden. -->
                    <div class="pswp__scroll-wrap">
                      <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
                      <!-- don't modify these 3 pswp__item elements, data is added later on. -->
                      <div class="pswp__container">
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                      </div>
                      <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                      <div class="pswp__ui pswp__ui--hidden">
                        <div class="pswp__top-bar">
                          <!--  Controls are self-explanatory. Order can be changed. -->
                          <div class="pswp__counter"></div>
                          <button class="btn btn-white btn-sm pswp__button pswp__button--close">Close (Esc)</button>
                          <button class="btn btn-white btn-sm pswp__button pswp__button--fs">Fullscreen</button>
                          <button class="btn btn-white btn-sm pswp__button pswp__button--arrow--left">Prev
                          </button>
                          <button class="btn btn-white btn-sm pswp__button pswp__button--arrow--right">Next
                          </button>
                          <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                          <!-- element will get class pswp__preloader--active when preloader is running -->
                          <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                              <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                          <div class="pswp__share-tooltip"></div>
                        </div>
                        <div class="pswp__caption">
                          <div class="pswp__caption__center"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-5 mx-auto">
                  <h3 class="mt-lg-0 mt-4">Minntz Indoor Flowers - Big Apple (3.5g Indica) - Cookies</h3>
                  <div class="rating">
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">grade</i>
                    <i class="material-icons text-lg">star_outline</i>
                  </div>
                  <i class="material-icons opacity-6 me-2 text-md">visibility</i>
                 <?= $ip_views ?><br>
                 <?php foreach($stock as $st) : ?>
                <p style="display: inline-block;">Stocks:</p>
                 <?= $st ?>
                 <?php endforeach; ?>
                  <h6 class="mb-0 mt-3">Price</h6>
                  <h5>$<span class="price">33.50</span></h5>
                  <span class="badge badge-success">In Stock</span>
                  <br>
                  <label class="mt-4">Description</label>
                  <p>Minntz - Big Apple</p>
                  <p>Flower | Cookies</p>

                  <p>20.037%~21.401% THC</p>

                  <p>Apple Fritter x Sherb bx1</p>

                  <p>Minntz was born from the partnership of legendary brands seed junkies and cookies. Seed junkies' strains are derived from a lineage of exceptional breeders who are now producing craft flowers; their commitment to craft and quality made them a natural fit for a partnership with cookies.</p>
                  <div class="row mt-4">
                    <div class="col-lg-2">
                      <label class="ms-0">Quantity</label>
                      <select class="form-control" name="choices-quantity" id="choices-quantity">
                        <option value="Choice 1" selected="">1</option>
                        <option value="Choice 2">2</option>
                        <option value="Choice 3">3</option>
                        <option value="Choice 4">4</option>
                        <option value="Choice 5">5</option>
                        <option value="Choice 6">6</option>
                        <option value="Choice 7">7</option>
                        <option value="Choice 8">8</option>
                        <option value="Choice 9">9</option>
                        <option value="Choice 10">10</option>
                      </select>
                    </div>
                  </div>
                  <div class="row mt-4">
                    <div class="col-lg-5">
                      <button class="btn bg-gradient-primary mb-0 mt-lg-auto w-100" type="button" name="button">Add to cart</button>
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