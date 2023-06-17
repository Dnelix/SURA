<?php
$UB = retrieveDataFrom('../models/databases/measurement_UB.json');
$LB = retrieveDataFrom('../models/databases/measurement_LB.json');
$table_columns = implode(", ", $measurement_parameters_array);

try{
    // confirm the user exists
    $query = $writeDB -> prepare('SELECT id FROM tbl_users WHERE id = :cid');
    $query -> bindParam(':cid', $custid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount <= 0){
        sendResponse(409, false, 'This user does not exist'); exit();
    }

    // get records
    $query = $readDB -> prepare ('SELECT ' .$table_columns. ' FROM tbl_measurements WHERE customerid = :customerid');
    $query -> bindParam(':customerid', $custid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query->rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'Measurement data does not exist for this customer'); exit();
    }

    $rows = $query->fetch(PDO::FETCH_ASSOC);

    //Build the display arrays
    $UBmetadata = array();
    foreach ($UB as $data => $value) {
        $UBmetadata[$data] = $value;
    }
    $LBmetadata = array();
    foreach ($LB as $data => $value) {
        $LBmetadata[$data] = $value;
    }

    $UBmeasurements = array();
    foreach ($rows as $parameter => $value) {
        if (isset($UBmetadata[$parameter])) {
            $metadataValue = $UBmetadata[$parameter];
            $UBmeasurements[$parameter] = array(
                'value' => $value,
                'metadata' => $metadataValue
            );
        }
    }
    $LBmeasurements = array();
    foreach ($rows as $parameter => $value) {
        if (isset($LBmetadata[$parameter])) {
            $metadataValue = $LBmetadata[$parameter];
            $LBmeasurements[$parameter] = array(
                'value' => $value,
                'metadata' => $metadataValue
            );
        }
    }
    
    //return data in an array
    $returnData = array();
    $returnData['UB'] = $UBmeasurements;
    $returnData['LB'] = $LBmeasurements;

    sendResponse(200, true, null, $returnData, true); // allow caching
    exit();

}
catch (PDOException $e){
    responseServerException($e, 'Error in retrieving measurements');
}