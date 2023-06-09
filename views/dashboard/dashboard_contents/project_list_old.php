<?php

$itemColor = "success";
$projectStatus = "ONGOING";

?>

<div class="card h-xl-100">
    <div class="card-header pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">Ongoing <?= $alt_job.'s'; ?></span>
            <span class="text-muted mt-1 fw-bold fs-7">You currently have 10 open <?= $alt_job.'s'; ?></span>
        </h3>
        <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="View all open and closed projects">
            <a href="projects" class="btn btn-sm btn-light btn-active-primary">
            <i class="fa fa-eye"></i> See all </a>
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <!--div class="card-body" style="background-color:#50CD89; background-image:url('assets/media/svg/shapes/top-green.png'); background-size:cover"-->
    <div class="card-body" style="background-color:#009EF7;">
        <div class="row g-5 g-xl-10">
            <div class="col-xl-4 mb-0 mb-xl-0">
                <div id="kt_sliders_widget_2_slider" class="card carousel carousel-custom carousel-stretch slide h-xl-100" data-bs-ride="carousel" data-bs-interval="5500">
                    <!--begin::Header-->
                    <div class="card-header pt-5">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label fw-bolder text-gray-800">Femi Agbo <span class="text-gray-400 mt-1 fw-bolder fs-7">[08112223342]</span></span>
                            <span class="badge badge-light-<?= $itemColor; ?>"><?= $projectStatus; ?></span>
                        </h4>
                        <div class="card-toolbar">
                            <ol class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-<?= $itemColor; ?>">
                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="0" class="active ms-1"></li>
                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="1" class="ms-1"></li>
                                <li data-bs-target="#kt_sliders_widget_2_slider" data-bs-slide-to="2" class="ms-1"></li>
                            </ol>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-6">
                        <div class="carousel-inner">
                            <!--begin::Item-->
                            <div class="carousel-item active show">
                                <div class="d-flex align-items-center mb-0">
                                    <div class="symbol symbol-70px symbol-circle me-5">
                                        <span class="symbol-label bg-light-danger">
                                            <span class="svg-icon svg-icon-3x svg-icon-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M7 20.5L2 17.6V11.8L7 8.90002L12 11.8V17.6L7 20.5ZM21 20.8V18.5L19 17.3L17 18.5V20.8L19 22L21 20.8Z" fill="currentColor" />
                                                    <path d="M22 14.1V6L15 2L8 6V14.1L15 18.2L22 14.1Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="m-0">
                                        <h4 class="fw-bolder text-gray-800 mb-3"><?= $alt_job; ?> Details</h4>
                                        <div class="d-flex d-grid gap-5">
                                            <div class="d-flex flex-column flex-shrink-0 fs-7 fw-bolder text-gray-400">
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Start Date: 21-01-2023</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Due Date: 21-02-2023</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Reminder: Not Set</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Status: In Progress</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="carousel-item">
                                <div class="d-flex align-items-center mb-0">
                                    <?= showProjectIcon('top'); ?>
                                    <div class="m-0">
                                        <h4 class="fw-bolder text-gray-800 mb-3">Style Details</h4>
                                        <div class="d-flex d-grid gap-5">
                                            <div class="d-flex flex-column flex-shrink-0 fs-7 fw-bolder text-gray-400">
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Material Color: Blue</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Style category: Trouser/shorts</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Style details: Put your hands on my sh...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="carousel-item">
                                <div class="d-flex align-items-center mb-0">
                                    <div class="symbol symbol-70px symbol-circle me-5">
                                        <span class="symbol-label bg-light-warning">
                                            <span class="fa fa-lightbulb fs-3x text-warning"></span>
                                        </span>
                                    </div>
                                    <div class="m-0">
                                        <h4 class="fw-bolder text-gray-800 mb-3">Financials</h4>
                                        <div class="d-flex d-grid gap-5">
                                            <div class="d-flex flex-column flex-shrink-0 fs-7 fw-bolder text-gray-400">
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Amount Charged : N20,000</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Advance Paid : N10,000</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Balance : N10,000</span>
                                                <span class="mb-2"> <i class="fa fa-caret-right"> &nbsp; </i> Expenses : N0 [<a href="#">Add Expense</a>]</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Body-->
                    <div class="card-footer pt-5">
                        <div class="">
                            <a href="#" class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">
                                Close <?= $alt_job; ?>
                            </a>
                            <a href="projects?pid=1" class="btn btn-sm btn-<?= $itemColor; ?>">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>