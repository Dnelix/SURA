<?php

try{
    $fields = 'u.id, u.username, u.email, u.phone, u.fullname, u.photo, u.active, DATE_FORMAT(u.lastlogin, "'.$read_dateformat.'") as lastlogin, u.role, DATE_FORMAT(u.createdon, "'.$read_dateformat.'") as createdon';

    //connect to the $readDB to perform this query
    $query = $readDB -> prepare ('SELECT '.$fields.' FROM tbl_users u INNER JOIN tbl_customers c ON u.id = c.customerid WHERE c.tailorid=:tailorid AND c.customerid = :customerid');
    $query -> bindParam(':tailorid', $tailorid, PDO::PARAM_INT);
    $query -> bindParam(':customerid', $custid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query->rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'Invalid tailor and customer combination');
    }
    
    $row = $query -> fetch(PDO::FETCH_ASSOC);

    $customerData = [
        "id" => $row['id'],
        "username" => $row['username'],
        "email" => $row['email'],
        "phone" => $row['phone'],
        "fullname" => $row['fullname'],
        "photo" => $row['photo'],
        "active" => $row['active'],
        "lastlogin" => $row['lastlogin'],
        "role" => $row['role'],
        "createdon" => $row['createdon'],
    ];

    // Get customer measurements

    $query = $readDB -> prepare ('SELECT * FROM tbl_measurements WHERE customerid = :customerid');
    $query -> bindParam(':customerid', $custid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query->rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'Customer measurements records do not exist');
    }

    $measurements = array();

    while($rows = $query->fetch(PDO::FETCH_ASSOC)){
        $measurements = $rows;
    }

    //return data in an array
    $returnData = array();
    $returnData['customerdata'] = $customerData;
    $returnData['measurements'] = $measurements;

    //create a success response and set the retrived array as data
    sendResponse(200, true, null, $returnData, true); // allow caching for this response to reduce load on server
    exit();

}
catch (PDOException $e){
    responseServerException($e, 'Failed to get customer data');
}

?>