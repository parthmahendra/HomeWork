<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();
$houseID = $_SESSION['houseID'];
$userID = $_SESSION['id'];
$choreInput = h($_POST['id']);

$oneTime = false;

if ('o' == substr($choreInput, 0 , 1)) {
    $oneTime = true;
}


$choreID = (int) substr($choreInput, 1);




if ($oneTime) {
    $deleteOneTimeTaskStatement = $database->prepare('DELETE FROM OneTimeTasks where houseID=:houseID and oneTimeTaskID=:id');
    $deleteOneTimeTaskStatement->bindValue(':houseID', $houseID);
    $deleteOneTimeTaskStatement->bindValue(':id', $choreID);
    $deleteOneTimeTaskStatement->execute();
} else {
    $deleteChoreStatement = $database->prepare('DELETE FROM Chores where houseID=:houseID and choreID=:id');
    $deleteChoreStatement->bindValue(':houseID', $houseID);
    $deleteChoreStatement->bindValue(':id', $choreID);

    $deleteTasksStatement = $database->prepare('DELETE FROM Task where houseID=:houseID and choreID=:id');
    $deleteTasksStatement->bindValue(':houseID', $houseID);
    $deleteTasksStatement->bindValue(':id', $choreID);

    $deleteChoreStatement->execute();
    $deleteTasksStatement->execute();

}