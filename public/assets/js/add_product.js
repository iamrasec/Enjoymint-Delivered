(function($) {
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
    data.new_strain_name = $("#new_strain_name").val().trim();
    data.new_strain_url = slugify($("#new_strain_name").val().trim());
    console.log(data);

    $.ajax({
      type: "POST",
      url: "/api/strain/add",
      data: data,
      dataType: "json",
      success: function(json) {
        console.log(json);
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

  /** this event will serve as removing of new added file input */
  $("body").delegate(".remove_image", "click", function(){
    $(this).parent().parent().remove();
  });

  $("#add_product").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.

    const formData = new FormData();
    const photos = document.querySelectorAll('input[type="file"]');

    photos.forEach(function (item, field) {
      formData.append('productImages[]', item.files[0]);
    });
    
    formData.append('name', $('#product_name').val());
    formData.append('sku', $('#sku').val());
    formData.append('purl', $('#purl').val());
    formData.append('description', $('#description').val());
    formData.append('qty', $('#qty').val());
    formData.append('strain', $('#strain').value);
    formData.append('brand', $('#brand').value);
    formData.append('thc_val', $('#thc_val').val());
    formData.append('cbd_val', $('#cbd_val').val());

    fetch('/admin/products/addProduct', {
      method: 'POST',
      body: formData,
    }) .then(response => response.json() ).then(response => {
        var { message, success }  = response;
        success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/products') : enjoymintAlert('Sorry!', message, 'error', 0);
    }).catch((error) => {
        console.log('Error:', error);
    });
  });
})(jQuery);