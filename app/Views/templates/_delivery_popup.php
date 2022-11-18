<div class="modal fade" id="delivery-modal" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md text-center" role="document">
    <div class="modal-content-datepicker">
      <div class="modal-body p-0">
        <div class="card card-plain">
          <div class="card-header pb-0 text-left">
            <h5 class="">Pick a Delivery Schedule</h5>
          </div>
          <div class="card-body">
            <form role="form text-left">
              <input style="color: white;" type="text" id="inline_picker" placeholder="delivery schedule" name="delivery_schedule" class="form-control datetime_picker">
              <div class="row">
                <div class="col-12">Pick a 3-hour window for your delivery</div>
              </div>
              <div class="row">
                <div class="col-12">
                  <!-- <label class="form-label">From</label> -->
                  <div class="input-group input-group-dynamic">
                    <select name="time_window" class="form-control w-100 border px-2" id="time_window">
                      <option value="1000-1300">10:00 am to 01:00 pm</option>
                      <option value="1030-1330">10:30 am to 01:30 pm</option>
                      <option value="1100-1400">11:00 am to 02:00 pm</option>
                      <option value="1130-1430">11:30 am to 02:30 pm</option>
                      <option value="1200-1500">12:00 pm to 03:00 pm</option>
                      <option value="1230-1530">12:30 pm to 03:30 pm</option>
                      <option value="1300-1600">01:00 pm to 04:00 pm</option>
                      <option value="1330-1630">01:30 pm to 04:30 pm</option>
                      <option value="1400-1700">02:00 pm to 05:00 pm</option>
                      <option value="1430-1730">02:30 pm to 05:30 pm</option>
                      <option value="1500-1800">03:00 pm to 06:00 pm</option>
                      <option value="1530-1830">03:30 pm to 06:30 pm</option>
                      <option value="1600-1900">04:00 pm to 07:00 pm</option>
                      <option value="1630-1930">04:30 pm to 07:30 pm</option>
                      <option value="1700-2000">05:00 pm to 08:00 pm</option>
                      <option value="1730-2030">05:30 pm to 08:30 pm</option>
                      <option value="1800-2100">06:00 pm to 09:00 pm</option>
                      <option value="1830-2130">06:30 pm to 09:30 pm</option>
                    </select>
                  </div>
                </div>
                <!-- <div class="col-6">
                  <label class="form-label">To</label>
                  <div class="input-group input-group-dynamic">
                    <select name="to" class="form-control w-100 border px-2" id="to">
                      <option value="1000">10:00</option>
                      <option value="1030">10:30</option>
                    </select>
                  </div>
                </div> -->
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link  ml-auto" data-bs-dismiss="modal">Later, let me browse products for now</button>
        <button type="button" class="btn save-delivery-schedule bg-gradient-primary">Save Date</button>
      </div>
    </div>
  </div>
</div>