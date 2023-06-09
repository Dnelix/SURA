<div class="card mb-5 mb-xl-10" id="financials">
    <div class="card-header border-0" aria-expanded="true" aria-controls="project_financials">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Financials <span class="fw-light text-muted"> : Let's help you track all your earnings</span></h3>
        </div>
    </div>
    <div id="project_financials_details">
        <form id="project_financials_form" class="form">
            <div class="card-body border-top p-9">

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Total Charge (modify at any time)</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="charge" class="form-control form-control-lg form-control-solid" value="<?= $defaultcurrency.' '.$income; ?>" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Total Expense (update at any time)</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="expense" class="form-control form-control-lg form-control-solid" value="<?= $defaultcurrency.' '.$expense; ?>" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Profit/Loss (calculated automatically)</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="earn" class="form-control form-control-lg form-control-solid" value="<?= $defaultcurrency.' '.$profit_loss; ?>" disabled />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Notes (optional)</label>
                    <div class="col-lg-8 fv-row">
                        <input type="text" name="notes" class="form-control form-control-lg form-control-solid" value="<?= $notes; ?>" placeholder="Important client" />
                    </div>
                </div>

            </div>
            
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>