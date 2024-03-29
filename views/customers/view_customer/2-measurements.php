
        <div class="row g-6 g-xl-9 mb-10">

            <div class="col-md-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to edit these measurements">
                            <a href="javascript:;" class="btn btn-sm btn-light btn-active-primary" data-bs-toggle="modal" data-bs-target="#modal_upper_body"><i class="fa fa-edit"></i> Edit</a>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <img src="assets/media/illustrations/measurements/ub.jpg" alt="upper body">
                            <div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>
                        </div>
                        <a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Upper Body (<?= $measureunit_text; ?>)</a>
                        <div class="fw-bold text-gray-400 mb-6">Measurements for tops and gowns</div>

                        <div class="d-flex flex-center flex-wrap" data-bs-toggle="modal" data-bs-target="#modal_upper_body">
                            <?php
                                //$upperBody = array_slice($measures, 0, 10, true);
                                foreach ($UBmeasures as $data){
                            ?>
                                <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                    <div class="fs-6 fw-bolder text-gray-700"><?= $data->value; ?></div>
                                    <div class="fw-bold text-gray-400"><?= $data->metadata->label; ?></div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-6">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to edit these measurements">
                            <a href="#" class="btn btn-sm btn-light btn-active-primary" data-bs-toggle="modal" data-bs-target="#modal_lower_body"><i class="fa fa-edit"></i> Edit</a>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <!--span class="symbol-label fs-2x fw-bold text-primary bg-light-primary">S</span-->
                            <img src="assets/media/illustrations/measurements/lb.jpg" alt="lower body">
                            <div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>
                        </div>
                        <a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bolder mb-0">Lower Body (<?= $measureunit_text; ?>)</a>
                        <div class="fw-bold text-gray-400 mb-6">Measurements for trousers, skirts, etc</div>

                        <div class="d-flex flex-center flex-wrap" data-bs-toggle="modal" data-bs-target="#modal_lower_body">
                            <?php
                                //$lowerBody = array_slice($measures, 10);
                                foreach ($LBmeasures as $data){
                            ?>
                                <div class="border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                                    <div class="fs-6 fw-bolder text-gray-700"><?= $data->value; ?></div>
                                    <div class="fw-bold text-gray-400"><?= $data->metadata->label; ?></div>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    