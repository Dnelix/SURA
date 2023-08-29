<?php
    try{
        $readQuery = 'SELECT id, title, filetype, filename, mimetype, refid, userid, updated FROM tbl_uploads WHERE refid = :refid AND userid = :userid';
        $query = $readDB -> prepare($readQuery);
        $query->bindParam(':refid', $refid, PDO::PARAM_INT);
        $query->bindParam(':userid', $userid, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();
        if($rowCount === 0){
            sendResponse(404, false, 'Image not found');
        }

        $imageArray = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $image = new Image(
                $row['id'],
                $row['title'],
                $row['filename'],
                $row['mimetype'],
                $row['refid'],
                $uploadFolderURL,
                $row['filetype'],
                $row['updated']
            );
            $imageArray = $image -> returnImageAsArray();
        }
        sendResponse(200, true, "Successful", $imageArray, true);
    }
    catch(ImageException $e){
        sendResponse(500, false, $e->getMessage());
    }
    catch(PDOException $e){
        error_log("Database Query Error: ".$e, 0);
        responseServerException($e, "Failed to get image attributes");
    }

?>