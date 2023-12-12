<?php

include "func.php";

if (isset($_POST['register']))
{

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    $response = reg_user($username, $password, $email);


    exit(json_encode($response));
}
