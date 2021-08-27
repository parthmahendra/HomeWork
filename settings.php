<?php
session_start();
include "access.php";
include "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>House Settings</title>
    <?php
    include "include.html";
    ?>

</head>


<style>

    #snackbar {
        visibility: hidden;
        width: fit-content;
        margin:auto;
        background-color: #333;
        color: #fff;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
        text-align: left;
        padding: 12px;
        position: fixed;
        z-index: 1;
        bottom: 0px;
        left: 50%;
        vertical-align: middle;
        flex-direction: row;
        display: flex;

        transform: translateX(-50%);
    }

    #snackbar.show {
        visibility: visible;
    }

    @-webkit-keyframes fadein {
        from {max-height: 0}
        to {max-height: 400px}
    }

    @keyframes fadein {
        from {max-height: 0}
        to {max-height: 400px}
    }

    @-webkit-keyframes fadeout {
        from {max-height: 400px}
        to {max-height: 0}
    }

    @keyframes fadeout {
        from {max-height: 400px}
        to {max-height: 0}
    }

    .buttons {
        text-align: right;
        vertical-align: middle;
        margin: 4px;
        display:flex;
    }

    .link-button {
        text-decoration: none;
        padding-left: 16px;
        font-size: 16px;
        cursor: pointer;
    }

    #snackBarText {
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
    }
</style>

<body>
<?php
include "drawerdash.html";
include 'accessibility.php';
echo "<link rel='stylesheet' type='text/css' href='drawer.css'>";
echo "<link rel='stylesheet' type='text/css' href='login.css'>";
echo "<link rel='stylesheet' type='text/css' href='list.css'>";
?>

<main class="mdl-layout__content">
    <div class="page-content">


        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text houseName"></h2>
            </div>
            <div class="my-supporting-text">
                Your house code is <span><?php echo $_SESSION['houseID']?></span>.
                Use this as the code when asking others to join your house.
            </div>
        </div>


        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">List of Chores</h2>
            </div>

            <div class="list">
                <ul class="demo-list-icon my-list choresList">
                </ul>
            </div>
            <button style="width: 100%" class="my-button" id="addChore">
                <i class="material-icons icon" style="color: white;">add</i>
                Add Chore
            </button>

        </div>

        <div id="addChoreCard" class="my-card content" style="display: none">
            <div class="my-title">
                <h2 class="my-title-text">Add Chore</h2>
            </div>

            <div class="login">
                <form action="dash.php" id="addChoreForm" method="post">
                    <div id="choreNameDiv" class="my-textfield">
                        <span id="choreNameError" class="my-text-error">Required</span>
                        <input class="my-text-input" type="text" id="choreNameInput" name="choreName">
                        <label class="my-text-label" for="choreNameInput">Chore Name</label>
                    </div>
                    <br>
                    <div id="choreDescriptionDiv" class="my-textfield">
                        <span id="choreDescriptionError" class="my-text-error">Required</span>
                        <input class="my-text-input" type="text" id="choreDescriptionInput"
                               name="choreDescription">
                        <label class="my-text-label" for="choreDescriptionInput">Chore Description</label>
                    </div>
                    <br>
                    <br>
                    <div id="choreFrequencyDiv">
                        <label class="my-radio" for="daily">
                            <input type="radio" id="daily" class="my-radio" name="choreFrequency" value="1"
                                   checked>
                            <span>Daily</span>
                        </label>
                        <br>
                        <label class="my-radio" for="weekly">
                            <input type="radio" id="weekly" class="my-radio" name="choreFrequency" value="7">
                            <span>Weekly</span>
                        </label>
                        <br>
                        <label class="my-radio" for="biweekly">
                            <input type="radio" id="biweekly" class="my-radio" name="choreFrequency"
                                   value="14">
                            <span>Biweekly</span>
                        </label>
                        <br>
                        <label class="my-radio" for="oneTime">
                            <input type="radio" id="oneTime" class="my-radio" name="choreFrequency" value="-1">
                            <span>One Time</span>
                        </label>


                    </div>
                    <br>
                    <br>
                    <div id="firstDateDiv">
                        <label for="firstDateInput">
                            <span>First Date: </span>
                        </label>
                        <input type="date" id="firstDateInput" name="firstDate" required>
                    </div>
                    <br>
                    <button class="my-button submit"
                            type="submit">
                        Add Chore
                    </button>

                </form>

            </div>


        </div>


        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">One Time Tasks</h2>
            </div>
            <div class="list">
                <ul class="demo-list-icon my-list oneTimeTaskList">
                </ul>
            </div>
        </div>




        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">House Members</h2>
            </div>
            <div class="list">
                <ul class="demo-list-icon my-list membersList">
                </ul>
            </div>
        </div>


    </div>
