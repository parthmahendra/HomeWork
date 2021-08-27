<?php

include "security.php";
include "database.php";


session_start();

$database = new Database();

$username = h($_POST['username']);

$sameUsernameStatement = $database->prepare("SELECT * FROM Users WHERE (username=:username)");
$sameUsernameStatement->bindValue(':username', $username);
$sameUsername = $sameUsernameStatement->execute();


$result = $sameUsername->fetchArray();

if ($result){

    if (password_verify($_POST['password'], $result['passwordHash']) == true){
        $_SESSION['id'] = $result['userID'];
        echo json_encode(1);
        return;

    } else {
        echo json_encode(0);
        return;
    }
} else {
    echo json_encode(0);
    return;

}
?>