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

function tConvert (time) {
  if(time > 1200) {
    time = time - 1200 + ' PM';
  }
  else {
    time = time + ' AM';
  }

  // console.log("time: " + time);
  // console.log("time length: " + time.length);

  var formattedTime = time.slice(0, time.length - 5) + ":" + time.slice(time.length - 5);

  return formattedTime;
}