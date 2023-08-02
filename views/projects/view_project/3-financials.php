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
                        <div class="input-group input-group-solid mb-5">
                            <span class="input-group-text"><?= $defaultcurrency; ?></span>
                            <input type="number" name="income" class="form-control form-control-lg form-control-solid" value="<?= $income; ?>" />
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Total Expense (update at any time)</label>
                    <div class="col-lg-8 fv-row">
                        <div class="input-group input-group-solid mb-5">
                            <span class="input-group-text"><?= $defaultcurrency; ?></span>
                            <input type="number" name="expense" class="form-control form-control-lg form-control-solid" value="<?= $expense; ?>" />
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Profit/Loss (calculated automatically)</label>
                    <div class="col-lg-8 fv-row">
                        <div class="input-group input-group-solid mb-5">
                            <span class="input-group-text"><?= $defaultcurrency; ?></span>
                            <input type="number" name="profit_loss" class="form-control form-control-lg form-control-solid" value="<?= $profit_loss; ?>" disabled />
                        </div>
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
                <button type="submit" class="btn btn-primary" id="project_finance_submit" onClick="updFinance();">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updFinance(){
        var pid = '<?= $_GET['pid']; ?>';
        var tid = '<?= $loguserid; ?>';
        var web = '<?= $c_website; ?>';
        
        var formID = "#project_financials_form";
        var submitButton = document.querySelector('#project_finance_submit');
        var type = "PATCH";
        var url = web+"controllers/projects.php?tailor="+tid+"&pid="+pid;
        
        AJAXcall(formID, submitButton, type, url, null, (responseMsg)=>{handleResponseMsg(responseMsg, 'reload');});
    }
</script>