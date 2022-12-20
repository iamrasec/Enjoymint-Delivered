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

function setCookie(key, value, expiry) {
  // console.log("setting cookie " + key);
  var expires = new Date();
  expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
  document.cookie = key + '=' + value + ';expires=' + expires.toUTCString() +';path=/';
}

function getCookie(key) {
  // console.log("key: " + key);
  var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
  return keyValue ? keyValue[2] : null;
}

function eraseCookie(key) {
  var keyValue = getCookie(key);
  setCookie(key, keyValue, '-1');
}

function updateTotal(tax) {
  let sub_total = 0;
  let sub_tax = 0;
  let total = 0;

  const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  });

  $(".product-qty").each(function() {
    // let product_id = $(this).find("input").data("pid");
    // let cart_product = {
    //   'id' : product_id,
    //   'name' : $("tr.pid-"+product_id).find(".product-title a").text(),
    //   'qty' : $(this).find("input").val(),
    //   'price' : $(this).find("input").data("unit-price"),
    // };

    let qty = $(this).find("input").val();
    let curr_price = $(this).find("input").data("unit-price");

    let price = qty * curr_price;

    sub_total += price;

    // order_data.push(cart_product);
  });

  sub_tax = sub_total * (tax - 1);
  total = sub_total * tax;

  $(".subtotal_temp").html(formatter.format(sub_total));
  $(".tax_temp").html(formatter.format(sub_tax));
  $(".total_temp").html(formatter.format(total));
}