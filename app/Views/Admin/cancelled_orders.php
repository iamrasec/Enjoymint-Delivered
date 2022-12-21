<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("content") ?>

<?php if(isset($role) && $role != 4): ?>
<?php echo $this->include('templates/__dash_navigation.php'); ?>
<?php endif; ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <div class="row">
 
      <div class="col-lg-6">
        <h4><?php echo $page_title; ?></h4>
      </div>
      <div class="col-lg-6 text-right d-flex flex-row justify-content-center">
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders'); ?>">View All Orders</a>
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders/active'); ?>">View Active Orders</a>
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/orders/cancelled'); ?>">View Cancelled Orders</a>
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
                    <th># of Products</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Order Status</th>
                    <th>Delivery Type</th>
                    <th>Delivery Schedule</th>
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

<?php echo $this->include('templates/_image_popup.php'); ?>

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
  .customer-valid-id {
    cursor: pointer;
  }
</style>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<!-- Load Data Table JS -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- Product List page js -->
<script>

// var jwt = $("[name='atoken']").attr('content');

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
          "targets": [0, 1, 2, 3, 4, 5, 6, 7,8],
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
          className: 'order-id px-2 text-xs',
          // "orderable": true,
        },
        { 
          data: 'customer_name',
          className: 'customer-name px-2 text-xs',
          // "orderable": true,
        },
        { 
          data: 'address',
          className: 'customer-address px-2 text-xs',
          // "orderable": true,
        },
        { 
          data: 'product_count',
          className: 'product-count text-xs',
          // "orderable": true,
        },
        { 
          data: 'total',
          className: 'total-cost text-xs',
          // "orderable": true,
          render: function(data, type, row) {
            return '$'+data;
          }
        },
        { 
          data: 'created',
          className: 'order-created text-xs',
          // "orderable": true,
        },
        { 
          data: 'status',
          className: 'order-status text-xs',
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
        { 
          data: 'delivery_type',
          className: 'delivery-type text-xs',
          render: function(data, type, row) {
            if(data == 0) {
              return 'Scheduled';
            }
            else if(data == 1) {
              return 'Fast-tracked';
            }
            else {
              return 'Scheduled';
            }
          }
        },
        { 
          data: 'delivery_schedule',
          className: 'delivery-schedule text-xs',
          render: function(data, type, row) {
            console.log(row.delivery_time);
            
            if(row.delivery_time != null) {
              let delTime = row.delivery_time.split("-");
              let delFrom = tConvert(delTime[0]);
              let delTo = tConvert(delTime[1]);

              return "Date: " + row.delivery_schedule + "<br>Time: " + delFrom + " - " + delTo;
            }
            else {
              return '';
            }
            
          }
        },
        // { 
        //   data: 'order_notes',
        //   className: 'order-notes text-xs',
        // },
        { 
          data: 'actions',
          render: function(data, type, row) {
            // console.log(row);
            let actions = '';
            if(row.status == 0 || row.status == 1) {
              // actions += '<a href="<?= base_url('admin/orders/edit'); ?>/'+row.id+'"><i class="fas fa-edit"></i></a>';
              // actions += '<a href="javascript;;"><i class="fas fa-trash"></i></a> | ';
              actions += '<a class="btn btn-link complete-order actions-'+row.id+' text-secondary ps-0 pe-2" data-id="'+row.id+'"><i class="fas fa-clipboard-check"></i> Complete Order</a> &nbsp;';
              actions += '<div class="dropdown d-inline actions-'+row.id+'"><button class="btn btn-link actions-'+row.id+' text-secondary ps-0 pe-2" id="navbarDropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v text-lg" aria-hidden="true"></i></button>';
              actions += '<div class="dropdown-menu dropdown-menu-end me-sm-n4 me-n3" aria-labelledby="navbarDropdownMenuLink" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 44px);">';
              actions += '<a class="dropdown-item" href="<?= base_url('admin/orders/edit'); ?>/'+row.id+'"><i class="fas fa-edit"></i> Edit Order</a>';
              actions += '<a class="dropdown-item" href="javascript;;"><i class="fas fa-trash"></i> Delete Order</a>';
              actions += '</div></div>';
            }

            return actions;
          }
        },
      ],
      order: [[0, 'desc']],
    });

    function product_area(d)
    {
      console.log('PRODUCT AREA');
      console.log(d);
      // console.log(d.customer_ids);
      // console.log(Object.keys(d.customer_ids).length);

      let order_notes = '';

      if(d.order_notes != "") {
        order_notes = '<div class="order_notes mb-3"><div><strong>Order Notes</strong></div><p class="fst-italic p-2 bg-warning bg-gradient" style="color: #ffffff;">'+ d.order_notes +'</p></div>';
      }

      let customer_ids = '';

      if(Object.keys(d.customer_ids).length > 0) {

        // let profile_img = '';
        // let valid_id = '';
        // let mmic = '';

        // let raw_data = Object.entries(d.customer_ids);

        // console.log(Object.entries(d.customer_ids)[0]);

        customer_ids += '<div class="customer_ids mb-3">';
        
        for([key, val] of Object.entries(d.customer_ids)) {
          if(key == 'profile_img' && val != '') {
            // console.log(val);
            customer_ids += '<div class="d-inline-block me-4" style="width:120px; width: 90px;"><div><strong>Profile Image</strong></div>'+ val +'</div>';
          }
          else if(key == 'valid_id' && val != '') {
            customer_ids += '<div class="d-inline-block me-4" style="width:120px; width: 90px;"><div><strong>Valid ID</strong></div>'+ val +'</div>';
          }
          else if(key == 'mmic' && val != '') {
            customer_ids += '<div class="d-inline-block me-4" style="width:120px; width: 90px;"><div><strong>MMIC</strong></div>'+ val +'</div>';
          }
        }
        customer_ids += '</div>';
      }

      let products_table = order_notes + customer_ids + '<table cellpadding="5" cellspacing="0" border="0" class="w-90 ms-5 fs-6">';
      
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

    $("body").delegate(".complete-order", "click", function(){
      let data = {};
      data.pid = $(this).data('id');

      $.ajax({
        type: "POST",
        url: '<?= base_url('/api/orders/complete'); ?>',
        data: data,
        dataType: "json",
        success: function(json) {
          console.log(json);
          enjoymintAlert('', 'Order is Completed', 'success', 0);
          $('.actions-'+data.pid).hide();
          $('td.order-status').html('Completed');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          // console.log(textStatus);
          enjoymintAlert('Error', 'Order Not Completed', 'error', 0);
        },
        beforeSend: function(xhr) {
          xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
        }
      });
    }); 

    // Show image modal
    $(document).on('click', '.customer-valid-id', function() {
      $('#imagepreview').attr('src', $(this).attr('src'));
      $('#imagemodal').modal('show');
    });
});
</script>

<?php $this->endSection(); ?>