</main>
<div id="snackbar">
    <div id="snackBarText">
    </div>
</div>

</div>

<script>


    function deleteChore() {

        var id = $(this).attr('id')

        $.ajax({
            type: "POST",
            url: "./deleteChore.php",
            data: {'id':id},
            success: function (data) {
                $(`li[id="${id}"]`).hide()
                var card = $('#snackbar');
                card.animate({"max-height": "0px"}, 250, function () {
                    card.removeClass('show')
                    card.html(`
                        <div id="snackBarText">
                        </div>`
                    )
                })
            }

        })

    }

    function cancelDeleteChore(){
        var card = $('#snackbar');
        card.animate({"max-height": "0px"}, 100, function () {
            card.removeClass('show')
            card.html(`
                        <div id="snackBarText">
                        </div>`
            )
        })
    }


    function myFunction() {
        // use this to gather the chorename and ID
        // add a button and if the button is clicked send a post request
        // to deleteChore containing the ID


        parent = $(this).parent().parent()

        name = parent.find('.my-list-item-primary > span').html()
        id = parent.attr('id');

        var card = $('#snackbar');
        card.css('max-height', '0')

        // Get the snackbar DIV
        var x = document.getElementById("snackBarText");
        x.innerText = "Are you sure you want to delete chore \"" + name + "\""

        card.append(`<div class="buttons"><a class="deleteButton link-button" id="${id}">Yes</a><a class="cancelButton link-button">No</a></div>`)
        // Add the "show" class to DIV
        card.addClass("show");

        card.animate({"max-height": "200px"}, 400)

        $('.deleteButton').on('click', deleteChore)
        $('.cancelButton').on('click', cancelDeleteChore)

    }


    $(document).ready(function () {


        $('#addChore').click(function () {
            var card = $('#addChoreCard');
            if (card.is(':hidden')) {
                $('#addChore').html(`
                        <i class="material-icons icon" style="color: white;">clear</i>
                        Cancel
                `)
                card.css("display", "block")
                card.css("max-height", "0")
                card.animate({"max-height": "800px"}, 400)


            } else {
                $('#addChore').html(`
                            <i class="material-icons icon" style="color: white;">add</i>
                            Add Chore
                `)
                card.css("max-height", "800px")
                card.animate({"max-height": "0"}, 250, function () {
                    card.css("display", "none")
                })

            }
        })


        $("#addChoreForm").submit(function () {

            var serialised = $("#addChoreForm").serialize();

            $.ajax({
                type: "POST",
                url: "./addChore.php",
                data: serialised,
                success: function (data) {


                    if (data == -2) {
                        $("#choreNameDiv").addClass("is-invalid")
                        return;
                    }

                    const el = JSON.parse(data);
                    if (el['oneTime'] === true) {
                        var list = $(".oneTimeTaskList");

                        if ($("#emptyNotifierOneTimeTasks").length > 0) {
                            list.html("");
                        }

                        list.append(
                            `
                            <li class="my-list-item my-list-item-two" id=\"o${el['choreID']}\">
                                <span class="my-list-item-primary">
                                    <span>${el['choreName']}</span>
                                    <span class="my-list-item-sub">
                                        Due on ${el['taskDate']}
                                        <br>
                                        Assigned to ${el['username']}
                                        <br>
                                        ${el['choreDescription']}
                                    </span>
                                </span>
                                    <span class="my-list-item-icon">
                                        <a href="#">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </span>
                            </li>
                            `
                        )


                    } else {
                        var list = $(".choresList");

                        if ($("#emptyNotifier").length > 0) {
                            list.html("");
                        }


                        list.append(
                            `
                            <li class="my-list-item my-list-item-two" id=\"c${el['choreID']}\">
                                <span class="my-list-item-primary">
                                    <span>${el['choreName']}</span>
                                    <span class="my-list-item-sub">
                                        Every ${el['choreFrequency']} days
                                        <br>
                                        Assigned to ${el['username']}
                                        <br>
                                        ${el['choreDescription']}
                                    </span>
                                </span>
                                    <span class="my-list-item-icon">
                                        <a href="#">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </span>
                            </li>
                            `)
                    }

                    $("#addChoreForm").trigger('reset')

                },
                fail: function (xhr, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });

            return false;
        })


        $.post('fetchChores.php', {}, function (response) {
            // console.log(response)
            var list = $(".choresList");
            var result = JSON.parse(response)
            var added = false;
            for (const el of result) {
                added = true;

                list.append(
                    `
                    <li class="my-list-item my-list-item-two" id=\"c${el['choreID']}\">
                        <span class="my-list-item-primary">
                            <span>${el['choreName']}</span>
                            <span class="my-list-item-sub">
                                Every ${el['choreFrequency']} days
                                <br>
                                Assigned to ${el['username']}
                                <br>
                                ${el['choreDescription']}
                            </span>
                        </span>
                            <span class="my-list-item-icon">
                                <a href="#">
                                    <i class="material-icons">delete</i>
                                </a>
                            </span>
                    </li>
                    `
                )

            }

            if (!added) {
                list.append(`
                <li class="my-list-item my-list-item-two" id="emptyNotifier">
                    <span class="my-list-item-primary">
                        <span>Looking empty...</span>
                        <span class="my-list-item-sub">
                            Add some chores to begin.
                        </span>
                    </span>
                </li>
                `)
            }
        });


        $.post('fetchHouseMembers.php', {}, function (response) {
            // console.log(response)
            var list = $(".membersList");
            console.log(response)
            var result = JSON.parse(response)
            for (const el of result) {
                list.append("" +
                    "<li class=\"my-list-item my-list-item-two\">" +
                    "   <span class=\"my-list-item-primary\"> " +
                    "       <span>" + el + "</span> " +
                    "   </span> " +
                    "</li>")
                // console.log(el)
            }
        });


        $.post('fetchOneTimeTasks.php', {}, function (response) {
            // console.log(response)
            var list = $(".oneTimeTaskList");
            console.log(response)
            var result = JSON.parse(response)
            var added = false;
            for (const el of result) {
                added = true;

                list.append(
                    `
                    <li class="my-list-item my-list-item-two" id=\"o${el['oneTimeTaskID']}\">
                        <span class="my-list-item-primary">
                            <span>${el['choreName']}</span>
                            <span class="my-list-item-sub">
                                Due on ${el['taskDate']}
                                <br>
                                Assigned to ${el['username']}
                                <br>
                                ${el['choreDescription']}
                            </span>
                        </span>
                            <span class="my-list-item-icon">
                                <a href="#">
                                    <i class="material-icons">delete</i>
                                </a>
                            </span>
                    </li>
                    `
                )

            }

            if (!added) {
                list.append(`
                <li class="my-list-item my-list-item-two" id="emptyNotifierOneTimeTasks">
                    <span class="my-list-item-primary">
                        <span>Looking empty...</span>
                        <span class="my-list-item-sub">
                            Add some tasks to begin.
                        </span>
                    </span>
                </li>
                `)
            }

        });

        $.post('fetchHouseDetails.php', {}, function (response) {
            // console.log(response)
            houseDetails = JSON.parse(response)
            $('.houseName').html(houseDetails['houseName'])
        })


        $('.choresList').on('click', "li > span > a", myFunction)
        $('.oneTimeTaskList').on('click', "li > span > a", myFunction)

    })


</script>


</body>
</html>