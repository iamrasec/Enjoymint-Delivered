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
      <div class="col-lg-1"></div>
      <div class="col-lg-5 text-right d-flex flex-row justify-content-center">
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders'); ?>">View All Orders</a>
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders/active'); ?>">View Active Orders</a>
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders/completed'); ?>">View Completed Orders</a>
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
                    <th></th>
                    <th class="order-id px-2">ID</th>
                    <th class="customer-name px-2">Customer Name</th>
                    <th class="customer-address px-2">Address</th>
                    <th>Products in Order</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                    <!-- <th>Actions</th> -->
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

<style>
  table.dataTable td.dt-control:before {
    height: 1em;
    width: 1em;
    margin-top: -9px;
    display: inline-block;
    color: white;
    border: 0.15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New",Courier,monospace;
    line-height: 1em;
    content: "+";
    background-color: #31b131;
  }
  table.dataTable tr.dt-hasChild td.dt-control:before {
    content: "-";
    background-color: #d33333;
  }
  table.dataTable .product-count, table.dataTable .total-cost, table.dataTable .order-status {
    text-align: center;
  }
  table.dataTable .order-id, table.dataTable .customer-name, table.dataTable .customer-address {
    text-align: left;
  }
</style>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<!-- Load Data Table JS -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- Product List page js -->
<script>

var jwt = $("[name='atoken']").attr('content');

$(document).ready(function () {
    var table = $('#sales-table').DataTable({
      processing: true,
      serverSide: true,
      order: [],
      ajax: {
        url: '<?= base_url('api/orders/list_cancelled'); ?>',
        type: 'POST',
        headers: {
          "Authorization": "Bearer "+ jwt,
        }
      },
      "columnDefs": [{ 
          "targets": [0, 1, 2, 3, 4, 5, 6, 7],
          // "orderable": false
      }],
      columns: [
        {
          className: 'dt-control',
          orderable: false,
          data: null,
          defaultContent: '',
        },
        { 
          data: 'id',
          className: 'order-id px-2',
          // "orderable": true,
        },
        { 
          data: 'customer_name',
          className: 'customer-name px-2',
          // "orderable": true,
        },
        { 
          data: 'address',
          className: 'customer-address px-2',
          // "orderable": true,
        },
        { 
          data: 'product_count',
          className: 'product-count',
          // "orderable": true,
        },
        { 
          data: 'total',
          className: 'total-cost',
          // "orderable": true,
          render: function(data, type, row) {
            return '$'+data;
          }
        },
        { 
          data: 'created',
          // "orderable": true,
        },
        { 
          data: 'status',
          className: 'order-status',
          // "orderable": true,
          render: function(data, type, row) {
            if(data == 0) {
              return 'Pending';
            }
            else if(data == 1) {
              return 'Assigned';
            }
            else if(data == 2) {
              return 'Completed';
            }
            else {
              return 'Cancelled';
            }
          }
        },
      ],
      order: [[0, 'desc']],
    });

    function product_area(d)
    {
      // console.log(d);

      let products_table = '<table cellpadding="5" cellspacing="0" border="0" class="w-90 ms-5 fs-6">';
      
      products_table += '<tr class="fw-bold"><td>Product Title</td><td class="text-center">Qty</td><td class="text-right">Unit Price</td><td class="text-right">Total</td></tr>';

      for(let i = 0; i < d.products.length; i++) {
        products_table += '<tr class="border"><td>'+ d.products[i].product_name +'</td><td class="text-center">'+ d.products[i].qty +'</td><td class="text-right">$'+ d.products[i].unit_price +'</td><td class="text-right">$'+ d.products[i].total +'</td></tr>';
      }

      products_table += '</table>';

      return products_table;
    }

    // Add event listener for opening and closing details
    $('#sales-table tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
 
        if (row.child.isShown()) {         
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(product_area(row.data())).show();
            tr.addClass('shown');
        }
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