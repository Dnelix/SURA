<?php

try{
    $deactivate  =   $jsonData->deactivate;

    if ($deactivate !== true && $deactivate !== "true"){
        sendResponse(400, false, 'You must confirm that you wish to deactivate your account');
    }

    //confirm details
    $query = $writeDB -> prepare('SELECT id, active FROM tbl_users WHERE id = :userid');
    $query -> bindParam(':userid', $userid, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0 || $rowCount > 1){
        sendResponse(401, false, 'Account not found or invalid!');
    }
    $row  =  $query -> fetch(PDO::FETCH_ASSOC);
    $ret_active = $row['active'];

    //Other checks
    if($ret_active !== "1"){    
        sendResponse(401, false, 'User account is already inactive!');
    }

    //update password and loginattempts
    $query = $writeDB -> prepare('UPDATE tbl_users SET active = "0" WHERE id = :id');
    $query -> bindParam(':id', $userid, PDO::PARAM_INT);
    $query -> execute();

    //return data
    $returnData = array();
    $returnData['user_id'] = $userid;
    $returnData['active'] = 0;

    sendResponse(201, true, 'Account has been deactivated', $returnData);
}
catch(Exception $e){
    responseServerException($e, 'Failed to update record. Check for errors');
}

?>