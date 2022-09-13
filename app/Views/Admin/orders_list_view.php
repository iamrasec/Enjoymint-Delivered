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
      <div class="col-lg-3 text-right d-flex flex-column justify-content-center" >
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders/drivers'); ?>">View Drivers Table</a>
      </div>
      <div class="col-lg-3 text-right d-flex flex-column justify-content-center">
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders/order'); ?>">View Completed Orders</a>
      </div>
    </div>
  <!-- <div class="row mt-3">
      <div class="card">
      <form role="form" method="post" action="/admin/orders/drivers">
        <h7>Driver Assignment:</h5>
        <select name="drivers"  style="margin-left:30px ;">
                    <option value="0">Select Driver:</option>
                    <option value="Jhon Cena">Jhon Cena</option>
                    <option value="Mr. Toge">Mr. Toge</option>
                    <option value="Alucard Doe">Alucard Doe</option>
                    <option value="Zilong Reyes">Zilong Reyes</option>
        </select></br>
        <h7>Active/Pending Orders:</h6>
        <select name="order">
        <?php foreach($active_orders as $order): ?>
          <?php  echo '<option value="$order->id">'.$order->id.$order->product.'</option>' ?>
        <?php endforeach; ?>
        </select>
        <button type="submit" class="btn bg-gradient-danger mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Assign</button>
      </div>
        </form>
      </div> -->
    
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
                    <th>Assigned Delivery</th>
                  </tr>
                </thead>
                <tbody>
                <form role="form" method="post" action="/api/orders/complete">
                <?php foreach($active_orders as $order): ?>
                  <tr class="text-xs font-weight-bold mb-0">
                    <td><?php echo $order->id; ?></td>
                    <td><?php echo $order->product; ?></td>
                    <td>Active Orders/Pending</td>
                    <td><?php echo $order->qty; ?></td>
                    <td><?php echo $order->price; ?></td>
                 <td><select name="drivers" id="driver">
                    <option value="0">Select Driver:</option>
                    <option value="Jhon Cena">Jhon Cenamon</option>
                    <option value="Mr. Toge">Mr. Toge</option>
                    <option value="Alucard Doe">Alucard Doe</option>
                    <option value="Zilong Reyes">Zilong Reyes</option>
                    
              </select><button type="submit" class="btn remove"  data-id="<?= $order->id; ?>">Assign</button></td>
                  </tr>
                <?php endforeach; ?>
                </form>
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

$("body").delegate(".remove", "click", function(){
      var prodId = $(this).data('id');
      // var id = $('#driver').val();
      console.log(prodId);
    
      fetch('/api/orders/complete/'+prodId, {
        method: 'POST',
        headers : {
          'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
        }
      }) .then(response => response.json()).then(response => {
          var { message, success, id }  = response;
          console.log(response);
          //success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/products') : enjoymintAlert('Sorry!', message, 'error', 0);
      }).catch((error) => {
          console.log('Error:', error);
      });
    }); 
});
</script>

<?php $this->endSection(); ?>