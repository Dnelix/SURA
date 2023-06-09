    <div class="modal fade" id="modal_new_customer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="las la-times fs-2x"></i>
                    </div>
                </div>
                
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form id="modal_new_customer_form" class="form" action="#">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Add a Customer</h1>
                            <div class="text-muted fw-bold fs-5">Add the customer details below. It's that simple.</div>
                        </div>
                        
                        <div class="d-flex flex-column mb-8 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2 required"> Full Name</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter customer name" name="fullname" />
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2"> Phone Number</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter phone number" name="phone" />
                        </div>
                        <div class="d-flex flex-column mb-10 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2"> Email Address</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Enter customer email" name="email" />
                        </div>
                        
                        <div class="text-center">
                            <button type="reset" id="modal_new_customer_cancel" class="btn btn-light me-3">Cancel</button>
                            <button type="submit" id="modal_new_customer_submit" class="btn btn-primary">
                                <?= displayLoadingIcon('Add Customer'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>