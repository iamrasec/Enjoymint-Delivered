<div class="container-fluid bg-dark home-contact-form-wrap">
  <div class="container">
    <div class="home-contact-form">
      <div class="row">
        <div class="col-12 col-md-6">
          <h3>Got Questions?</h3>
          <div class="sub-head">Call <a href="tel:4084844644">(408) 484-4644</a></div>
          <p>If you have any questions or concerns, please don't hesitate to call the number above.<br>
            One of our professional staff will gladly attend to any inquiries.<br>
            Thank you and welcome to Enjoymint Delivered!</p>
        </div>
        <div class="col-12 col-md-6">
          <form id="contact_form" method="post" action="<?php echo base_url('api/contact/save'); ?>">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group input-group-dynamic mb-4">
                    <label class="form-label">First Name</label>
                    <input class="form-control" aria-label="First Name" name="first_name" type="text" >
                  </div>
                </div>
                <div class="col-md-6 ps-2">
                  <div class="input-group input-group-dynamic">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" placeholder="" name="last_name" aria-label="Last Name" >
                  </div>
                </div>
              </div>
              <div class="mb-4">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Phone Number</label>
                  <input type="text" class="form-control" placeholder="" name="phone_number" aria-label="Phone Number" >
                </div>
              </div>
              <div class="mb-4">
                <div class="input-group input-group-dynamic">
                  <label class="form-label">Email Address</label>
                  <input type="email" class="form-control" name="email">
                </div>
              </div>
              <div class="input-group mb-4 input-group-static">
                <label>Your message</label>
                <textarea name="message" class="form-control" id="message" name="message" rows="4"></textarea>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="submit" class="btn bg-gradient-dark w-100">Send Message</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>