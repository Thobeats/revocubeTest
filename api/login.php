<?php
session_start();

include "func.php";

if (isset($_POST['login']))
{

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $response = login_user($username, $password);

    exit(json_encode($response));
}
else{
    exit("Invalid Request");
}






