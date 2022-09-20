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
          <?php  // echo '<option value="$order->id">'.$order->id.$order->product.'</option>' ?>
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
                    <th></th>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Products in Order</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                
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
</main>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<!-- Load Data Table JS -->
<script src="<?= base_url('assets/js/plugins/jquery.dataTables.min.js') ?>"></script>
<!-- Product List page js -->
<script>

var jwt = $("[name='atoken']").attr('content');

$(document).ready(function () {
    $('#sales-table').DataTable({
      processing: true,
      serverSide: true,
      order: [],
      ajax: {
        url: '<?= base_url('api/orders/list_pending'); ?>',
        type: 'POST',
        headers: {
          "Authorization": "Bearer "+ jwt,
        }
      },
      "columnDefs": [{ 
          "targets": [0, 1, 2, 3, 4, 5, 6, 7],
          "orderable": false
      }],
      columns: [
        {
          className: 'dt-control',
          orderable: false,
          data: null,
          defaultContent: '',
        },
        { data: 'id' },
        { data: 'customer_name' },
        { data: 'address' },
        { data: 'product_count' },
        { data: 'total' },
        { data: 'created' },
        { data: 'status' },
        { 
          data: 'actions',
          render: function (data, type, row) {
            let actions = '';
            actions += '<a href="javascript;;">Edit</a> | ';
            actions += '<a href="javascript;;">Delete</a> | ';
            actions += '<a href="javascript;;">Close</a>';

            return actions;
          }
        },
      ],
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