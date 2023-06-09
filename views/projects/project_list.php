<div class="card mb-8">
    <div class="card-body pt-9 pb-0">   
        <div class="d-flex flex-wrap flex-stack mb-6">
            <h3 class="fw-bolder my-2">My Projects 
            <span class="fs-6 text-gray-400 fw-bold ms-1">Select a project to view details</span></h3>
            
            <div class="d-flex flex-wrap my-2">
                <div class="me-4">
                    <select name="status" data-control="select2" data-hide-search="true" class="form-select form-select-sm bg-body border-body w-125px">
                        <option value="Active" selected="selected">In Progress</option>
                        <option value="Not started">Not Started</option>
                        <option value="Declined">Terminated</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <!--a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">New Project</a-->
            </div>
        </div>
    </div>
</div>

<div class="row g-6 g-xl-9">

    <?php if($projectCount <= 0){ ?>

    <div class="col-md-12 col-xl-12">
        <p class="fs-6 text-gray-400 fw-bold ms-1">You have no active/ongoing <?= $alt_job; ?>s at the moment. When you have registered your customers, you can start a new project by clicking the "New Project" button above.</p>
    </div>

    <?php 
        } else { 
            foreach($projects->projectlist as $project) {
        
    ?>

        <div class="col-md-6 col-xl-4">
            <a href="projects?pid=<?= $project->id; ?>" class="card border-hover-primary">

                <div class="card-header border-0 pt-9">
                    <div class="card-title m-0">
                        <?= showProjectIcon($project->style_catg,'small'); ?>
                    </div>
                    <div class="card-toolbar">
                        <?= showStatus($project->status); ?>
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
                            <div class="fs-6 text-gray-800 fw-bolder"><?= $defaultcurrency.$project->income; ?></div>
                            <div class="fw-bold text-gray-400">Budget</div>
                        </div>
                        
                    </div>

                    <!--label for="progressbar" class="fw-bold text-gray-400" style="float:right"><?= calculateTimeLeft($project->end); ?> </label-->
                    <div class="h-4px w-100 bg-light mb-5" data-bs-toggle="tooltip" title="This project 50% completed">
                        <div class="bg-primary rounded h-4px" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="symbol-group symbol-hover">
                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="<?= getuserDataById('username', $project->customerid); ?>">
                            <?= showCustomerIcon($project->customerid, getInitials(getuserDataById('fullname', $project->customerid))); ?>
                            <span class=""><?= getuserDataById('username', $project->customerid); ?></span>
                        </div>
                    </div>

                </div>
            </a>
        </div>
    <?php } } ?>
</div>