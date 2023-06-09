<div class="row g-5 g-xl-8">
    <div class="col-xl-12 mb-xl-10">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Activities</span>
                    <span class="text-muted mt-1 fw-bold fs-7">You have 2 <?= $alt_job; ?>(s) with this customer</span>
                </h3>
                <!--div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a user">
                    <a href="#" class="btn btn-sm btn-light btn-active-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">
                    <i class="fa fa-plus"></i> Add</a>
                </div-->
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
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input widget-9-check" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="text-dark fw-bolder text-hover-primary fs-6">Charles Ilo </a>
                                    <span class="text-muted fw-bold text-muted d-block fs-7">Making of trouser and shirt</span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bolder text-hover-primary fs-6">29th Apr 2023</span>
                                    <span class="text-muted fw-bold text-muted d-block fs-7">2:19 am</span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bolder text-hover-primary fs-6">29th Apr 2023</span>
                                    <span class="text-muted fw-bold text-muted d-block fs-7">2:19 am</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-warning">DELAYED</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Update project status">
                                            <?= $svg_optionsicon; ?>
                                        </a>
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Edit record">
                                            <?= $svg_editicon; ?>
                                        </a>
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Delete record">
                                            <?= $svg_deleteicon; ?>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>