(function($) {
  $(document).ready(function() {
    $('.product-category').select2({
      placeholder: "Select a Category",
      // allowClear: true
    });

    $('.experience-class').select2({
      placeholder: "Select an Experience",
      // allowClear: true
    });
    
    $('.delivery-type').select2({
      placeholder: "Select Delivery Type",
      // allowClear: true
    });
  });

  $(document).on('keyup', '#product_name', function() {
    $('#purl').val(slugify($(this).val()));
  });

  $(document).on('click', '.btn-modal', function(e) {
    e.preventDefault();
    
    return false;
  });

  $(document).on('click', '.add-new-strain', function(e) {
    e.preventDefault();
    let data = {};
    let jwt = $("[name='atoken']").attr('content');
    console.log(jwt);
    data.name = $("#new_strain_name").val().trim();
    data.url_slug = slugify($("#new_strain_name").val().trim());
    console.log(data);

    $.ajax({
      type: "POST",
      url: "/api/strain/add",
      data: data,
      dataType: "json",
      success: function(json) {
        console.log(json);
        if(json.message == true) {
          $("#newStrainModal .cancel-btn").click();
          $("new_strain_name").val("");
          $("#select_strain").prop('selectedIndex', -1);
          $("#select_strain").append('<option value="'+json.data+'" selected="selected">'+data.name+'</option>');
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
      },
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
      }
    });
  });

  $(document).on('click', '.add-new-brand', function(e) {
    e.preventDefault();
    let data = {};
    let jwt = $("[name='atoken']").attr('content');
    console.log(jwt);
    data.name = $("#new_brand_name").val().trim();
    data.url = slugify($("#new_brand_name").val().trim());
    console.log(data);

    $.ajax({
      type: "POST",
      url: "/api/brand/add",
      data: data,
      dataType: "json",
      success: function(json) {
        console.log(json);
        if(json.message == true) {
          $("#newBrandModal .cancel-btn").click();
          $("new_brand_name").val("");
          $("#select_brand").prop('selectedIndex', -1);
          $("#select_brand").append('<option value="'+json.data+'" selected="selected">'+data.name+'</option>');
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
      },
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
      }
    });
  });

  /** this event will serve as adding of new image file input on clicking plus icon */
  $("#add_image").click(function () {
    var fileInputForm = 
    "<div class=\"row\"><div class=\"col-lg-10\">" +
      "<input type=\"file\" name=\"images[]\" accept=\"image/png, image/jpeg, image/jpg\" class=\"form-control\">" +
    "</div>" +
    "<div class=\"col-lg-2\">"+
    "  <button type=\"button\" class=\"btn bg-gradient-danger btn-sm remove_image\"><span class=\"material-icons\">delete</span></button>"+
    "</div></div>";
    $("#image_lists").append(fileInputForm); 
  });

  /** this event will serve as adding of new variant of product */
  $("#add_variant").click(function () {
    console.log("Add Variant Button Clicked!");

    var fileInputForm = 
    '<div class="row added_variant">' 
      + '<div class="col-lg-10">' 
        + '<div class="row">' 
          + '<div class="col-lg-6">' 
            + '<label>Unit</label>' 
            + '<select class="variant_unit form-control w-100 border px-2" name="variant_unit[]" onfocus="focused(this)" onfocusout="defocused(this)">' 
              + '<option value="pct">Percent (%)</option>' 
              + '<option value="mg">Milligrams (mg)</option>' 
              + '<option value="g">Grams (g)</option>' 
              + '<option value="oz">Ounces (oz)</option>' 
            + '</select>' 
          + '</div>' 
          + '<div class="col-lg-6">' 
            + '<label>Unit Value</label>' 
            + '<input type="number" name="variant_unit_value[]" class="variant_unit_value form-control w-100 border px-2">' 
          + '</div>' 
          + '<div class="col-lg-6">' 
            + '<label>Price</label>' 
            + '<input type="number" name="variant_price[]" class="variant_price form-control w-100 border px-2">' 
          + '</div>' 
          + '<div class="col-lg-6">' 
            + '<label>  </label>' 
            + '<input type="number" name="variant_qty[]" class="variant_qty form-control w-100 border px-2">' 
          + '</div>' 
        + '</div>' 
      + '</div>' 
      + '<div class="col-lg-2"><br><br>' 
        + '<button type="button" class="btn bg-gradient-danger btn-sm remove_variant"><span class="material-icons">delete</span></button>' 
      + '</div>' 
    + '</div><hr class="breaker">';

    $("#variants").append(fileInputForm); 
  });

  /** this event will serve as removing of new added file input */
  $("body").delegate(".remove_image", "click", function(){
    $(this).parent().parent().remove();
  });

  /** this event will serve as removing of new added file input */
  $("body").delegate(".remove_variant", "click", function(){
    $(this).parent().parent().remove();
  });

  $("#edit_product").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.

    const formData = new FormData();
    const photos = document.querySelectorAll('input[type="file"]');
  
    photos.forEach(function (item, field) {
      formData.append('productImages[]', item.files[0]);
    });

    let current_images = document.querySelectorAll('input[name="current_images[]"]');

    // console.log(current_images);

    for(let i = 0; i < current_images.length; i++) {
      formData.append('current_images[]', current_images[i].value);
    }

    // current_images.forEach(function(item, field) {
    //   console.log(item);
    //   console.log(field);
    //   formData.append('current_images[]', item.files[0]);
    // });

    // var variant_count = 0;
    var variants_arr = [];
    $(".added_variant").each(function() {

      // variants_arr[variant_count].variant_unit = $(this).find(".variant_unit").find(":selected").val();
      // variants_arr.variant_count.variant_unit_value = $(this).find(".variant_unit_value").val();
      // variants_arr.variant_count.variant_price = $(this).find(".variant_price").val();
      // variants_arr.variant_count.variant_qty = $(this).find(".variant_qty").val();

      let variant = {
        variant_unit : $(this).find(".variant_unit").find(":selected").val(),
        variant_unit_value : $(this).find(".variant_unit_value").val(),
        variant_price : $(this).find(".variant_price").val(),
        variant_qty : $(this).find(".variant_qty").val(),
      };

      variants_arr.push(variant);

      // variant_count++;
      // console.log(variant_unit);
    });

    var added_variants = JSON.stringify(variants_arr);

    console.log(variants_arr);

    formData.append('name', $('#product_name').val());
    formData.append('sku', $('#sku').val());
    formData.append('purl', $('#purl').val());
    formData.append('description', $('#description').val());
    formData.append('qty', $('#qty').val());
    formData.append('price', $('#price').val());
    formData.append('strain', $('#select_strain').val());
    formData.append('brand', $('#select_brand').val());
    formData.append('thc_val', $('#thc_val').val());
    formData.append('thc_measure', $('#thc_measure').val());
    formData.append('cbd_val', $('#cbd_val').val());
    formData.append('cbd_measure', $('#cbd_measure').val());
    formData.append('categories', $('#category').val());
    formData.append('experiences', $('#experience').val());
    formData.append('unit_measure', $('#unit').val());
    formData.append('unit_value', $('#unit_value').val());
    formData.append('delivery_type', $('#del_type').val());
    formData.append('lowstock_threshold', $('#lowstock_threshold').val());
    formData.append('variants', added_variants);

    // console.log(formData);

    /** Disable submit while debugging variants */
    fetch('/api/products/edit_product/' + $('#pid').val(),  {
      method: 'POST',
      body: formData,
      headers : {
        'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
      }
    }) .then(response => response.json() ).then(response => {
        var { message, success }  = response;
        success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/products') : enjoymintAlert('Sorry!', message, 'error', 0);
    }).catch((error) => {
        console.log('Error:', error);
    });
  });
})(jQuery);