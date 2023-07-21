<?php
/*
PAYLOAD
{
    fullname*:'customer full name',
    phone*:'user phone or 000000000',
    email:'user email or username@sura.ng',
    tailor:'userID or 0'
    Password:'DEFAULT-newUser@x1',
    measures: {....}
}
*/
    //check if the content is JSON and retrieve
    $jsonData = validateJsonRequest();

    //1. ensure all mandatory fields are provided
    if(!isset($jsonData->phone) || !isset($jsonData->fullname) || !isset($jsonData->tailor)){
        $message = "";
        $message .= (!isset($jsonData->phone) ? 'Phone number not provided' : false);
        $message .= (!isset($jsonData->fullname) ? 'Customer name not provided' : false);
        $message .= (!isset($jsonData->tailor) ? 'Every customer must be assigned to a tailor' : false);

        sendResponse(400, false, $message);
    }

    //2. check if the strings are empty or have values above the DB limits
    if(strlen($jsonData->phone) < 1 || strlen($jsonData->phone) > 15 || strlen($jsonData->fullname) < 1 || strlen($jsonData->fullname) > 50){
        $message = "";
        $message .= (strlen($jsonData->phone) < 1 ? 'Phone Number cannot be blank' : false);
        $message .= (strlen($jsonData->phone) > 15 ? 'Phone number cannot be greater than 15 characters' : false);
        $message .= (strlen($jsonData->email) < 1 ? 'Customer name cannot be blank' : false);
        $message .= (strlen($jsonData->email) > 50 ? 'Customer name cannot be greater than 50 characters' : false);

        sendResponse(400, false, $message);
    }

    //3. Collate data and strip off white spaces
    $name = trim($jsonData->fullname);
    $phone = trim($jsonData->phone);
    $email = (isset($jsonData->email) ? trim($jsonData->email) : "" );
    $tailor = (isset($jsonData->tailor) ? $jsonData->tailor : 0 );
    $role = "customer";

    //4. Set defaults
    if ($email !== ""){
        $email_parts = explode("@", $email);
        $username = strtolower($email_parts[0]); //create temporal username from email
    } else {
        $username = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $name).date('s')); // create temporal username from fullname
        $email = $username.'@'.$c_shortsite; // create temporal email as well
    }
    $password = getRandomPassword(8); //generate a random 8-digit password
    $createdon = date('d/m/Y H:i');

    //5. Check if customer already exists under this tailor
    try{
        $query = $writeDB -> prepare('SELECT c.id FROM tbl_customers c INNER JOIN tbl_users u ON c.customerid = u.id WHERE u.phone = :phone OR u.email = :email AND c.tailorid = :tailorid');
        $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
        $query -> bindParam(':email', $email, PDO::PARAM_STR);
        $query -> bindParam(':tailorid', $tailor, PDO::PARAM_INT);
        $query -> execute();

        $rowCount = $query -> rowCount();
        if($rowCount !== 0){
            sendResponse(409, false, 'Customer already exists for tailor'); exit();
        }

        // Hash Password
        $hash_pass = password_hash($password, PASSWORD_DEFAULT); //hash using the standard PHP hashing

        //6. Insert record into user table
        $query = $writeDB -> prepare('INSERT INTO tbl_users (username, password, email, phone, fullname, active, role, createdon) VALUES(:username, :password, :email, :phone, :fullname, "1", :role, STR_TO_DATE(:createdon, \'%d/%m/%Y %H:%i\'))');
        $query -> bindParam(':username', $username, PDO::PARAM_STR);
        $query -> bindParam(':password', $hash_pass, PDO::PARAM_STR);
        $query -> bindParam(':email', $email, PDO::PARAM_STR);
        $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
        $query -> bindParam(':fullname', $name, PDO::PARAM_STR);
        $query -> bindParam(':role', $role, PDO::PARAM_STR);
        $query -> bindParam(':createdon', $createdon, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query -> rowCount();
        $lastID = $writeDB->lastInsertId();

        if($rowCount === 0) {
            sendResponse(500, false, 'Unable to create user data in user DB'); exit();
        }

        //7. create record in customer table
        $query = $writeDB -> prepare('INSERT INTO tbl_customers (customerid, tailorid, rating) VALUES(:customerid, :tailorid, 0)');
        $query -> bindParam(':customerid', $lastID, PDO::PARAM_INT);
        $query -> bindParam(':tailorid', $tailor, PDO::PARAM_INT);
        $query -> execute();

        $rowCount = $query -> rowCount();
        if($rowCount === 0) {
            sendResponse(500, false, 'Unable to create customer data in customer DB'); exit();
        }

        //8. INSERT CUSTOMER MEASUREMENTS IN DB
        $values = array(); // Define an empty array to store the values of each input

        // Loop over all the column names and get their corresponding values from the form. measurement_parameters_array is defined in customers index page
        foreach ($measurement_parameters_array as $measure) {
            if (isset($jsonData -> $measure)) {
                $value = filter_var($jsonData->$measure, FILTER_SANITIZE_STRING); // Sanitize 
                $values[] = $value; // Add the sanitized value to the array
            } else {
                $values[] = 0; // If input is not set, add 0 to the array
            }
        }

        $table_columns = implode(", ", $measurement_parameters_array);
        $table_values = implode(", ", $values);

        $sql = 'INSERT INTO tbl_measurements (customerid, ' . $table_columns . ') VALUES (:customerid, ' . $table_values . ')';
        $query = $writeDB->prepare($sql);
        $query -> bindParam(':customerid', $lastID, PDO::PARAM_INT);
        $result = $query->execute();

        $rowCount = $query -> rowCount();
        if($rowCount === 0) {
            sendResponse(500, false, 'Unable to create customer measurements in DB'); exit();
        }
        
        //9. return the newly created user details
        $returnData = array();
        $returnData['user_id'] = $lastID;
        $returnData['username'] = $username;
        $returnData['fullname'] = $name;
        $returnData['password'] = $hash_pass;
        $returnData['email'] = $email;
        $returnData['phone'] = $phone;
        $returnData['active'] = 1;
        $returnData['createdon'] = $createdon;

        sendResponse(201, true, 'Customer Created', $returnData);

    }
    catch (PDOException $e){
        responseServerException($e, 'An error occurred while creating customer account. Please try again');
    }


?>