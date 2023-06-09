<?php
// -------------send email-----------------------
if (isset($_POST['email'])){
    $email = htmlspecialchars($_POST['email']);

    $auth_details = [
        'email' => $email
    ];

    $json_data = json_encode($auth_details);
    $json_data = json_encode('success');
    echo $json_data;

}  

// -------------change password--------------------
else if (isset($_POST['password']) && isset($_POST['passwordConfirm'])){
    $pasword = $_POST['password'];
    $confirmPasword = $_POST['passwordConfirm'];

    $auth_details = [
        'password' => $pasword,
        'confirm' => $confirmPasword
    ];

    $json_data = json_encode('success');
    $json_data = json_encode($auth_details);
    echo $json_data;

} else {
    $json_data = json_encode('A problem occurred. Please try again');
    echo $json_data;
}

?>