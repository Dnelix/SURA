

   <div class="card card-flush h-xl-100">
        
        <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px" style="background-image:url('assets/media/svg/shapes/top-green.png')">
            <h3 class="card-title align-items-start flex-column text-white pt-15">
                <span class="fw-bolder fs-2x mb-3">Hello, <?= $logusername; ?></span>
                <div class="fs-4 text-white">
                    <span class="opacity-75">You have</span>
                    <span class="position-relative d-inline-block">
                        <a href="projects" class="link-white opacity-75-hover fw-bolder d-block mb-1">4 <?= $alt_job.'s'; ?></a>
                        <span class="position-absolute opacity-50 bottom-0 start-0 border-2 border-white border-bottom w-100"></span>
                    </span>
                    <span class="opacity-75">approaching deadline</span>
                </div>
            </h3>
            
        </div>

        <div class="card-body mt-n20">
            <div class="mt-n20 position-relative">
                <div class="row g-3 g-lg-6">
                    <div class="col-6">
                    <a href="customers">
                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                            <div class="symbol symbol-30px me-5 mb-8">
                                <span class="symbol-label">
                                    <i class="fa fa-users fs-2x text-primary"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1"><?= $customerCount; ?></span>
                                <span class="text-gray-500 fw-bold fs-6">My Customers</span>
                            </div>
                        </div>
                    </a>
                    </div>

                    <div class="col-6">
                    <a href="projects">
                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                            <div class="symbol symbol-30px me-5 mb-8">
                                <span class="symbol-label">
                                    <i class="fa fa-briefcase fs-2x text-danger"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1">10</span>
                                <span class="text-gray-500 fw-bold fs-6">Open <?= $alt_job.'s'; ?></span>
                            </div>
                        </div>
                    </a>
                    </div>

                    <div class="col-6">
                    <a href="projects">
                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                            <div class="symbol symbol-30px me-5 mb-8">
                                <span class="symbol-label">
                                    <i class="fa fa-briefcase fs-2x text-info"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1"><?= $projectCount; ?></span>
                                <span class="text-gray-500 fw-bold fs-6">All <?= $alt_job.'s'; ?></span>
                            </div>
                        </div>
                    </a>
                    </div>

                    <div class="col-6">
                    <a href="financials">
                        <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                            <div class="symbol symbol-30px me-5 mb-8">
                                <span class="symbol-label">
                                    <i class="fa fa-lightbulb fs-2x text-warning"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <span class="text-gray-700 fw-boldest d-block fs-2qx lh-1 ls-n1 mb-1">N8.2k</span>
                                <span class="text-gray-500 fw-bold fs-6">Profit Earned</span>
                            </div>
                        </div>
                    </a>
                    </div>

                </div>
            </div>
        </div>
    </div>