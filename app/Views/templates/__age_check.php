<style>
.modal-backdrop {
  opacity: 0.75 !important;
}

#ageModal .modal-body {
  position: relative;
  -webkit-box-flex: 1;
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  padding: 5rem;
  background: url('<?= base_url('assets/img/age-check/cannabis.jpg'); ?>') center center no-repeat;
  background-size: cover;
  border: 1px solid #c8b328;
  text-align: center;
}

#ageModal .age-check-container {
  background: rgba(255, 255, 255, 0.5);
  border: 1px solid #000000;
  border-radius: 10px;
  width: auto;
}

#ageModal .form-control {
  color: #000000;
  border: 1px solid #000000 !important;
  background: #ffffff !important;
}

#ageModal .btn {
  border-radius: 5px !important;
  color: #ffffff;
  border: 2px solid #063c23 !important;
  background: #198754;
  padding: .5rem 3rem !important;
}

#ageModal .btn:hover {
  background: #3bbd80;
  color:  #ffffff;
  border: 2px solid #063c23 !important;
  padding: .5rem 3rem !important;
}
@media (max-width:768px) {
  #ageModal .mobile-margin-bottom{
    margin-bottom:.5rem;
  }
  
}
</style>

<!-- Age Verification Modal -->
<div class="modal fade" id="ageModal" role="dialog" aria-labelledby="gridSystemModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
    <div class="modal-content">
      <div class="modal-body d-flex justify-content-center align-content-center w-100">
        <div class="row d-flex justify-content-center align-content-center align-self-center w-45">
          <div class="col-4 col-sm-12 col-lg-4 age-check-container d-flex px-5 py-5 w-auto">
            <div class="row ">
              <div class="col-xs-12 col-sm-12 mx-auto" id="modal-age-limit" style="margin-bottom: 2.5rem; color: #fff;">
              <img src="/assets/img/Enjoymint-Logo-Landscape-White-2.png" class="logo" />
              </div>
                <div class="card mb-3">
                    <div class="form-check form-check-info text-left">
                      <input class="form-check-input" type="checkbox" value="1" name="age" id="age_check" >
                      <label class="form-check-label" for="flexCheckDefault">
                        <a href="javascript:;" class="text-dark font-weight-bolder" style="font-size: medium;">Are you 21 years old?</a>
                        <small class="d-block fs-xs fst-italic ms-2">You must be 21 to buy cannabis</small>
                      </label>
                    </div>
                </div>  
                                                                        
            <div class="card">
              <div class="form-check form-check-info text-left">
                      <input class="form-check-input" type="checkbox" value="1" name="accept_terms" id="accept_terms" >
                      <label class="form-check-label" for="flexCheckDefault">
                         <a href="javascript:;" class="text-dark font-weight-bolder" style="font-size: medium;">I agree the Terms of Agreement</a>
                         <small class="d-block fs-xs fst-italic ms-2">By continuing , you agree to our <a href="<?php base_url('terms'); ?>" style="text-decoration: underline;">Term of Services</a> and <a href="<?php base_url('privacy'); ?>" style="text-decoration: underline;">Privacy Policy </a></small>
                      </label>
                  </div>
            </div>  
             
              <div class="col-xs-12 col-lg-12" id="age-submit-area" style="margin-top: 2rem;
              color: #fff;">
              <a href="#" class="btn btn-default" id="age-submit">SUBMIT</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
