<?php
    session_start();
    include "api/func.php";

    if (get_user() == []){
        header("Location: login_page.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Book</title>
</head>
<body>
    
</body>
</html>