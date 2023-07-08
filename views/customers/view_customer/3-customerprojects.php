<div class="row g-5 g-xl-8">
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Activities</span>
                    <span class="text-muted mt-1 fw-bold fs-7">You have <?= $projectsCount.' '.$alt_job; ?>(s) with this customer</span>
                </h3>
                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to start a new project">
                    <a href="javascript:;" class="btn btn-sm btn-dark btn-active-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
                    <i class="fa fa-plus"></i> New <?= $alt_job; ?></a>
                </div>
            </div>
            
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bolder text-muted">
                                <th class="w-25px">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check" />
                                    </div>
                                </th>
                                <th class="min-w-200px"><?= $alt_job; ?> Info</th>
                                <th class="min-w-150px">Start Date</th>
                                <th class="min-w-150px">End Date</th>
                                <th class="min-w-150px"> Status </th>
                                <th class="min-w-100px text-end">Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody>

                        <?php
                            if ($projectsList){
                                foreach($projectsList as $item){
                        ?>

                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input widget-9-check" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bolder text-hover-primary fs-6"><?= getuserDataById('fullname', $item->customerid); ?> </a>
                                    <span class="text-muted fw-bold text-muted d-block fs-7"><?= $item->title; ?></span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bolder text-hover-primary fs-6"><?= readableDateTime($item->start, 'dateonly'); ?></span>
                                    <span class="text-muted fw-bold text-muted d-block fs-7"><?= readableDateTime($item->start, 'timeonly'); ?></span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bolder text-hover-primary fs-6"><?= readableDateTime($item->end, 'dateonly'); ?></span>
                                    <span class="text-muted fw-bold text-muted d-block fs-7"><?= readableDateTime($item->start, 'timeonly'); ?></span>
                                </td>
                                <td>
                                    <?= showStatus($item->status); ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <!--a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Update project status">
                                            <?//= $svg_optionsicon; ?>
                                        </a-->
                                        <a href="projects?pid=<?= $item->id; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="View/Edit Project details">
                                            <?= $svg_editicon; ?>
                                        </a>
                                        <a href="javascript:deleteProject('<?= $loguserid; ?>','<?= $item->id; ?>')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Delete project">
                                            <?= $svg_deleteicon; ?>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        <?php      
                            }
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteProject(tid,pid){
        var web = '<?= $c_website; ?>';

        var confirm = swal_confirm('Delete this project? '+tid+pid);

        if(confirm == true){
            console.log('Confirmed'); return false;
        } else {
            console.log(confirm); return false;
        }

        var formID = "#modal_LB_form";
        var submitButton = document.querySelector('#modal_LB_submit');
        var type = "PATCH";
        var url = web+"controllers/projects.php?customer="+cid;

        AJAXcall(formID, submitButton, type, url);
    }
</script>