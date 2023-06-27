<?php
    $start_date = DateTime::createFromFormat('d/m/Y H:i', $start);
    $end_date = date_create($end);
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
        <form id="project_details_form" class="form">
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
                        <textarea class="form-control form-control-lg form-control-solid"><?= $description; ?></textarea>
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
                        <select name="remind_on" data-control="select2" data-hide-search="true" class="form-select form-select-lg bg-light border-body">
                            <option value="1">One day to due date</option>
                            <option value="3">Three days to due date</option>
                            <option value="7">One week to due date</option>
                            <option value="14">Two weeks to due date</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Style Details (optional)</label>
                    <div class="col-lg-9 fv-row">
                        <textarea class="form-control form-control-lg form-control-solid"><?= $style_det; ?></textarea>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Upload Images</label>
                    <div class="col-lg-9 fv-row">
                        <input type="file" name="style_img1" class="form-control form-control-lg form-control-solid" />
                    </div>
                </div>

            </div>
            
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-primary" id="details_edit_submit"><?= displayLoadingIcon('Save Changes'); ?></button>
            </div>
        </form>
    </div>
</div>