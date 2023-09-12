<?php include_once('views/head.php'); ?>
<!---------------------------------------->
<body id="kt_body" class="bg-body"> 
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-xl-row flex-column-fluid">

        <?php 
            //$path = $_SERVER['QUERY_STRING'];
            
            if (isset($_GET['tid']) && !empty($_GET['tid'])){  //tailor id is required for now
                $tid = $_GET['tid'];
                include_once('views/new/1-info.php');

                /*if (isset($_GET['cid']) && !empty($_GET['cid'])){
                    $cid = $_GET['cid'];
                    
                    $measurements = retrieveDataFrom($c_website.'controllers/measurements.php?customer='. $cid)->data;
                    $UBmeasures = (!empty($measurements) ? (array)$measurements->UB : null);
                    $LBmeasures = (!empty($measurements) ? (array)$measurements->LB : null);

                    include_once('views/new/3-measurements.php');
                } else {*/
                    include_once('views/new/2-customer_reg.php');
                //}

            } else if (array_key_exists('login', $_GET)){
                include_once('views/new/1-info.php');
                include_once('views/new/3-customer_login.php');
            } else {
                include ('views/general/404_content.php');
                exit();
            }
            
        ?>

        </div>
    </div>

<!---------------------------------------->
<?php include_once('views/foot.php'); ?>