<?php include_once('views/head.php'); ?>
<?php
// $users called in from header.php
$customers = retrieveDataFrom($c_website.'controllers/customers.php?tailor='.$loguserid) -> data;
$customerCount = (isset($customers->count) ? $customers->count : 0);
$customerList = (isset($customers->customerlist) ? $customers->customerlist : null);

$projects = retrieveDataFrom($c_website.'controllers/projects.php?tailor='.$loguserid) -> data; 
$projectCount = (isset($projects->count) ? $projects->count : 0);
    
    $incomeTotal = $expenseTotal = $balanceTotal = 0;
    if(!empty($projects)){
        $projectlist = $projects->projectlist;
        foreach ($projectlist as $project) {
            $incomeTotal += $project->income;
            $expenseTotal += $project->expense;
        }
        $balanceTotal = $incomeTotal - $expenseTotal;
    }

$openProjects = empty($projects) ? null : array_filter($projects->projectlist, function($item) { return $item->status !== 'Completed'; });

$bizdata = retrieveDataFrom($c_website.'controllers/business.php?userid='.$loguserid) -> data;
$profileStatus = (empty($bizdata->description) || empty($bizdata->address) || empty($bizdata->state)) ? "incomplete" : "complete";
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
                <?php include_once('views/dashboard/dashboard_contents.php'); ?>
                <?php include_once('views/general/footer.php'); ?>
                <?php include_once('views/general/scrolltop.php'); ?>
                
                <!-- Include Modals -->
                <?php include_once('views/components/modals/share_link.php'); ?>
                <?php include_once('views/components/modals/new_customer.php'); ?>
                <?php include_once('views/components/modals/new_project.php'); ?>
                <?php include_once('views/components/modals/add_measurements.php'); ?>

                <?//php include_once('views/components/modals/create_campaign.php'); ?>
                <?//php include_once('views/components/modals/upgrade_plan.php'); ?>
                <?//php include_once('views/components/modals/search_users.php'); ?>

            </div>
        </div>
    </div>
    <!--end::Root-->
<!---------------------------------------->
<?php include_once('views/foot.php'); ?>

<script>
    window.addEventListener('DOMContentLoaded', randomizeSpans);
    window.addEventListener('DOMContentLoaded', showToastMsg("<?= $profileStatus; ?>"));
</script>