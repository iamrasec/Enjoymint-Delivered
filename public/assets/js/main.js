(function($) {
  $(window).scroll(function() {
    if ($(this).scrollTop() > 1) {
      $('nav.navbar').addClass("sticky_header");
    }
    else {
      $('nav.navbar').removeClass("sticky_header");
    }
  });

  $('.has-child').mouseover(function() {
    $(this).find('ul').removeClass('d-none');
  }).mouseout(function() {
    $(this).find('ul').addClass('d-none');
  });

  $('.product-filter-form').hide();
  $(document).on('click', '#product-filter-toggle', function() {
    // if($(this).hasClass('open')) {
    //   $('.product-filter-form').hide("slide", {direction: "up"}, 1000, function() {
    //     $('.product-filter-form').addClass('d-none');
    //   });
    //   $(this).removeClass('open');
    // }
    // else {
    //   $('.product-filter-form').removeClass('d-none');
    //   $(this).addClass('open');
    //   $('.product-filter-form').show("slide", {direction: "down"}, 1000);
    // }

    $('.product-filter-form').slideToggle('slow');
  });

  // $(document).on("click", "#modal-login-btn", function() {
  //   let data = {};
  //   data.email = $("input[name=email]").val();
  //   data.password = $("input[name=password]").val();

  //   $.ajax({
  //     type: "POST",
  //     url: baseUrl + "/api/users/login",
  //     data: data,
  //     dataType: "json",
  //     success: function(json) {
  //       console.log(json);
  //     },
  //     error: function(XMLHttpRequest, textStatus, errorThrown) {
  //       console.log(textStatus);
  //     },
  //   });
  // });
})(jQuery);

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

function add_to_cart(uid, pid, qty, vid = 0)
{
  // let jwt = $("[name='atoken']").attr('content');

  let data = {};
  data.uid = uid;
  data.pid = pid;
  data.vid = vid;
  data.qty = qty;

  $.ajax({
    type: "POST",
    url: baseUrl + '/api/cart/add',
    // url: baseUrl + '/cart/add',
    data: data,
    dataType: "json",
    success: function(json) {
      // console.log("successs");
      enjoymintAlert('', 'Product added to cart', 'success', 0);
      $(".add-product-"+pid).removeAttr('disabled');
      $(".lds-hourglass").addClass('d-none');

      // console.log(json);

      let arr = [];

      var cart_data_cookie = getCookie('cart_data');

      // console.log("cart_data cookie: ");
      // console.log(cart_data_cookie);

      if(cart_data_cookie != null) {
        var cookie_products = JSON.parse(cart_data_cookie);

        // console.log(cookie_products);

        var pid_added = false;
        cookie_products.forEach(function(product) {
          // console.log(product);

          if(product.pid == json.pid) {
            product.qty = parseInt(product.qty) + parseInt(json.qty);
            pid_added = true;
          }
        });

        if(pid_added == false) {
          cookie_products.push({'pid': json.pid, 'qty': parseInt(json.qty)});
        }

        setCookie('cart_data',JSON.stringify(cookie_products),'1');

        // console.log(cookie_products);
      }
      else {
        cookie_products = [{'pid': json.pid, 'qty': parseInt(json.qty)}];

        // console.log(cookie_products);

        setCookie('cart_data',JSON.stringify(cookie_products),'1');
      }

      // console.log(cookie_products.length);

      cartCountr = cookie_products.length;

      setCookie('cart_items_count',cartCountr,'1');

      update_cart_count_override(cartCountr);
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      // console.log(textStatus);
    },
    beforeSend: function(xhr) {
      xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
    }
  });

  update_cart_count();
}

function update_cart_count()
{
  // Initialize
  let get_cookie = '';
  let cookie_products = [];
  let new_count = 0;
  let cookie_cart = 'cart_data';
    
  // Check if cookie exists
  get_cookie = getCookie(cookie_cart);

  // If cookie exists.  Check products
  if(get_cookie) {
    // Parse JSON data into readable array
    cookie_products = JSON.parse(get_cookie);

    // Count the number of products in the cookie
    new_count = cookie_products.length;

    // console.log("New Cart Count: " + new_count);

    // Update the cart counter
    $("#count_cart").html(new_count);
  }
  else {
    update_cart();
  }
}

