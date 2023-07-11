<div class="card mb-8">
    <div class="card-body pt-9 pb-0">
        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
            <div class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                <?= showCustomerIcon($cid, $c_initials, null, 'large'); ?>
            </div>
            
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-1">
                            <a class="text-gray-600 text-hover-primary fs-2 fw-bolder me-3">New <?= $alt_job; ?> for</a>
                            <span class="badge badge-light-info me-auto"><?= strToUpper($customerName); ?></span>
                        </div>
                        <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                            <a href="javascript:;" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                <?= $svg_usericon; ?>
                                <?= $customerdata->phone; ?>
                            </a>
                            <a href="javascript:;" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                <?= $svg_mailicon; ?>
                                <?= ($customerdata->email !== "") ? $customerdata->email : $customerdata->username."@".$c_shortsite; ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="d-flex my-4">
                        <a href="#" onClick="history.back()" class="btn btn-sm btn-light me-2"><span class="indicator-label"><i class="fa fa-arrow-left"></i> Go back</span></a>
                    </div>
                </div>
                
                <div class="d-flex flex-wrap justify-content-start">
                    <div class="d-flex flex-wrap">
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="fs-4 fw-bolder"><?= readableDateTime($customerdata->createdon, 'dateonly'); ?></div>
                            </div>
                            <div class="fw-bold fs-6 text-gray-400">Date Added</div>
                        </div>
                        
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-arrow-down me-2"></i>
                                <div class="fs-4 fw-bolder counted" data-kt-countup="true" data-kt-countup-value="<?= $projectsCount; ?>"><?= $projectsCount; ?></div>
                            </div>
                            <div class="fw-bold fs-6 text-gray-400"><?= $alt_job; ?>s</div>
                        </div>
                        
                        <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-arrow-up me-2"></i>
                                <div class="fs-4 fw-bolder text-success counted" data-kt-countup="true" data-kt-countup-value="15000" data-kt-countup-prefix="$"><?= $defaultcurrency; ?>15,000</div>
                            </div>
                            <div class="fw-bold fs-6 text-gray-400">Total Charges</div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="separator"></div>
        <div><p><br/></p></div>
    </div>
</div>