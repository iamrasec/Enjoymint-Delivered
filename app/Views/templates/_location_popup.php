<?php $this->section("content") ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<style>
    /* Define the checked style */
    .btn {
      padding: 10px;
      border: none;
      cursor: pointer;
      position: relative;
    }
    .btn-default {
      background-color: #f8f8f8;
      color: #333;
    }
    .btn-checked {
      background-color: #333;
      color: #fff;
      border: solid 3px;
    }
    .btn-badge {
      position: absolute;
      top: 5px;
      right: 5px;
      background-color: #f00;
      color: #fff;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
    }
  </style>
<div class="modal fade" id="location-modal" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md text-center" role="document">
    <div class="modal-content-datepicker">
      <div class="modal-body p-0">
        <div class="card card-plain">
        <form method='post' action="<?= base_url('/shop/location')?>" id="locationForm">
        <div class="card-header pb-0 text-left">
            <h5 class="">Delivery Scheduled</h5>
            <input type="hidden" id="delivery" name="delivery">
            <button type="button" id="btn1" class="btn btn-default" value="same_day" style="border:solid 2px;">Scheduled same day</button>
            <button type="button" id="btn2" class="btn btn-default" value="next_day" style="border:solid 2px;">Scheduled next day</button>
          </div>
          <div class="card-header pb-0 text-left">
            <h5 class="">Enter your Location</h5>
          </div>
          <div class="card-body">
          
              <div class="row">
                <div class="col-12">
                <p class="text-danger restrict">
                <?php if (session()->getFlashdata('message') !== NULL) : ?>
                   
                        <?php echo session()->getFlashdata('message'); ?>
                   
              <?php endif; ?>
              </p>
                  <?php if($location_keyword == null): ?>
                    <div id="location">                                     
                    <input type="text"  id="searchLocation" required name="location" class="form-control" placeholder="Enter Location...">             
                    </div>
                    <?php else: ?>
                    <div id="location">                                     
                    <input type="text" value="<?= $location_keyword['address'] ;?>"  id="searchLocation" required name="location" class="form-control" placeholder="Enter Location...">             
                    </div>
                   <?php endif; ?>

                </div>
              </div>
          </div>                       
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link  ml-auto" data-bs-dismiss="modal">Later, let me browse products for now</button>
        <?php if($uid == null) : ?>
        <button type="submit"   class="btn bg-gradient-primary shop-btn" disabled>Shop</button>
        <?php else: ?>
        <button type="submit"   class="btn bg-gradient-primary shop-btn" >Shop</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
</form>
<style>

    #searchLocation{
    border: 1px solid gray;
    margin-left: -13%;
    border-radius: 2px;
    position: absolute;
    width: 80%;
    margin-top: -10px;
    }

    .modal-footer{
      margin-top: 20px;
    }

    #location{
      padding-left: 20%;
    }

    .restrict{
    margin-top: -30px;
    margin-left: 6%;
    font-weight: normal;
    float: left;
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
  
    // Get the buttons by their IDs
    const btn1 = document.getElementById('btn1');
    const btn2 = document.getElementById('btn2');

   
    console.log('<?php echo $location_delivery['delivery_schedule']; ?>');
    // Add click event listeners to the buttons
    btn1.addEventListener('click', function() {
      event.preventDefault();
      // Toggle checked style on button 1
      btn1.classList.toggle('btn-checked');
      // Remove checked style from button 2
      btn2.classList.remove('btn-checked');
      $('#delivery').val('same_day');
     
    });
    <?php if($location_delivery['delivery_schedule'] == 'same day') {?>
    btn1.classList.toggle('btn-checked');
    btn2.classList.remove('btn-checked');
    <?php }if($location_delivery['delivery_schedule'] == 'next day'){?>
    btn2.classList.toggle('btn-checked');
    btn1.classList.remove('btn-checked');
    <?php }?>
    
    btn2.addEventListener('click', function() {
      event.preventDefault();
      // Toggle checked style on button 2
      btn2.classList.toggle('btn-checked');
      // Remove checked style from button 1
      btn1.classList.remove('btn-checked');
      $('#delivery').val('next_day');
      
    });
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

    $(document).on("click", ".shop-btn", function(e) {
    

    // console.log($("input[name=guid]").val());
    // console.log(sched);

    if($("input[name=guid]").val() == 0) {
      $("#loginRegisterModal").modal('show');
    }
    else {
    //   const fd = new FormData();
    //   fd.append('delivery_schedule', sched);
    //   fetch('<?= base_url('cart/checkout'); ?>',{
    //     method: 'POST',
    //     body: fd
    //   })
    //   .then()
    //   .catch((error) => {
    //     console.log('Error:', error);
    // });

      // window.location.replace("<?= base_url('cart/checkout/'); ?>");
      $("#locationForm").submit();
    }
  });
 </script>
<?php $this->endSection() ?>