<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-lg-6">
        <h4><?php echo $page_title; ?></h4>
      </div>
    
    </div>

    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsives">
              <table id="sales-table" class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Quantity</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach($product_sale as $sale): ?>
                  <tr class="text-xs font-weight-bold mb-0">
                    <td><?php echo $sale->id; ?></td>
                    <td><?php echo $sale->product; ?></td>
                    <td>Completed</td>
                    <td><?php echo $sale->qty; ?></td>
                  <td><?php echo $sale->price; ?></td>
                  </tr>
                <?php endforeach; ?>
               <?php foreach($sales as $sale) :?>
                Total Sales:<?php echo $sale;?> 
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                </tfoot>
               
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- <footer class="footer py-4  ">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start">
            
          </div>
        </div>
        <div class="col-lg-6">
          <ul class="nav nav-footer justify-content-center justify-content-lg-end">
            <li class="nav-item">
              <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer> -->
</main>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<!-- Load Data Table JS -->
<script src="<?= base_url('assets/js/plugins/jquery.dataTables.min.js') ?>"></script>
<!-- Product List page js -->
<script>

$(document).ready(function () {
    $('#sales-table').DataTable({
      'searching' :  true
});
});
</script>

<?php $this->endSection(); ?>