<?php
try{
    //1. validate mandatory fields
    $mandatoryFields = array('phone', 'email', 'password');
    $errorMsg = validateMandatoryFields($jsonData, $mandatoryFields);
    if (!empty($errorMsg)) {
        sendResponse(400, false, $errorMsg); exit();
    }

    //2. validate lengths
    if(strlen($jsonData->phone) < 1 || strlen($jsonData->phone) > 15 || strlen($jsonData->email) < 1 || strlen($jsonData->email) > 100 || strlen($jsonData->password) < 6 || strlen($jsonData->password) > 255){
        $message = "";
        $message .= (strlen($jsonData->phone) < 1 ? 'Phone Number cannot be blank. ' : false);
        $message .= (strlen($jsonData->phone) > 15 ? 'Phone number cannot be greater than 15 characters. ' : false);
        $message .= (strlen($jsonData->email) < 1 ? 'Email Address cannot be blank. ' : false);
        $message .= (strlen($jsonData->email) > 100 ? 'Email Address cannot be greater than 100 characters. ' : false);
        $message .= (strlen($jsonData->password) < 6 ? 'Password cannot be less than 6 characters. ' : false);
        $message .= (strlen($jsonData->password) > 255 ? 'Password cannot be greater than 255 characters. ' : false);

        sendResponse(400, false, $message); exit();
    }

    //3. Collate data and strip off white spaces
    $phone      = trim($jsonData->phone);
    $email      = trim($jsonData->email);
    $password   = $jsonData->password; 
    //set defaults
    $email_parts = explode("@", $email);
    $username   = strtolower($email_parts[0]); //create temporal username from email
    $createdon  = date($dateformat);
    $bizname    = (isset($jsonData->bizname) ? $jsonData->bizname : "" );

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(400, false, 'Invalid email address provided'); exit();
    }

    //4. Check if user already exists
    $query = $writeDB -> prepare('SELECT id FROM tbl_users WHERE username = :username OR phone = :phone OR email = :email');
    $query -> bindParam(':username', $username, PDO::PARAM_STR);
    $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
    $query -> bindParam(':email', $email, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();

    if($rowCount !== 0){
        sendResponse(409, false, 'Email address or Phone number already exists'); exit();
    }

    //5. Hash Password
    $hash_pass = password_hash($password, PASSWORD_DEFAULT); //hash using the standard PHP hashing

    //6. Insert record into user table
    $query = $writeDB -> prepare('INSERT INTO tbl_users (username, password, email, phone, active, role, createdon) VALUES(:username, :password, :email, :phone, "1", :role, STR_TO_DATE(:createdon, '.$write_dateformat.'))');
    $query -> bindParam(':username', $username, PDO::PARAM_STR);
    $query -> bindParam(':password', $hash_pass, PDO::PARAM_STR);
    $query -> bindParam(':email', $email, PDO::PARAM_STR);
    $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
    $query -> bindParam(':role', $role, PDO::PARAM_STR);
    $query -> bindParam(':createdon', $createdon, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0) {
        sendResponse(500, false, 'Unable to create user data in DB'); exit();
    }
    
    //7. obtain the insertID
    $insertID = $writeDB->lastInsertId();

    //8. Insert record into business table
    $query = $writeDB -> prepare('INSERT INTO tbl_business (owner, name, phone, email, currency) VALUES(:owner, :name, :phone, :email, :currency)');
    $query -> bindParam(':owner', $insertID, PDO::PARAM_INT);
    $query -> bindParam(':name', $bizname, PDO::PARAM_STR);
    $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
    $query -> bindParam(':email', $email, PDO::PARAM_STR);
    $query -> bindParam(':currency', $defaultcurrency, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0) {
        sendResponse(500, false, 'Unable to create business data in DB'); exit();
    }

    //9. return the newly created user details (w/o password)
    $returnData = array();
    $returnData['user_id'] = $insertID;
    $returnData['username'] = $username;
    $returnData['password'] = $hash_pass;
    $returnData['email'] = $email;
    $returnData['phone'] = $phone;
    $returnData['active'] = 1;
    $returnData['createdon'] = $createdon;
    //sendResponse(201, true, 'User Created', $returnData);

    //10. send email
    $subject = "We're glad you're here!";
    $message = "We love to make your work easier. Say goodbye to all the hassles associated with maintaining your customer data. Login to {$c_shortsite} to get started.";
    $sendMail = sendEmail('welcome', $subject, $email, $username, $message, 'Felix');
    $returnData['email'] = $sendMail;
}
catch (PDOException $e){
    responseServerException($e, 'An error occurred while creating business account. Please try again');
}

?>