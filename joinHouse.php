<?php

session_start();
include "security.php";
include "access.php";
include "database.php";


$username = h($_POST['houseCode']);
$password = $_POST['housePassword'];

$database = new Database();

$houseJoinStatement = $database->prepare("SELECT * FROM House WHERE (houseID=:houseCode)");
$houseJoinStatement->bindValue(":houseCode", $username);
$output = $houseJoinStatement->execute();
$result = $output->fetchArray();

if (empty($username) or empty($password)) {
    echo json_encode(2);
    return;
} elseif ($result){

    if (password_verify($password, $result['passwordHash']) == true){
        $_SESSION['houseID'] = $result['houseID'];


        $addMemberStatement = $database->prepare(
            "INSERT INTO Members (relationshipID, userID, houseID) VALUES (
                NULL, 
                :userID,
                :houseID
            )"
        );
        $addMemberStatement->bindValue(":userID", $_SESSION['id']);
        $addMemberStatement->bindValue(":houseID", $result['houseID']);
        $resultsMember = $addMemberStatement->execute();

        echo json_encode(1);

        $fetchUserName = $database->prepare('SELECT * FROM Users where userID=:userID');
        $fetchUserName->bindValue(':userID', $_SESSION['id']);
        $username = $fetchUserName->execute()->fetchArray()['username'];


        // Mail everybody in the house
        $statement = $database->prepare('SELECT * FROM Members where houseID=:houseID');
        $statement->bindValue(':houseID', $result['houseID']);
        $output = $statement->execute();

        while ($row = $output->fetchArray()) {
            $fetchUserDetails = $database->prepare('SELECT * FROM Users where userID=:userID');
            $fetchUserDetails->bindValue(':userID', $row['userID']);
            $userDetails = $fetchUserDetails->execute()->fetchArray();

            $email = $userDetails['email'];
            $msg = "A New user \"" . $username . "\" joined your house. Log in to give the newbie some work.";
            $msg = wordwrap($msg,70);
            mail($email,"New User Joined Your House",$msg);

        }


        return;

    } else {
        echo json_encode(0);
        return;
    }
} else {
    echo json_encode(0);
    return;

}
