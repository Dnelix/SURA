
<div class="card mb-5 mb-xl-10" id="measure_details_edit"> 
    <div class="card-header cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Lower Body Measurements 
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Your lower body measurements include measurements for trousers, skirts, shorts, etc. You can get someone else to take your measurements."></i>
            </h3>
        </div>
    </div>

    <div id="measure_details_edit_body">
        <form id="LB_main_form" class="form" onSubmit="return false">
            <div class="card-body border-top p-9">
                <div class="text-muted mb-7">
                    Check the tips for each field if you're unsure how to measure correctly.
                    All measurements MUST be done in <?= strtoupper($measureunit_text)." (".strToUpper($measureunit).")"; ?>
                </div>
            
                <div class="d-flex flex-column mb-8">
                    <div class="col-md-12">
                        <div class="row fv-row">
                        <?php
                            foreach ($LBmeasures as $data){
                                $metadata = $data->metadata;
                                $value = empty($data->value)?'':$data->value;
                        ?>
                                <div class="col-6 mb-8">
                                    <label class="fs-6 fw-bold form-label">
                                        <?= $metadata->label;  ?> 
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="<?= $metadata->tooltip; ?>"></i>
                                    </label>
                                    <input type="number" name="<?= $metadata->name;  ?>" class="form-control form-control-lg form-control-solid" placeholder="0.0" value="<?= $value;?>" />
                                    <!--div class="form-text fs-8">Range:  <span class="text-primary"><?//= $data->sizes->$bs . $measureunit; ?></span> </div-->
                                </div>

                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Reset values</button>
                <button type="submit" id="LB_main_submit" class="btn btn-primary" onClick="javascript:submitLB()">
                    <?= displayLoadingIcon('Save'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function submitLB(){
        var cid = '<?= $_GET['cid']; ?>';
        var web = '<?= $c_website; ?>';

        var formID = "#LB_main_form";
        var submitButton = document.querySelector('#LB_main_submit');
        var type = "PATCH";
        var url = web+"controllers/measurements.php?customer="+cid;

        AJAXcall(formID, submitButton, type, url, null, (responseMsg)=>{
            if(responseMsg.status !== 'success'){ return false; }
            swal_confirm("DONE! What do you want to do next?", "Save & Exit", "Stay on this page")
            .then((result) => {
                if (result.isConfirmed) {
                    history.back();
                } else if (result.isDenied) {
                    console.log("Canceled");
                }
            })
            .catch((error) => {
                console.error(error);
            });
        });
    }
</script>