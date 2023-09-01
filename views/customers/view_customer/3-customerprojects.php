<div class="row g-5 g-xl-8">
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Activities</span>
                    <span class="text-muted mt-1 fw-bold fs-7">You have <?= $projectsCount.' '.$alt_job; ?>(s) with this customer</span>
                </h3>
                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to start a new project">
                    <a href="add_project?cid=<?= $cid; ?>" class="btn btn-sm btn-dark btn-active-primary">
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
                                <th class="min-w-150px"><?= $alt_job; ?> Info</th>
                                <th class="min-w-100px">Start Date</th>
                                <th class="min-w-100px">End Date</th>
                                <?php if ($customerdata->role === 'business'){ ?>
                                    <th class="min-w-100px">Income</th>
                                    <th class="min-w-100px">Expense</th>
                                <?php } else { ?>
                                    <th class="min-w-100px">Expense</th>
                                <?php } ?>
                                <th class="min-w-100px"> Status </th>
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
                                    <a href="projects?pid=<?= $item->id; ?>" class="text-dark fw-bolder text-hover-primary fs-6"><?= $item->title; ?> </a>
                                    <span class="text-muted fw-bold d-block fs-7"><?= limit_text($item->description, 7); ?></span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bolder fs-6"><?= readableDateTime($item->start, 'dateonly'); ?></span>
                                    <span class="text-muted fw-bold d-block fs-7"><?= readableDateTime($item->start, 'timeonly'); ?></span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bolder fs-6"><?= readableDateTime($item->end, 'dateonly'); ?></span>
                                    <span class="text-muted fw-bold d-block fs-7"><?= readableDateTime($item->start, 'timeonly'); ?></span>
                                </td>

                                <td><span class="text-success fw-bolder fs-6"><?= $item->income; ?></span></td>
                            <?php if ($customerdata->role === 'business'){ ?>
                                <td><span class="text-danger fw-bolder fs-6"><?= $item->expense; ?></span></td>
                            <?php } ?>

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
                                        <a href="javascript:deleteProject('<?= $item->id; ?>')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Delete project">
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
    function deleteProject(pid){
        var web = '<?= $c_website; ?>';
        var tid = '<?= $loguserid; ?>';
        var type = 'DELETE';
        var url = web+"controllers/projects.php?tailor="+tid+"&pid="+pid;

        swal_confirm("Are you sure you want to delete this record? This cannot be undone!", "Delete", "Cancel")
        .then((result) => {
            if (result.isConfirmed) {
                AJAXcall(null, null, type, url, null);
            } else if (result.isDenied) {
                console.log("Canceled");
            }
        })
        .catch((error) => {
            console.error(error);
        });
    }
</script>