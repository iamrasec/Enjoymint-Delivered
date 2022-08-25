(function($) {
  $(document).on('keyup', '#cat_name', function() {
    $('#cat_url').val(slugify($(this).val()));
  });

  $(document).on('click', '.form-submit', function() {
    $("#add_category_form").submit();
  });

})(jQuery);