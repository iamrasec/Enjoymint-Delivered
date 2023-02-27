(function($) {
  $(document).on('keyup', '#title', function() {
    $('#promo_url').val(slugify($(this).val()));
  });

  $(document).ready(function() {
    $('#sale_start_date').datetimepicker({
      timepicker: true,
      datepicker: true,
      format: 'Y-m-d h:i a',
    });

    $('#sale_end_date').datetimepicker({
      timepicker: true,
      datepicker: true,
      format: 'Y-m-d h:i a',
    });
  });
                
  $(document).ready(function() {
    // Initialize select2 inputs
    $(".select2-field").select2();
  
    // $("#cond_req_purchase").change(function() {
    //   console.log("Required Purchase");
    //   if($("#cond_req_purchase").prop("checked")) {
    //     $(".if_required_purchase").removeClass("d-none");
    //   }
    //   else {
    //     $(".if_required_purchase").addClass("d-none");
    //   }
    // });  

    $(document).on("change", ".cond_req_purchase", function() {
      // console.log("Required Purchase");

      let currCondID = $(this).data('promo-cond-id');

      let condContainer = (currCondID == 1) ? '#primary-condition' : '#addl-cond-' + currCondID;

      if($(this).prop("checked")) {
        $(condContainer + " .if_required_purchase").removeClass("d-none");
      }
      else {
        $(condContainer + " .if_required_purchase").addClass("d-none");
      }
    });

    // $(document).on("change", "input[type=radio][name=promo_products]", function() {
    $(document).on("change", "input.promo_products", function() {
      let currCondID = $(this).data('promo-cond-id');
      console.log("currCondID: " + currCondID);

      let condContainer = (currCondID == 1) ? '#primary-condition' : '#addl-cond-' + currCondID;

      switch($(this).val()) {
        case 'promo_products_all':
          $(condContainer + " .promo_products_select_products").addClass("d-none");
          $(condContainer + " .promo_products_select_cat").addClass("d-none");
          break;
        case 'promo_products_cat':
          $(condContainer + " .promo_products_select_products").addClass("d-none");
          $(condContainer + " .promo_products_select_cat").removeClass("d-none");
          break;
        case 'promo_products_specific':
          $(condContainer + " .promo_products_select_products").removeClass("d-none");
          $(condContainer + " .promo_products_select_cat").addClass("d-none");
          break;
      }
    });
    
    // $(document).on("change", "input[type=radio][name=req_purchase_type]", function() {
      $(document).on("change", "input.req_purchase_type", function() {
        let currCondID = $(this).data('promo-cond-id');
        // console.log("currCondID: " + currCondID);
  
        let condContainer = (currCondID == 1) ? '#primary-condition' : '#addl-cond-' + currCondID;
  
        switch($(this).val()) {
          case 'req_purchase_type_any':
            $(condContainer + " .product_required_purchase").addClass("d-none");
            $(condContainer + " .category_required_purchase").addClass("d-none");
            break;
          case 'req_purchase_type_category':
            $(condContainer + " .product_required_purchase").addClass("d-none");
            $(condContainer + " .category_required_purchase").removeClass("d-none");
            break;
          case 'req_purchase_type_product':
            $(condContainer + " .product_required_purchase").removeClass("d-none");
            $(condContainer + " .category_required_purchase").addClass("d-none");
            break;
          
        }
      });

    $(document).on("click", ".add-condition-btn", function(e) {
      e.preventDefault();  
      console.log("Add New Condition!");

      add_condition();

      return false;
    });
    
    $(document).on("click", ".remove-cond", function(e) {
      e.preventDefault();
      console.log("Remove Additional Condition "+$(this).data('remove-cond')+"!");

      $("#addl-cond-" + $(this).data('remove-cond')).remove();

      // let promo_counter = parseInt($("#promo-con-cntr").val()) - 1;

      // $("#promo-con-cntr").val(promo_counter);

      return false;
    });
  });

    $(document).on("#add_promo").submit(function(e) {
      e.preventDefault();

      // const formData = new FormData();
      // let addl_conds = document.querySelectorAll(".addl-cond");
      
      // let submitCondData = [];

      // submitCondData.cond1 = [

      // ];

      submit_promo();
      return false;
    // });  

  });

function add_condition() {
  let promo_counter = parseInt($("#promo-con-cntr").val()) + 1;

  $("#promo-con-cntr").val(promo_counter);

  console.log(promo_counter);

  let prodChoices = '';

  for(var p in prodArr) {
    prodChoices += '<option value="'+prodArr[p][0]['id']+'">'+prodArr[p][1]['name']+'</option>';
  }

  let catChoices = '';

  for(var c in CatArr) {
    catChoices += '<option value="'+CatArr[c][0]['id']+'">'+CatArr[c][1]['name']+'</option>';
  }

  let cond_container = '<div class="card mt-4 addl-cond" id="addl-cond-'+promo_counter+'" data-promo-cond="'+promo_counter+'">'
  + '<strong>Additional Condition <a href="#" class="remove-cond ms-3 btn btn-danger btn-sm" data-remove-cond="'+promo_counter+'"><span class="material-icons">close</span> Remove</a></strong>'
  + '<div class="border p-3" id="condition-'+promo_counter+'">'
  + '<div class="row mt-2"><div class="col-12 col-md-12 col-xs-12 mb-3">'
    + '<label class="form-label fw-bold">Promo Products</label>'
    + '<div class="d-flex">'
      + '<div class="form-check me-4"><input class="form-check-input promo_products" type="radio" name="promo_products'+promo_counter+'" id="promo_products_all" value="promo_products_all" data-promo-cond-id="'+promo_counter+'" checked><label class="form-check-label" for="promo_products_all">All Products</label></div>'
      + '<div class="form-check me-4"><input class="form-check-input promo_products" type="radio" name="promo_products'+promo_counter+'" id="promo_products_specific" value="promo_products_specific" data-promo-cond-id="'+promo_counter+'"><label class="form-check-label" for="promo_products_specific">Specific Product(s)</label></div>'
      + '<div class="form-check me-4"><input class="form-check-input promo_products" type="radio" name="promo_products'+promo_counter+'" id="promo_products_cat" value="promo_products_cat" data-promo-cond-id="'+promo_counter+'"><label class="form-check-label" for="promo_products_cat">Products in Category</label></div>'
    + '</div>'
  + '</div>'
  + '<div class="row mt-4 promo_products_select_products d-none"><div class="col-12 col-md-12 col-xs-12 mb-3">'
    + '<label class="form-label" for="promo_products_selected">Select Product(s) Included in the Promo</label>'
    + '<select class="select2-field promo_products_select_products_select" name="promo_products_selected'+promo_counter+'[]" multiple="multiple" data-promo-cond-id="'+promo_counter+'">'
    + prodChoices
    + '</select>'
  + '</div></div>'
  + '<div class="row mt-4 promo_products_select_cat d-none"><div class="col-12 col-md-12 col-xs-12 mb-3">'
    + '<label class="form-label" for="promo_products_selected">Select Category Included in the Promo</label>'
    + '<select class="select2-field promo_products_select_cat_select" name="promo_products_selected'+promo_counter+'[]" multiple="multiple" data-promo-cond-id="'+promo_counter+'">'
    + catChoices
    + '</select>'
  + '</div></div>'
  + '</div>'
  + '<div class="row mt-4"><div class="col-12 col-md-12 col-xs-12 mb-3"><div class="input-group input-group-dynamic is-filled">'
    + '<input type="checkbox" id="cond_req_purchase" class="cond_req_purchase" name="cond_req_purchase" data-promo-cond-id="'+promo_counter+'"> &nbsp; <strong>Require Purchase</strong><small class="d-block fs-xs fst-italic ms-2">(The required product will be purchased at REGULAR PRICE)</small>'
  + '</div></div></div>'
  + '<div class="row mt-4 if_required_purchase d-none"><div class="col-12 col-md-12 col-xs-12 mb-3 d-flex">'
    + '<div class="form-check me-4 is-filled">'
      + '<input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type'+promo_counter+'" id="req_purchase_type_any" value="req_purchase_type_any" data-promo-cond-id="'+promo_counter+'" checked>'
      + '<label class="form-check-label" for="req_purchase_type_any">Any Product(s)</label>'
    + '</div>'
    + '<div class="form-check me-4 is-filled">'
      + '<input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type'+promo_counter+'" id="req_purchase_type_product" value="req_purchase_type_product" data-promo-cond-id="'+promo_counter+'">'
      + '<label class="form-check-label" for="req_purchase_type_product">Specific Product(s)</label>'
    + '</div>'
    + '<div class="form-check me-4 is-filled">'
      + '<input class="form-check-input req_purchase_type" type="radio" name="req_purchase_type'+promo_counter+'" id="req_purchase_type_category" value="req_purchase_type_category" data-promo-cond-id="'+promo_counter+'">'
      + '<label class="form-check-label" for="req_purchase_type_category">Products in Category</label>'
    + '</div>'
  + '</div></div>'
  + '<div class="row mt-4 product_required_purchase d-none"><div class="col-12 col-md-12 col-xs-12 mb-3">'
    + '<label class="form-label fw-bold" for="required_product">Required Purchase: Select Product(s)</label>'
    + '<select class="select2-field required_product_select" name="required_product[]" multiple="multiple">'
    + prodChoices
    + '</select>'
  + '</div></div>'
  + '<div class="row mt-4 category_required_purchase d-none"><div class="col-12 col-md-12 col-xs-12 mb-3">'
    + '<label class="form-label fw-bold" for="required_product">Required Purchase: Select Category</label>'
    + '<select class="select2-field required_category_select" name="required_product[]" multiple="multiple">'
    + catChoices
    + '</select>'
  + '</div></div>'
  + '<div class="row mt-4 if_required_purchase d-none"><div class="col-12 col-md-12 col-xs-12 mb-3">'
     + '<label class="form-label fw-bold" for="required_product">Required Quantity Purchased <small class="d-block fw-normal fs-xs fst-italic ms-2">(Minimum quantity is 1)</small></label>'
     + '<div class="input-group input-group-dynamic">'
     +  '<input type="number" class="req_purchase_qty" id="req_purchase_qty" name="req_purchase_qty'+promo_counter+'" min="1" value="1" step="1">'
     +  '</div>'
     +'</div></div>'
  + '<div class="row mt-4 if_required_purchase d-none"><div class="col-12 col-md-12 col-xs-12 mb-3">'
    +'<label class="form-label fw-bold" for="name">Number of Products Discounted for this condition</label>'
    +'<ul>'
      + '<li><small class="d-block fw-normal fs-xs fst-italic ms-2">This will override the "Maximum Number of Products Discounted per Purchase"</small></li>'
      + '<li><small class="d-block fw-normal fs-xs fst-italic ms-2">Multiple quantities of the same product also counts towards this condition.</small></li>'
      + '<li><small class="d-block fw-normal fs-xs fst-italic ms-2">Leaving this at 0 will not impose any limitation on the Number of Products Discounted.</small></li>'
    + '</ul>'
    +'<div class="input-group input-group-dynamic">'
      +'<input type="number" class="num_prod_discounted_cond" id="num_prod_discounted_cond" name="num_prod_discounted_cond'+promo_counter+'" placeholder="0" min="0" value="0" step="1">'
    +'</div>'
  + '</div></div>'
+ '</div>'
+ '</div>'; 

  $(cond_container).appendTo(".additional-conditions-wrap");
  
  $("#addl-cond-"+promo_counter+" .select2-field").select2();
}

function submit_promo() {
  let submitCondData = [];

  let cond1 = {};
  cond1.promo_products = $("#primary-condition input[type=radio][name=promo_products1]:checked").val();
  cond1.products_specific = "";
  cond1.products_cat = "";
  cond1.req_purchase = ($("#primary-condition #cond_req_purchase").prop('checked')) ? 1 : 0;

  if(cond1.promo_products == "promo_products_specific") {
    cond1.products_specific = $("#primary-condition #promo_products_select_products_select").select2('data');
  }
  
  if(cond1.promo_products == "promo_products_cat") {
    cond1.products_cat = $("#primary-condition #promo_products_select_cat_select").select2('data');
  }

  if(cond1.req_purchase == 1) {
    cond1.req_pp = $("#primary-condition input[type=radio][name=req_purchase_type1]:checked").val();
    cond1.req_pp_specific = "";
    cond1.req_pp_cat = "";
    cond1.req_pp_qty = $("#primary-condition input.req_purchase_qty").val();
    cond1.req_pp_discounted_counter = $("#primary-condition .num_prod_discounted_cond").val();

    if(cond1.req_pp == "req_purchase_type_product") {
      cond1.req_pp_specific = $("#primary-condition #required_product_select").select2('data');
    }

    if(cond1.req_pp == "req_purchase_type_category") {
      cond1.req_pp_cat = $("#primary-condition #required_category_select").select2('data');
    }
  }

  console.log("cond1: ");
  console.log(cond1.req_purchase);
  

  // submitCondData = [
  //   {'cond1' : [
  //     {'promo_products': cond1.promo_products},
  //   ]}
  // ];

  submitCondData.push(cond1);

  let addl_conds = document.querySelectorAll(".addl-cond");


  addl_conds.forEach(function(item, field) {
    console.log('additional conds');
    console.log(item);
    // console.log(field);
    let condNum = $(item).data('promo-cond');
    let cond = 'cond' + condNum;
    console.log(condNum);
    console.log(cond);
    let addlCondArr = {};
    addlCondArr.promo_products = $("#addl-cond-"+condNum+" input[type=radio][name=promo_products"+condNum+"]:checked").val();
    addlCondArr.products_specific = "";
    addlCondArr.products_cat = "";
    addlCondArr.req_purchase = ($("#addl-cond-"+condNum+" #cond_req_purchase").prop('checked')) ? 1 : 0;

    if(addlCondArr.promo_products == "promo_products_specific") {
      addlCondArr.products_specific = $("#addl-cond-"+condNum+" .promo_products_select_products_select").select2('data');
    }

    if(addlCondArr.promo_products == "promo_products_cat") {
      addlCondArr.products_cat = $("#addl-cond-"+condNum+" .promo_products_select_cat_select").select2('data');
    }

    if(addlCondArr.req_purchase == 1) {
      addlCondArr.req_pp = $("#addl-cond-"+condNum+" input[type=radio][name=req_purchase_type1]:checked").val();
      addlCondArr.req_pp_specific = "";
      addlCondArr.req_pp_cat = "";
      addlCondArr.req_pp_qty = $("#addl-cond-"+condNum+" input.req_purchase_qty").val();
      addlCondArr.req_pp_discounted_counter = $("#addl-cond-"+condNum+" .num_prod_discounted_cond").val();

      if(addlCondArr.req_pp == "promo_products_specific") {
        addlCondArr.req_pp_specific = $("#addl-cond-"+condNum+" .required_product_select").select2('data');
      }

      if(addlCondArr.req_pp == "promo_products_cat") {
        addlCondArr.req_pp_cat = $("#addl-cond-"+condNum+" .required_category_select").select2('data');
      }
    }
                  
    submitCondData.push(addlCondArr);
  });
  // console.log(addl_conds);
  // console.log(formData);
  console.log(submitCondData);


  const formData = new FormData();

  formData.append('title', $("#title").val());
  formData.append('promo_url', $("#promo_url").val());
  formData.append('description', $("#quill_content").val());
  formData.append('promo_type', $("#promo_type").val());
  formData.append('discount_val', $("#discount_val").val());
  formData.append('promo_code', $("#promo_code").val());
  formData.append('usage_limit', $("#usage_limit").val());
  formData.append('show_products', $("#show_products").val());
  formData.append('max_prod_discounted', $("#max_prod_discounted").val());
  formData.append('mechanics', JSON.stringify(submitCondData) );
  // formData.append('all_products', $("#promo_products_select_products_select").val());
  // formData.append('all_categories', $("#promo_products_select_cat_select").val());
  // formData.append('all_req_products', $("#required_product_select").val());
  // formData.append('all_req_categories', $("#required_category_select").val());
  // formData.append('req_purchase_qty', $("#req_purchase_qty").val());
  // formData.append('num_prod_discounted_cond', $("#num_prod_discounted_cond").val());

  // if($('#promo_products_all').is(':checked')){
  //   formData.append('promo_products1',  'promo_products_all');
  // }
  // if($('#promo_products_specific').is(':checked')){
  //   formData.append('promo_products1',  'promo_products_specific');
  // }
  // if($('#promo_products_cat').is(':checked')){
  //   formData.append('promo_products1',  'promo_products_cat');
  // } 
  // if($('#cond_req_purchase').is(':checked')){
  //   formData.append('cond_req_purchase',  '1');
  // }
  // if($('#req_purchase_type_any').is(':checked')){
  //   formData.append('req_purchase_type1',  'req_purchase_any');
  // }
  // if($('#req_purchase_type_product').is(':checked')){
  //   formData.append('req_purchase_type1',  'req_purchase_product');
  // }
  // if($('#req_purchase_type_category').is(':checked')){
  //   formData.append('req_purchase_type1',  'req_purchase_category');
  // }

  if($("#sale_start_date").val() != "") {
    formData.append('sale_start_date', $('#sale_start_date').val());
    formData.append('sale_end_date', $('#sale_end_date').val());
  }

  fetch('/api/promotion/add',  {
      method: 'POST',
      body: formData,
      headers : {
        'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
      }
    }) .then(response => response.json()).then(response => {
        var { message, success }  = response;
        success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/promotion') : enjoymintAlert('Sorry!', message, 'error', 0);
    }).catch((error) => {
        console.log('Error:', error);
    });

 

}
})(jQuery);