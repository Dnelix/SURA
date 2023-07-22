<?php
try {
    
    $columns = explode(', ', $all_fields);              // from the $all_fields list
    foreach($columns as $field){ $$field = false; }     // create a variable with the column name and set to false
    $updated = false;

    $queryFields = "";

    foreach($columns as $field){
        if(isset($jsonData->$field)){                   // if the field exists in the decoded json
            $queryFields .= "{$field} = :{$field}, ";   //append to queryFields string. Don't forget the comma and space (this will not work for date fields)
            $$field = true;                             //update the status of the column variable to true
            $updated = true;                            // show that at least one field is updated
        }
    }

    $queryFields = rtrim($queryFields, ", ");           //remove the last (right most) comma and space from the queryfields string

    if (!$updated) {                                    //check that at least one variable have been updated to true
        sendResponse(400, false, 'You have not made any changes');
    }

    // confirm the record exists
    $query = $readDB -> prepare ('SELECT id FROM tbl_projects WHERE id = :pid AND tailorid = :tid');            
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
    $query -> bindParam(':tid', $tailorid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'Record not found. Update failed!');
    }
    
    //write out the query string. Concatenate queryfields
    $queryString = "UPDATE tbl_projects SET ".$queryFields." WHERE id = :pid AND tailorid = :tid";
    $query = $writeDB -> prepare($queryString);
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
    $query -> bindParam(':tid', $tailorid, PDO::PARAM_INT);
    
    foreach($columns as $field){                            // check for the fields that have been updated and bind them to the query
        if($$field === true){
            $query -> bindValue(":{$field}", $jsonData->$field, PDO::PARAM_STR);   //use bindValue() because of the dynamic values
        }
    }
    
    //execute
    $query -> execute();
    $errorInfo = $query->errorInfo();
    $rowCount = $query->rowCount();
    if($rowCount === 0){
        sendResponse(400, false, 'You have not updated this record');
    }
    
    //return the newly updated record
    $table_columns = $all_fields;

    $query = $writeDB -> prepare('SELECT '. $table_columns .' FROM tbl_projects WHERE id = :pid');
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
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
    
    sendResponse(200, true, "Project Record Updated", $returnData);
    exit();
    
}
catch (PDOException $e){
    responseServerException($e, 'Failed to update record. Check for errors');
}

?>