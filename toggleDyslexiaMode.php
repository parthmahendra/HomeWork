<?php
session_start();
include "access.php";
include "security.php";
include "database.php";


$database = new Database();
$userID = $_SESSION['id'];
$dyslexiaMode = h($_POST['dyslexiaMode']);

$updateStatement = $database->prepare('UPDATE Users set dyslexiaMode=:dyslexiaMode where (userID=:userID)');
$updateStatement->bindValue(':dyslexiaMode', $dyslexiaMode);
$updateStatement->bindValue(':userID', $userID);
$updateStatement->execute();

$_SESSION['dyslexiaMode'] = $dyslexiaMode;

echo $dyslexiaMode;