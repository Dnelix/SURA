<?php

try{
    $fields = 'id, tailorid, customerid, title, description, DATE_FORMAT(start_date, "'.$read_dateformat.'") as start, DATE_FORMAT(end_date, "'.$read_dateformat.'") as end, DATE_FORMAT(remind_on, "'.$read_dateformat.'") as remind, status, style_category, style_details, style_img1, style_img2, style_img3, income, expense, notes';

    //connect to the $readDB to perform this query
    $query = $readDB -> prepare ('SELECT '.$fields.' FROM tbl_projects WHERE tailorid=:tailorid AND id=:pid LIMIT 1');
    $query -> bindParam(':tailorid', $tailorid, PDO::PARAM_INT);
    $query -> bindParam(':pid', $pid, PDO::PARAM_INT);
    $query -> execute();

    $rowCount = $query->rowCount();
    if($rowCount === 0){
        sendResponse(404, false, 'Invalid tailor and project combination');
    }
    
    $row = $query -> fetch(PDO::FETCH_ASSOC);

    $projectData = [
        "id"            => $row['id'], 
        "tailorid"      => $row['tailorid'], 
        "customerid"    => $row['customerid'], 
        "title"         => $row['title'], 
        "description"   => $row['description'], 
        "start"         => $row['start'], 
        "end"           => $row['end'], 
        "remind"        => $row['remind'], 
        "status"        => $row['status'], 
        "style_catg"    => $row['style_category'],
        "style_det"     => $row['style_details'],
        "style_img1"    => $row['style_img1'],
        "style_img2"    => $row['style_img2'],
        "style_img3"    => $row['style_img3'],
        "income"        => $row['income'],
        "expense"       => $row['expense'],
        "notes"         => $row['notes']
    ];

    //return data in an array
    $returnData = $projectData;

    //create a success response and set the retrived array as data
    sendResponse(200, true, null, $returnData, true); // allow caching for this response to reduce load on server
    exit();

}
catch (PDOException $e){
    responseServerException($e, 'Failed to get users');
}

?>