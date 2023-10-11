<?php
    $start_date = DateTime::createFromFormat($dateformat, $start);
    $end_date = DateTime::createFromFormat($dateformat, $end);
    $remind_on = DateTime::createFromFormat($dateformat, $remind);
?>

<div class="card mb-5 mb-xl-10 d-none" id="project_details_edit">
    <div class="card-header cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Project Details</h3>
        </div>
        <a href="javascript:;" class="btn btn-sm btn-light-dark btn-active-primary align-self-center" onClick="toggleView('#project_details_edit','#project_details_view')">
            Close Editing &nbsp; <i class="fa fa-times"></i>
        </a>
    </div>

    <div id="project_details_edit_body">
        <form id="project_edit_form" class="form">
            <div class="card-body border-top p-9">

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-bold fs-6">Title</label>
                    <div class="col-lg-9 fv-row">
                        <input type="text" name="title" class="form-control form-control-lg form-control-solid" value="<?= $title; ?>" />
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Description</label>
                    <div class="col-lg-9 fv-row">
                        <textarea class="form-control form-control-lg form-control-solid" name="description"><?= $description; ?></textarea>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Start and End dates</label>
                    <div class="col-lg-9 fv-row">
                        <div class="row">
                            <div class="col-lg-6 fv-row form-floating">
                                <input type="date" name="start_date" class="form-control form-control-lg form-control-solid" value="<?= $start_date->format('Y-m-d'); ?>" />
                                <label>Start Date</label>
                            </div>
                            <div class="col-lg-6 fv-row form-floating">
                                <input type="date" name="end_date" class="form-control form-control-lg form-control-solid" value="<?= date_format($end_date, 'Y-m-d'); ?>" />
                                <label>Due Date</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Set Reminder</label>
                    <div class="col-lg-9 fv-row">
                        <input type="datetime-local" name="remind_on" class="form-control form-control-lg form-control-solid" value="<?= $remind_on->format('Y-m-d h:m'); ?>" />
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6 required">Style Category</label>
                    <div class="col-lg-9 fv-row">
                        <select name="style_category" data-control="select2" data-hide-search="true" required class="form-select form-select-lg bg-light border-body">
                            <option value="">Select a category</option>
                            <?php
                                $wearOptions = retrieveDataFrom('models/databases/wear-categories.json');
                                foreach ($wearOptions as $cat){
                                    $sel = ($cat->name == $style_catg) ? 'selected':'';
                                    echo '<option value="'.$cat->name.'" '.$sel.' >'. $cat->name .' ('. implode(", ", $cat->types) .')</option>';
                                } 
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Style Details (optional)</label>
                    <div class="col-lg-9 fv-row">
                        <textarea class="form-control form-control-lg form-control-solid" name="style_details"><?= $style_det; ?></textarea>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Upload Images</label>
                    <div class="col-lg-9 fv-row">
                        <input type="file" name="style_img1" class="form-control form-control-lg form-control-solid" disabled />
                    </div>
                </div>

            </div>
            
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-primary" id="project_edit_submit" onClick="updProject();"><?= displayLoadingIcon('Save Changes'); ?></button>
            </div>
        </form>
    </div>
</div>

<script>
    function updProject(){
        var pid = '<?= $_GET['pid']; ?>';
        var tid = '<?= $loguserid; ?>';
        var web = '<?= $c_website; ?>';

        var formID = "#project_edit_form";
        var submitButton = document.querySelector('#project_edit_submit');
        var type = "PATCH";
        var url = web+"controllers/projects.php?tailor="+tid+"&pid="+pid;

        AJAXcall(formID, submitButton, type, url, null, (responseMsg)=>{handleResponseMsg(responseMsg, 'reload');});
    }
</script>