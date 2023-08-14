<?php
try{
    //1. validate mandatory fields
    $mandatoryFields = array('fullname', 'phone', 'tailor', 'password');
    $errorMsg = validateMandatoryFields($jsonData, $mandatoryFields);
    if (!empty($errorMsg)) {
        sendResponse(400, false, $errorMsg); exit();
    }

    //2. Check if the strings are empty or have values above the DB limits
    if(strlen($jsonData->phone) < 1 || strlen($jsonData->phone) > 15 || strlen($jsonData->fullname) < 1 || strlen($jsonData->fullname) > 50){
        $message = "";
        $message .= (strlen($jsonData->phone) < 1 ? 'Phone Number cannot be blank. ' : false);
        $message .= (strlen($jsonData->phone) > 15 ? 'Phone number cannot be greater than 15 characters. ' : false);
        $message .= (strlen($jsonData->fullname) < 1 ? 'Customer name cannot be blank. ' : false);
        $message .= (strlen($jsonData->fullname) > 50 ? 'Customer name cannot be greater than 50 characters. ' : false);

        sendResponse(400, false, $message);
    }

    //3. Collate data and strip off white spaces
    $name = trim($jsonData->fullname);
    $phone = trim($jsonData->phone);
    $tailor = (isset($jsonData->tailor) ? $jsonData->tailor : 0 );
    $email = (isset($jsonData->email) ? trim($jsonData->email) : "" );
    $password = isset($jsonData->password) ? $jsonData->password : getRandomPassword(8); //generate a random 8-digit password

    //4. Set defaults
    $createdon = date($dateformat);
    if ($email !== ""){
        $email_parts = explode("@", $email);
        $username = strtolower($email_parts[0]); //create temporal username from email
    } else {
        $username = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $name).date('s')); // create temporal username from fullname
        $email = $username.'@'.$c_shortsite; // create temporal email as well
    }
    
    //5. Confirm that tailor exists as a business
    $query = $writeDB -> prepare('SELECT id FROM tbl_users WHERE tbl_users.id = :id AND tbl_users.role = :role');
    $query -> bindParam(':id', $tailor, PDO::PARAM_INT);
    $query -> bindValue(':role', "business", PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount < 1){
        sendResponse(400, false, 'Only valid business accounts can create customers'); exit();
    }

    //6. Check if customer already exists under this tailor
    $query = $writeDB -> prepare('SELECT c.id FROM tbl_customers c INNER JOIN tbl_users u ON c.customerid = u.id WHERE c.tailorid = :tailorid AND (u.phone = :phone OR u.email = :email)' );
    $query -> bindParam(':tailorid', $tailor, PDO::PARAM_INT);
    $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
    $query -> bindParam(':email', $email, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount !== 0){
        sendResponse(409, false, 'Customer already exists for tailor'); exit();
    }

    //7. Verify if user record already exists
    $query = $writeDB -> prepare('SELECT id FROM tbl_users WHERE phone = :phone OR email = :email');
    $query -> bindParam(':phone', $phone, PDO::PARAM_STR);
    $query -> bindParam(':email', $email, PDO::PARAM_STR);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount > 0){
        //customer exists. Fetch ID
        $row = $query -> fetch(PDO::FETCH_ASSOC);
        $insertID = $row['id'];
    } else {
        //create as a user record first
        $hash_pass = password_hash($password, PASSWORD_DEFAULT); // hash password

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
        if($rowCount === 0) {
            sendResponse(500, false, 'Unable to create user data in user DB'); exit();
        }

        $insertID = $writeDB->lastInsertId();
    }

    //8. Create record in customer table using the fetched ID
    $query = $writeDB -> prepare('INSERT INTO tbl_customers (customerid, tailorid, rating) VALUES(:customerid, :tailorid, 0)');
    $query -> bindParam(':customerid', $insertID, PDO::PARAM_INT);
    $query -> bindParam(':tailorid', $tailor, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0) {
        sendResponse(500, false, 'Unable to create customer data in customer DB'); exit();
    }

    //9. return the newly created user details
    $returnData = array();
    $returnData['user_id'] = $insertID;
    $returnData['username'] = $username;
    $returnData['email'] = $email;
    $returnData['phone'] = $phone;
    $returnData['tailor'] = $tailor;
    $returnData['createdon'] = $createdon;

}
catch (PDOException $e){
    responseServerException($e, 'An error occurred while creating customer account. Please try again');
}
?>