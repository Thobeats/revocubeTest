<?php

function conn(){
    $conn = mysqli_connect('127.0.0.1', 'root', 'T3mil0luw4@28', 'revocube');
    if (!$conn){
        return "not connected";
    }

    return $conn;
}

function login_user($username, $password)
{
    
    $connection = conn();
    $response;

    //validate the input
    if ($username == "" || !check_username($username)){
        $response = [
            "code" => 1,
            "message" => "Username is invalid"
        ];
    }
    else
    {
        $query = check_username($username);
        $user = mysqli_fetch_assoc($query);
        // Check if the Password is correct
        $password_check = hash_password($password);

        if (!hash_password($password_check, $user['password']))
        {
            $response = [
                "code" => 1,
                "message" => "Password is invalid"
            ];
        }
        else
        {
            session_start();
            //Create session
            set_user($user);
            $response = [
                "code" => 0,
                "message" => "login successful"
            ];
        }       

    }

    return $response;
}


function reg_user($username, $password, $email)
{
    try{
        //validate the input
        if ($username == ""){
            return [
                "code" => 1,
                "message" => "Username is invalid"
            ];
    
        }
    
        if ($password == "" || strlen($password) < 6)
        {
            return [
                "code" => 1,
                "message" => "Password is invalid"
            ];
        }
    
        if ($email == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            return [
                "code" => 1,
                "message" => "Email is invalid"
            ];
        }
    
        //Save the user
        $newUser = create_new_user($username, $password, $email);
    
        if (!$newUser)
        {
            return [
                "code" => 1,
                "message" => mysqli_error(conn())
            ];
        }
    
        return [
            "code" => 0,
            "message" => "User Saved Successfully"
        ];
        
    }catch(Exception $e){
        return $e->getMessage();
    }
}

function create_new_user($username, $password, $email):bool
{   
    $sql = "INSERT INTO users (`username`, `email`, `password`, `create_time`) 
            values('$username', '$email', '$password', now())";
    if (!mysqli_query(conn(), $sql))
    {
        return false;
    }

    return true;
}

function check_username($username):bool
{
    $check = mysqli_query(conn(), "select * from users where username = '$username'");
    return mysqli_num_rows($check);
}

function hash_password($password):bool
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function hash_check($password,$hashed_password)
{
    return password_hash($password, PASSWORD_DEFAULT) == $hashed_password;
}


function save_phonebook($name, $phone_number)
{
    $user = get_user();
    $user_id = $user['user_id'];

    $sql = "INSERT INTO phonebook (`user_id`, `name`, `phone_number`, `create_time`) 
            values('$user_id', '$name', '$phone_number', now())";
    if (!mysqli_query(conn(), $sql))
    {
        return false;
    }

    return true;
}

function get_phonebook($phone_number)
{
    $user = get_user();
    $user_id = $user['user_id'];

    $sql = "SELECT FROM phonebook where `user_id` = '$user_id' and phone_number = '$phone_number'";
    $query = mysqli_query(conn(), $sql);
    return mysqli_fetch_assoc($query);
}

function get_all_numbers()
{
    $user = get_user();
    $user_id = $user['user_id'];
    $sql = "SELECT FROM phonebook where `user_id` = '$user_id'";
    $query = mysqli_query(conn(), $sql);
    return mysqli_fetch_assoc($query);
}

function edit_number($id, $phone_number)
{
    $sql = "UPDATE phonebook set phone_number = '$phone_number' where `id` = '$id'";
    $query = mysqli_query(conn(), $sql);
    return mysqli_fetch_assoc($query);
}

function delete_number($id)
{
    $sql = "DELETE from phonebook where `id` = '$id'";
    $query = mysqli_query(conn(), $sql);
    return mysqli_fetch_assoc($query);
}

function set_user($user)
{
    $_SESSION['user'] = $user;
}

function get_user()
{
    return $_SESSION['user'];
}