<?php
/*
    PAYLOAD
    {
        phone*:'user phone or 000000000',
        email*:'user email or username@sura.ng',
        Password*:'user pass or newUser@x1',
        bizname:'Business name or empty', //for a business
        OR
        tailor:'userID or 0'  //for a customer
    }
*/

//check if the content is JSON and retrieve
$jsonData = validateJsonRequest();

//confirm user role
if(array_key_exists('type', $_GET)){
    $role = strtolower($_GET['type']);
} else if(isset($jsonData->role)){
    $role = strtolower($jsonData->role);
} else {
    $role = isset($jsonData->bizname) ? "business" : "customer";
}

//then process data depending on user role
try{
    //You cannot use transaction rollback if a part of the code is waiting on last_insert_id

    if($role === 'customer'){
        require_once('create/create_customer.php');
    } else if ($role === 'business'){
        require_once('create/create_business.php');
    } else {
        sendResponse(400, false, 'Cannot create user, invalid user role!');
        exit();
    }
    
    //finally, create measurements record for the user
    $insertID = $returnData['user_id'];
    $measure = sendToController('nodata', 'controllers/measurements.php?customer='.$insertID);
    $returnData['measurements'] = $measure;
    
    sendResponse(201, true, 'User Data Created!', $returnData);
    
}
catch(PDOException $e){
    responseServerException($e, 'There was an issue with user creation. Please try again');
}

?>