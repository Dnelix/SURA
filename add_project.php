<?php include_once('views/head.php'); ?>
<?php
$cid = (isset($_GET['cid']) ? $_GET['cid'] : '');
$tid = (isset($_GET['tid']) ? $_GET['tid'] : '');

if($cid == $loguserid){ //personal project
    $record = retrieveDataFrom($c_website.'controllers/users.php?userid='. $cid);
    $customerdata = $record->data;
} else { //customer project
    $record = retrieveDataFrom($c_website.'controllers/customers.php?tailor='. $loguserid .'&customer='. $cid);
    $customerdata = !empty($record->data) ? $record->data->customerdata : null;
}
$customerName = isset($customerdata->fullname) ? $customerdata->fullname : $customerdata->username;
$c_initials = getInitials($customerName);

$projects = retrieveDataFrom($c_website.'controllers/projects.php?tailor='. $loguserid .'&customer='. $cid);
$projectsCount  = (empty($projects->data->count)) ? 0 : $projects->data->count;

    $incomeTotal = $expenseTotal = $balanceTotal = 0;
    if(!empty($projects->data)){
        $projectlist = $projects->data->projectlist;
        foreach ($projectlist as $project) {
            $incomeTotal += $project->income;
            $expenseTotal += $project->expense;
        }
        $balanceTotal = $incomeTotal - $expenseTotal;
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
                            <?php 
                                if (isset($_GET['cid']) && !empty($_GET['cid'])){
                                    include('views/addProject/1-customersummary.php');
                                    include('views/addProject/2-project_details.php');
                                } else {
                                    include_once('views/general/404_content.php');
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!--end::Container-->

                <!--------------------------------------------->
                <?php include_once('views/general/footer.php'); ?>
                <?php include_once('views/general/scrolltop.php'); ?>
                
                <!-- Include Modals -->
                <?php include_once('views/components/modals/share_link.php'); ?>
                <?php include_once('views/components/modals/new_customer.php'); ?>

            </div>
        </div>
    </div>
    <!--end::Root-->
<!---------------------------------------->
<?php include_once('views/foot.php'); ?>