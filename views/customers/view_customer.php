<?php
    $cid = $_GET['cid'];

    $record = retrieveDataFrom($c_website.'controllers/customers.php?tailor='. $loguserid .'&customer='. $cid);
    $measurements = retrieveDataFrom($c_website.'controllers/measurements.php?customer='. $cid)->data;

    if($record->data !== NULL){
        
        $customerdata = $record->data->customerdata;
        $UBmeasures = (($measurements->UB !== null) ? (array)$measurements->UB : null); //Upperbody measurements
        $LBmeasures = (($measurements->LB !== null) ? (array)$measurements->LB : null); //Lowerbody measurements

        $customerName = isset($customerdata->fullname) ? $customerdata->fullname : $customerdata->username;
        $initials = getInitials($customerName);

        include_once('view_customer/1-customersummary.php');
        include_once('view_customer/2-measurements.php');
        include_once('view_customer/3-customerprojects.php');
    } else {
        include_once('views/general/404_content.php');
    }
?>
