<?php include_once('views/head.php'); ?>

<?php 
    $customers = retrieveDataFrom($c_website.'controllers/customers.php?tailor='.$loguserid); 
    $customerCount = (isset($customers->data->count) ? $customers->data->count : 0);
?>
<!---------------------------------------->
<body id="kt_body" style="<?= $bodystyle; ?>" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">

                <?php include_once('views/general/header.php'); ?>
                <?php include_once('views/general/breadcrumb.php'); ?>
                <!--------------------------------------------->

                <!--begin::Container-->
                <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
                    <div class="content flex-row-fluid" id="kt_content">
                        <div class="row g-5 g-xl-10">
                            <div class="col-xl-12 mb-xl-10">
                                <?php 
                                    if (isset($_GET['cid']) && $_GET['cid'] !== ''){
                                        include('views/customers/view_customer.php'); 
                                    } else {
                                        include('views/customers/customer_list.php'); 
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Container-->

                <!--------------------------------------------->
                <?php include_once('views/general/footer.php'); ?>
                <?php include_once('views/general/scrolltop.php'); ?>
                
                <!-- Include Modals -->
                <?php 
                if (isset($_GET['cid'])){
                    $cid = $_GET['cid'];
                    include_once('views/components/modals/upper_body.php');
                    include_once('views/components/modals/lower_body.php'); 
                }
                ?>

                <?php include_once('views/components/modals/share_link.php'); ?>
                <?php include_once('views/components/modals/new_customer.php'); ?>
                <?php include_once('views/components/modals/new_project.php'); ?>

            </div>
        </div>
    </div>
    <!--end::Root-->
<!---------------------------------------->
<?php include_once('views/foot.php'); ?>