<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>
<style>
  .breaker {
    margin-top: 10px;
  }
</style>
<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <form id="add_product" class="enjoymint-form" enctype="multipart/form-data">
      <div class="row">
        <div class="col-lg-6">
          <h4><?php echo $page_title; ?></h4>
        </div>
        <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
          <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-8 col-xs-12 mt-lg-0 mt-4">
          <div class="card">
            <div class="card-body">
              <h5 class="font-weight-bolder">Product Information</h5>
              <pre><?php print_r($product_data); ?></pre>
              <div class="row mt-4">
                <div class="col-8 col-md-8 col-xs-12 mb-3">
                  <label class="form-label" for="name">Name</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="product_name" class="form-control w-100 border px-2" name="name" value="<?= $product_data->name; ?>" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">SKU</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" class="form-control w-100 border px-2" id="sku" name="sku" value="<?= $product_data->sku; ?>" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-md-8 col-xs-12">
                  <div class="row">
                    <label class="form-label">Product URL</label>
                    <div class="col-4 col-sm-4 pe-0">
                      <p class="text-xs mt-3 float-end px-0"><?php echo base_url(); ?>/product/</p>
                    </div>
                    <div class="col-8 col-md-8 col-xs-8 mb-3 ps-1">
                      <div class="input-group input-group-dynamic">
                        <input type="text" id="purl" class="form-control w-100 border px-2" name="purl" value="<?= $product_data->url; ?>" required onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-xs-12">
                  <div class="row">
                    <div class="col-md-12 col-xs-12">
                      <label class="form-label">Stock Quantity</label>
                      <div class="input-group input-group-dynamic">
                        <input type="number" min="0" value="0" class="form-control w-100 border px-2" id="qty" name="qty" value="<?= $product_data->stocks; ?>" required onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Category</label>
                  <div class="input-group input-group-dynamic">
                  <select class="product-category form-control w-100 border px-2" name="category[]" id="category" multiple onfocus="focused(this)" onfocusout="defocused(this)">
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                    <?php endforeach; ?>
                  </select>
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Price</label>
                  <div class="input-group input-group-dynamic">
                    <input type="number" class="form-control w-100 border px-2" id="price" name="price" value="<?= $product_data->price; ?>" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-8 col-md-8 col-xs-12 mb-3"></div>
                <div class="col-sm-12">
                  <label class="mt-4">Description</label>
                  <p class="form-text text-muted text-xs ms-1 d-inline">
                    (optional)
                  </p>
                  <div id="edit-description-edit" class="h-50">
                    <textarea class="w-100" id="description" name="description"><?= $product_data->description; ?></textarea>
                  </div>
                </div>
                
              </div>
              <div class="row mt-4">
                <div class="col-md-6 col-xs-12">
                  <label class="form-label w-100">Strain <button id="new_strain" class="text-xs float-end btn btn-modal bg-gradient-success btn-block mb-3" data-bs-toggle="modal" data-bs-target="#newStrainModal">Add New Strain</button></label>
                  <div class="input-group input-group-dynamic">
                    <select name="strain" id="select_strain" class="form-control">
                      <option value="0">None</option>
                      <?php foreach($strains as $strain): ?>
                      <option value="<?php echo $strain->id; ?>"><?php echo $strain->name; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 col-xs-12">
                  <label class="form-label w-100">Brand <button id="new_brand" class="text-xs float-end btn btn-modal bg-gradient-success btn-block mb-3" data-bs-toggle="modal" data-bs-target="#newBrandModal">Add New Brand</button></label>
                  <div class="input-group input-group-dynamic">
                    <select name="brand" id="select_brand" class="form-control">
                      <option value="0">None</option>
                      <?php foreach($brands as $brand): ?>
                      <option value="<?php echo $brand->id; ?>"><?php echo $brand->name; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <h6>Compounds</h6>
                <div class="col-md-6 col-xs-12">
                  <label class="form-label w-100">THC</label>
                  <div class="row">
                    <div class="col-md-6 col-xs-6">
                      <div class="input-group input-group-dynamic">
                        <input type="text" name="thc_val" id="thc_val" class="form-control w-100 border px-2" required  onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="input-group input-group-dynamic">
                        <select name="thc_measure" class="form-control">
                          <option value="pct">Percent (%)</option>
                          <option value="mg">Milligrams (mg)</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xs-12">
                  <label class="form-label w-100">CBD</label>
                  <div class="row">
                    <div class="col-md-6 col-xs-6">
                      <div class="input-group input-group-dynamic">
                        <input type="text" name="cbd_val" id="cbd_val" class="form-control w-100 border px-2" required onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="input-group input-group-dynamic">
                        <select name="cbd_measure" class="form-control">
                          <option value="pct">Percent (%)</option>
                          <option value="mg">Milligrams (mg)</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-xs-12 mt-lg-4 mt-4">
          <h6>Variants</h6>                
          <div class="row" id='variants'>
            <div class="row">
              <div class="col-lg-10">
                <div class="row">
                  <div class="col-lg-6">
                    <label>Unit</label>
                    <input type="text" name="unit[]" class="form-control">
                  </div>
                  <div class="col-lg-6">
                    <label>Unit Value</label>
                    <input type="number" name="value[]" class="form-control">
                  </div>
                  <div class="col-lg-6">
                    <label>Price</label>
                    <input type="number" name="price[]" class="form-control">
                  </div>
                  <div class="col-lg-6">
                    <label>Stocks/Qty</label>
                    <input type="number" name="qty[]" class="form-control">
                  </div>
                </div>
              </div>
              <div class="col-lg-2">
                <br><br>
                <button type="button" class="btn bg-gradient-danger btn-sm remove_variant"><span class="material-icons">delete</span></button>
              </div>
            </div><hr class='breaker'>
          </div><br>
          <button type="button" class="btn bg-gradient-success btn-sm" id='add_variant'><span class="material-icons">add</span></button>

          <br><br><br>
          <h6>Images</h6>
          <div class="row" id='image_lists'>
            <div class="row">
              <div class="col-lg-10">
                <input type="file" name="images[]" accept="image/png, image/jpeg, image/jpg" class="form-control">
              </div>
              <div class="col-lg-2">
                <button type="button" class="btn bg-gradient-danger btn-sm remove_image"><span class="material-icons">delete</span></button>
              </div>
            </div>
          </div>
          <button type="button" class="btn bg-gradient-success btn-sm" id='add_image'><span class="material-icons">add</span></button>
        </div>
      </div>
    </form>
  </div>
</main>

<!-- New Strain Modal -->
<div class="modal fade" id="newStrainModal" tabindex="-1" role="dialog" aria-labelledby="newStrainModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title font-weight-normal" id="newStrainModalLabel">Add New Strain</h6>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="input-group input-group-outline my-3">
              <label class="form-label">Strain Name</label>
              <input type="text" class="form-control" name="new_strain_name" value="" id="new_strain_name">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="cancel-btn btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="add-new-strain save-btn btn bg-gradient-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- New Brand Modal -->
<div class="modal fade" id="newBrandModal" tabindex="-1" role="dialog" aria-labelledby="newBrandModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title font-weight-normal" id="exampleModalLabel">Add New Brand</h6>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="input-group input-group-outline my-3">
              <label class="form-label">Brand Name</label>
              <input type="text" class="form-control" name="new_brand_name" value="" id="new_brand_name">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="cancel-btn btn bg-gradient-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="add-new-brand save-btn btn bg-gradient-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<?php $this->endSection(); ?>
<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/add_product.js"></script>
<?php $this->endSection() ?>