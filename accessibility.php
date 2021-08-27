<?php
include "access.php";

$database = new Database();

// If dyslexia mode is not set attempt to load it
if (!isset($_SESSION['dyslexiaMode'])) {
    $statement = $database->prepare("SELECT * FROM Users WHERE (userID=:userID)");
    $statement->bindValue(":userID", $_SESSION['id']);
    $output = $statement->execute();
    $result = $output->fetchArray();

    $_SESSION['dyslexiaMode'] = $result['dyslexiaMode'];
}

// Now that we have loaded it, apply the appropriate stylesheet
if ($_SESSION['dyslexiaMode'] == 1){
    echo "<link rel='stylesheet' type='text/css' href='css/accessibility.css'>";
}