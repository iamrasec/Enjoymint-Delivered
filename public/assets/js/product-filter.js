console.log('Product Filter Online');

(function($) {
  $(document).ready(function() {
    $( "#price-slider" ).slider({
      range: true,
      min: 0,
      max: 300,
      values: [ $( "#min_price" ).val(), $( "#max_price" ).val() ],
      slide: function( event, ui ) {
        $( "#min_price" ).val( ui.values[ 0 ]);
        $( "#max_price" ).val( ui.values[ 1 ] );
        $( ".price-range-display" ).text("Price Range: $" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
      }
    });

    $( "#thc-slider" ).slider({
      range: true,
      min: 0,
      max: 100,
      values: [ $( "#min_thc" ).val(), $( "#max_thc" ).val() ],
      slide: function( event, ui ) {
        $( "#min_thc" ).val( ui.values[ 0 ]);
        $( "#max_thc" ).val( ui.values[ 1 ] );
        $( ".thc-range-display" ).text("THC % Value: " + ui.values[ 0 ] + "% - " + ui.values[ 1 ] + "%");
      }
    });

    $( "#cbd-slider" ).slider({
      range: true,
      min: 0,
      max: 100,
      values: [ $( "#min_cbd" ).val(), $( "#max_cbd" ).val() ],
      slide: function( event, ui ) {
        $( "#min_cbd" ).val( ui.values[ 0 ]);
        $( "#max_cbd" ).val( ui.values[ 1 ] );
        $( ".cbd-range-display" ).text("CBD % Value: " + ui.values[ 0 ] + "% - " + ui.values[ 1 ] + "%");
      }
    });
  });
})(jQuery);