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

    //then perform basic checks on data
    //1. ensure all mandatory fields are provided
    if(!isset($jsonData->phone) || !isset($jsonData->email) || !isset($jsonData->password)){

        $message = (!isset($jsonData->phone) ? 'Phone number not provided' : false);
        $message .= (!isset($jsonData->email) ? 'Email Address not provided' : false);
        $message .= (!isset($jsonData->password) ? 'Password not provided' : false);

        sendResponse(400, false, $message);
    }

    //2. check if the strings are empty or have values above the DB limits
    if(strlen($jsonData->phone) < 1 || strlen($jsonData->phone) > 15 || strlen($jsonData->email) < 1 || strlen($jsonData->email) > 100 || strlen($jsonData->password) < 1 || strlen($jsonData->password) > 255){

        $message = (strlen($jsonData->phone) < 1 ? 'Phone Number cannot be blank' : false);
        $message .= (strlen($jsonData->phone) > 15 ? 'Phone number cannot be greater than 15 characters' : false);
        $message .= (strlen($jsonData->email) < 1 ? 'Email Address cannot be blank' : false);
        $message .= (strlen($jsonData->email) > 100 ? 'Email Address cannot be greater than 100 characters' : false);
        $message .= (strlen($jsonData->password) < 1 ? 'Password cannot be blank' : false);
        $message .= (strlen($jsonData->password) > 255 ? 'Password cannot be greater than 255 characters' : false);

        sendResponse(400, false, $message);
    }

    //3. Collate data and strip off white spaces
    $phone = trim($jsonData->phone); //trim automatically removes extra leading or preceeding white space
    $email = trim($jsonData->email);
    $password = $jsonData->password; // don't trim passwords
    $email_parts = explode("@", $email);
    $username = strtolower($email_parts[0]); //create temporal username from email
    $createdon = date('d/m/Y H:i');

    $role = (isset($jsonData->bizname) ? "business" : "customer" ); //define role
    $bizname = (isset($jsonData->bizname) ? $jsonData->bizname : "" ); //get business name if it is set
    $tailor = (isset($jsonData->tailor) ? $jsonData->tailor : 0 ); //get initiating userID if it is set

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(400, false, 'Invalid email address provided');
    }

    //4. Check if user already exists
    try{

        $query = $writeDB -> prepare('SELECT id FROM tbl_users WHERE username = :username OR phone = :phone');
        $query -> bindParam(':username', $username, PDO::PARAM_STR);
        $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query -> rowCount();

        if($rowCount !== 0){
            sendResponse(409, false, 'Email address or Phone number already exists'); exit();
        }

        // Hash Password
        $hash_pass = password_hash($password, PASSWORD_DEFAULT); //hash using the standard PHP hashing

        //Insert record into user table
        $query = $writeDB -> prepare('INSERT INTO tbl_users (username, password, email, phone, active, role, createdon, profile_completion) VALUES(:username, :password, :email, :phone, "1", :role, STR_TO_DATE(:createdon, '.$write_dateformat.'), 50)');
        $query -> bindParam(':username', $username, PDO::PARAM_STR);
        $query -> bindParam(':password', $hash_pass, PDO::PARAM_STR);
        $query -> bindParam(':email', $email, PDO::PARAM_STR);
        $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
        $query -> bindParam(':role', $role, PDO::PARAM_STR);
        $query -> bindParam(':createdon', $createdon, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query -> rowCount();
        $lastID = $writeDB->lastInsertId();

        if($rowCount === 0) {
            sendResponse(500, false, 'Unable to create user data in DB'); exit();
        }
        
        //if it is a business, insert business details into business table
        if ($role === "business") {
            $query = $writeDB -> prepare('INSERT INTO tbl_business (owner, name, phone, email, currency) VALUES(:owner, :name, :phone, :email, :currency)');
            $query -> bindParam(':owner', $lastID, PDO::PARAM_INT);
            $query -> bindParam(':name', $bizname, PDO::PARAM_STR);
            $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
            $query -> bindParam(':email', $email, PDO::PARAM_STR);
            $query -> bindParam(':currency', $defaultcurrency, PDO::PARAM_STR);
            $query -> execute();

            $rowCount = $query -> rowCount();
            if($rowCount === 0) {
                sendResponse(500, false, 'Unable to create business data in DB'); exit();
            }
        }

        //else create record in customer table
        else if($role === "customer"){
            $query = $writeDB -> prepare('INSERT INTO tbl_customers (customerid, tailorid) VALUES(:customerid, :tailorid)');
            $query -> bindParam(':customerid', $lastID, PDO::PARAM_INT);
            $query -> bindParam(':tailorid', $tailor, PDO::PARAM_INT);
            $query -> execute();

            $rowCount = $query -> rowCount();
            if($rowCount === 0) {
                sendResponse(500, false, 'Unable to create customer data in DB'); exit();
            }
        }
        else {
            sendResponse(500, false, 'Unable to create additional data. User is neither customer nor business'); exit();
        }
        
        //return the newly created user details (w/o password)
        $returnData = array();
        $returnData['user_id'] = $lastID;
        $returnData['username'] = $username;
        $returnData['password'] = $hash_pass;
        $returnData['email'] = $email;
        $returnData['phone'] = $phone;
        $returnData['active'] = 1;
        $returnData['createdon'] = $createdon;
        $returnData['profile_completion'] = '50%';

        sendResponse(201, true, 'User Created', $returnData);

    }
    catch (PDOException $e){
        responseServerException($e, 'An error occurred while creating user account. Please try again');
    }



?>