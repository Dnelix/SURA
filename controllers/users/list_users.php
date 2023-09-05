<?php

try{
    $all_fields = 'id, username, email, phone, fullname, photo, active, DATE_FORMAT(lastlogin, "'.$read_dateformat.'") as lastlogin, loginattempts, role, DATE_FORMAT(createdon, "'.$read_dateformat.'") as createdon';

    $query = $writeDB -> prepare ('SELECT '.$all_fields.' FROM tbl_users');
    $query -> execute();

    $rowCount = $query->rowCount();

    if($rowCount === 0){
        sendResponse(404, false, 'No users found');
    }
    
    //else
    //$userArray = array(); //initialize the array that will hold the data

    while($row = $query -> fetch(PDO::FETCH_ASSOC)) {
        $userArray[$row['username']] = [
            "id" => $row['id'], 
            "username" => $row['username'], 
            "email" => $row['email'], 
            "phone" => $row['phone'], 
            "fullname" => $row['fullname'], 
            "photo" => $row['photo'], 
            "active" => $row['active'], 
            "lastlogin" => $row['lastlogin'],
            "loginattempts" => $row['loginattempts'],
            "role" => $row['role'],
            "createdon" => $row['createdon'],
        ];
    }
    //return data in an array
    $returnData = array();
    $returnData['count'] = $rowCount;
    $returnData['userlist'] = $userArray;

    //create a success response and set the retrived array as data
    sendResponse(200, true, null, $returnData, true); // allow caching for this response to reduce load on server
    exit();

}
catch (PDOException $e){
    responseServerException($e, 'Failed to get users');
}

?>