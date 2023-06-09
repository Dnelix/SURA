<?php include_once('views/head.php'); ?>
<!---------------------------------------->
<body id="kt_body" class="bg-body">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column" id="add_customer_stepper">
            
            <?php include_once('views/addCustomer/nav.php'); ?>

            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <div class="w-lg-700px p-10 p-lg-15 mx-auto">

                        <form class="my-auto pb-5" novalidate="novalidate" id="add_customer_form">

                            <?php include_once('views/addCustomer/1_details.php'); ?>
                            <?php include_once('views/addCustomer/2_upperbody.php'); ?>
                            <?php include_once('views/addCustomer/3_lowerbody.php'); ?>
                            <?php include_once('views/addCustomer/4_finish.php'); ?>

                            <?php include_once('views/addCustomer/actions.php'); ?>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Root-->
<!---------------------------------------->
<?php include_once('views/foot.php'); ?>