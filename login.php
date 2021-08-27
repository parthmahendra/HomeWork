<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <?php
    include "include.html";
    ?>
</head>

<body>
<?php
include "drawer.html";
echo "<link rel='stylesheet' type='text/css' href='drawer.css'>";
echo "<link rel='stylesheet' type='text/css' href='login.css'>";
?>
<main class="mdl-layout__content">
    <div class="page-content">
        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text">Login</h2>
            </div>
            <div class="my-supporting-text">
                Don't have an account? <a href="register.php">Register here</a>.
            </div>

            <div class="login">
                <form id="login" method="post" action="authenticate.php">
                    <div id="usernameDiv" class="my-textfield">
                        <span id="emailInvalid" class="my-text-error">Username/Password incorrect</span>
                        <input class="my-text-input" type="text" id="sample1" name="username">
                        <label class="my-text-label" for="sample1">Username</label>
                    </div>
                    <br>
                    <div id="passwordDiv" class="my-textfield">
                        <span id="passwordInvalid" class="my-text-error">Username/Password incorrect</span>
                        <input class="my-text-input" type="password" id="sample2" name="password">
                        <label class="my-text-label" for="sample2">Password</label>
                    </div>
                    <br>
                    <button class="my-button submit"
                            type="submit">
                        Login
                    </button>
                </form>
            </div>

        </div>
    </div>
</main>

<script>
    $(document).ready(function () {

        $("#login").submit(function () { // intercepts the submit event
            $.ajax({ // make an AJAX request
                type: "POST",
                url: "./authenticate.php", // it's the URL of your component B
                data: $("#login").serialize(), // serializes the form's elements
                success: function (data) {
                    console.log(data);

                    if (data == 0) {
                        $("#sample2").val("")
                        $("#usernameDiv").addClass("is-invalid")
                        $("#passwordDiv").addClass("is-invalid")
                    } else if (data == 1) {
                        window.location = "dash.php"
                    }
                },
                fail: function (xhr, textStatus, errorThrown) {
                    alert(data);
                }
            });

            return false;
        });
    })
</script>

</div>
</body>
</html>