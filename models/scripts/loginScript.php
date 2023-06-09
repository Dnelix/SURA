<?php

if (isset($_POST['email']) && isset($_POST['password'])){
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $keep = $_POST['keep'];

    $auth_details = [
        'email' => $email,
        'password' => $password,
        'keep' => $keep
    ];

    $json_data = json_encode($auth_details);
    $json_data = json_encode('success');
    echo $json_data;
}

else {
    $json_data = json_encode('A problem occurred. Please try again');
    echo $json_data;
}

?>