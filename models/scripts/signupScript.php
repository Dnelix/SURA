<?php

if (isset($_POST['email']) && isset($_POST['password'])){
    $bizName = htmlspecialchars($_POST['bizName']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $auth_details = [
        'biz-name' => $bizName,
        'phone' => $phone,
        'email' => $email,
        'password' => $password
    ];

    $json_data = json_encode('success');
    $json_data = json_encode($auth_details);
    echo $json_data;
}

else {
    $json_data = json_encode('A problem occurred. Please try again');
    echo $json_data;
}

?>