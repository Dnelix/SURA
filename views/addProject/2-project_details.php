
<div class="card mb-5 mb-xl-10" id="project_details_edit">
    <div class="card-header cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Project Details</h3>
        </div>
    </div>

    <div id="project_details_edit_body">
        <form id="project_details_form" class="form" onSubmit="return false;">
            <div class="card-body border-top p-9">

            <?php if ($userdata->role === "business") { ?>
                <input type="hidden" name="tailorid" value="<?= $loguserid; ?>"/>
                <input type="hidden" name="customerid" value="<?= $cid; ?>"/>
            <?php } else { ?>
                <input type="hidden" name="tailorid" value="<?= $tid; ?>"/>
                <input type="hidden" name="customerid" value="<?= $loguserid; ?>"/>
            <?php } ?>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-bold fs-6">Title</label>
                    <div class="col-lg-9 fv-row">
                        <input type="text" name="title" class="form-control form-control-lg form-control-solid" value="A dress for <?= $customerName; ?>" />
                    </div>
                </div>
                <!--div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Description (optional)</label>
                    <div class="col-lg-9 fv-row">
                        <textarea name="description" class="form-control form-control-lg form-control-solid" placeholder="A brief description for this task"></textarea>
                    </div>
                </div-->
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6 required">Start and End dates</label>
                    <div class="col-lg-9 fv-row">
                        <div class="row">
                            <div class="col-lg-6 fv-row form-floating">
                                <input type="datetime-local" name="start_date" class="form-control form-control-lg form-control-solid" value="<?= date('Y-m-d h:i'); ?>" />
                                <label>Start Date</label>
                            </div>
                            <div class="col-lg-6 fv-row form-floating">
                                <input type="datetime-local" name="end_date" class="form-control form-control-lg form-control-solid" value="" />
                                <label>Due Date</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Set Reminder</label>
                    <div class="col-lg-9 fv-row">
                        <select name="remind_on" data-control="select2" data-hide-search="true" class="form-select form-select-lg bg-light border-body">
                            <option value="-1">One day to due date</option>
                            <option value="-3">Three days to due date</option>
                            <option value="-7">One week to due date</option>
                            <option value="-14">Two weeks to due date</option>
                        </select>
                    </div>
                </div>

                <br/>
                <hr/>
                <h3 class="fw-bolder m-0">Style Details</h3>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6 required">Style Category</label>
                    <div class="col-lg-9 fv-row">
                        <select name="style_catg" data-control="select2" data-hide-search="true" required class="form-select form-select-lg bg-light border-body">
                            <option value="">Select a category</option>
                            <?php
                                $wearOptions = retrieveDataFrom('models/databases/wear-categories.json');
                                foreach ($wearOptions as $cat){
                                    echo '<option value="'.$cat->name.'">'. $cat->name .' ('. implode($cat->types, ", ") .')</option>';
                                } 
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Style Description (optional)</label>
                    <div class="col-lg-9 fv-row">
                        <textarea name="style_det" class="form-control form-control-lg form-control-solid" placeholder="Make a long skirt with a halter top..."></textarea>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-bold fs-6">Upload Images</label>
                    <div class="col-lg-9 fv-row">
                        <input type="file" name="style_img1" class="form-control form-control-lg form-control-solid" />
                    </div>
                </div>

                <?php if ($userdata->role === "business") { ?>
                <br/>
                <hr/>
                <h3 class="fw-bolder m-0">Project Financials</h3>

                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Amount Charged (you can modify this later)</label>
                    <div class="col-lg-8 fv-row">
                        <div class="input-group input-group-solid mb-5">
                            <span class="input-group-text"><?= $defaultcurrency; ?></span>
                            <input type="number" name="income" class="form-control form-control-lg form-control-solid" placeholder="0.00" />
                        </div>
                    </div>
                </div>
                <div class="row mb-6">
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Expenses (you can update this later)</label>
                    <div class="col-lg-8 fv-row">
                        <div class="input-group input-group-solid mb-5">
                            <span class="input-group-text"><?= $defaultcurrency; ?></span>
                            <input type="number" name="expense" class="form-control form-control-lg form-control-solid" placeholder="0.00" />
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>
            
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                <button type="submit" class="btn btn-primary" id="project_details_submit"><?= displayLoadingIcon('Create Project'); ?></button>
            </div>
        </form>
    </div>
</div>