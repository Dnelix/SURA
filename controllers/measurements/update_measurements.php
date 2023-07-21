<?php

$jsonData = validateJsonRequest();

// You might wanna include conditions that ensure only a customer or his tailor can update measurements

try{
    $updated        = false;                            //this will track updated columns
    $queryFields    = "";                               //an empty string that will form part of the sql and dynamically hold fields that should be updated

    foreach($measurement_parameters_array as $field){ 
        $$field = false;                                // create a variable with the column name and set to false

        if(isset($jsonData->$field) && !empty($jsonData->$field)){ // if the field exists in the decoded json
            $queryFields .= "{$field} = :{$field}, ";   //append to queryFields string. Don't forget the comma and space (this will not work for date fields)
            $$field = true;                             //update the status of the column variable to true
            $updated = true;                            // show that at least one field is updated
        }
    }     

    
    $queryFields = rtrim($queryFields, ", ");           //remove the last (right most) comma and space from the queryfields string 

    if (!$updated) {
        sendResponse(400, false, 'You have not made any changes');
    }

    // confirm the record exists
    $query = $writeDB -> prepare('SELECT id FROM tbl_measurements WHERE customerid = :cid');
    $query -> bindParam(':cid', $custid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount <= 0){
        sendResponse(409, false, 'Measurement data does not exist for this customer. Add measurements first'); exit();
    }

    //write out the query string. Concatenate queryfields
    $queryString = "UPDATE tbl_measurements SET ".$queryFields." WHERE customerid = :cid";
    $query = $writeDB -> prepare($queryString);
    $query -> bindParam(':cid', $custid, PDO::PARAM_INT);

    foreach($measurement_parameters_array as $field){                               // check for the fields that have been updated and bind them to the query
        if($$field === true){
            $query -> bindValue(":{$field}", $jsonData->$field, PDO::PARAM_STR);    //use bindValue() because of the dynamic values
        }
    }

    $query -> execute();
    $errorInfo = $query->errorInfo(); //catch error

    $rowCount = $query->rowCount();
    if($rowCount === 0){
        sendResponse(400, false, 'You have not updated this record');
    }

    // return newly updated records
    $table_columns = implode(", ", $measurement_parameters_array);

    $query = $writeDB -> prepare('SELECT '. $table_columns .' FROM tbl_measurements WHERE customerid = :cid');
    $query -> bindParam(':cid', $custid, PDO::PARAM_INT);
    $query -> execute();
    
    $rowCount = $query -> rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'No record found after update');
    }
    
    $returnData = array();

    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        foreach ($row as $columnName => $value){
            $returnData[$columnName] = $value;
        }
    }
    
    sendResponse(200, true, "Measurements Updated!", $returnData);
    exit();

}
catch (PDOException $e){
    responseServerException($e, 'Failed to update record. Check for errors');
}