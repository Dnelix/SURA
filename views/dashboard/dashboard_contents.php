    <!--begin::Container-->
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="row g-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-4 mb-xl-10">
                    <?php include_once('dashboard_contents/overview.php'); ?>
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-8 mb-xl-10">
                    <?php 
                        if($customerCount <= 0){ 
                            include_once('dashboard_contents/no_customer.php');
                        } else {
                            include_once('dashboard_contents/customer_list.php');
                        }
                    ?>
                </div>
                <!--end::Col-->
            </div>
            <!-- End Row -->

            <!-- Begin Row -->
            <div class="row g-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-12 mb-xl-10">
                    <?php include('dashboard_contents/project_list.php'); ?>
                </div>
                <!--end::Col-->
            </div>
        </div>
    </div>
    <!--end::Container-->