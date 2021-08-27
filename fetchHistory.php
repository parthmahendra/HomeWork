<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

$houseID = $_SESSION['houseID'];
$userID = $_SESSION['id'];

$statement = $database->prepare('SELECT * FROM House where houseID=:houseID');
$statement->bindValue(':houseID', $houseID);
$outputHouseDetails = $statement->execute();
$house = $outputHouseDetails->fetchArray();



$today = (int)date_diff(new DateTime(), new DateTime($house['dateCreated']))->format('%a');

$taskList = array();

// Chore tasks
$statement = $database->prepare(
    'SELECT * FROM Task 
    where houseID=:houseID and dueDate 
    between :today and :nextWeek 
    order by dueDate'
);
$statement->bindValue(':houseID', $houseID);
$statement->bindValue(':today', 0);
$statement->bindValue(':nextWeek', $today + 7);
$taskOutput = $statement->execute();


while (($task = $taskOutput->fetchArray())) {

    $task['oneTime'] = false;

    // Insert the date string
    $temp = $house['dateCreated'];
    $task['dueDateString'] = date_add(
        new DateTime($temp),
        new DateInterval('P' . $task['dueDate'] . 'D')
    )->format('Y-m-d');

    // Need to insert name and description
    $statement = $database->prepare('SELECT * FROM Chores where choreID=:choreID');
    $statement->bindValue(':choreID', $task['choreID']);
    $chore = $statement->execute()->fetchArray();
    $task['taskName'] = $chore['choreName'];
    $task['description'] = $chore['description'];

    // Need to insert name of the user
    $statement = $database->prepare('SELECT * FROM Users where (userID=:userID)');
    $statement->bindValue(':userID', $task['memberID']);
    $task['name'] = $statement->execute()->fetchArray()['username'];


    array_push($taskList, $task);
}


// Do one time tasks here
$statement = $database->prepare(
    'SELECT * FROM OneTimeTasks 
    where houseID=:houseID and taskDate 
    between :today and :nextWeek 
    order by taskDate'
);
$statement->bindValue(':houseID', $houseID);
$statement->bindValue(':today', $today);
$statement->bindValue(':nextWeek', $today + 7);
$taskOutput = $statement->execute();

while (($task = $taskOutput->fetchArray())) {

    $task['oneTime'] = true;

    // Insert the date string
    $temp = $house['dateCreated'];
    $task['dueDateString'] = date_add(
        new DateTime($temp),
        new DateInterval('P' . $task['taskDate'] . 'D')
    )->format('Y-m-d');

    $task['dueDate'] = $task['taskDate'];

    $task['taskID'] = $task['oneTimeTaskID'];
    unset($task['oneTimeTaskID']);

    $statement = $database->prepare('SELECT * FROM Users where (userID=:userID)');
    $statement->bindValue(':userID', $task['userID']);
    $task['name'] = $statement->execute()->fetchArray()['username'];



    array_push($taskList, $task);
}

$time = array();
foreach ($taskList as $key => $row) {
    $time[$key] = $row['dueDateString'];
}

array_multisort($time, SORT_ASC, $taskList);
echo json_encode($taskList);
return;