<?php
session_start();
include "access.php";
include "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <?php
    include "include.html";
    ?>
</head>
<body>
<?php
include "drawerdash.html";
include 'accessibility.php';
echo "<link rel='stylesheet' type='text/css' href='drawer.css'>";
?>

<main class="mdl-layout__content">
    <div class="page-content">

        <div class="my-card content">
            <div class="my-title">
                <h2 class="my-title-text username"></h2>
            </div>
            <div class="my-supporting-text">
                Your email is: <a id="email"></a>
                <br>
                <div id="dyslexiaToggleDiv">

                    <label class="my-radio" for="dyslexiaToggle">
                        <input id="dyslexiaToggle" type="checkbox" class="my-checkbox">
                        <span>Dyslexia Friendly Mode</span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</main>


<script>



    $(document).ready(function (){

        $.post('fetchUserDetails.php', {}, function (response){
            var userDetails = JSON.parse(response)
            console.log(userDetails)

            $('#email').html(userDetails['email'])
            $('.username').html('Settings for ' + userDetails['username'])

            if (userDetails['dyslexiaMode'] == 1){
                $('#dyslexiaToggle').prop('checked', true)
            } else {
                $('#dyslexiaToggle').prop('checked', false)
            }

        })


        $('#dyslexiaToggle').change(function () {
            const ajaxurl = 'toggleDyslexiaMode.php'

            data = {}
            if (this.checked) {
                data = {"dyslexiaMode": "1"}
            } else {
                data = {"dyslexiaMode": "0"}
            }
            $.post(ajaxurl, data, function (response) {
                console.log(response)
                if (response == 1){
                    $('*:not(i)').css('font-family', 'Century Gothic', 'important')
                } else if (response == 0) {
                    $('*:not(i)').css('font-family', '')
                    location.reload();
                }
            });
        })


    })
</script>

</body>
</html>