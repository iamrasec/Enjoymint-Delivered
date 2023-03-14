(function($) {
  $(document).on('click', '.dropdown-item', function() {
    // console.log("Variation Selector Clicked!");
    $(".dropdown-item").parent('li').removeClass("d-none");
    $(this).parent('li').addClass("d-none");

    let pid = $(this).data('pid');
    let vid = $(this).data('variation-id');
    let vPrice = $(this).data('price') ;
    let vUnit = $(this).data('unit');
    let vStock = $(this).data('stock');

    $("#variant-selector-" + pid).find('.price').html("$" + vPrice);
    $("#variant-selector-" + pid).find('.unit').html("$" + vUnit);

    // console.log($("#variant-selector-" + vid));

    $(this).closest('.product-info-bottom').find('.low-stock-indicator').html("Only " + vStock + " left!");

    if(vUnit > 5) {
      $(this).closest('.product-info-bottom').find('.low-stock-indicator').addClass('d-none');
    }
    else {
      $(this).closest('.product-info-bottom').find('.low-stock-indicator').removeClass('d-none');
    }

    $(this).closest('.product-info-bottom').find('.add-to-cart').data('vid', vid);

    // console.log($(this).closest('.product-info-bottom').find('.add-to-cart').data('pid'));
    // console.log($(this).closest('.product-info-bottom').find('.add-to-cart').data('vid'));
    // console.log($(this).closest('.product-info-bottom').find('.add-to-cart'));
  });
})(jQuery);