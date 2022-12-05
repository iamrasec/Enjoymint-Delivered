<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("styles") ?>
<link id="pagestyle" href="<?php echo base_url('assets/css/admin_products.css'); ?>" rel="stylesheet" />
<?php $this->endSection() ?>

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
        <div class="switch-view">
          <ul>
            <li><?php echo ($state != 'all') ? '<a href="'.base_url('admin/products').'">All</a>' : 'All'; ?></li>
            <li><?php echo ($state != 'low-stock') ? '<a href="'.base_url('admin/products?state=low-stock').'">Low Stock</a>' : 'Low Stock'; ?></li>
            <li><?php echo ($state != 'out-of-stock') ? '<a href="'.base_url('admin/products?state=out-of-stock').'">Out Of Stock</a>' : 'Out Of Stock'; ?></li>
            <li><?php echo ($state != 'archived') ? '<a href="'.base_url('admin/products?state=archived').'">Archived</a>' : 'Archived'; ?></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-6 text-right d-flex flex-column justify-content-center">
        <a class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2" href="<?php echo base_url('/admin/products/add_product'); ?>">Add Product</a>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-lg-12 mt-lg-0 mt-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsives">
              <table id="products-table" class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Stocks</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tfoot>
                    <th>ID</th>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Stocks</th>
                    <th>Status</th>
                    <th>Action</th>
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
  $(document).ready(function () {
      var state = '<?php echo $state; ?>';
      $('#products-table').DataTable({
        // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
          "url": "<?= base_url('admin/products/getProductLists'); ?>/" + state,
          "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
          "targets": [0, 1, 2, 3, 4],
          // "orderable": false
        }],
        "columns": [
          { 
            data: 'id',
            className: 'product-id px-2 text-xs',
            // "orderable": true,
          },
          { 
            data: 'name',
            className: 'product-name px-2 text-xs',
            // "orderable": true,
          },
          { 
            data: 'url',
            className: 'product-url px-2 text-xs',
            // "orderable": true,
          },
          { 
            data: 'stocks',
            className: 'product-url px-2 text-xs',
            // "orderable": true,
          },
          { 
            data: 'archived',
            className: 'product-status px-2',
            render: function(data, type, row) {
              // console.log(row.archived);

              if(row.archived == 1) {
                return '<span class="offline text-center d-block fs-4">●</span>';
              }
              else {
                return '<span class="online text-center d-block fs-4">●</span>';
              }
            }  
            // "orderable": true,
          },
          { 
          data: 'actions',
          render: function(data, type, row) {
            // console.log(row);
            let actions = '';
            actions += '<a href="<?= base_url('products'); ?>/'+row.url+'" target="_blank"><i class="fas fa-eye"></i></a> | ';
            actions += '<a href="<?= base_url('admin/products/edit_product'); ?>/'+row.id+'"><i class="fas fa-edit"></i></a> | ';
            actions += '<a href="javascript;;" class="removeBtn" data-id="'+row.id+'"><i class="fas fa-trash"></i></a>';

            return actions;
          }
        },
        ],
    });

    $("body").delegate(".removeBtn", "click", function(){
      var prodId = $(this).data('id');
      console.log(prodId);
      
      fetch('/api/products/delete_product/'+prodId,  {
        method: 'POST',
        headers : {
          'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
        }
      }) .then(response => response.json()).then(response => {
          var { message, success }  = response;
          success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/products') : enjoymintAlert('Sorry!', message, 'error', 0);
      }).catch((error) => {
          console.log('Error:', error);
      });
    }); 
  });

</script>
<?php $this->endSection(); ?>