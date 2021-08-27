<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

$houseID = $_SESSION['houseID'];



$fetchHouseCreation = $database->prepare('SELECT * FROM House WHERE (houseID=:houseID)');
$fetchHouseCreation->bindValue(":houseID", $houseID);
$houseDetails = $fetchHouseCreation->execute()->fetchArray();
$houseCreationDate = $houseDetails['dateCreated'];

try {
    $houseCreationDateTime = new DateTime($houseCreationDate);
} catch (Exception $e) {
    return;
}


$fetchChoresStatement = $database->prepare("SELECT * FROM OneTimeTasks WHERE (houseID=:houseID)");
$fetchChoresStatement->bindValue(":houseID", $houseID);
$output = $fetchChoresStatement->execute();

$choreList = array();

while ($row = $output->fetchArray()) {
    $statement = $database->prepare("SELECT * FROM Users WHERE (userID=:userID)");
    $statement->bindValue(":userID", $row['userID']);
    $result = $statement->execute();
    $userDetails = $result->fetchArray();

    $temp = new DateTime($houseCreationDate);
    $dateInterval = new DateInterval('P'.$row['taskDate'].'D');
    $temp = date_add($temp, $dateInterval);


    array_push($choreList, [
        "oneTimeTaskID" => $row['oneTimeTaskID'],
        "username" => $userDetails['username'],
        "userID" => $userDetails['userID'],
        "choreName" => $row['taskName'],
        "choreDescription" => $row['description'],
        "taskDate" => $temp->format('Y-m-d')
    ]);
}

echo json_encode($choreList);
return;
