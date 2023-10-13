<?php

try{
    //confirm details
    $query = $readDB -> prepare('SELECT id, username, email, phone, active FROM tbl_users WHERE username = :username OR email = :email');
    $query -> bindParam(':username', $resetUserData, PDO::PARAM_STR);
    $query -> bindParam(':email', $resetUserData, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0 || $rowCount > 1){
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

    //Create reset URL and email body
    $url = $c_website.$newPassword_link."&usr=".$ret_username."&i=".md5($ret_phone);

    $message = "
        <p> You are getting this mail because you requested to reset your password on $company. </p>
        <p> click on the button below to change your password. </p>
        <p>
            <a class='button' href='$url' title='Reset Password' style='width:100%; background-color:$color_pri; text-decoration:none; display:inline-block; padding:10px 0; color:#fff; font-size:14px; line-height:21px; text-align:center; font-weight:bold; border-radius:7px;'>Change My Password</a>
        </p>
        <p> Alternatively, if the button doesn't work, copy and paste this link in your browser: </p> 
        <p style='max-width:450px; overflow-wrap: break-word; line-height:100%'> <code style='background-color:#faeec3;'> $url </code> </p>
        <p> If you did not initiate this request, simply ignore this mail and <b>do not share the link with anyone</b>.</p>
    ";

    //sendEmail($type, $subject, $to_mail, $to_name='', $message='', $sender='')
    $emailOutput = sendEmail('general', 'Reset your '.$company.' password!', $ret_email, $ret_username, $message);
    sendResponse(200, true, $emailOutput);
}
catch(Exception $e){
    responseServerException($e, 'Failed to reset password! Check for errors');
}