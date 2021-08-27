<?php
session_start();
include "access.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <?php
    include "include.html";
    ?>

</head>


<body>
<?php
include "drawernohouse.html";
include "database.php";
echo "<link rel='stylesheet' type='text/css' href='drawer.css'>";
echo "<link rel='stylesheet' type='text/css' href='login.css'>";

?>

<main class="mdl-layout__content">
    <div class="page-content">


        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">You are not in a house</h2>
            </div>

            <div class="my-supporting-text">
                You are currently not in a house. Join one with all of your flat mates or create one and share
                the details with your flat.
            </div>

        </div>


        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">Join a House</h2>
            </div>

            <div class="login">
                <form action="dash.php" id="joinForm" method="post">
                    <div id="joinNameDiv" class="my-textfield">
                        <span id="joinNameError" class="my-text-error">Code/Password Incorrect</span>
                        <input class="my-text-input" type="text" id="joinNameInput" name="houseCode">
                        <label class="my-text-label" for="joinNameInput">Code</label>
                    </div>
                    <br>
                    <div id="joinPasswordDiv" class="my-textfield">
                        <span id="joinPasswordError" class="my-text-error">Code/Password Incorrect</span>
                        <input class="my-text-input" type="password" id="joinPasswordInput" name="housePassword">
                        <label class="my-text-label" for="joinPasswordInput">Password</label>
                    </div>
                    <br>
                    <button class="my-button submit"
                            type="submit">
                        Join
                    </button>
                </form>
            </div>
        </div>

        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">Create a House</h2>
            </div>

            <div class="login">
                <form action="dash.php" id="createForm" method="post">
                    <div id="createNameDiv" class="my-textfield">
                        <span id="createNameError" class="my-text-error">Code/Password Incorrect</span>
                        <input class="my-text-input" type="text" id="createNameInput" name="houseName">
                        <label class="my-text-label" for="createNameInput">Name of House</label>
                    </div>
                    <br>
                    <div id="createPasswordDiv" class="my-textfield">
                        <span id="createPasswordError" class="my-text-error">Code/Password Incorrect</span>
                        <input class="my-text-input" type="password" id="createPasswordInput"
                               name="housePassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                        <label class="my-text-label" for="createPasswordInput">Password</label>
                    </div>
                    <br>
                    <button class="my-button submit"
                            type="submit">
                        Create
                    </button>
                </form>
            </div>


        </div>
        <div class="my-card content success" style="display: none">
            <div class="my-title">
                <h2 class="my-title-text">Success!</h2>
            </div>


            <div class="my-supporting-text">
                The account has been created. <a href="login.php">Login</a> to begin.
            </div>


        </div>
        <div class="my-card content invalidPassword" style="display: none">
            <div class="my-title">
                <h2 class="my-title-text">Password is invalid</h2>
            </div>

            <div class="my-supporting-text">
                Password needs to have:
                <br> 1 upper case character
                <br> 1 lower case character
                <br> 1 number
                <br> A length of at least 8
            </div>


        </div>
    </div>
</main>

</div>


</body>

<script>

    $(document).ready(function () {
        $("#createForm").submit(function () { // intercepts the submit event
            $.ajax({ // make an AJAX request
                type: "POST",
                url: "./createHouse.php", // it's the URL of your component B
                data: $("#createForm").serialize(), // serializes the form's elements
                success: function (data) {
                    console.log(data);

                    if (data == 0) {
                        $("#createPasswordDiv").addClass("is-invalid")
                        $(".invalidPassword").css("display", "block")
                    } else if (data == 2) {

                        $("#createForm > div > .my-text-error").each(function () {
                            this.innerHTML = "Required"
                        })

                        $("#createForm > div > input").filter(function () {
                            return !this.value;
                        }).parent().addClass("is-invalid");
                    } else if (data == 1) {
                        window.location = "dash.php"
                        // $(".success").css("display", "block")
                    }
                },
                fail: function (xhr, textStatus, errorThrown) {
                    alert(data);
                }
            });

            return false;
        })


        $("#createPasswordDiv").on('input', function () {
            card = $(".invalidPassword")
            if ($("#createPasswordDiv").hasClass("is-invalid")) {
                // $(".invalidPassword").css("display", "block")

                if (card.css("display") === 'none') {
                    card.css("max-height", "0")
                    card.css("display","block")
                    card.animate({"max-height": "800px"}, 400)
                }


                // $("#createPasswordError").show()
                $("#createPasswordError").html("Invalid Password")
                // console.log("shown")
                // $(".invalidPassword").show()
            } else {

                card.css("max-height", "800px")
                card.animate({"max-height": "0"}, 250, function () {
                        card.css("display", "none")
                    }
                )

                // $(".invalidPassword").css("display", "none")
                // $(".invalidPassword").hide()
            }
        })




        $("#joinForm").submit(function () { // intercepts the submit event
            $.ajax({ // make an AJAX request
                type: "POST",
                url: "./joinHouse.php", // it's the URL of your component B
                data: $("#joinForm").serialize(), // serializes the form's elements
                success: function (data) {
                    console.log(data);


                    if (data == 2){
                        $("#joinForm > div > .my-text-error").each(function () {
                            this.innerHTML = "Required"
                        })

                        $("#joinForm > div > input").filter(function () {
                            return !this.value;
                        }).parent().addClass("is-invalid");

                    } else if (data == 0) {
                        $("#joinPasswordInput").val("")
                        $("#joinNameError").html("Code/Password Incorrect")
                        $("#joinPasswordError").html("Code/Password Incorrect")

                        $("#joinNameDiv").addClass("is-invalid")
                        $("#joinPasswordDiv").addClass("is-invalid")
                    } else if (data == 1) {
                        window.location = "dash.php"
                    }
                },
                fail: function (xhr, textStatus, errorThrown) {
                    alert(data);
                }
            });

            return false;
        })
    })


</script>


</html>