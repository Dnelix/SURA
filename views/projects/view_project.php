<?php
    $pid = $_GET['pid'];

    $record = retrieveDataFrom($c_website.'controllers/projects.php?tailor='. $loguserid .'&pid='. $pid);

    if($record->data !== NULL){
        
        $projectData = $record->data;

        foreach($projectData as $data => $value){
            if ($value !== null && $value !== ''){
                ${$data} = $value;
            } else {
                ${$data} = 'N/A';
            }
        }

        $profit_loss = $income - $expense;

        include_once('view_project/1-projectsummary.php');
        include_once('view_project/2-styledetails.php');
        include_once('view_project/3-financials.php');

    } else {
        include_once('views/general/404_content.php');
    }
?>
