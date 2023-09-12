<?php include_once('views/head.php'); ?>

<?php 
    if ($logrole === 'business'){
        $projects = retrieveDataFrom($c_website.'controllers/projects.php?tailor='.$loguserid);
        $projects = (!empty($projects)) ? $projects->data : null; 
    } else {
        $projects = retrieveDataFrom($c_website.'controllers/projects.php?cid='.$loguserid);
        $projects = (!empty($projects)) ? $projects->data : null; 
    }
    $projectCount = (isset($projects->count) ? $projects->count : 0);
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
                                if (isset($_GET['pid']) && $_GET['pid'] !== ''){
                                    include('views/projects/view_project.php'); 
                                } else {
                                    $list = ($logrole === 'business') ? 'project_list':'customer_project_list';
                                    include('views/projects/'.$list.'.php'); 
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
                <?php include_once('views/components/modals/new_project.php'); ?>

            </div>
        </div>
    </div>
    <!--end::Root-->
<!---------------------------------------->
<?php include_once('views/foot.php'); ?>