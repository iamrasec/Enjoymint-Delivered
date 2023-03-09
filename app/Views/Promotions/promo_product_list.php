
<?php $this->extend("templates/base_dashboard"); ?>

<?php $this->section("styles") ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  .breaker {
    margin-top: 10px;
  }
  .promo_products_select_products .select2-container, 
  .promo_products_select_cat .select2-container,
  .product_required_purchase .select2-container,
  .category_required_purchase .select2-container {
    display: block !important;
    width: 100% !important;
  }
  .xdsoft_datetimepicker .xdsoft_datepicker {
    width: 600px !important;
  }
</style>
<?php $this->endSection() ?>

<?php $this->section("content") ?>

<?php echo $this->include('templates/__dash_navigation.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <!-- Navbar -->
  <?php echo $this->include('templates/__dash_top_nav.php'); ?>
  <!-- End Navbar -->
  
  <div class="container-fluid py-4">
    <form id="add_promo" class="enjoymint-form" enctype="multipart/form-data">
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
              <h5 class="font-weight-bolder">Promo Information</h5>
                   
              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Title</label>
                  <div class="input-group input-group-dynamic">
                    <input type="text" id="title" value=" <?= $data1[0]->title; ?>" class="form-control w-100 border px-2" name="title" required>
                  </div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <label class="form-label" for="name">Description</label>
                  <div class="input-group input-group-dynamic">
                  
                    <input type="text" value="<?= $data1[0]->description ?>"  name="description">
                  </div>
                </div>
              </div>

                 <div class="row mt-4">
                  <div class="col-md-12 col-xs-12">
                    <div class="row">
                    <div class="col-md-3 col-xs-3">
                        <label class="form-label w-100">Prome Code</label>
                        <div class="input-group input-group-dynamic">
                        <input type="number" id="promo_code" value="<?= $data1[0]->promo_code ?>" name="promo_code" class="form-control w-100 border px-2" >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <div class="row mt-4">
                <div class="col-12 col-md-12 col-xs-12 mb-3">
                  <h5>Promo Conditions</h5>

                  <input type="hidden" id="promo-con-cntr" value="1">
                    
                  <div class="card" data-promo-condition="1">
                    <div class="card-body">
                      <!-- START: Condition #1 -->
                      <strong>Primary Condition</strong>
                      <div class="border p-3" id="primary-condition">
                        <div class="row mt-2">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <label class="form-label fw-bold" for="name">Promo Products</label>
                            <div class="d-flex">
                              <div class="form-check me-4">
                                <input class="form-check-input promo_products" type="radio" name="promo_products1" id="promo_products_all" value="promo_products_all" data-promo-cond-id="1" <?= $checked ;?>>
                                <label class="form-check-label" for="promo_products_all">All Products</label>
                              </div>
                              <div class="form-check me-4">
                                <input class="form-check-input promo_products" type="radio" name="promo_products1" id="promo_products_specific" value="promo_products_specific" data-promo-cond-id="1" <?= $checked1 ;?>>
                                <label class="form-check-label" for="promo_products_specific">Specific Product(s)</label>
                                </div>
                              <div class="form-check me-4">
                                <input class="form-check-input promo_products" type="radio" name="promo_products1" id="promo_products_cat" value="promo_products_cat" data-promo-cond-id="1" <?= $checked2 ;?>>
                                <label class="form-check-label" for="promo_products_cat">Products in Category</label>
                              </div>
                            </div>

                            <div class="row mt-4 promo_products_select_products d-none">
                              <div class="col-12 col-md-12 col-xs-12 mb-3">
                              <label class="form-label" for="promo_products_selected">Select Product(s) Included in the Promo</label>
                              <select class="select2-field promo_products_select_products_select" id="promo_products_select_products_select" name="all_products[]" multiple="multiple">
                              </select>
                              </div>
                            </div>

                            <div class="row mt-4 promo_products_select_cat d-none">
                              <div class="col-12 col-md-12 col-xs-12 mb-3">
                              <label class="form-label" for="promo_products_selected">Select Category Included in the Promo</label>
                              <select class="select2-field promo_products_select_cat_select" id="promo_products_select_cat_select" name="all_categories[]" multiple="multiple">
                               
                              </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <div class="input-group input-group-dynamic">
                              <input type="checkbox" id="cond_req_purchase" class="cond_req_purchase" name="cond_req_purchase" data-promo-cond-id="<?= $req ;?>"> &nbsp; <strong>Require Purchase</strong>
                              <small class="d-block fs-xs fst-italic ms-2">(The required product will be purchased at REGULAR PRICE)</small>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4 if_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3 d-flex">
                            <div class="form-check me-4">
                              <input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type1" id="req_purchase_type_any" value="req_purchase_type_any" data-promo-cond-id="1" checked>
                              <label class="form-check-label" for="req_purchase_type_any">Any Product(s)</label>
                            </div>
                            <div class="form-check me-4">
                              <input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type1" id="req_purchase_type_product" value="req_purchase_type_product" data-promo-cond-id="1">
                              <label class="form-check-label" for="req_purchase_type_product">Specific Product(s)</label>
                            </div>
                            <div class="form-check me-4">
                              <input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type1" id="req_purchase_type_category" value="req_purchase_type_category" data-promo-cond-id="1">
                              <label class="form-check-label" for="req_purchase_type_category">Products in Category</label>
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4 product_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                          <label class="form-label fw-bold" for="required_product">Required Purchase: Select Product(s)</label>
                          <select class="select2-field required_product_select" id="required_product_select" name="all_products[]" multiple="multiple">
                           
                          </select>
                          </div>
                        </div>

                        <div class="row mt-4 category_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                          <label class="form-label fw-bold" for="required_product">Required Purchase: Select Category</label>
                          <select class="select2-field required_category_select" id="required_category_select" name="all_categories[]" multiple="multiple">
                           
                          </select>
                          </div>
                        </div>

                        <div class="row mt-4 if_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <label class="form-label fw-bold" for="required_product">Required Quantity Purchased <small class="d-block fw-normal fs-xs fst-italic ms-2">(Minimum quantity is 1)</small></label>
                            <div class="input-group input-group-dynamic">
                              <input type="number" class="req_purchase_qty" id="req_purchase_qty" name="req_purchase_qty" min="1" value="1" step="1">
                            </div>
                          </div>
                        </div>

                        <div class="row mt-4 if_required_purchase d-none">
                          <div class="col-12 col-md-12 col-xs-12 mb-3">
                            <label class="form-label fw-bold" for="name">Number of Products Discounted for this condition</label>
                            <ul>
                              <li><small class="d-block fw-normal fs-xs fst-italic ms-2">This will override the "Maximum Number of Products Discounted per Purchase"</small></li>
                              <li><small class="d-block fw-normal fs-xs fst-italic ms-2">Multiple quantities of the same product also counts towards this condition.</small></li>
                              <li><small class="d-block fw-normal fs-xs fst-italic ms-2">Leaving this at 0 will not impose any limitation on the Number of Products Discounted.</small></li>
                            </ul>
                            <div class="input-group input-group-dynamic">
                              <input type="number" class="num_prod_discounted_cond" id="num_prod_discounted_cond" name="num_prod_discounted_cond" placeholder="0" min="0" value="0" step="1">
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- END: Condition #1 -->
                      <div class="additional-conditions-wrap"></div>

                      <div class="row mt-3 mb-5">
                        <div class="col-lg-12">
                          <button class="btn add-condition-btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Add Another Condition</button>
                        </div>
                      </div>

                    </div>
                  </div>

                </div>
              </div>

              <!-- <pre></pre> -->

              <div class="row mt-3 mb-5">
                <div class="col-lg-12 text-right d-flex flex-column justify-content-end">
                  <button type="submit" class="btn bg-gradient-primary mb-0 ms-lg-auto me-lg-0 me-auto mt-lg-0 mt-2">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-12 col-xs-12 mt-lg-0 mt-4">
          <div class="card">
            <div class="card-body">
              <h5 class="font-weight-bolder">Products List</h5>
              <table id="product-table" class="w-100" id= "table-id">
                      <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                  <?php if($product_data != null): ?>
                <?php foreach($product_data as $data): ?>
                  <?php if(isset($data[0])) : ?>
                  <tr class="text-xs font-weight-bold mb-0">
                    <td><?php echo $data[0]->id; ?></td>
                    <td><?php echo $data[0]->name; ?></td>
                    <td>   
                        <a href=<?= site_url('products/' . $data[0]->url) ?> class="btn btn-info btn-sm">View</a>             
                        <a href='<?= base_url('/admin/products/edit_product/'.$data[0]->id) ?>' class="btn btn-success btn-sm">Edit</a>
                    </td>
                  </tr>
                 
                  <?php endif ; ?>
                <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                  <td>No product include </td>
                  <?php endif; ?>
                  </tr>
                </tbody>
                <div class='pagination-container' >
				<nav>
				  <ul class="pagination">
            
            <li data-page="prev" >
								     <span> < <span class="sr-only">(current)</span></span>
								    </li>
				   <!--	Here the JS Function Will Add the Rows -->
        <li data-page="next" id="prev">
								       <span> > <span class="sr-only">(current)</span></span>
								    </li>
				  </ul>
				</nav>
			</div>
                    </table>

       
                </div>
                <?php //echo $pager->links() ; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

    </form>
  </div>
</main>

<?php $this->endSection(); ?>
<?php $this->section("scripts") ?>
<script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/js/add_promo.js"></script>

<?php $this->endSection() ?>
<style>
.bootstrap-tagsinput {
  background-color: #fff;
  border: 1px solid #ccc;
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  display: inline-block;
  padding: 4px 6px;
  color: #555;
  vertical-align: middle;
  border-radius: 4px;
  max-width: 100%;
  line-height: 22px;
  cursor: text;
}
.bootstrap-tagsinput input {
  border: none;
  box-shadow: none;
  outline: none;
  background-color: transparent;
  padding: 0 6px;
  margin: 0;
  width: auto;
  max-width: inherit;
}
.bootstrap-tagsinput.form-control input::-moz-placeholder {
  color: #777;
  opacity: 1;
}
.bootstrap-tagsinput.form-control input:-ms-input-placeholder {
  color: #777;
}
.bootstrap-tagsinput.form-control input::-webkit-input-placeholder {
  color: #777;
}
.bootstrap-tagsinput input:focus {
  border: none;
  box-shadow: none;
}
.bootstrap-tagsinput .tag {
  margin-right: 2px;
  color:black;
}
.bootstrap-tagsinput .tag [data-role="remove"] {
  margin-left: 8px;
  cursor: pointer;
}
.bootstrap-tagsinput .tag [data-role="remove"]:after {
  content: "x";
  padding: 0px 2px;
}
.bootstrap-tagsinput .tag [data-role="remove"]:hover {
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}
.bootstrap-tagsinput .tag [data-role="remove"]:hover:active {
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}
.pagination li:hover{
    cursor: pointer;
}
		table tbody tr {
			display: none;
		}



</style>
<script>
   getPagination('#table-id');
					//getPagination('.table-class');
					//getPagination('table');

		  /*					PAGINATION 
		  - on change max rows select options fade out all rows gt option value mx = 5
		  - append pagination list as per numbers of rows / max rows option (20row/5= 4pages )
		  - each pagination li on click -> fade out all tr gt max rows * li num and (5*pagenum 2 = 10 rows)
		  - fade out all tr lt max rows * li num - max rows ((5*pagenum 2 = 10) - 5)
		  - fade in all tr between (maxRows*PageNum) and (maxRows*pageNum)- MaxRows 
		  */
		 

function getPagination(table) {
  var lastPage = 1;

  $('#maxRows')
    .on('change', function(evt) {
      //$('.paginationprev').html('');						// reset pagination

     lastPage = 1;
      $('.pagination')
        .find('li')
        .slice(1, -1)
        .remove();
      var trnum = 0; // reset tr counter
      var maxRows = parseInt($(this).val()); // get Max Rows from select option

      if (maxRows == 5000) {
        $('.pagination').hide();
      } else {
        $('.pagination').show();
      }

      var totalRows = $(table + ' tbody tr').length; // numbers of rows
      $(table + ' tr:gt(0)').each(function() {
        // each TR in  table and not the header
        trnum++; // Start Counter
        if (trnum > maxRows) {
          // if tr number gt maxRows

          $(this).hide(); // fade it out
        }
        if (trnum <= maxRows) {
          $(this).show();
        } // else fade in Important in case if it ..
      }); //  was fade out to fade it in
      if (totalRows > maxRows) {
        // if tr total rows gt max rows option
        var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
        //	numbers of pages
        for (var i = 1; i <= pagenum; ) {
          // for each page append pagination li
          $('.pagination #prev')
            .before(
              '<li data-page="' +
                i +
                '">\
								  <span>' +
                i++ +
                '<span class="sr-only">(current)</span></span>\
								</li>'
            )
            .show();
        } // end for i
      } // end if row count > max rows
      $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
      $('.pagination li').on('click', function(evt) {
        // on click each page
        evt.stopImmediatePropagation();
        evt.preventDefault();
        var pageNum = $(this).attr('data-page'); // get it's number

        var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

        if (pageNum == 'prev') {
          if (lastPage == 1) {
            return;
          }
          pageNum = --lastPage;
        }
        if (pageNum == 'next') {
          if (lastPage == $('.pagination li').length - 2) {
            return;
          }
          pageNum = ++lastPage;
        }

        lastPage = pageNum;
        var trIndex = 0; // reset tr counter
        $('.pagination li').removeClass('active'); // remove active class from all li
        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
        // $(this).addClass('active');					// add active class to the clicked
	  	limitPagging();
        $(table + ' tr:gt(0)').each(function() {
          // each tr in table not the header
          trIndex++; // tr index counter
          // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
          if (
            trIndex > maxRows * pageNum ||
            trIndex <= maxRows * pageNum - maxRows
          ) {
            $(this).hide();
          } else {
            $(this).show();
          } //else fade in
        }); // end of for each tr in table
      }); // end of on click pagination list
	  limitPagging();
    })
    .val(5)
    .change();

  // end of on select change

  // END OF PAGINATION
}

function limitPagging(){
	// alert($('.pagination li').length)

	if($('.pagination li').length > 7 ){
			if( $('.pagination li.active').attr('data-page') <= 3 ){
			$('.pagination li:gt(5)').hide();
			$('.pagination li:lt(5)').show();
			$('.pagination [data-page="next"]').show();
		}if ($('.pagination li.active').attr('data-page') > 3){
			$('.pagination li:gt(0)').hide();
			$('.pagination [data-page="next"]').show();
			for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
				$('.pagination [data-page="'+i+'"]').show();

			}

		}
	}
}

$(function() {
  // Just to append id number for each row
  $('table tr:eq(0)').prepend('<th> ID </th>');

  var id = 0;

  $('table tr:gt(0)').each(function() {
    id++;
    $(this).prepend('<td>' + id + '</td>');
  });
});

</script>