function update_cart_count_override(count)
{
  $("#count_cart").html(count);
}

function update_cart()
{
  // console.log("jwt: " + jwt);
  
  if(jwt != "") {
    // console.log("user is logged in.  ajax request count.");
  }
  else {
    // console.log("user is not logged in.");
  }
  
  // let jwt = $("[name='atoken']").attr('content');
  
  let data = {};
  data.token = jwt;
  
  $.ajax({
    type: "POST",
    url: baseUrl + '/api/cart/fetch',
    data: data,
    dataType: "json",
    success: function(json) {
      // console.log(json);
      setCookie('cart_data',JSON.stringify(json.cartProducts),'1');
      update_cart_count();
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      // console.log(textStatus);
    },
    beforeSend: function(xhr) {
      xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
    }
  });
}

function delete_cart_item(guid, toRemove)
{
  // console.log('deleting item '+ toRemove);
  // console.log('JWT: '+ jwt);
  var itemDeleted = false;

  if(jwt != "" && guid != 0) {
    let data = {};
    data.pid = toRemove;
    data.guid = guid;
    
    $.ajax({
      type: "POST",
      url: baseUrl + '/api/cart/delete_cart_item',
      data: data,
      dataType: "json",
      success: function(json) {
        // console.log(json);
        // console.log("guid: "+guid);
        // update_cart_summary(guid);
        $("tr.pid-"+toRemove).fadeOut(300, function() { 
          $(this).remove();
          itemDeleted = true;
        });

        if(itemDeleted == true) {
          update_cart_summary(guid, toRemove);
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        console.log(textStatus);
      },
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
      }
    });
  }
  // else {
    // console.log("User not logged in");

    let get_cookie = getCookie('cart_data');

    // console.log(get_cookie);

    if(get_cookie) {
      // Parse JSON data into readable array
      cookie_products = JSON.parse(get_cookie);
  
      // console.log(cookie_products);

      for(var i = 0; i < cookie_products.length; i++) {
        // console.log(cookie_products[i]['pid']);
        if(cookie_products[i]['pid'] == toRemove) {
          cookie_products.splice(i, 1);
          $("tr.pid-"+toRemove).hide('slow', function(){ 
            $("tr.pid-"+toRemove).remove(); 
          });
          itemDeleted = true;
        }
      }

      // console.log(cookie_products);
      setCookie('cart_data',JSON.stringify(cookie_products),'1');
    }
  // }

  console.log("guid: "+guid);
  console.log("itemDeleted: "+itemDeleted);
  if(itemDeleted == true && jwt == "") {
    update_cart_summary(guid, toRemove);
  }
  
  update_cart_count();
  
  // If there are no more items in the cookie, redirect back to cart.
  if(cookie_products.length == 0) {
    // Timeout for 2 seconds before reloading the page
    setTimeout(function() {
      // window.location.replace(baseUrl + '/cart');
      window.location.reload();
    }, 2000);
  }

  // return cookie_products.length;
}

