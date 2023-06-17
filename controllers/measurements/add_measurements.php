<?php

$jsonData = validateJsonRequest();

try{
    // confirm the user exists
    $query = $writeDB -> prepare('SELECT id FROM tbl_users WHERE id = :cid');
    $query -> bindParam(':cid', $custid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount <= 0){
        sendResponse(409, false, 'This user does not exist'); exit();
    }

    // confirm the user's measurement have not been created before
    $query = $writeDB -> prepare('SELECT id FROM tbl_measurements WHERE customerid = :cid');
    $query -> bindParam(':cid', $custid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount > 0){
        sendResponse(409, false, 'Measurement data already exist for this customer. Please update the customer measurements instead'); exit();
    }

    // Define an empty array to store the values of each input
    $values = array();

    // Loop over all the column names and get their corresponding values from the form
    foreach ($measurement_parameters_array as $measure) {
        if (isset($jsonData -> $measure)) {
            $value = filter_var($jsonData->$measure, FILTER_SANITIZE_STRING); // Sanitize 
            $values[] = $value; // Add the sanitized value to the array
        } else {
            $values[] = 0; // If input is not set, add 0 to the array
        }
    }

    $table_columns = implode(", ", $measurement_parameters_array);
    $table_values = implode(", ", $values);

    $sql = 'INSERT INTO tbl_measurements (customerid, ' . $table_columns . ') VALUES (:customerid, ' . $table_values . ')';
    $query = $writeDB->prepare($sql);
    $query -> bindParam(':customerid', $custid, PDO::PARAM_INT);
    $result = $query->execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0) {
        sendResponse(500, false, 'Unable to create customer measurements in DB'); exit();
    }
    
    $returnData = array();
    foreach ($measurement_parameters_array as $measure) {
        if (isset($jsonData -> $measure) && !empty($jsonData->$measure)) {
            $returnData[$measure] = $jsonData -> $measure;
        }
    }
    sendResponse(201, true, 'Measurements added for customer', $returnData);

}
catch (PDOException $e){
    responseServerException($e, 'An error occurred while creating customer measurements. Please try again');
}