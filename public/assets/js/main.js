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