<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

$houseID = $_SESSION['houseID'];

$fetchChoresStatement = $database->prepare("SELECT * FROM Chores WHERE (houseID=:houseID)");
$fetchChoresStatement->bindValue(":houseID", $houseID);
$output = $fetchChoresStatement->execute();

$choreList = array();

while ($row = $output->fetchArray()){
    $statement = $database->prepare("SELECT * FROM Users WHERE (userID=:userID)");
    $statement->bindValue(":userID", $row['userID']);
    $result = $statement->execute();
    $userDetails = $result->fetchArray();

    array_push($choreList, [
        "choreID" => $row['choreID'],
        "username" => $userDetails['username'],
        "userID" => $userDetails['userID'],
        "choreName" => $row['choreName'],
        "choreDescription" => $row['description'],
        "choreFrequency" => $row['choreFrequency'],
    ]);
}

echo json_encode($choreList);