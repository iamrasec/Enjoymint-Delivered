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
})(jQuery);