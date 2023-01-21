<?php $this->section("content") ?>
<div class="modal fade" id="location-modal" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md text-center" role="document">
    <div class="modal-content-datepicker">
      <div class="modal-body p-0">
        <div class="card card-plain">
          <div class="card-header pb-0 text-left">
            <h5 class="">Enter your Location</h5>
          </div>
          <div class="card-body">
          <form method='post' action="<?= base_url('/shop/location')?>" id="searchForm">
              <div class="row">
                <div class="col-12">
                <?php foreach( $location_keyword as $location) : ?>
                    <div id="location">                                     
                    <input type="text" value="<?= $location->address ;?>"  id="searchLocation" required name="location" class="form-control" placeholder="Enter Location...">             
                    </div>
                    <?php endforeach; ?>
                </div>
              </div>
          </div>                       
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link  ml-auto" data-bs-dismiss="modal">Later, let me browse products for now</button>
        <button type="submit"   class="btn bg-gradient-primary">Shop</button>
      </div>
    </div>
  </div>
</div>
</form>
<style>

    #searchLocation{
    border: 1px solid gray;
    width: 500px;
    margin-left: 28px;
    border-radius: 2px;
    }

    .pac-container {
    z-index: 1051 !important;
    box-sizing: content-box;   
    padding-right: 5px;
    padding-left: 10px;
    margin-left: -10px;
    margin-top: 11px;
    }
    
    .pac-item {
    font-size:15px;
    }                       

    .pac-item-query {
    font-size:15px;
    }
    
</style>

<?php $this->endSection() ?>

<?php $this->section("scripts") ?>
 <script>
   $('#location').on('click', function(){
    $(".location").click();
  });
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyD18INrxrjZ5J9w9LbBiN68thy_7fZn_jE&callback=initMap" ></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>

  var autocomplete;
        function initMap() {
            var input = document.getElementById('searchLocation');
            autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener("place_changed", fillInAddress);
            
        }
    function fillInAddress(){
        var place = autocomplete.getPlace();
        var street="";
        
        console.log(place);
    }
              
    window.document.addEventListener("DOMContentLoaded", () => {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
        const $section = document.querySelector(`[data-section=${params.section}]`);
        if ($section) {
            const $header = document.getElementById("topnav");
            const sectionRect = $section.getBoundingClientRect();
            const headerRect = $header.getBoundingClientRect();
            window.scroll({top: ((sectionRect.top + window.scrollY) - headerRect.height * 2), behavior: "smooth"});
            $section.classList.add("active-section");
        }
    });
 </script>
<?php $this->endSection() ?>