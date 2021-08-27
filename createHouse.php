<?php
session_start();
include "access.php";
include "security.php";
include "database.php";


$database = new Database();

$username = h($_POST['houseName']);

$password = $_POST['housePassword'];


$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);



if (empty($username) or empty($password)) {
    echo json_encode(2);
    return;
} elseif (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
    echo json_encode(0);
    return;
} else {

        $addHouseStatement = $database->prepare(
            "INSERT INTO House (houseID, name, passwordHash, generatedTill, dateCreated) VALUES (
                NULL,
                :name,
                :passwordHash,
                30,
                :date
            )"
        );

        $today = new DateTime('now');

        $addHouseStatement->bindValue(":name", $username);
        $addHouseStatement->bindValue(":passwordHash", password_hash($password, PASSWORD_DEFAULT));
        $addHouseStatement->bindValue(":date", $today->format('Y-m-d'));
        $resultHouse = $addHouseStatement->execute();

        $houseID = $database->database->lastInsertRowID();

        $addMemberStatement = $database->prepare(
            "INSERT INTO Members (relationshipID, userID, houseID) VALUES (
                NULL, 
                :userID,
                :houseID
            )"
        );
        $addMemberStatement->bindValue(":userID", $_SESSION['id']);
        $addMemberStatement->bindValue(":houseID", $houseID);
        $resultsMember = $addMemberStatement->execute();

        $_SESSION['houseID'] = $houseID;

        echo json_encode(1);
        return;

}


?>
