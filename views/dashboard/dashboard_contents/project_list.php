<div class="d-flex flex-wrap flex-stack mb-6">
    <h3 class="fw-bolder my-2">My <?= $alt_job; ?> 
    <span class="fs-6 text-gray-400 fw-bold ms-1">Active</span></h3>
    
    <div class="d-flex flex-wrap my-2">
        <div class="me-4">
            <select name="status" id="status_filter" onChange="filterStatus('status_filter')" data-control="select2" data-hide-search="true" class="form-select form-select-sm bg-body border-body w-125px">
                <option value="ALL" selected="selected">ALL</option>
                <option value="In Progress">In Progress</option>
                <option value="Not Started">Not Started</option>
                <option value="Delayed">Delayed</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        <a href="javascript:;" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_new_project">New <?= $alt_job; ?></a>
    </div>
</div>

<div class="row g-6 g-xl-9">

    <?php if($projectCount <= 0){ ?>

    <div class="col-md-12 col-xl-12">
        <p class="fs-6 text-gray-400 fw-bold ms-1">You have no active/ongoing <?= $alt_job; ?>s at the moment. When you have registered your customers, you can start a new project by clicking the "New Project" button above.</p>
    </div>

    <?php 
        } else { 
            $counter = 0;
            $pageLimit = 6;
            $listTotal = ($projectCount <= $pageLimit) ? $projectCount : $pageLimit;

            foreach($projects->projectlist as $project) {
                if ($counter >= $pageLimit) { break;}
        
    ?>

        <div class="col-md-4 col-xl-4">
            <a href="javascript:;" class="card border-hover-primary">

                <div class="card-header border-0 pt-9">
                    <div class="card-title m-0">
                        <?= showProjectIcon($project->style_catg,'small'); ?>
                    </div>
                    
                    <div class="card-toolbar">
                        <div class="me-6 my-1">
                            <select id="fs<?= $project->id ;?>" name="filter_status" onChange="updateStatus('<?= $project->id ;?>')" data-control="select2" data-hide-search="true" class="w-125px form-select form-select-solid form-select-sm">
                                <option value="In Progress" <?= ($project->status === 'In Progress' ? 'selected="selected"' : ''); ?>>In Progress</option>
                                <option value="Not Started" <?= ($project->status === 'Not Started' ? 'selected="selected"' : ''); ?>>Not Started</option>
                                <option value="Delayed" <?= ($project->status === 'Delayed' ? 'selected="selected"' : ''); ?>>Delayed</option>
                                <option value="Completed" <?= ($project->status === 'Completed' ? 'selected="selected"' : ''); ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-9">
                    <div class="fs-3 fw-bolder text-dark"><?= $project->title; ?></div>
                    <p class="text-gray-400 fw-bold fs-5 mt-1 mb-7"><?= limit_text($project->description, 15); ?></p>
                    <div class="d-flex flex-wrap mb-5">
                        
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-7 mb-3">
                            <div class="fs-6 text-gray-800 fw-bolder"><?= readableDateTime($project->end, 'dateonly'); ?></div>
                            <div class="fw-bold text-gray-400">Due Date</div>
                        </div>
                        
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 mb-3">
                            <div class="fs-6 text-gray-800 fw-bolder"><?= $project->income; ?></div>
                            <div class="fw-bold text-gray-400">Budget</div>
                        </div>
                        
                    </div>

                    <div class="h-4px w-100 bg-light mb-5" data-bs-toggle="tooltip" title="This project <?= $project->completion; ?>% completed">
                        <div class="bg-success rounded h-4px" role="progressbar" style="width: <?= $project->completion; ?>%" aria-valuenow="<?= $project->completion; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="symbol-group symbol-hover">
                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="<?= getuserDataById('username', $project->customerid); ?>">
                            <?= showCustomerIcon($project->customerid, getInitials(getuserDataById('fullname', $project->customerid))); ?>
                            <span class=""><?= getuserDataById('username', $project->customerid); ?></span>
                        </div>
                    </div>

                    <span style="float:right">
                        <button onClick="goTo('projects?pid=<?= $project->id; ?>')" class="btn text-primary">
                            View details &nbsp; <i class="fa fa-arrow-right"></i>
                        </button>
                    </span>
                </div>

            </a>
        </div>
    <?php $counter++; } ?>
    
    <div class="d-flex flex-stack flex-wrap pt-10">
		<div class="fs-6 fw-bold text-gray-700">
            Showing 1 to <?= $listTotal.' of '.$projectCount.' '.$alt_job; ?>s - &nbsp;
            <a class="fw-bolder" href="projects">View all</a>
        </div>
    </div>

    <?php } ?>
</div>

<script>
    function filterStatus(inputID){
        var status = _(inputID).value;
        goTo('projects?filter='+status);
    }
    function updateStatus(pid){
        var status = _('fs'+pid).value;
        var tid = '<?= $loguserid; ?>';
        var web = '<?= $c_website; ?>';
        var submitButton = _('fs'+pid);
        var type = "PATCH";
        var url = web+"controllers/projects.php?tailor="+tid+"&pid="+pid;
        var data = {
            "status" : status
        };

        AJAXcall(null, submitButton, type, url, data);
    }
</script>