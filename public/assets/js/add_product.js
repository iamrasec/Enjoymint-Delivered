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
    data.new_strain_name = $("#new_strain_name").val().trim();
    data.new_strain_url = slugify($("#new_strain_name").val().trim());
    console.log(data);

    $.ajax({
      type: "POST",
      url: "/api/strain/add",
      data: data,
      dataType: "json",
      success: function(json) {

      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {

      },
      beforeSend: function(xhr) {
        
      }
    });
  });
})(jQuery);