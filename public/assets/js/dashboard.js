function slugify(content) {
	return content.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
}

function enjoymintAlert(title, text, icon, is_reload = 0, redirect, update_cart)
{
  swal({
    title: title,
    text: text,
    icon: icon,
    showCancelButton: false,
    confirmButtonColor: '#32243d',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ok'
  }).then((result) => {
      if(is_reload === 1){
        window.location.reload();
      }
      if(redirect){
        window.location.href = redirect;
      }

  });
}