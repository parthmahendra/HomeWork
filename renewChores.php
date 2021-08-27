<?php
//session_start();
include "access.php";
include "security.php";
//include "database.php";

$database = new Database();

$houseID = $_SESSION['houseID'];

// Fetch the chores
$statement = $database->prepare('SELECT * FROM Chores where houseID=:houseID');
$statement->bindValue(':houseID', $houseID);
$output = $statement->execute();

// Fetch the house details
$statement = $database->prepare('SELECT * FROM House where houseID=:houseID');
$statement->bindValue(':houseID', $houseID);
$outputHouseDetails = $statement->execute();
$house = $outputHouseDetails->fetchArray();

while (($chore = $output->fetchArray())) {

    $generatedTill = $chore['generatedTill'];

    $today = (int)date_diff(new DateTime(), new DateTime($house['dateCreated']))->format('%a');
    $endDate = 31 + $today;

    if ($generatedTill >= $endDate){
        continue;
    }

    while ($generatedTill < $endDate){
        $taskAddStatement = $database->prepare('INSERT INTO Task (houseID, choreID, dueDate, memberID) values (
            :houseID,
            :choreID,
            :dueDate,
            :memberID
        )');
        $taskAddStatement->bindValue(':houseID', $houseID);
        $taskAddStatement->bindValue(':choreID', $chore['choreID']);
        $taskAddStatement->bindValue(':dueDate', $generatedTill);
        $taskAddStatement->bindValue(':memberID', $chore['userID']);

        $taskAddStatement->execute();
        $generatedTill += $chore['choreFrequency'];
    }

    $generatedTillDate = $generatedTill - $chore['choreFrequency'];
    $updateGeneratedTillDate = $database->prepare('UPDATE Chores SET generatedTill=:generatedTillDate where choreID=:choreID');
    $updateGeneratedTillDate->bindValue(':generatedTillDate', $generatedTillDate);
    $updateGeneratedTillDate->bindValue(':choreID', $chore['choreID']);
    $updateGeneratedTillDate->execute();

}