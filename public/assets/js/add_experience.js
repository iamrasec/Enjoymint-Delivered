(function($) {
    $(document).on('keyup', '#exp_name', function() {
      $('#exp_url').val(slugify($(this).val()));
    });
  
    $(document).on('click', '.form-submit', function() {
      $("#add_experience_form").submit();
    });
  
  })(jQuery);