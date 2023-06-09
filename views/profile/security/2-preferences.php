<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_email_preferences" aria-expanded="true" aria-controls="kt_account_email_preferences">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Email Preferences</h3>
        </div>
    </div>
    <div id="kt_account_settings_email_preferences" class="collapse show">
        <form class="form">
            <div class="card-body border-top px-9 py-9">
                <label class="form-check form-check-custom form-check-solid align-items-start">
                    <input class="form-check-input me-3" type="checkbox" name="email-preferences[]" value="1" />
                    <span class="form-check-label d-flex flex-column align-items-start">
                        <span class="fw-bolder fs-5 mb-0">Successful Payments</span>
                        <span class="text-muted fs-6">Receive a notification for every successful payment.</span>
                    </span>
                </label>

                <div class="separator separator-dashed my-6"></div>
                
                <label class="form-check form-check-custom form-check-solid align-items-start">
                    <input class="form-check-input me-3" type="checkbox" name="email-preferences[]" checked="checked" value="1" />
                    <span class="form-check-label d-flex flex-column align-items-start">
                        <span class="fw-bolder fs-5 mb-0">Payouts</span>
                        <span class="text-muted fs-6">Receive a notification for every initiated payout.</span>
                    </span>
                </label>

                <div class="separator separator-dashed my-6"></div>
                
                <label class="form-check form-check-custom form-check-solid align-items-start">
                    <input class="form-check-input me-3" type="checkbox" name="email-preferences[]" value="1" />
                    <span class="form-check-label d-flex flex-column align-items-start">
                        <span class="fw-bolder fs-5 mb-0">Fee Collection</span>
                        <span class="text-muted fs-6">Receive a notification each time you collect a fee from sales</span>
                    </span>
                </label>
                
                <div class="separator separator-dashed my-6"></div>
                
                <label class="form-check form-check-custom form-check-solid align-items-start">
                    <input class="form-check-input me-3" type="checkbox" name="email-preferences[]" checked="checked" value="1" />
                    <span class="form-check-label d-flex flex-column align-items-start">
                        <span class="fw-bolder fs-5 mb-0">Customer Payment Dispute</span>
                        <span class="text-muted fs-6">Receive a notification if a payment is disputed by a customer and for dispute purposes.</span>
                    </span>
                </label>
                
                <div class="separator separator-dashed my-6"></div>
                
                <label class="form-check form-check-custom form-check-solid align-items-start">
                    <input class="form-check-input me-3" type="checkbox" name="email-preferences[]" value="1" />
                    <span class="form-check-label d-flex flex-column align-items-start">
                        <span class="fw-bolder fs-5 mb-0">Refund Alerts</span>
                        <span class="text-muted fs-6">Receive a notification if a payment is stated as risk by the Finance Department.</span>
                    </span>
                </label>
                
                <div class="separator separator-dashed my-6"></div>
                
                <label class="form-check form-check-custom form-check-solid align-items-start">
                    <input class="form-check-input me-3" type="checkbox" name="email-preferences[]" checked="checked" value="1" />
                    <span class="form-check-label d-flex flex-column align-items-start">
                        <span class="fw-bolder fs-5 mb-0">Invoice Payments</span>
                        <span class="text-muted fs-6">Receive a notification if a customer sends an incorrect amount to pay their invoice.</span>
                    </span>
                </label>
                
                <div class="separator separator-dashed my-6"></div>
                
                <label class="form-check form-check-custom form-check-solid align-items-start">
                    <input class="form-check-input me-3" type="checkbox" name="email-preferences[]" value="1" />
                    <span class="form-check-label d-flex flex-column align-items-start">
                        <span class="fw-bolder fs-5 mb-0">Webhook API Endpoints</span>
                        <span class="text-muted fs-6">Receive notifications for consistently failing webhook API endpoints.</span>
                    </span>
                </label>
            </div>
            
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button class="btn btn-primary px-6">Save Changes</button>
            </div>
        </form>
    </div>
</div>