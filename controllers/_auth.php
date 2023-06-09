<?php
    //if session is not set, redirect to login
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        $userloggedin = (isset($_SESSION["loggedin"]) ? $_SESSION["loggedin"]:false);
        $logsessionid = (isset($_SESSION["id"]) ? $_SESSION["id"]:false);
        $loguserid = (isset($_SESSION["userid"]) ? $_SESSION["userid"]:false);
        $logusername = (isset($_SESSION["username"]) ? $_SESSION["username"]:false);
        $logtoken = (isset($_SESSION["accesstoken"]) ? $_SESSION["accesstoken"]:false);

        if ($curPage == "" || $curPage == $home){
            echo '<script> window.location.href = "dashboard"; </script>';
            exit();
        }

    } else {
        if ($curPage != "" && $curPage != $home){
            echo '<script> window.location.href = "'. $home .'"; </script>';
            exit();
        }
    }

    /*if you find cust_option in the URL redirect appropriately
    if(isset($_GET['cust_option']) && isset($_GET['cust_option'])!==""){
        $cust_option = $_GET['cust_option'];
        echo '<script> window.location.replace("add_customer?opt='.$cust_option.'"); </script>';
        exit();
    }*/
?> 