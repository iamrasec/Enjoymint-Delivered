<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("styles") ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  .breaker {
    margin-top: 10px;
  }
  .promo_products_select_products .select2-container, 
  .promo_products_select_cat .select2-container,
  .product_required_purchase .select2-container,
  .category_required_purchase .select2-container {
    display: block !important;
    width: 100% !important;
  }
  .xdsoft_datetimepicker .xdsoft_datepicker {
    width: 600px !important;
  }
</style>
<?php $this->endSection() ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <form id="edit_promo" class="enjoymint-form" enctype="multipart/form-data">
      <div class="row">
        <div class="col-lg-6">
          <h4><?php echo $page_title; ?></h4>
        </div>
        <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
          <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-12 col-xs-12 mt-lg-0 mt-4">
          <div class="card">
            <div class="card-body">
              <h5 class="font-weight-bolder">Promo Information</h5>
              <?php foreach($promo_data as $promo): ?>
                <input type="hidden" value="<?= $promo->id; ?>" name="id" id="id">
              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Title</label>
                  <div class="input-group input-group-dynamic">
                  <input type="text" id="title" class="form-control w-100 border px-2" value="<?= $promo->title ; ?>" name="title" required>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-md-12 col-xs-12">
                  <div class="row">
                    <label class="form-label">Promo URL</label>
                    <div class="col-2 col-sm-2 pe-0">
                        <p class="text-xs mt-3 float-end px-0"><?php echo base_url(); ?>/promotion/</p>
                    </div>
                    <div class="col-10 col-md-10 col-xs-8 mb-3 ps-1">
                      <div class="input-group input-group-dynamic">
                        <input type="text" id="promo_url" class="form-control w-100 border px-2" value="<?= $promo->url ; ?>" name="promo_url" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Description</label>
                  <div class="input-group input-group-dynamic">
                    <div id="quill_editor" class="w-100"></div>
                    <input type="hidden" id="quill_content" value="<?= $promo->description ; ?>" name="description">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-4 col-md-4 col-xs-6 mb-3">
                  <label class="form-label" for="name">Promo Type</label>
                  <div class="input-group input-group-dynamic">
                    <select name="promo_type" id="promo_type" value="<?= $promo->promo_type ; ?>" class="form-control w-100 border px-2">
                      <option value="percent_off">Percentage Off (%)</option>
                      <option value="fixed">Fixed Deduction</option>
                      <option value="sale_price">Sale Price</option>
                      <option value="bxgx">BxGx</option>
                      <option value="bundle">Bundle</option>
                      <option value="special">Special</option>
                    </select>
                  </div>
                </div>
                <div class="col-2 col-md-2 col-xs-6 mb-3">
                  <label class="form-label" for="name">Discount Value / Sale Price</label>
                  <div class="input-group input-group-dynamic">
                    <input type="number" id="discount_val" name="discount_val" value="<?= $promo->discount_value ; ?>" placeholder="0.00" min="0.00" value="0.00" step="0.01">
                  </div>
                </div>
                <div class="col-6 col-md-6 col-xs-12 mb-3">
                  <label class="form-label" for="name">Image</label>
                  <div class="input-group input-group-dynamic">
                    <input type="file" id="blog_image" name="blog_image" accept="image/png, image/jpeg, image/jpg">
                  </div>
                </div>
              </div>
                 <div class="row mt-4">
                  <div class="col-md-12 col-xs-12">
                    <div class="row">
                    <div class="col-md-3 col-xs-3">
                        <label class="form-label w-100">Prome Code</label>
                        <div class="input-group input-group-dynamic">
                        <input type="number" id="promo_code" name="promo_code" value="<?= $promo->promo_code ; ?>" class="form-control w-100 border px-2" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <label class="form-label w-100">Usage Limit</label>
                        <div class="input-group input-group-dynamic">
                        <input type="number" id="usage_limit" name="usage_limit" value="<?= $promo->usage_limit ; ?>" class="form-control w-100 border px-2" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <label class="form-label w-100">Start Date</label>
                        <div class="input-group input-group-dynamic">
                        <input type="text" id="sale_start_date" name="sale_start_date" value="<?= $promo->start_date ; ?>" class="form-control datetime_picker w-100 border px-2" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <label class="form-label w-100">End Date</label>
                        <div class="input-group input-group-dynamic">
                          <input type="text" id="sale_end_date" name="sale_end_date" value="<?= $promo->end_date ; ?>" class="form-control datetime_picker w-100 border px-2" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <div class="input-group input-group-dynamic">
                    <input type="checkbox" id="show_products" name="show_products" value="1"> &nbsp; Show Products in Promotion Page
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Maximum Number of Products Discounted per Purchase <small class="d-block fw-normal fs-xs fst-italic ms-2">(Leaving this at 0 will not impose any limitation on the Maximum Number of Products Discounted per Purchase)</small></label>
                  <div class="input-group input-group-dynamic">
                    <input type="number" id="max_prod_discounted" value="<?= $promo->max_prod_discounted ; ?>" name="max_prod_discounted" placeholder="0" min="0" value="0" step="1">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <h5>Promo Conditions</h5>

                  <input type="hidden" id="promo-con-cntr" value="1">

                  <div class="card" data-promo-condition="1">
                    <div class="card-body">
                      <!-- START: Condition #1 -->
                      <strong>Primary Condition</strong>
                      <div class="border p-3" id="primary-condition">
                        <div class="row mt-2">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <label class="form-label fw-bold" for="name">Promo Products</label>
                            <div class="d-flex">
                              <div class="form-check me-4">
                              <?php if($promo->mechanics['promo_products'] != "") : ?>
                                <input class="form-check-input promo_products" value="<?= $promo['mechanics']->promo_products ; ?>" type="radio" name="promo_products1" id="promo_products_all" value="promo_products_all" data-promo-cond-id="1" checked>
                                <label class="form-check-label" for="promo_products_all">All Products</label>
                              </div>
                              <?php endif; ?>
                              <div class="form-check me-4">
                                <input class="form-check-input promo_products" type="radio" name="promo_products1" id="promo_products_specific" value="promo_products_specific" data-promo-cond-id="1">
                                <label class="form-check-label" for="promo_products_specific">Specific Product(s)</label>
                                </div>
                              <div class="form-check me-4">
                                <input class="form-check-input promo_products" type="radio" name="promo_products1" id="promo_products_cat" value="promo_products_cat" data-promo-cond-id="1">
                                <label class="form-check-label" for="promo_products_cat">Products in Category</label>
                              </div>
                            </div>

                            <div class="row mt-4 promo_products_select_products d-none">
                              <div class="col-12 col-md-12 col-xs-12 mb-3">
                              <label class="form-label" for="promo_products_selected">Select Product(s) Included in the Promo</label>
                              <select class="select2-field promo_products_select_products_select" id="promo_products_select_products_select" name="all_products[]" multiple="multiple">
                               
                              </select>
                              </div>
                            </div>

                            <div class="row mt-4 promo_products_select_cat d-none">
                              <div class="col-12 col-md-12 col-xs-12 mb-3">
                              <label class="form-label" for="promo_products_selected">Select Category Included in the Promo</label>
                              <select class="select2-field promo_products_select_cat_select" id="promo_products_select_cat_select" name="all_categories[]" multiple="multiple">
                                
                              </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <div class="input-group input-group-dynamic">
                              <input type="checkbox" id="cond_req_purchase" class="cond_req_purchase" name="cond_req_purchase" data-promo-cond-id="1"> &nbsp; <strong>Require Purchase</strong>
                              <small class="d-block fs-xs fst-italic ms-2">(The required product will be purchased at REGULAR PRICE)</small>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4 if_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3 d-flex">
                            <div class="form-check me-4">
                              <input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type1" id="req_purchase_type_any" value="req_purchase_type_any" data-promo-cond-id="1" checked>
                              <label class="form-check-label" for="req_purchase_type_any">Any Product(s)</label>
                            </div>
                            <div class="form-check me-4">
                              <input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type1" id="req_purchase_type_product" value="req_purchase_type_product" data-promo-cond-id="1">
                              <label class="form-check-label" for="req_purchase_type_product">Specific Product(s)</label>
                            </div>
                            <div class="form-check me-4">
                              <input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type1" id="req_purchase_type_category" value="req_purchase_type_category" data-promo-cond-id="1">
                              <label class="form-check-label" for="req_purchase_type_category">Products in Category</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4 product_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                          <label class="form-label fw-bold" for="required_product">Required Purchase: Select Product(s)</label>
                          <select class="select2-field required_product_select" id="required_product_select" name="all_products[]" multiple="multiple">
                            
                          </select>
                          </div>
                        </div>

                        <div class="row mt-4 category_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                          <label class="form-label fw-bold" for="required_product">Required Purchase: Select Category</label>
                          <select class="select2-field required_category_select" id="required_category_select" name="all_categories[]" multiple="multiple">
                            
                          </select>
                          </div>
                        </div>

                        <div class="row mt-4 if_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <label class="form-label fw-bold" for="required_product">Required Quantity Purchased <small class="d-block fw-normal fs-xs fst-italic ms-2">(Minimum quantity is 1)</small></label>
                            <div class="input-group input-group-dynamic">
                              <input type="number" class="req_purchase_qty" id="req_purchase_qty" name="req_purchase_qty" min="1" value="1" step="1">
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4 if_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <label class="form-label fw-bold" for="name">Number of Products Discounted for this condition</label>
                            <ul>
                              <li><small class="d-block fw-normal fs-xs fst-italic ms-2">This will override the "Maximum Number of Products Discounted per Purchase"</small></li>
                              <li><small class="d-block fw-normal fs-xs fst-italic ms-2">Multiple quantities of the same product also counts towards this condition.</small></li>
                              <li><small class="d-block fw-normal fs-xs fst-italic ms-2">Leaving this at 0 will not impose any limitation on the Number of Products Discounted.</small></li>
                            </ul>
                            <div class="input-group input-group-dynamic">
                              <input type="number" class="num_prod_discounted_cond" id="num_prod_discounted_cond" name="num_prod_discounted_cond" placeholder="0" min="0" value="0" step="1">
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- END: Condition #1 -->
                      <div class="additional-conditions-wrap"></div>

                      <div class="row mt-3 mb-5">
                        <div class="col-lg-12">
                          <button class="btn add-condition-btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Add Another Condition</button>
                        </div>
                      </div>

                    </div>
                  </div>

                </div>
              </div>
                <?php endforeach; ?>
              <!-- <pre></pre> -->

              <div class="row mt-3 mb-5">
                <div class="col-lg-12 text-right d-flex flex-column justify-content-end">
                  <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</main>

