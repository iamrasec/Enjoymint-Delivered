(function($) {
  $("#contact_form").submit(function(e) {
    e.preventDefault();

    const contactFormData = new FormData();

    contactFormData.append('first_name', $('#contact_form input[name="first_name"]').val());
    contactFormData.append('last_name', $('#contact_form input[name="last_name"]').val());
    contactFormData.append('phone_number', $('#contact_form input[name="phone_number"]').val());
    contactFormData.append('email', $('#contact_form input[name="email"]').val());
    contactFormData.append('message', $('#contact_form #message').val());

    console.log(contactFormData);

    fetch('/api/contact/save',  {
      method: 'POST',
      body: contactFormData,
      headers : {
        'Authorization': 'Bearer ' + $("[name='atoken']").attr('content')
      }
    }) .then(response => response.json() ).then(response => {
        var { message, success }  = response;
        console.log(response);
        // success ? enjoymintAlert('Nice!', message, 'success', 0, '/admin/products') : enjoymintAlert('Sorry!', message, 'error', 0);
    }).catch((error) => {
        console.log('Error:', error);
    });
  });
})(jQuery);