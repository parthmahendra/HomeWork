<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

$userID = $_SESSION['id'];

$fetchMembersStatement = $database->prepare("SELECT * FROM Users WHERE (userID=:userID)");
$fetchMembersStatement->bindValue(":userID", $userID);
$output = $fetchMembersStatement->execute()->fetchArray();

$userDetails = array();
$userDetails['username'] = $output['username'];
$userDetails['email'] = $output['email'];
$userDetails['dyslexiaMode'] = $output['dyslexiaMode'];

echo json_encode($userDetails);
