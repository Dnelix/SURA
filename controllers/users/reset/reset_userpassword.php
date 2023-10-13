<?php
$jsonData = validateJsonRequest();

try{
    $usr            =   (isset($jsonData->usr) && !empty($jsonData->usr)) ? $jsonData->usr : $resetUserData; //email or username
    $p_hash         =   $jsonData->i;   //md5 hashed phone number
    $new_pass       =   $jsonData->password;
    $new_pass_conf  =   $jsonData->confirmpassword;
    // Hash Password
    $hash_new = password_hash($new_pass, PASSWORD_DEFAULT);

    if ($new_pass == '' || $p_hash == ''){
        sendResponse(400, false, 'Some required information missing'); exit();
    }

    if ($new_pass !== $new_pass_conf){
        sendResponse(400, false, 'Password mismatch!'); exit();
    }

    //confirm details
    $query = $writeDB -> prepare('SELECT id, username, email, phone, active FROM tbl_users WHERE username = :username OR email = :email');
    $query -> bindParam(':username', $usr, PDO::PARAM_STR);
    $query -> bindParam(':email', $usr, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount < 1){
        sendResponse(401, false, 'Account not found or invalid!');
    }
    $row  =  $query -> fetch(PDO::FETCH_ASSOC);
    $ret_id         = $row['id'];
    $ret_username   = $row['username'];
    $ret_email      = $row['email'];
    $ret_phone      = $row['phone'];
    $ret_active     = $row['active'];

    //Other checks
    if($ret_active !== "1"){    
        sendResponse(401, false, 'This user account is inactive! Please contact customer service to activate your account');
    }

    if(md5($ret_phone) !== $p_hash){    
        sendResponse(401, false, 'Invalid authorization code!');
    }

    //update password and loginattempts
    $query = $writeDB -> prepare('UPDATE tbl_users SET password = :password, loginattempts = 0 WHERE id = :id AND username = :username');
    $query -> bindParam(':id', $ret_id, PDO::PARAM_INT);
    $query -> bindParam(':password', $hash_new, PDO::PARAM_STR);
    $query -> bindParam(':username', $ret_username, PDO::PARAM_STR);
    $query -> execute();

    //return data
    $returnData = array();
    $returnData['user_id'] = $ret_id;
    $returnData['username'] = $ret_username;
    
    //send email
    $message = '
    <p> Your password have been successfully reset! We hope you found the process to be smooth? Be free to write us back if you have suggestions to improve our service to you.</p>
    <p> Additionally, if you didn\'t request this action, contact us immediately so we can secure your account from possible hackers </p>
    <p> <i>We hope you never stop enjoying our services!</i> </p>
    ';
    $emailOutput = sendEmail('general', 'Your password was just reset', $ret_email, $ret_username, $message, 'Felix');
    $returnData['email_feedback'] = $emailOutput;

    //response
    sendResponse(201, true, 'Yeee! You have successfully reset your password. PROCEED to login with your new credentials', $returnData);
}
catch(Exception $e){
    responseServerException($e, 'Failed to update record. Check for errors');
}

?>