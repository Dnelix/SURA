<?php
try{
    $writeDB->beginTransaction();

    $query = $writeDB->prepare('SELECT id, title, filetype, filename, mimetype, ref_id, updated FROM tbl_uploads WHERE id = :imgid AND ref_id = :refid');
    $query->bindParam(':imgid', $imageid, PDO::PARAM_INT);
    $query->bindParam(':refid', $ref_id, PDO::PARAM_INT);
    $query->execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0){
        $writeDB->rollBack();
        sendResponse(404, false, 'This file does not exist');
    }

    $image = null;
    while ($row = $query->fetch(PDO::FETCH_ASSOC)){
        $image = new Image(
            $row['id'],
            $row['title'],
            $row['filename'],
            $row['mimetype'],
            $row['ref_id'],
            $row['updated']
        );
    }
    
    if($image === null){
        $writeDB->rollback();
        sendResponse(500, false, "Failed to get image from model");
    }

    //Delete
    $sql = "DELETE FROM tbl_uploads WHERE id = :imgid AND ref_id = :refid";
    $query = $writeDB->prepare($sql);
    $query->bindParam(':imgid', $imageid, PDO::PARAM_INT);
    $query->bindParam(':refid', $ref_id, PDO::PARAM_INT);
    $query->execute();

    $rowCount = $query -> rowCount();
    if($rowCount === 0){
        $writeDB->rollBack();
        sendResponse(404, false, 'File not found');
    }

    $image -> deleteImageFile();

    $writeDB -> commit();

    sendResponse(200, true, "Image Deleted");

}
catch(ImageException $e){
    $writeDB->rollBack();
    sendResponse(500, false, $e->getMessage());
}
catch(PDOException $e){
    error_log("Database Query Error: ".$e, 0);
    $writeDB->rollBack();
    responseServerException($e, "Failed to delete image");
}

?>