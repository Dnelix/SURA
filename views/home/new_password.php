<div class="flex-row-fluid d-flex flex-center justfiy-content-xl-first p-10">
        <div class="d-flex flex-center p-15 shadow-sm bg-body rounded w-100 w-md-550px mx-auto ms-xl-20">

          <form class="form" novalidate="novalidate" id="kt_new_password_form" data-kt-redirect-url="?login">
            <div class="text-center mb-10">
              <h1 class="text-dark mb-3" id="logtext">Set up a new password</h1>
              <div class="text-gray-400 fw-bold fs-4">Try something memorable that you can remember</div>
            </div>

            <!--begin::password group-->
            <div class="mb-10 fv-row" data-kt-password-meter="true">
                <div class="mb-1">
                    <label class="form-label fw-bolder text-dark fs-6">New Password</label>
                    
                    <div class="position-relative mb-3">
                        <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="password" autocomplete="off" />
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                            <i class="bi bi-eye-slash fs-2"></i>
                            <i class="bi bi-eye fs-2 d-none"></i>
                        </span>
                    </div>

                    <!--begin::Meter-->
                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                        <div class="flex-grow-1 bg-secondary bg-active-danger rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-warning rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                    </div>
                    <!--end::Meter-->
                </div>
                
                <div class="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
            </div>
            <!--end::password group=-->
            
            <div class="fv-row mb-5">
                <label class="form-label fw-bolder text-dark fs-6">Confirm Password</label>
                <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm-password" autocomplete="off" />
            </div>
            
            <div class="fv-row mb-10">
                <label class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="checkbox" name="toc" value="1" />
                    <span class="form-check-label fw-bold text-gray-700 fs-6">I Agree with <?= $company.'\'s'; ?> 
                    <a href="<?= $terms_link; ?>" class="ms-1 link-primary"> Terms and conditions</a>.</span>
                </label>
            </div>
            
            <div class="text-center pb-lg-0 pb-8">
              <button type="submit" id="kt_new_password_submit" class="btn btn-lg btn-primary w-100 mb-5">
                <span class="indicator-label">Reset My pasword</span>
                <span class="indicator-progress">Please wait... 
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
              </button>
            </div>
          </form>
        </div>
      </div>