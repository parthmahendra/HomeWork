<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <?php
    include "include.html";
    ?>
</head>


<body>
<?php
include "drawer.html";
echo "<link rel='stylesheet' type='text/css' href='login.css'>";
echo "<link rel='stylesheet' type='text/css' href='drawer.css'>";
?>


<main class="mdl-layout__content">
    <div class="page-content">
        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">Sign up</h2>
            </div>
            <div class="my-supporting-text">
                Already have an account? <a href="login.php">Login here</a>.
            </div>

            <div class="login">
                <form action="newUser.php" method="post" id="register">
                    <div class="my-textfield" id="usernameError">
                        <span id="usernameSpan" class="my-text-error">Username is taken</span>
                        <input class="my-text-input" type="text" id="sample1" name="username_register">
                        <label class="my-text-label" for="sample1">Username</label>
                    </div>
                    <br>
                    <div class="my-textfield" id="emailError">
                        <span id="emailInvalid" class="my-text-error">Email is not valid</span>
                        <input class="my-text-input" type="email" id="sample2" name="email_register">
                        <label class="my-text-label" for="sample2">Email</label>
                    </div>
                    <br>
                    <div class="my-textfield" id="passwordDiv">
                        <span id="passwordInvalid" class="my-text-error">Password is invalid</span>
                        <input class="my-text-input" type="password" id="sample3" name="password_register"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                        <label class="my-text-label" for="sample3">Password</label>
                    </div>
                    <br>
                    <button class="my-button submit"
                            type="submit">
                        Register
                    </button>
                </form>
            </div>

        </div>

        <div class="my-card content invalidPassword" style="display: none">
                        <div class="my-title">
                            <h2 class="my-title-text">Password is invalid</h2>
                        </div>

<!--            <span id="passwordInvalid" class="mdl-textfield__error">Password is invalid</span>-->
                        <div class="my-supporting-text">
                            Password needs to have:
                            <br> 1 upper case character
                            <br> 1 lower case character
                            <br> 1 number
                            <br> A length of at least 8
                        </div>


        </div>

        <div class="my-card content success" style="display: none">
            <div class="my-title">
                <h2 class="my-title-text">Success!</h2>
            </div>

            <!--            <span id="passwordInvalid" class="mdl-textfield__error">Password is invalid</span>-->
            <div class="my-supporting-text">
                The account has been created. <a href="login.php">Login</a> to begin.
            </div>


        </div>
    </div>
</main>


</div>

</body>

<script>
    $(document).ready(function () {


        $( "#register" ).submit(function( event ) {
            if (!$("#password").val() || !$("#username").val()) {
                $("#error-messages").append("Please fill in all fields")
            }
        });

        $("#register").submit(function () {
            $.ajax({
                type: "POST",
                url: "./newUser.php",
                data: $("#register").serialize(),
                success: function (data) {
                    console.log(data);

                    if (data == 2) {
                        $("#usernameError").addClass("is-invalid")
                    } else if (data == 3) {
                        $("#emailInvalid").text("Email is taken.")
                        $("#emailError").addClass("is-invalid")
                    } else if (data == 4) {
                        $("#passwordDiv").addClass("is-invalid")
                        $(".invalidPassword").css("display", "block")
                    } else if (data == 5) {

                        $(".my-text-error").each(function() {
                            this.innerHTML = "Required"
                        })


                        $("#register > div > input").filter(function() {
                            return !this.value;
                        }).parent().addClass("is-invalid");
                    } else if (data == 1) {
                        card = $(".success")
                        card.css("max-height", "0")
                        card.css("display","block")
                        card.animate({"max-height": "800px"}, 400)
                    }
                },
                fail: function (xhr, textStatus, errorThrown) {
                    alert(data);
                }
            });

            return false;
        });

        $("#emailError").on('input', function () {
            $("#emailInvalid").text("Email is Invalid")
        })

        $("#usernameError").on('input', function () {
            $("#usernameSpan").text("Username is taken")
        })

        $("#passwordDiv").on('input', function () {
            card = $(".invalidPassword")

            if ($("#passwordDiv").hasClass("is-invalid")){


                if (card.css("display") === 'none') {
                    card.css("max-height", "0")
                    card.css("display","block")
                    card.animate({"max-height": "800px"}, 400)
                }

                $("#passwordInvalid").html("Invalid Password")
                // console.log("shown")
            } else {
                card.css("max-height", "800px")
                card.animate({"max-height": "0"}, 250, function () {
                        card.css("display", "none")
                    }
                )
            }
        })


    });
</script>


</html>