<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>
<style>
  .breaker {
    margin-top: 10px;
  }
</style>
<?php // echo $this->include('templates/__dash_navigation.php'); ?>

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
        <div class="col-lg-12 col-xs-12 mt-lg-0 mt-4">
          <div class="card">
            <div class="card-body">
              <h5 class="font-weight-bolder">Order Information</h5>
              <div class="row mt-4">
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Start Time</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="product_name" class="form-control w-100 border px-2" name="name" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">End Time</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" class="form-control w-100 border px-2" id="sku" name="sku" onfocus="focused(this)" required onfocusout="defocused(this)">
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-8 col-md-8 col-xs-12 mb-3">
                  <label class="form-label" for="name">Customer Name</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="product_name" class="form-control w-100 border px-2" name="name" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Phone</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" class="form-control w-100 border px-2" id="sku" name="sku" onfocus="focused(this)" required onfocusout="defocused(this)">
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Order Number</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="product_name" class="form-control w-100 border px-2" name="name" required onfocus="focused(this)" onfocusout="defocused(this)">
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Order Source</label>
                  <div class="input-group input-group-dynamic">
                    <select id="order_source" name="order_source" class="form-control w-100 border px-2" required onfocus="focused(this)" onfocusout="defocused(this)">
                      <option>Please Select One</option>
                      <option value="phone">Phone</option>
                      <option value="meadows">Meadows</option>
                      <option value="weedmaps">Weedmaps</option>
                      <option value="getnugg">GetNugg</option>
                      <option value="textus">TextUs</option>
                      <option value="sticky_rose">Sticky Rose</option>
                      <option value="tawk">Tawk</option>
                      <option value="missed_call">Missed Call</option>
                    </select>
                  </div>
                </div>
                <div class="col-4 col-md-4 col-xs-12 mb-3">
                  <label class="form-label" for="name">Order Status</label>
                  <div class="input-group input-group-dynamic">
                    <select id="order_status" name="order_status" class="form-control w-100 border px-2" required onfocus="focused(this)" onfocusout="defocused(this)">
                      <option>Please Select One</option>
                      <option value="completed">Completed</option>
                      <option value="cancelled">Cancelled</option>
                      <option value="preorder">Pre-Order</option>
                      <option value="pending">Pending</option>
                      <option value="inquiry">Inquiry</option>
                      <option value="escalation">Escalation</option>
                      <option value="exchange_return">Exchange & Return</option>
                      <option value="troubleshooting">Troubleshooting</option>
                      <option value="coaching">Coaching</option>
                      <option value="training_shadowing">Training / Shadowing</option>
                      <option value="break">Break</option>
                      <option value="callback">Callback</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Additional Note</label>
                  <div class="input-group input-group-dynamic">
                    <textarea onfocus="focused(this)" required onfocusout="defocused(this)" style="width: 100%;"></textarea>
                  </div>
                </div>
              </div>
              
              
              
            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?php echo base_url(); ?>/assets/js/add_product.js"></script>
<?php $this->endSection() ?>