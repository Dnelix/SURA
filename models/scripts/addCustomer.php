<?php
    function validateCustomerByPhone($phone){
        //check DB

        //if cust exists
        return $phone;
        //else
        //return false;
    }

    if(isset($_POST['phone']) && $_POST['phone'] !== ""){

        $phone = htmlspecialchars($_POST['phone']);
        validateCustomerByPhone($phone);

    }
?>