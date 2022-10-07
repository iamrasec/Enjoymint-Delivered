(function($) {

    $(document).on('click', '.btn-modal', function(e) {
      e.preventDefault();
      
      return false;
    });
  
    /** this event will serve as removing of new added file input */
    $("body").delegate(".remove_image", "click", function(){
      $(this).parent().parent().remove();
    });
  
    /** this event will serve as removing of new added file input */
    $("body").delegate(".remove_variant", "click", function(){
      $(this).parent().parent().remove();
    });
  
    $("#edit_user").submit(function(e) {
      e.preventDefault(); // avoid to execute the actual submit of the form.
  
      const formData = new FormData();

      formData.append('first_name', $('#first_name').val());
      formData.append('last_name', $('#last_name').val());
      formData.append('email', $('#email').val());
      formData.append('role', $('#role').val());
      formData.append('password', $('#password').val());
      formData.append('password_confirm', $('#password_confirm').val());
  
      fetch('/users/edit_user/' + $('#id').val(),  {
        method: 'POST',
        body: formData,
        headers : {
          'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
        }
      }) .then(response => response.json()).then(response => {
          var { message, success }  = response;
          success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/users') : enjoymintAlert('Sorry!', message, 'error', 0);
      }).catch((error) => {
          console.log('Error:', error);
      });
    });
  })(jQuery);