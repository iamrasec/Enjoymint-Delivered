<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

  <div class="container-fluid py-4">
    <h3>OnFleet Test</h3>
    <div class="row">
      <div class="col-6 col-md-6 px-2">
        <h5>Existing Tasks</h5>
        <pre><?php print_r($existing_tasks); ?></pre>
      </div>
      <div class="col-6 col-md-6 px-2">
        <h5>New Task</h5>
        <form id="test-dispatch" method="POST">
          <div class="row mb-4">
            <div class="col-12 col-md-12 col-xs-12">
              <label class="form-label" for="task_details">Task/Order Details (Task Notes)</label>
              <div class="input-group input-group-dynamic">
                <textarea name="task_details" class="form-control w-100 border px-2" style="min-height: 200px;"></textarea>
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <h5>Recipient</h5>
            <div class="col-6 col-md-6 col-xs-12">
              <label class="form-label" for="recipient_name">Name</label>
              <div class="input-group input-group-dynamic">
                <input type="text" name="recipient_name" class="form-control w-100 border px-2">
              </div>
            </div>
            <div class="col-6 col-md-6 col-xs-12">
              <label class="form-label" for="recipient_phone">Phone</label>
              <div class="input-group input-group-dynamic">
                <input type="text" name="recipient_phone" class="form-control w-100 border px-2">
              </div>
            </div>
            <div class="col-12 col-md-12 col-xs-12 mt-2">
              <label class="form-label" for="recipient_notes">Recipient Notes</label>
              <div class="input-group input-group-dynamic">
                <textarea name="recipient_notes" class="form-control w-100 border px-2" style="min-height: 200px;"></textarea>
              </div>
            </div>
          </div>
          <div class="col-lg-12 text-right d-flex flex-column justify-content-center">
            <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
          </div>
        </form>
      </div>
    </div>
    
  </div>
</main>

<?php $this->endSection(); ?>

<?php $this->section("scripts") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php $this->endSection() ?>