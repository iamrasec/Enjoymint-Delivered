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
})(jQuery);