<?php

try {
    //keep track of all columns in the db_table that can potentially be updated
    $columns = array('email','phone','fullname','photo','active','role');
    foreach($columns as $field){ $$field = false; }     // create a variable with the column name and set to false
    $updated = false;

    //create an empty string that will form part of your sql and dynamically hold fields that should be updated
    $queryFields = "";

    //then we write the sql statement to import only the fields that have been provided in the JSON data into the query string.
    foreach($columns as $field){
        if(isset($jsonData->$field)){                   // if the field exists in the decoded json
            $queryFields .= "{$field} = :{$field}, ";   //append to queryFields string. Don't forget the comma and space (this will not work for date fields)
            $$field = true;                             //update the status of the column variable to true
            $updated = true;                            // show that at least one field is updated
        }
    }

    //remove the last (right most) comma and space from the queryfields string 
    $queryFields = rtrim($queryFields, ", ");

    //check that at least one variable have been updated to true
    if (!$updated) {
        sendResponse(400, false, 'You have not made any changes');
    }

    // confirm the record exists
    $query = $readDB -> prepare ('SELECT id FROM tbl_users WHERE id = :userid');            
    $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'Record not found. Update failed!');
    }
    
    //write out the query string. Concatenate queryfields
    $queryString = "UPDATE tbl_users SET ".$queryFields." WHERE id = :userid";
    $query = $writeDB -> prepare($queryString);
    $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
    // check for the fields that have been updated and bind them to the query
    foreach($columns as $field){
        if($$field === true){
            $query -> bindValue(":{$field}", $jsonData->$field, PDO::PARAM_STR);        //use bindValue() because of the dynamic values
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
    $table_columns = implode(", ", $columns);

    $query = $writeDB -> prepare('SELECT '. $table_columns .' FROM tbl_users WHERE id = :userid');
    $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
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
    
    sendResponse(200, true, "User Record Updated", $returnData);
    exit();
    
}
catch (PDOException $e){
    responseServerException($e, 'Failed to update record. Check for errors');
}

?>