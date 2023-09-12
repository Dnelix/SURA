<?php include_once('views/head.php'); ?>

<?php 
    $cid = $loguserid;
    $record = retrieveDataFrom($c_website.'controllers/users.php?userid='. $cid);

    $measurements = retrieveDataFrom($c_website.'controllers/measurements.php?customer='. $cid);
    $measurements = (!empty($measurements)) ? $measurements->data : null;

    $projects = retrieveDataFrom($c_website.'controllers/projects.php?cid='.$cid);
    $projects = (!empty($projects)) ? $projects->data : null; 
    $projectsCount = (isset($projects->count) ? $projects->count : 0);

    $incomeTotal = $expenseTotal = $balanceTotal = 0;
    if(!empty($projects->data)){
        $projectlist = $projects->data->projectlist;
        foreach ($projectlist as $project) {
            $incomeTotal += $project->income;
            //$expenseTotal += $project->expense;
        }
        //$balanceTotal = $incomeTotal - $expenseTotal;
    }

    if($record !== NULL){
        
        $customerdata = $record->data;

        $UBmeasures = (($measurements->UB !== null) ? (array)$measurements->UB : null); //Upperbody measurements
        $LBmeasures = (($measurements->LB !== null) ? (array)$measurements->LB : null); //Lowerbody measurements

        $projectsList   = ($projectsCount < 1) ? null : $projects->projectlist;

        $customerName = isset($customerdata->fullname) ? $customerdata->fullname : $customerdata->username;
        $initials = getInitials($customerName);
    }
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
                                    include_once('views/customers/view_customer/1-customersummary.php');
                                    include_once('views/customers/view_customer/2-measurements.php');
                                    include_once('views/customers/view_customer/4-tailorcustomerprojects.php');
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
                if (isset($cid)){
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