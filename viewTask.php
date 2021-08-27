<?php
session_start();
include "access.php";
include "security.php";
include "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task</title>
    <?php
    include "include.html";
    ?>
</head>
<body>

<?php

include "drawerdash.html";
include 'accessibility.php';
echo "<link rel='stylesheet' type='text/css' href='drawer.css'>";
echo "<link rel='stylesheet' type='text/css' href='login.css'>";

?>

<?php
// Verify if user has access to the chore
// User has access to the task if the task belongs to his house

$database = new Database();


$houseID = $_SESSION['houseID'];
$userID = $_SESSION['id'];
$taskInput = h($_GET['id']);

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

$output = $statement->execute();

if ($res = $output->fetchArray()) {
} else {
    echo "Error: This is a Certified HTTP 403 Moment";
    return;
}

?>


<main class="mdl-layout__content">
    <div class="page-content">
        <div class="my-card content taskCard">
            <div class="my-title">
                <h2 class="name my-title-text"></h2>
            </div>

            <div class="my-supporting-text dueDate">

            </div>

            <div class="my-supporting-text description">

            </div>


        </div>
    </div>

</main>

</div>


<script>


    $(document).ready(function () {
        $.post('fetchChoreDetails.php', {'id': "<?php echo h($_GET['id'])?>"}, function (response) {
            console.log(response)
            var task = JSON.parse(response);

            $('.name').html(task['taskName'])
            $('.description').html(task['description'])
            $('.dueDate').html('Due on ' + task['taskDate'])
        })

    });


</script>

</body>
</html>