<?php

try{
    $fields = 'u.id, u.username, u.email, u.phone, u.fullname, u.photo, u.active, DATE_FORMAT(u.lastlogin, "'.$read_dateformat.'") as lastlogin, DATE_FORMAT(u.createdon, "'.$read_dateformat.'") as createdon';

    //connect to the $readDB to perform this query
    $query = $readDB -> prepare ('SELECT '.$fields.' FROM tbl_users u INNER JOIN tbl_customers c ON u.id = c.customerid WHERE c.tailorid=:tailorid ORDER BY u.id DESC');
    $query -> bindParam(':tailorid', $tailorid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query->rowCount();

    if($rowCount === 0){
        sendResponse(404, false, 'No customers found for this user');
    }
    
    $customerArray = array(); //initialize the array that will hold the data

    while($row = $query -> fetch(PDO::FETCH_ASSOC)) {
        $customerArray[] = [
            "id" => $row['id'], 
            "username" => $row['username'], 
            "email" => $row['email'], 
            "phone" => $row['phone'], 
            "fullname" => $row['fullname'], 
            "photo" => $row['photo'], 
            "active" => $row['active'], 
            "lastlogin" => $row['lastlogin'],
            "createdon" => $row['createdon'],
        ];
    }
    //return data in an array
    $returnData = array();
    $returnData['count'] = $rowCount;
    $returnData['customerlist'] = $customerArray;

    //create a success response and set the retrived array as data
    sendResponse(200, true, null, $returnData, true); // allow caching for this response to reduce load on server
    exit();

}
catch (PDOException $e){
    responseServerException($e, 'Failed to get users');
}

?>