<?php
    try{
        $readQuery = 'SELECT id, title, filetype, filename, mimetype, ref_id FROM tbl_uploads WHERE id = :imgid AND ref_id = :refid';
        $query = $readDB -> prepare($readQuery);
        $query->bindParam(':imgid', $imageid, PDO::PARAM_INT);
        $query->bindParam(':refid', $ref_id, PDO::PARAM_INT);
        $query->execute();

        $rowCount = $query->rowCount();
        if($rowCount === 0){
            sendResponse(404, false, 'Image not found');
        }

        $image = null;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)){
            $image = new Image(
                $row['id'],
                $row['title'],
                $row['filename'],
                $row['mimetype'],
                $row['ref_id']
            );
        }

        if ($image === null){
            sendResponse(404, false, 'Image not created');
        }

        $image -> getImageFile(); //run the getImageFile function defined in the model
    }
    catch(ImageException $e){
        sendResponse(500, false, $e->getMessage());
    }
    catch(PDOException $e){
        error_log("Database Query Error: ".$e, 0);
        responseServerException($e, "Failed to get image file");
    }

?>