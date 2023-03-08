(function($) {

    $("#pro_code").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        const formData = new FormData();
    
        formData.append('promo_code', $('#promo_code').val());
    
        fetch('/cart/promo_add',  {
          method: 'POST',
          body: formData,
          headers : {
            'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
          }
        }) .then(response => response.json()).then(response => {
            var { message, success }  = response;
            success ? enjoymintAlert('Nice!', message, 'success', 0, '/cart') : enjoymintAlert('Sorry!', message, 'error', 0);
        }).catch((error) => { 
            console.log('Error:', error);
        });
      });

  })(jQuery);