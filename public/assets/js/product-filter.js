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

    // const filter_availability = 0;

    // $(document).on('change', '#availability_filter', function() {
    //   // console.log($(this).val());

    //   filter_availability = $(this).val();
    // });

    $(document).on('click', '#searchFormSubmit', function(e) {
      e.preventDefault();

      $('.waiting-large').removeClass('d-none');
      $('.loading-overlay').removeClass('d-none');

      let data = {};
      data.availability = $('#availability option:selected').val();
      data.category = $('#category option:selected').val();
      data.strain = $('#strain option:selected').val();
      data.brands = $('#brands option:selected').val();
      data.min_price = $('#min_price').val();
      data.max_price = $('#max_price').val();
      data.min_thc = $('#min_thc').val();
      data.max_thc = $('#max_thc').val();
      data.min_cbd = $('#min_cbd').val();
      data.max_cbd = $('#max_cbd').val();

      let delivery_cookie = getCookie("delivery_schedule");

      $.ajax({
        type: "POST",
        url: baseUrl + '/shop?' + $.param(data),
        data: data,
        dataType: "json",
        success: function(json) {
          console.log(json);
          console.log($.param(data));

          $("#products-section").html(json.data);
          window.history.replaceState(null, null, "?"+$.param(data));

          if(delivery_cookie && json.fast_tracked == false) {
            let delsched = JSON.parse(delivery_cookie);
            let delTime = delsched.t.split("-");
            let delFrom = tConvert(delTime[0]);
            let delTo = tConvert(delTime[1]);
            
            $("input.datetime_picker").val(delsched.d + " @ " + delFrom + " - " + delTo);
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log(textStatus);
        },
        beforeSend: function(xhr) {
          xhr.setRequestHeader("Authorization", 'Bearer '+ jwt);
        }
      });

      return false;
    });
    
  });

  $.support.touch = 'ontouchend' in document;

  // Ignore browsers without touch support
  if (!$.support.touch) {
    return;
  }

  var mouseProto = $.ui.mouse.prototype,
      _mouseInit = mouseProto._mouseInit,
      _mouseDestroy = mouseProto._mouseDestroy,
      touchHandled;

  /**
   * Simulate a mouse event based on a corresponding touch event
   * @param {Object} event A touch event
   * @param {String} simulatedType The corresponding mouse event
   */
  function simulateMouseEvent (event, simulatedType) {

    // Ignore multi-touch events
    if (event.originalEvent.touches.length > 1) {
      return;
    }

    event.preventDefault();

    var touch = event.originalEvent.changedTouches[0],
        simulatedEvent = document.createEvent('MouseEvents');
    
    // Initialize the simulated mouse event using the touch event's coordinates
    simulatedEvent.initMouseEvent(
      simulatedType,    // type
      true,             // bubbles                    
      true,             // cancelable                 
      window,           // view                       
      1,                // detail                     
      touch.screenX,    // screenX                    
      touch.screenY,    // screenY                    
      touch.clientX,    // clientX                    
      touch.clientY,    // clientY                    
      false,            // ctrlKey                    
      false,            // altKey                     
      false,            // shiftKey                   
      false,            // metaKey                    
      0,                // button                     
      null              // relatedTarget              
    );

    // Dispatch the simulated event to the target element
    event.target.dispatchEvent(simulatedEvent);
  }

  /**
   * Handle the jQuery UI widget's touchstart events
   * @param {Object} event The widget element's touchstart event
   */
  mouseProto._touchStart = function (event) {

    var self = this;

    // Ignore the event if another widget is already being handled
    if (touchHandled || !self._mouseCapture(event.originalEvent.changedTouches[0])) {
      return;
    }

    // Set the flag to prevent other widgets from inheriting the touch event
    touchHandled = true;

    // Track movement to determine if interaction was a click
    self._touchMoved = false;

    // Simulate the mouseover event
    simulateMouseEvent(event, 'mouseover');

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');

    // Simulate the mousedown event
    simulateMouseEvent(event, 'mousedown');
  };

  /**
   * Handle the jQuery UI widget's touchmove events
   * @param {Object} event The document's touchmove event
   */
  mouseProto._touchMove = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Interaction was not a click
    this._touchMoved = true;

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');
  };

  /**
   * Handle the jQuery UI widget's touchend events
   * @param {Object} event The document's touchend event
   */
  mouseProto._touchEnd = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Simulate the mouseup event
    simulateMouseEvent(event, 'mouseup');

    // Simulate the mouseout event
    simulateMouseEvent(event, 'mouseout');

    // If the touch interaction did not move, it should trigger a click
    if (!this._touchMoved) {

      // Simulate the click event
      simulateMouseEvent(event, 'click');
    }

    // Unset the flag to allow other widgets to inherit the touch event
    touchHandled = false;
  };

  /**
   * A duck punch of the $.ui.mouse _mouseInit method to support touch events.
   * This method extends the widget with bound touch event handlers that
   * translate touch events to mouse events and pass them to the widget's
   * original mouse event handling methods.
   */
  mouseProto._mouseInit = function () {
    
    var self = this;

    // Delegate the touch handlers to the widget's element
    self.element.bind({
      touchstart: $.proxy(self, '_touchStart'),
      touchmove: $.proxy(self, '_touchMove'),
      touchend: $.proxy(self, '_touchEnd')
    });

    // Call the original $.ui.mouse init method
    _mouseInit.call(self);
  };

  /**
   * Remove the touch event handlers
   */
  mouseProto._mouseDestroy = function () {
    
    var self = this;

    // Delegate the touch handlers to the widget's element
    self.element.unbind({
      touchstart: $.proxy(self, '_touchStart'),
      touchmove: $.proxy(self, '_touchMove'),
      touchend: $.proxy(self, '_touchEnd')
    });

    // Call the original $.ui.mouse destroy method
    _mouseDestroy.call(self);
  };
  !function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}
})(jQuery);