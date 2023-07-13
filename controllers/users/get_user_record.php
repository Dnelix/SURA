<?php

try{
    $all_fields = 'id, username, email, phone, fullname, photo, active, DATE_FORMAT(lastlogin, "'.$read_dateformat.'") as lastlogin, loginattempts, role, DATE_FORMAT(createdon, "'.$read_dateformat.'") as createdon';
    
    //connect to the $readDB to perform this query since it's a read request
    $query = $readDB -> prepare ('SELECT '.$all_fields.' FROM tbl_users WHERE id = :userid LIMIT 1');            
    $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query->rowCount();
    if($rowCount === 0){ sendResponse(404, false, 'User not Found');}

    $returnData = array(); //return data in an array

    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        foreach ($row as $columnName => $value){
            $returnData[$columnName] = $value;
        }
    }

    //create a success response and set the retrived array as data. Also cache the response for faster reloading within 60secs
    sendResponse(200, true, 'success', $returnData, true);

}
catch (PDOException $e){
    responseServerException($e, "There was an error with fetching users");
}

?>