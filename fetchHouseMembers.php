<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

$houseID = $_SESSION['houseID'];

$fetchMembersStatement = $database->prepare("SELECT * FROM Members WHERE (houseID=:houseID)");
$fetchMembersStatement->bindValue(":houseID", $houseID);
$output = $fetchMembersStatement->execute();

$userList = array();

while ($row = $output->fetchArray()){
    $statement = $database->prepare("SELECT * FROM Users WHERE (userID=:userID)");
    $statement->bindValue(":userID", $row['userID']);
    $result = $statement->execute();
    $userDetails = $result->fetchArray();
    array_push($userList, $userDetails['username']);
}

echo json_encode($userList);




