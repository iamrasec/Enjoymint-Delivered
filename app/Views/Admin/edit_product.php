<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>
<style>
  .breaker {
    margin-top: 10px;
  }
  .xdsoft_datetimepicker .xdsoft_datepicker {
    width: 600px !important;
  }
</style>
<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <form id="edit_product" class="enjoymint-form" enctype="multipart/form-data">
      <input type="hidden" value="<?= $product_data->id; ?>" name="pid" id="pid">
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
                        <input type="number" min="0" value="<?= $product_data->stocks; ?>" class="form-control w-100 border px-2" id="qty" name="qty" required onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Unit</label>
                  <div class="input-group input-group-dynamic">
                  <select class="form-control w-100 border px-2" name="unit" id="unit" onfocus="focused(this)" onfocusout="defocused(this)">
                    <option value="pct" <?= ($product_data->unit_measure == 'pct') ? 'selected' : ''; ?>>Percent (%)</option>
                    <option value="mg" <?= ($product_data->unit_measure == 'mg') ? 'selected' : ''; ?>>Milligrams (mg)</option>
                    <option value="g <?= ($product_data->unit_measure == 'g') ? 'selected' : ''; ?>">Grams (g)</option>
                    <option value="oz <?= ($product_data->unit_measure == 'oz') ? 'selected' : ''; ?>">Ounces (oz)</option>
                  </select>
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Unit value</label>
                  <div class="input-group input-group-dynamic">
                    <input type="number" class="form-control w-100 border px-2" id="unit_value" name="unit_value" placeholder="0.00" min="0" step="0.01" value="<?= $product_data->unit_value; ?>" onfocus="focused(this)" required onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Price</label>
                  <div class="input-group input-group-dynamic">
                    <input type="number" class="form-control w-100 border px-2" id="price" name="price" placeholder="0.00" min="0" step="0.01" value="<?= $product_data->price; ?>" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Category</label>
                  <div class="input-group input-group-dynamic">
                  <select class="product-category form-control w-100 border px-2" name="category[]" id="category" multiple onfocus="focused(this)" onfocusout="defocused(this)">
                    <?php foreach($categories as $category): ?>
                    <?php $selected = (in_array($category->id, $product_categories)) ? ' selected' : ''; ?>
                    <option value="<?php echo $category->id; ?>"<?= $selected; ?>><?php echo $category->name; ?></option>
                    <?php endforeach; ?>
                  </select>
                  </div>
                </div>

                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Experience</label>
                  <div class="input-group input-group-dynamic">
                  <select class="experience-class form-control w-100 border px-2" name="experience[]" id="experience" multiple onfocus="focused(this)" onfocusout="defocused(this)">
                    <?php foreach($experiences as $experience): ?>
                    <?php $selected = (in_array($experience->id, $product_experience)) ? ' selected' : ''; ?>
                    <option value="<?php echo $experience->id; ?>"<?= $selected; ?>><?php echo $experience->name; ?></option>
                    <?php endforeach; ?>
                  </select>
                  </div>
                </div>

                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Delivery Type</label>
                  <div class="input-group input-group-dynamic">
                  <select class="delivery-type form-control w-100 border px-2" name="del_type[]" id="del_type" multiple onfocus="focused(this)" onfocusout="defocused(this)">
                    <option value="1" <?= ($product_data->delivery_type == 1) ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="2" <?= ($product_data->delivery_type == 2) ? 'selected' : ''; ?>>Fast Tracked</option>
                  </select>
                  </div>
                </div>
              </div>
              
              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3"></div>
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
                      <option value="<?php echo $strain->id; ?>" <?php echo ($product_data->strain == $strain->id) ? 'selected' : ''; ?>><?php echo $strain->name; ?></option>
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
                      <option value="<?php echo $brand->id; ?>" <?php echo ($product_data->brands == $brand->id) ? 'selected' : ''; ?>><?php echo $brand->name; ?></option>
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
                        <input type="text" name="thc_val" id="thc_val" class="form-control w-100 border px-2" value="<?= $product_data->thc_value; ?>" onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="input-group input-group-dynamic">
                        <select name="thc_measure" id="thc_measure" class="form-control">
                          <option>Please select THC Unit of Measure</option>
                          <option value="pct" <?php echo ($product_data->thc_unit == 'pct') ? 'selected' : ''; ?>>Percent (%)</option>
                          <option value="mg" <?php echo ($product_data->thc_unit == 'mg') ? 'selected' : ''; ?>>Milligrams (mg)</option>
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
                        <input type="text" name="cbd_val" id="cbd_val" class="form-control w-100 border px-2" value="<?= $product_data->cbd_value; ?>" onfocus="focused(this)" onfocusout="defocused(this)">
                      </div>
                    </div>
                    <div class="col-md-6 col-xs-6">
                      <div class="input-group input-group-dynamic">
                        <select name="cbd_measure" id="cbd_measure" class="form-control">
                          <option>Please select CBD Unit of Measure</option>
                          <option value="pct" <?php echo ($product_data->cbd_unit == 'pct') ? 'selected' : ''; ?>>Percent (%)</option>
                          <option value="mg" <?php echo ($product_data->cbd_unit == 'mg') ? 'selected' : ''; ?>>Milligrams (mg)</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-6 col-xs-12">
                    <label class="form-label w-100">Low Stock Threshold</label>
                    <div class="input-group input-group-dynamic">
                      <input type="number" name="lowstock_threshold" id="lowstock_threshold" placeholder="0" min="0" value="<?= $product_data->lowstock_threshold; ?>" step="1" class="form-control w-100 border px-2" onfocus="focused(this)" onfocusout="defocused(this)">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-xs-12 mt-lg-4 mt-4">
          <!-- <h6>Variants</h6>                
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

          <br><br><br> -->

          <h6>Images</h6>
          <div class="row" id='image_lists'>
            <?php foreach($images as $image): ?>
            <div class="row current_images mb-5">
              <div class="col-lg-5">
                <input type="hidden" name="current_images[]" value="<?= $image->id; ?>">
                <img class="min-height-100 max-height-100 border-radius-lg shadow" src="<?= base_url('products/images/'.$image->filename); ?>" alt="" />
              </div>
              <div class="col-lg-5"></div>
              <div class="col-lg-2">
                <button type="button" class="btn bg-gradient-danger btn-sm remove_image"><span class="material-icons">delete</span></button>
              </div>
            </div>
            <?php endforeach; ?>
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

        <div class="row mt-4">
          <div class="col-lg-8 col-xs-12 mt-lg-0 mt-4">
            <div class="card">
              <div class="card-body">
                <h6>Sale</h6>
                <div class="row mt-4">
                  <div class="col-md-6 col-xs-12">
                    <label class="form-label w-100">Discount</label>
                    <div class="row">
                      <div class="col-md-6 col-xs-6">
                        <div class="input-group input-group-dynamic">
                          <input type="number" name="discount_val" id="discount_val" placeholder="0.00" min="0" value="0" step="0.01" class="form-control w-100 border px-2" onfocus="focused(this)" onfocusout="defocused(this)">
                        </div>
                      </div>
                      <div class="col-md-6 col-xs-6">
                        <div class="input-group input-group-dynamic">
                          <select name="discount_type" id="discount_type" class="form-control w-100 border px-2">
                            <option value="percent">Percent (%)</option>
                            <option value="fixed">Fixed deduction</option>
                            <option value="sale_price">Sale Price</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 col-xs-12">
                    <div class="row">
                      <div class="col-md-6 col-xs-6">
                        <label class="form-label w-100">Start Date</label>
                        <div class="input-group input-group-dynamic">
                          <input type="text" id="sale_start_date" name="sale_start_date" value="" class="form-control datetime_picker" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-6 col-xs-6">
                        <label class="form-label w-100">End Date</label>
                        <div class="input-group input-group-dynamic">
                          <input type="text" id="sale_end_date" name="sale_end_date" value="" class="form-control datetime_picker" autocomplete="off">
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row mt-4">
                  <div><strong>Active Promotions</strong></div>
                  <?php if(!empty($discount)): ?>
                  <table>
                    <thead>
                      <tr>
                        <th>Variation ID</th>
                        <th>Discount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($discount as $active_discount): ?>
                      <tr>
                        <td><?= $active_discount->variant_id; ?></td>
                        <td><?= $active_discount->variant_id; ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                  <?php else: ?>
                    <p>No Active Sale/Promotions Available</p>
                  <?php endif; ?>
                </div>
                <pre><?= print_r($discount, 1); ?></pre>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-xs-12">
            <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
          </div>
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
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/edit_product.js"></script>
<?php $this->endSection() ?>