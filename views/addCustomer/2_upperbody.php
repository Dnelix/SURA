    <div class="" data-kt-stepper-element="content">
        <div class="w-100">
            <div class="pb-10 pb-lg-15">
                <h2 class="fw-bolder d-flex align-items-center text-dark">Upper Body Measurements 
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Your upper body measurements. These measurements cover for shirts, tops, gowns, jackets etc. The results are usually better when someone else takes the measurements."></i></h2>
                <div class="text-muted fw-bold fs-6"> 
                    Check the tips for each field if you're unsure how to measure correctly.
                    <br/>All measurements MUST be done in <?= strtoupper($measureunit_text)." (".$measureunit.")"; ?>
                </div>
            </div>
            
            <div class="fv-row mb-10">
                <div class="col-md-12 fv-row">
                    <div class="row fv-row">
                        
                    <?php
                        $bs = 's6';
                        $UB = retrieveDataFrom('models/databases/measurement_UB.json');
                        foreach ($UB as $data) {
                    ?>
                            <div class="col-4 mb-10">
                                <label class="fs-6 fw-bold form-label">
                                    <?= $data->label . '(' . $measureunit . ')';  ?> 
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="<?= $data->tooltip;  ?>"></i>
                                </label>
                                <input name="<?= $data->name;  ?>" class="form-control form-control-lg form-control-solid" value="" placeholder="0" />
                                <!--div class="form-text">Range:  $data->sizes->$bs . $measureunit; </div-->
                            </div>

                    <?php
                        }
                    ?>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>