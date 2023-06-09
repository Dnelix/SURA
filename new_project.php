<?php include_once('views/head.php'); ?>
<!---------------------------------------->
<body id="kt_body" class="bg-body">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid stepper stepper-pills stepper-column" id="kt_create_account_stepper">
            
            <?php include_once('views/newProject/nav.php'); ?>

            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <div class="w-lg-700px p-10 p-lg-15 mx-auto">

                        <form class="my-auto pb-5" novalidate="novalidate" id="kt_create_account_form">

                            <?php include_once('views/newProject/1_customer.php'); ?>
                            <?php include_once('views/newProject/2_style.php'); ?>
                            <?php include_once('views/newProject/3_finance.php'); ?>
                            <?php include_once('views/newProject/4_reminder.php'); ?>

                            <?php include_once('views/newProject/actions.php'); ?>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Root-->
<!---------------------------------------->
<?php include_once('views/foot.php'); ?>