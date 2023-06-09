<?php

try{
    $all_fields = 'id, username, email, phone, fullname, photo, active, DATE_FORMAT(lastlogin, "'.$read_dateformat.'") as lastlogin, loginattempts, role, DATE_FORMAT(createdon, "'.$read_dateformat.'") as createdon, profile_completion';
    
    //connect to the $readDB to perform this query since it's a read request
    $query = $readDB -> prepare ('SELECT '.$all_fields.' FROM tbl_users WHERE id = :userid LIMIT 1');            
    $query -> bindParam(':userid', $userid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query->rowCount();
    if($rowCount === 0){ sendResponse(404, false, 'User not Found');}
    
    $userinfo = [];
    while($row = $query -> fetch(PDO::FETCH_ASSOC)) {
        /*$userinfo["id"] = $row['id'];
        $userinfo["fullname"] = $row['fullname'];
        $userinfo["username"] = $row['username'];
        $userinfo["active"] = $row['active'];
        $userinfo["createdon"] = $row['createdon'];
        $userinfo["loginattempts"] = $row['loginattempts'];
        $userinfo["lastlogin"] = $row['lastlogin'];*/

        $userinfo = [
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
            "profile_completion" => $row['profile_completion']
        ];
    }

    //return data in an array
    $returnData = $userinfo;

    //create a success response and set the retrived array as data. Also cache the response for faster reloading within 60secs
    sendResponse(200, true, 'success', $returnData, true);

}
catch (PDOException $e){
    responseServerException($e, "There was an error with fetching users");
}

?>