<?php

try{
    $status  =   ucWords($jsonData->status);
    $completion = (isset($jsonData->completion) ? $jsonData->completion : 0);
    switch ($status){
        case "Completed":
            $completion = ($completion===0) ? 100 : $completion;
            break;
        case "Not Started":
        case "Delayed":
            $completion = ($completion===0) ? 10 : $completion;
            break;
        case "In Progress":
            $completion = ($completion===0) ? 50 : $completion;
            break;

        default:
            $completion = ($completion===0) ? 50 : $completion;
            break;
    }

    //update project status and completion
    $query = $writeDB -> prepare('UPDATE tbl_projects SET status = :status, completion = :completion WHERE id = :pid AND tailorid = :tid');
    $query -> bindParam(':status', $status, PDO::PARAM_STR);
    $query -> bindParam(':completion', $completion, PDO::PARAM_STR);
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
    $query -> bindParam(':tid', $tailorid, PDO::PARAM_INT);
    $query -> execute();

    //return data
    $returnData = array();
    $returnData['pid'] = $pid;
    $returnData['status'] = $status;
    $returnData['completion'] = $completion;

    sendResponse(201, true, 'Status Updated', $returnData);
}
catch(Exception $e){
    responseServerException($e, 'Failed to update project status. Check for errors');
}

?>