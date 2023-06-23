<div class="card mb-5 mb-xl-10" id="project_details_view">
    <div class="card-header cursor-pointer">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Project Details</h3>
        </div>
        <a href="javascript:;" class="btn btn-sm btn-primary align-self-center" onClick="toggleView('#project_details_view', '#project_details_edit')">
            Edit details
        </a>
    </div>
    <div class="card-body p-9">
        <div class="row mb-7">
            <label class="col-lg-3 fw-bold text-muted">Title</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?= $title; ?></span>
            </div>
        </div>
        <div class="row mb-7">
            <label class="col-lg-3 fw-bold text-muted">Description</label>
            <div class="col-lg-8 fv-row">
                <span class="fw-bold text-gray-800 fs-6"><?= $description; ?></span>
            </div>
        </div>
        <div class="row mb-7">
            <label class="col-lg-3 fw-bold text-muted">Due Date</label>
            <div class="col-lg-8">
                <span class="fw-bolder fs-6 text-gray-800"><?= readableDateTime($end, 'dateonly'); ?></span>
            </div>
        </div>
        <div class="row mb-10">
            <label class="col-lg-3 fw-bold text-muted">Reminder</label>
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800"><?= ($remind == 'N/A') ? 'Reminder not set' : readableDateTime($remind); ?></span>
            </div>
        </div>

        <div class="row mb-10">
            <h4 class="fw-bolder m-0">Project type : <?= strToUpper($style_catg); ?></h3>
            <span class="col-lg-10 mt-5 fw-bold text-muted"><?= $style_det; ?></span>
        </div>

        <div class="row mb-10 row-cols-2 row-cols-lg-5">
            <div class="col">
                <a class="d-block overlay" data-fslightbox="lightbox-hot-sales" href="assets/media/avatars/300-1.jpg">
                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/avatars/300-1.jpg')"></div>
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                    </div>
                </a>
            </div>
            <div class="col">
                <a class="d-block overlay" data-fslightbox="lightbox-hot-sales" href="assets/media/avatars/300-2.jpg">
                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/avatars/300-2.jpg')"></div>
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                    </div>
                </a>
            </div>
            <div class="col">
                <a class="d-block overlay" data-fslightbox="lightbox-hot-sales" href="assets/media/avatars/300-3.jpg">
                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px" style="background-image:url('assets/media/avatars/300-3.jpg')"></div>
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <i class="bi bi-eye-fill fs-2x text-white"></i>
                    </div>
                </a>
            </div>
            
        </div>

    </div>
</div>