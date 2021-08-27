<?php
session_start();
include "access.php";
include "security.php";
include "database.php";

$database = new Database();

$houseID = $_SESSION['houseID'];

$fetchMembersStatement = $database->prepare("SELECT * FROM House WHERE (houseID=:houseID)");
$fetchMembersStatement->bindValue(":houseID", $houseID);
$output = $fetchMembersStatement->execute()->fetchArray();


$houseDetails = array();
$houseDetails['houseName'] = $output['name'];
$houseDetails['houseID'] = $houseID;

echo json_encode($houseDetails);
