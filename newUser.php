<?php

include "security.php";
include "database.php";

$database = new Database();

$username = h($_POST['username_register']);

$password = $_POST['password_register'];
$escapedEmail = h($_POST['email_register']);


$sameUsernameStatement = $database->prepare("SELECT * FROM Users WHERE (username=:username)");
$sameUsernameStatement->bindValue(':username', $username);
$sameUsername = $sameUsernameStatement->execute();


$sameEmailStatement = $database->prepare("SELECT * FROM Users WHERE (email=:email)");
$sameEmailStatement->bindValue(':email', $escapedEmail);
$sameEmail = $sameEmailStatement->execute();

$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);


if ($sameUsername->fetchArray()) {
    echo json_encode(2);
    return;
} elseif ($sameEmail->fetchArray()) {
    echo json_encode(3);
    return;
} elseif (empty($escapedEmail) or empty($username) or empty($password) ) {
    echo json_encode(5);
    return;
} elseif (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
    echo json_encode(4);
    return;
} else {


        $registerStatement = $database->prepare("INSERT INTO Users (userID,email,username,passwordHash) VALUES (
            NULL, 
            :escapedEmail,
            :username,
            :password
        )");
        $registerStatement->bindValue(':escapedEmail', $escapedEmail);
        $registerStatement->bindValue(':username', $username);
        $registerStatement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $results = $registerStatement->execute();

        echo json_encode(1);
        return;

}


