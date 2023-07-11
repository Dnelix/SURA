<?php
/*
PAYLOAD
    {
        tailorid*:'0',
        customerid:'1',
        title*:'project title',
        description:'user phone or 000000000',
        start_date*:'user email or username@sura.ng',
        ...
    }
*/
    //check if the content is JSON and retrieve
    $jsonData = validateJsonRequest();

    //1. ensure all mandatory fields are provided
    if(!isset($jsonData->tailorid) || !isset($jsonData->title) || !isset($jsonData->start_date)){
        $message = "";
        $message .= (!isset($jsonData->tailorid) ? 'A project cannot exist without a tailor' : false);
        $message .= (!isset($jsonData->title) ? 'You must provide a title for the project' : false);
        $message .= (!isset($jsonData->start_date) ? 'You must specify a start date for the project' : false);

        sendResponse(400, false, $message);
    }

    if($jsonData->tailorid == '' || !is_numeric($jsonData->tailorid)){ 
        sendResponse(400, false, 'Invalid tailor Identifier'); 
    }
    if(isset($jsonData->customerid) && !is_numeric($jsonData->customerid)){ 
        sendResponse(400, false, 'Invalid customer Identifier'); 
    }

    //2. check if the strings are empty or have values above the DB limits
    if(strlen($jsonData->title) < 1 || strlen($jsonData->title) > 100){
        sendResponse(400, false, "Title cannot be more than 100 characters or empty");
    }

    //3. Collate data and strip off white spaces
    $tailorid   = $jsonData->tailorid;
    $custid     = $jsonData->customerid;
    $title      = trim($jsonData->title);
    $style_det  = (isset($jsonData->style_det) ? $jsonData->style_det : "" );
    $desc       = (isset($jsonData->description) ? trim($jsonData->description) : $style_det );
    $start      = date('d/m/Y H:i', strtotime($jsonData->start_date));
    $end        = (isset($jsonData->end_date) ? date('d/m/Y H:i', strtotime($jsonData->end_date)) : addtoDate($start, 1, 'week'));
    $remind     = (isset($jsonData->remind_on) ? $jsonData->remind_on : null );
    $status     = (isset($jsonData->status) ? $jsonData->status : "Not Started" );
    $style_catg = (isset($jsonData->style_catg) ? $jsonData->style_catg : "" );
    $income     = (isset($jsonData->income) ? $jsonData->income : 0 );
    $expense    = (isset($jsonData->expense) ? $jsonData->expense : 0 );
    $notes      = (isset($jsonData->notes) ? $jsonData->notes : "" );
    //4. Handle Images
    $style_img1 = (isset($jsonData->style_img1) ? $jsonData->style_img1 : "" );
    $style_img2 = (isset($jsonData->style_img2) ? $jsonData->style_img2 : "" );
    $style_img3 = (isset($jsonData->style_img3) ? $jsonData->style_img3 : "" );

    try{

        //Insert record into project table
        $fields = 'tailorid, customerid, title, description, start_date, end_date, remind_on, status, style_category, style_details, style_img1, style_img2, style_img3, income, expense, notes';
        $values = ":tailorid, :custid, :title, :desc, STR_TO_DATE(:start, {$write_dateformat}), STR_TO_DATE(:end, {$write_dateformat}), STR_TO_DATE(:remind, {$write_dateformat}), :status, :stylecatg, :styledet, :styleimg1, :styleimg2, :styleimg3, :income, :expense, :notes";
        
        $query = $writeDB -> prepare('INSERT INTO tbl_projects ('.$fields.') VALUES('.$values.')');
        $query -> bindParam(':tailorid', $tailorid, PDO::PARAM_INT);
        $query -> bindParam(':custid', $custid, PDO::PARAM_INT);
        $query -> bindParam(':title', $title, PDO::PARAM_STR);
        $query -> bindParam(':desc', $desc, PDO::PARAM_STR);
        $query -> bindParam(':start', $start, PDO::PARAM_STR);
        $query -> bindParam(':end', $end, PDO::PARAM_STR);
        $query -> bindParam(':remind', $remind, PDO::PARAM_STR);
        $query -> bindParam(':status', $status, PDO::PARAM_STR);
        $query -> bindParam(':stylecatg', $style_catg, PDO::PARAM_STR);
        $query -> bindParam(':styledet', $style_det, PDO::PARAM_STR);
        $query -> bindParam(':styleimg1', $styleimg1, PDO::PARAM_STR);
        $query -> bindParam(':styleimg2', $styleimg2, PDO::PARAM_STR);
        $query -> bindParam(':styleimg3', $styleimg3, PDO::PARAM_STR);
        $query -> bindParam(':income', $income, PDO::PARAM_STR);
        $query -> bindParam(':expense', $expense, PDO::PARAM_STR);
        $query -> bindParam(':notes', $notes, PDO::PARAM_STR);
        $query -> execute();

        $rowCount = $query -> rowCount();
        if($rowCount === 0) {
            sendResponse(500, false, 'Unable to create user data in user DB'); exit();
        }
        
        //Return the newly created user details
        $returnData = array();
        $returnData['tailor']   = $tailorid;
        $returnData['customer'] = $custid;
        $returnData['title']    = $title;
        $returnData['desc']     = $desc;
        $returnData['start']    = $start;
        $returnData['end']      = $end;
        $returnData['status']   = $status;
        $returnData['style']    = $style_catg;

        sendResponse(201, true, 'Project Created Successfully', $returnData);

    }
    catch (PDOException $e){
        responseServerException($e, 'An error occurred while creating this project. Please try again');
    }



?>