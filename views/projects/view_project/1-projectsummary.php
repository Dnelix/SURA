<div class="card mb-8"> 
    <div class="card-body pt-9 pb-0">
        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
            <div class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                <?= showProjectIcon($style_catg, 'large'); ?>
            </div>
            
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-1">
                            <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3"><?= $title; ?></a>

                            <div class="card-toolbar">
                                <button class="btn" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"><?= showStatus($status); ?></btn>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">SET PROJECT STATUS</div>
                                    </div>
                                    <div class="menu-item px-3"> 
                                        <ul>
                                            <li class="pt-3" onClick="updateStatus('<?= $id; ?>', 'Not Started')"> <?= showStatus('Not Started'); ?></li>
                                            <li class="pt-3" onClick="updateStatus('<?= $id; ?>', 'In Progress')"> <?= showStatus('In Progress'); ?> </li>
                                            <li class="pt-3" onClick="updateStatus('<?= $id; ?>', 'Delayed')"> <?= showStatus('Delayed'); ?> </li>
                                            <li class="pt-3" onClick="updateStatus('<?= $id; ?>', 'Completed')"> <?= showStatus('Completed'); ?> </li>
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                            <a href="customers?cid=<?= $customerid; ?>" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                            <span class="svg-icon svg-icon-4 me-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="currentColor"></path>
                                    <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <?= getuserDataById('fullname', $customerid); ?></a>
                            
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <a href="javascript:;" onClick="history.back()" class="btn btn-sm btn-light me-2"><span class="indicator-label"><i class="fa fa-arrow-left"></i> Go back</span></a>

                        <div class="me-0">
                            <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="bi bi-three-dots fs-3"></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true" style="">
                                <div class="menu-item px-3">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Project Options</div>
                                </div>

                                <div class="menu-item px-3"><a href="#" class="menu-link px-3">Delete Project</a></div>
                                <div class="menu-item px-3"><a href="#" class="menu-link px-3">Cancel Project</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-wrap justify-content-start">
                    <div class="d-flex flex-wrap">
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="fs-4 fw-bolder"><?= readableDateTime($start, 'dateonly'); ?></div>
                            </div>
                            <div class="fw-bold fs-6 text-gray-400">Date Started</div>
                        </div>
                        
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="fs-4 fw-bolder"><?= readableDateTime($end, 'dateonly'); ?></div>
                            </div>
                            <div class="fw-bold fs-6 text-gray-400">Date Due</div>
                        </div>
                        
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-3 svg-icon-danger me-2">
                                    <i class="fa fa-arrow-down"></i>
                                </span>
                                <div class="fs-4 fw-bolder counted" data-kt-countup="true" data-kt-countup-value="<?= $income; ?>" data-kt-countup-prefix="<?= $defaultcurrency; ?>"><?= $defaultcurrency.' '.$income; ?></div>
                            </div>
                            <div class="fw-bold fs-6 text-gray-400">Income</div>
                        </div>
                        
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                    <i class="fa fa-arrow-up"></i>
                                </span>
                                <div class="fs-4 fw-bolder counted" data-kt-countup="true" data-kt-countup-value="<?= $expense; ?>" data-kt-countup-prefix="<?= $defaultcurrency; ?>"><?= $defaultcurrency.' '.$expense; ?></div>
                            </div>
                            <div class="fw-bold fs-6 text-gray-400">Expenses</div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="separator"></div>
        <div><p><br/></p></div>
    </div>
</div>

<script>
    function updateStatus(pid, status){
        var tid = '<?= $loguserid; ?>';
        var web = '<?= $c_website; ?>';
        var submitButton = _('fs'+pid);
        var type = "PATCH";
        var url = web+"controllers/projects.php?tailor="+tid+"&pid="+pid;
        var data = {
            "status" : status
        };

        AJAXcall(null, submitButton, type, url, data);
        setTimeout(reloadPage(), 500);
    }
</script>