    <div class="card h-md-100" style="background: linear-gradient(112.14deg, #000000 0%, #3A7BD5 100%)">
        <div class="card-body">
            <div class="row align-items-center h-100">
                <div class="col-8 ps-xl-13">

                    <div class="text-white mb-6 pt-6">
                        <span class="fs-4 fw-bold me-2 d-block lh-1 pb-2 opacity-75">Build and manage your </span>
                        <span class="fs-2qx fw-bolder">Customer Database</span>
                    </div>
                    <span class="fw-bold text-white fs-6 mb-8 d-block opacity-75">
                        <?= $company; ?> helps you to easily maintain clean and reliable data for all your customers using any of the options below. Now it would be easier to locate any client data and we will help you ensure it's always up to date.
                    </span>

                    <div class="d-flex align-items-center flex-wrap d-grid gap-2 mb-10 mb-xl-20">
                        <div class="d-flex align-items-center me-5 me-xl-13">
                            <div class="symbol symbol-30px symbol-circle me-3">
                                <span class="symbol-label bg-warning">
                                    <?= $svg_bullet1; ?>
                                </span>
                            </div>
                            <div class="text-white">
                                <span class="fw-bold d-block fs-8 opacity-75">EASY</span>
                                <span class="fw-bolder fs-7">Use a form - </span>
                                <span class="fw-bold fs-8 opacity-75">Fill out a simple form, add measurements now or later</span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-30px symbol-circle me-3">
                                <span class="symbol-label bg-danger">
                                    <?= $svg_bullet1; ?>
                                </span>
                            </div>
                            <div class="text-white">
                                <span class="fw-bold opacity-75 d-block fs-8">EASIER</span>
                                <span class="fw-bolder fs-7">Share Link - </span>
                                <span class="fw-bold fs-8 opacity-75">Send a link to your customer to provide the details you need</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-30px symbol-circle me-3">
                                <span class="symbol-label bg-info">
                                    <?= $svg_bullet1; ?>
                                </span>
                            </div>
                            <div class="text-white">
                                <span class="fw-bold opacity-75 d-block fs-8">EASIEST</span>
                                <span class="fw-bolder fs-7">Bulk Upload - </span>
                                <span class="fw-bold fs-8 opacity-75">Upload a file with all the data and we will do the rest</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-column flex-sm-row d-grid gap-2">
                        <a href="add_customer" class="btn btn-warning flex-shrink-0 me-2" data-bs-toggle="modal" data-bs-target="#modal_new_customer">Create customer</a>
                        <a href="#" class="btn btn-danger flex-shrink-0" style="background: rgba(255, 255, 255, 0.2)" data-bs-toggle="modal" data-bs-target="#modal_share_link">Share Link</a>
                    </div>
                </div>
                
                <div class="col-4 pt-10">
                    <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center h-225px" style="background-image:url('./assets/media/svg/illustrations/easy/5.svg');"></div>
                </div>
            </div>
        </div>
    </div>