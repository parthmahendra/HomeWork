<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();
$userID = $_SESSION['id'];
$houseID = $_SESSION['houseID'];
$taskInput = h($_POST['id']);
$completedValue = h($_POST['completed']);

$completed = (int) $completedValue;


$oneTime = false;

if ('o' == substr($taskInput, 0 , 1)) {
    $oneTime = true;
}

$taskID = (int) substr($taskInput, 1);

if (!$oneTime){
    $statement = $database->prepare('UPDATE Task SET complete=:complete where (taskID=:ID and memberID=:userID)');
} else {
    $statement = $database->prepare('UPDATE OneTimeTasks set complete=:complete where (oneTimeTaskID=:ID and userID=:userID)');
}

$statement->bindValue(':complete', $completed);
$statement->bindValue(':ID', $taskID);
$statement->bindValue(':userID', $userID);
$statement->execute();

echo 1;