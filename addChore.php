<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

// If anything is empty, exit
if (empty($_POST['choreFrequency']) or empty($_POST['firstDate']) or empty($_POST['choreName']) or empty($_SESSION['houseID'])) {
    echo -2;
    return;
}

// Load all the variables
// Escape all of them
$houseID = $_SESSION['houseID'];
$description = h($_POST['choreDescription']);
$choreFrequency = (int)h($_POST['choreFrequency']);
$firstDateString = h($_POST['firstDate']);
$choreName = h($_POST['choreName']);
$oneTime = false;
if ($choreFrequency == -1) {
    $oneTime = true;
}


try {
    $firstDate = new DateTime($firstDateString);
} catch (Exception $e) {
    echo 0;
    return;
}


$fetchHouse = $database->prepare('SELECT * FROM House WHERE houseID=:houseID');
$fetchHouse->bindValue(':houseID', $houseID);
$result = $fetchHouse->execute()->fetchArray();

try {
    $houseCreatedDate = new DateTime($result['dateCreated']);
} catch (Exception $e) {
    echo -1;
    return;
}


$fetchUsersStatement = $database->prepare("SELECT * FROM Members where houseID=:houseID");
$fetchUsersStatement->bindValue(':houseID', $houseID);
$fetchUserResult = $fetchUsersStatement->execute();

$lowestUser = null;
$lowestAmount = 6000;
$lowestUserName = null;

$emailList = array();

// Find the most "free" individual
while ($row = $fetchUserResult->fetchArray()) {
    $statement = $database->prepare("SELECT * FROM Chores WHERE (userID=:userID)");
    $statement->bindValue(":userID", $row['userID']);
    $choreFetchResults = $statement->execute();

    $userChoreDays = 0;
    while ($choreRow = $choreFetchResults->fetchArray()) {
        $userChoreDays += 1 / $choreRow['choreFrequency'];
    }


    if ($lowestAmount >= $userChoreDays) {
        $lowestAmount = $userChoreDays;
        $lowestUser = $row['userID'];

        $fetchUsernameStatement = $database->prepare("SELECT * FROM Users WHERE (userID=:userID)");
        $fetchUsernameStatement->bindValue(':userID', $row['userID']);
        $userDetails = $fetchUsernameStatement->execute()->fetchArray();


        array_push($emailList, $userDetails['email']);
        $lowestUserName = $userDetails['username'];
    }

}


if ($firstDate < $houseCreatedDate) {
    $sign = '-';
} else {
    $sign = '';
}
$difference = date_diff($firstDate, $houseCreatedDate);
$firstDateDifference = $difference->format('%a');
$firstDateDifference = (int)($sign . $firstDateDifference);

$addChoreStatement = $database->prepare(
    'INSERT INTO Chores (userID,houseID,description,choreFrequency,firstDate,choreName) VALUES (
                :userID,
                :houseID,
                :description,
                :choreFrequency,
                :firstDate,
                :choreName
    )'
);
$addChoreStatement->bindValue(':userID', $lowestUser);
$addChoreStatement->bindValue(':houseID', $houseID);
$addChoreStatement->bindValue(':description', $description);
$addChoreStatement->bindValue(':choreFrequency', $choreFrequency);
$addChoreStatement->bindValue(':firstDate', $firstDateDifference);
$addChoreStatement->bindValue(':choreName', $choreName);

if (!$oneTime) {
    $addChoreStatement->execute();
}

$choreID = $database->database->lastInsertRowID();

echo json_encode([
    "choreID" => $choreID,
    "username" => $lowestUserName,
    "choreName" => $choreName,
    "choreFrequency" => $choreFrequency,
    "choreDescription" => $description,
    "oneTime" => $oneTime,
    "taskDate" => $firstDateString
]);

$dueDate = $firstDateDifference;
$today = (int)date_diff(new DateTime(), $houseCreatedDate)->format('%a');
$endDate = 31 + $today;


if ($oneTime) {
    $taskAddStatement = $database->prepare('INSERT INTO OneTimeTasks (userID, houseID, description, taskDate, taskName) VALUES (
        :userID,
        :houseID,
        :description,
        :taskDate,
        :taskName
    )');
    $taskAddStatement->bindValue(':userID', $lowestUser);
    $taskAddStatement->bindValue(':houseID', $houseID);
    $taskAddStatement->bindValue(':description', $description);
    $taskAddStatement->bindValue(':taskDate', $firstDateDifference);
    $taskAddStatement->bindValue(':taskName', $choreName);
    $taskAddStatement->execute();
} else {
    while ($dueDate < $endDate) {
        $taskAddStatement = $database->prepare('INSERT INTO Task (houseID, choreID, dueDate, memberID) values (
            :houseID,
            :choreID,
            :dueDate,
            :memberID
        )');
        $taskAddStatement->bindValue(':houseID', $houseID);
        $taskAddStatement->bindValue(':choreID', $choreID);
        $taskAddStatement->bindValue(':dueDate', $dueDate);
        $taskAddStatement->bindValue(':memberID', $lowestUser);

        $taskAddStatement->execute();
        $dueDate += $choreFrequency;
    }
    $generatedTillDate = $dueDate - $choreFrequency;
    $updateGeneratedTillDate = $database->prepare('UPDATE Chores SET generatedTill=:generatedTillDate where choreID=:choreID');
    $updateGeneratedTillDate->bindValue(':generatedTillDate', $generatedTillDate);
    $updateGeneratedTillDate->bindValue(':choreID', $choreID);
    $updateGeneratedTillDate->execute();
}


// send email to everyone notifying them a new chore has been added

foreach ($emailList as &$email){
    $msg = "New chore \"" . $choreName . "\" has been added. Log in to check it out.";
    $msg = wordwrap($msg,70);
    mail($email,"New Chore Added",$msg);
}

return;


