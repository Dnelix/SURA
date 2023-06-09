<?php

try{

    $email      =   $jsonData->emailaddress;
    $password   =   $jsonData->confirmemailpassword;
    // Hash Password
    $hash_pass = password_hash($password, PASSWORD_DEFAULT);

    if ($email == '' || $password == ''){
        sendResponse(400, false, 'You must provide both an email address and your account password to proceed');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(400, false, 'Invalid email address provided');
    }

    //confirm details
    $query = $writeDB -> prepare('SELECT password, active, loginattempts FROM tbl_users WHERE id = :userid');
    $query -> bindParam(':userid', $userid, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0 || $rowCount > 1){
        sendResponse(401, false, 'Account not found or invalid!');
    }
    $row  =  $query -> fetch(PDO::FETCH_ASSOC);
    $ret_password       = $row['password'];
    $ret_active         = $row['active'];
    $ret_loginattempts  = $row['loginattempts'];

    //verify that the email does not already exist
    $query = $writeDB -> prepare('SELECT email FROM tbl_users WHERE email = :email');
    $query -> bindParam(':email', $email, PDO::PARAM_STR);
    $query -> execute();
    $rowCount = $query -> rowCount();
    if($rowCount > 0){
        sendResponse(401, false, 'This email is already in use! Please use another email.');
    }

    //Other checks
    if($ret_active !== "1"){    
        sendResponse(401, false, 'User account is not active!');
    }

    if($ret_loginattempts >= $max_loginattempts){
        sendResponse(401, false, 'Number of attempts exceeded! User account have been locked out.');
    }

    if(!password_verify($password, $ret_password)) { 
        // increment login attempts
        $query = $writeDB->prepare('UPDATE tbl_users set loginattempts = loginattempts+1 WHERE id = :id');
        $query -> bindParam(':id', $userid, PDO::PARAM_INT);
        $query -> execute();

        sendResponse(401, false, 'Password is incorrect! Your account will be locked if you enter an incorrect password '. $max_loginattempts .' times');
    }

    //update email and loginattempts
    $query = $writeDB -> prepare('UPDATE tbl_users SET email = :email, loginattempts = 0 WHERE id = :id');
    $query -> bindParam(':id', $userid, PDO::PARAM_INT);
    $query -> bindParam(':email', $email, PDO::PARAM_STR);
    $query -> execute();

    //return data
    $returnData = array();
    $returnData['user_id'] = $userid;
    $returnData['new_email'] = $email;

    sendResponse(201, true, 'Account email successfully changed', $returnData);
}
catch(Exception $e){
    responseServerException($e, 'Failed to update record. Check for errors');
}

?>