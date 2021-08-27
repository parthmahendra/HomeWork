<?php
session_start();
include "access.php";
include "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flat Tasks</title>
    <?php
    include "include.html";
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
        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">The Flat's Chores</h2>
            </div>
            <div class="table">
                <table class="my-data-table">
                    <thead>
                    <tr>
                        <th class="cell-non-numeric">Task</th>
                        <th class="cell-non-numeric">Deadline</th>
                        <th class="cell-non-numeric">Member</th>
                        <th class="cell-checkbox">Done</th>
                    </tr>
                    </thead>
                    <tbody id="taskList">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

</div>

<script>


    function ticked() {
        // console.log("bruh")
        return false;
    }

    function redirect(){
    }


    $(document).ready(function () {
        $.post('fetchHouseTasks.php', {}, function (response) {
            var list = $("#taskList");
            console.log(response)
            var result = JSON.parse(response)
            var added = false;

            // const today = new Date();
            const presentDate = new Date();


            for (const el of result) {
                added = true;

                var idPrefix = el['oneTime'] ? 'o' : 'c';

                var checked = el['complete'] ? 'checked' : ''

                console.log(checked)

                var taskDeadline = new Date(el['dueDateString']).getTime() - new Date(
                    presentDate.getFullYear(), presentDate.getMonth(), presentDate.getDate()
                )
                const taskDeadlineDays = taskDeadline / (1000 * 3600 * 24)
                const days = Math.floor(taskDeadlineDays).toString();

                var deadlineOutput = 'in ' + days + ' days'

                if (taskDeadlineDays < 1) {
                    deadlineOutput = 'Today'
                }

                if (1 <= taskDeadlineDays && taskDeadlineDays < 2) {
                    deadlineOutput = 'Tomorrow'
                }



                list.append(
                    `
                    <tr>
                        <td class="cell-non-numeric taskName"><a class="taskName" href="viewTask.php?id=${idPrefix}${el['0']}">${el['taskName']}</a></td>
                        <td class="cell-non-numeric">Due ${deadlineOutput} on ${el['dueDateString']}</td>
                        <td class="cell-non-numeric">${el['name']}</td>
                        <td class="cell-checkbox">
                                <input type="checkbox" id=\"${idPrefix}${el['0']}\" class="my-checkbox taskCheckBox" disabled=disabled ${checked}>
                        </td>
                    </tr>
                    `
                )

            }

            if (!added) {
                $('.empty').css('display', 'block')
            }


        });

        var tasklist = $('#taskList');


        tasklist.on("click", "tr > .taskName", redirect)


    });
</script>

</body>
</html>