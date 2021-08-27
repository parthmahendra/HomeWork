<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

$houseID = $_SESSION['houseID'];
$userID = $_SESSION['id'];
$taskInput = h($_POST['id']);



$oneTime = false;

if ('o' == substr($taskInput, 0 , 1)) {
    $oneTime = true;
}


$taskID = (int) substr($taskInput, 1);


if (!$oneTime){
    $statement = $database->prepare('SELECT * FROM Task where houseID=:houseID and taskID=:ID');
} else {
    $statement = $database->prepare('SELECT * FROM OneTimeTasks where (oneTimeTaskID=:ID and houseID=:houseID)');
}

$statement->bindValue(':ID', $taskID);
$statement->bindValue(':houseID', $houseID);

$task = $statement->execute()->fetchArray();


$statement = $database->prepare('SELECT * FROM House where houseID=:houseID');
$statement->bindValue(':houseID', $houseID);
$outputHouseDetails = $statement->execute();
$house = $outputHouseDetails->fetchArray();


$taskDetails = array();
if ($oneTime){


    $taskDetails['taskName'] = $task['taskName'];
    $taskDetails['description'] = $task['description'];

    $temp = $house['dateCreated'];
    $taskDetails['taskDate'] = date_add(
        new DateTime($temp),
        new DateInterval('P' . $task['taskDate'] . 'D')
    )->format('Y-m-d');

} else {

    $temp = $house['dateCreated'];
    $taskDetails['taskDate'] = date_add(
        new DateTime($temp),
        new DateInterval('P' . $task['dueDate'] . 'D')
    )->format('Y-m-d');

    $statement = $database->prepare('SELECT * FROM Chores where choreID=:choreID');
    $statement->bindValue(':choreID', $task['choreID']);
    $chore = $statement->execute()->fetchArray();

    $taskDetails['taskName'] = $chore['choreName'];
    $taskDetails['description'] = $chore['description'];
}


echo json_encode($taskDetails);


