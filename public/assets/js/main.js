(function($) {
  $(window).scroll(function() {
    if ($(this).scrollTop() > 1) {
      $('nav.navbar').addClass("sticky_header");
    }
    else {
      $('nav.navbar').removeClass("sticky_header");
    }
  });
})(jQuery);

function setCookie(key, value, expiry) {
  var expires = new Date();
  expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
  document.cookie = key + '=' + value + ';expires=' + expires.toUTCString() +';path=/';
}

function getCookie(key) {
  console.log("key: " + key);
  var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
  return keyValue ? keyValue[2] : null;
}

function eraseCookie(key) {
  var keyValue = getCookie(key);
  setCookie(key, keyValue, '-1');
}

function add_to_cart(uid, pid, qty)
{
  let jwt = $("[name='atoken']").attr('content');

  let data = {};
  data.uid = uid;
  data.pid = pid;
  data.qty = qty;

  $.ajax({
    type: "POST",
    url: "<?= base_url('/api/cart/add'); ?>",
    data: data,
    dataType: "json",
    success: function(json) {
      console.log("successs");
      $(".add-to-cart").removeAttr('disabled');
      $(".lds-hourglass").addClass('d-none');

      console.log(json);

      let arr = [];

      var cart_data_cookie = getCookie('cart_data');

      console.log("cart_data cookie: ");
      console.log(cart_data_cookie);

      if(cart_data_cookie != null) {
        var cookie_products = JSON.parse(cart_data_cookie);

        console.log(cookie_products);

        var pid_added = false;
        cookie_products.forEach(function(product) {
          console.log(product);

          if(product.pid == json.pid) {
            product.qty = parseInt(product.qty) + parseInt(json.qty);
            pid_added = true;
          }
        });

        if(pid_added == false) {
          cookie_products.push({'pid': json.pid, 'qty': parseInt(json.qty)});
        }

        setCookie('cart_data',JSON.stringify(cookie_products),'1');

        console.log(cookie_products);
      }
      else {
        cookie_products = [{'pid': json.pid, 'qty': parseInt(json.qty)}];

        setCookie('cart_data',JSON.stringify(cookie_products),'1');
      }

      console.log(cookie_products.length);

      cartCountr = cookie_products.length;

      setCookie('cart_items_count',cartCountr,'1');

      // if(json.newItem > 0) {
        // let currentCount = $("#count_cart").text();
        // $("#count_cart").html(parseInt(currentCount) + json.newItem);
      // }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(textStatus);
    },
    beforeSend: function(xhr) {
      xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
    }
  });
}

function update_cart_count()
{
  // Initialize
  let get_cookie = '';
  let cookie_products = [];
    
  // Check if cookie exists
  get_cookie = getCookie(cookie_cart);

  // If cookie exists.  Check products
  if(get_cookie) {
    
  }
}