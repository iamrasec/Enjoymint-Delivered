<div class="modal fade" id="loginRegisterModal" tabindex="-1" role="dialog" aria-labelledby="loginRegisterModal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div class="card card-plain">
          <div class="card-header pb-0 text-left">
            <h5 class="">Welcome back</h5>
            <p class="mb-0">Enter your email and password to sign in</p>
          </div>
          <div class="card-body">
            <form role="form text-left">
              <div class="input-group input-group-outline my-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
              </div>
              <div class="input-group input-group-outline my-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
              </div>
              <!-- <div class="form-check form-switch d-flex align-items-center">
                <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
              </div> -->
              <div class="text-center">
                <button type="button" class="btn btn-primary bg-primary-green btn-lg w-100 mt-4 mb-0">Sign in</button>
              </div>
            </form>
          </div>
          <div class="card-footer text-center pt-0 px-lg-2 px-1">
            <p class="mb-4 text-sm mx-auto">
              Don't have an account?
              <a href="<?= base_url('users/register'); ?>" class="text-primary text-gradient font-weight-bold">Sign up</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>