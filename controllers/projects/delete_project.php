<?php

try{

    // confirm the record exists
    $query = $readDB -> prepare ('SELECT id FROM tbl_projects WHERE id = :pid AND tailorid = :tid');            
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
    $query -> bindParam(':tid', $tailorid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'Record not found. Delete failed!');
    }

    $returnData = array();
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        foreach ($row as $columnName => $value){
            $returnData[$columnName] = $value;
        }
    }

    // Delete the record
    $query = $readDB -> prepare ('DELETE FROM tbl_projects WHERE id = :pid AND tailorid = :tid');            
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
    $query -> bindParam(':tid', $tailorid, PDO::PARAM_INT);
    $query -> execute();

    // Check
    $query = $readDB -> prepare ('SELECT id FROM tbl_projects WHERE id = :pid AND tailorid = :tid');            
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
    $query -> bindParam(':tid', $tailorid, PDO::PARAM_INT);
    $query -> execute();
    $rowCount = $query -> rowCount();
    if($rowCount > 0){
        sendResponse(404, false, 'Failed to delete the record');
    }

    sendResponse(200, true, "Project deleted successfully", $returnData);
    exit();
}
catch(PDOException $e){
    responseServerException($e, 'Failed to delete record. Check for errors');
}