function update_cart_summary(guid, toRemove = 0)
{
  console.log("updating cart summary");
  console.log("toRemove: " + toRemove);

  if(guid != 0) {
    let data = {};
    data.guid = guid;
  
    $(".cart-summary").hide();
    $(".spinner-wrap").removeClass('d-none').show();
  
    $.ajax({
      type: "POST",
      url: baseUrl + '/api/cart/update_cart_summary',
      data: data,
      dataType: "json",
      success: function(json) {
        console.log(json);
        if(json.order_costs.item_count >= 1) {
          $(".cart-summary .cart-item-count").html(json.order_costs.item_count + " item");
        }
        else {
          $(".cart-summary .cart-item-count").html(json.order_costs.item_count + " items");
        }
        
        $(".cart-summary .subtotal-cost").html("$"+json.order_costs.subtotal);
        $(".cart-summary .tax-cost").html("$"+json.order_costs.tax);
        
        if(json.order_costs.service_charge > 0) {
          // console.log("service charge cost: " + json.order_costs.service_charge);
          $(".cart-summary .service-charge-cost").html("$"+json.order_costs.service_charge.toFixed(2));
          $(".cart-summary .service-charge").removeClass('d-none');
        }
        else {
          $(".cart-summary .service-charge").addClass('d-none');
        }
        
        $(".cart-summary .total-cost").html("$"+json.order_costs.total);
        $(".cart-summary").show();
        $(".spinner-wrap").hide().addClass('d-none');

        if(json.order_costs.subtotal < 25) {
          $(".subtotal_below_min").removeClass("d-none");
          $(".checkout-btn").prop("disabled", true);
        }
        else if(json.order_costs.subtotal >= 25) {
          $(".service-charge").removeClass("d-none");
          
          if(json.order_costs.subtotal < 50) {
            let subtotal_short_amount = 50 - json.order_costs.subtotal;
            $(".subtotal_short_amount").html(subtotal_short_amount.toFixed(2));
            $(".subtotal_short_alert").removeClass("d-none");
            $(".subtotal_below_min").addClass("d-none");
            $(".checkout-btn").prop("disabled", false);
          }
          else {
            $(".subtotal_short_alert").addClass("d-none");
            $(".subtotal_below_min").addClass("d-none");
            $(".checkout-btn").prop("disabled", false);
          }
        }
        else {
          $(".service-charge").addClass("d-none");
          $(".subtotal_short_alert").addClass("d-none");
          $(".subtotal_below_min").addClass("d-none");
          $(".checkout-btn").prop("disabled", false);
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        // console.log(textStatus);
      },
      beforeSend: function(xhr) {
        xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
      }
    });
  }
  else {
    if(toRemove != 0) {
      let new_subtotal = 0;
      let new_total = 0;
      let new_item_count = 0;
      
      $("input.product-qty").each(function() {
        if($(this).data('pid') != toRemove) {
          new_subtotal += $(this).data('unit-price') * $(this).val();
          new_item_count++;
        }
        
        // if(toRemove == $(this).data('pid')) {
        //   console.log("To Remove: "+$(this).data('pid'));
        // }
        // else {
        //   console.log($(this).data('pid'));
        // }
      });

      let new_tax = new_subtotal.toFixed(2) * (tax_rate - 1);

      if(new_subtotal < 25) {
        $(".subtotal_below_min").removeClass("d-none");
        $(".checkout-btn").prop("disabled", true);
      }
      else if(new_subtotal >= 25 && new_subtotal < 50) {
        new_total = (new_subtotal.toFixed(2) * tax_rate) + service_charge;
        $(".service-charge").removeClass("d-none");
        
        if(new_subtotal >= 25 && new_subtotal < 50) {
          let subtotal_short_amount = 50 - new_subtotal;
          $(".subtotal_short_amount").html(subtotal_short_amount.toFixed(2));
          $(".subtotal_short_alert").removeClass("d-none");
          $(".subtotal_below_min").addClass("d-none");
        }
        else {
          $(".subtotal_short_alert").addClass("d-none");
          $(".subtotal_below_min").addClass("d-none");
        }
      }
      else {
        new_total = new_subtotal.toFixed(2) * tax_rate;
        $(".service-charge").addClass("d-none");
        $(".subtotal_short_alert").addClass("d-none");
        $(".subtotal_below_min").addClass("d-none");
      }

      if(new_item_count > 1) {
        $("#cart-checkout .cart-item-count").html(new_item_count + " items");
      }
      else {
        $("#cart-checkout .cart-item-count").html(new_item_count + " item");
      }

      $("#cart-checkout .subtotal-cost").html("$"+new_subtotal.toFixed(2));
      $("#cart-checkout .tax-cost").html("$"+new_tax.toFixed(2));
      $("#cart-checkout .total-cost").html("$"+new_total.toFixed(2));
      
    }

    // let get_cookie = getCookie('cart_data');

    // // console.log(get_cookie);

    // if(get_cookie) {
    //   // Parse JSON data into readable array
    //   cookie_products = JSON.parse(get_cookie);

    //   // console.log(cookie_products);

    //   for(var i = 0; i < cookie_products.length; i++) {
    //     console.log(cookie_products[i]);
    //     // console.log(cookie_products[i]['pid']);
    //     // if(cookie_products[i]['pid'] == toRemove) {
    //     //   cookie_products.splice(i, 1);
    //     //   $("tr.pid-"+toRemove).hide('slow', function(){ $("tr.pid-"+toRemove).remove(); });
    //     // }
    //   }
    // }
  }
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