<?php $this->endSection(); ?>
<?php $this->section("scripts") ?>
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/edit_promo.js"></script>
<script>
  var toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
      ['blockquote', 'code-block'],

      [{ 'header': 1 }, { 'header': 2 }],               // custom button values
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
      [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
      [{ 'direction': 'rtl' }],                         // text direction

      [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      [ 'link', 'image', 'video', 'formula' ],          // add's image support
      [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
      [{ 'font': [] }],
      [{ 'align': [] }],

      ['clean']                                         // remove formatting button
  ];

  var quill = new Quill('#quill_editor', {
    modules: {
      'toolbar': toolbarOptions
    },
    theme: 'snow'
  });

  quill.on('text-change', function(delta, oldDelta, source) {
    document.getElementById("quill_content").value = quill.root.innerHTML;
  });

  

  // console.log(prodArr);

  
  
</script>
<?php $this->endSection() ?>
<style>
.bootstrap-tagsinput {
  background-color: #fff;
  border: 1px solid #ccc;
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  display: inline-block;
  padding: 4px 6px;
  color: #555;
  vertical-align: middle;
  border-radius: 4px;
  max-width: 100%;
  line-height: 22px;
  cursor: text;
}
.bootstrap-tagsinput input {
  border: none;
  box-shadow: none;
  outline: none;
  background-color: transparent;
  padding: 0 6px;
  margin: 0;
  width: auto;
  max-width: inherit;
}
.bootstrap-tagsinput.form-control input::-moz-placeholder {
  color: #777;
  opacity: 1;
}
.bootstrap-tagsinput.form-control input:-ms-input-placeholder {
  color: #777;
}
.bootstrap-tagsinput.form-control input::-webkit-input-placeholder {
  color: #777;
}
.bootstrap-tagsinput input:focus {
  border: none;
  box-shadow: none;
}
.bootstrap-tagsinput .tag {
  margin-right: 2px;
  color:black;
}
.bootstrap-tagsinput .tag [data-role="remove"] {
  margin-left: 8px;
  cursor: pointer;
}
.bootstrap-tagsinput .tag [data-role="remove"]:after {
  content: "x";
  padding: 0px 2px;
}
.bootstrap-tagsinput .tag [data-role="remove"]:hover {
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}
.bootstrap-tagsinput .tag [data-role="remove"]:hover:active {
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}



</style>