<?php
session_start();
include "access.php";
include "database.php";

$database = new Database();

if (!isset($_SESSION['houseID'])) {

    $statement = $database->prepare("SELECT * FROM Members WHERE (userID=:userID)");
    $statement->bindValue(":userID", $_SESSION['id']);
    $output = $statement->execute();
    $result = $output->fetchArray();

    if ($result) {
        $_SESSION['houseID'] = $result['houseID'];
//        include 'renewChores.php';
    } else {
        header('Location: newhouse.php');
        return;
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Tasks</title>
    <?php
    include "include.html";
    include 'renewChores.php';
    ?>

</head>

<body>

<?php

include "drawerdash.html";
include 'accessibility.php';

echo "<link rel='stylesheet' type='text/css' href='drawer.css'>";
echo "<link rel='stylesheet' type='text/css' href='table.css'>";

?>


<main class="mdl-layout__content">
    <div class="page-content">
        <div class="my-card content notification">
            <div class="my-title">
                <h2 class="my-title-text">Notification</h2>
            </div>

            <div class="my-supporting-text notification-text">
                You have <span id="taskCount"></span> tasks remaining today. <br> Click card to dismiss.
            </div>


        </div>


        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">Your Upcoming Chores</h2>
            </div>
            <div class="table">
                <table class="my-data-table">
                    <thead>
                    <tr>
                        <th class="cell-non-numeric">Task</th>
                        <th class="cell-non-numeric">Deadline</th>
                        <th class="cell-checkbox">Completed</th>
                    </tr>
                    </thead>
                    <tbody id="taskList">

                    </tbody>
                </table>
            </div>
        </div>

        <div class="my-card content empty" style="display: none">
            <div class="my-title">
                <h2 class="my-title-text">Your Task List is Empty</h2>
            </div>

            <div class="my-supporting-text">
                Start adding some chores at <a href="settings.php">House Settings</a> to begin.
            </div>


        </div>


    </div>
</main>

</div>

<script>


    function ticked() {
        const ajaxurl = 'completeTask.php'

        var today = false;
        if ($(this).hasClass('today')) {
            today = true
        }

        data = {}
        var notificationCounter = $('#taskCount')
        if (this.checked) {
            data = {"id": this.id, "completed": 1}
            if (today) {
                notificationCounter.html(parseInt(notificationCounter.html()) - 1)
            }
        } else {
            data = {"id": this.id, "completed": 0}
            if (today) {
                notificationCounter.html(parseInt(notificationCounter.html()) + 1)
            }
        }
        $.post(ajaxurl, data, function (response) {

        });
    }

    function redirect() {

    }


    $(document).ready(function () {
        $.post('fetchTasks.php', {}, function (response) {
            var list = $("#taskList");
            var result = JSON.parse(response)
            var added = false;

            const presentDate = new Date();

            var outstandingTasks = 0;


            for (const el of result) {
                added = true;

                var idPrefix = el['oneTime'] ? 'o' : 'c';


                var checked = el['complete'] ? 'checked' : ''

                var taskDeadline = new Date(el['dueDateString']).getTime() - new Date(
                    presentDate.getFullYear(), presentDate.getMonth(), presentDate.getDate()
                )
                const taskDeadlineDays = taskDeadline / (1000 * 3600 * 24)
                const days = Math.floor(taskDeadlineDays).toString();

                var deadlineOutput = 'in ' + days + ' days'

                var today = '';
                if (taskDeadlineDays < 1) {
                    deadlineOutput = 'Today'
                    if (!el['complete']) {
                        outstandingTasks++;
                    }
                    today = 'today'
                }

                if (1 <= taskDeadlineDays && taskDeadlineDays < 2) {
                    deadlineOutput = 'Tomorrow'


                }


                list.append(
                    `
                    <tr>
                        <td class="cell-non-numeric taskName"><a class="taskName" href="viewTask.php?id=${idPrefix}${el['0']}">${el['taskName']}</a></td>
                        <td class="cell-non-numeric">Due ${deadlineOutput} on ${el['dueDateString']}</td>
                        <td class="cell-checkbox">
                            <input type="checkbox" id=\"${idPrefix}${el['0']}\" class="my-checkbox taskCheckBox ${today}" ${checked}>
                        </td>
                    </tr>
                    `
                )

            }

            $('#taskCount').html(outstandingTasks)

            if (!added) {
                $('.empty').css('display', 'block')
            }

        });

        var tasklist = $('#taskList');
        tasklist.on("change", "tr > td > input", ticked)
        tasklist.on("click", "tr > .taskName", redirect)

        $('.notification').on('click', function () {
            $(this).hide()
        })


    });
</script>


</body>
</html>