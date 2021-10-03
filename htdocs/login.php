<!--
    Author: Maksym Hanushchak, 000776919

    Date: November 27, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    Login page. To be used as alternative to modular login form.
-->
<?php if(!isset($_SESSION)) { session_start(); } ?> <!-- Starts session if not yet started -->

<!DOCTYPE html>
<html lang="en">

<head>
    <?php // If logged in user tries to access this page, redirects the user to index.php
        if(isset($_SESSION['user_id']))
        {
            echo("<script>location.replace('index.php');</script>"); // Echoes javascript code that redirects to index.php
            die();
        }
    ?>
    <title>Log In - imagio - Photo Gallery</title>
    <?php include 'head.php'; ?> <!-- The rest of the code for <head> tag is defined in this file. Reduces code repetition -->
</head>

<body>
    <div class="main_container">
        <div class="content_container">
            <?php include 'header.php'; ?> <!-- displays the header on the webpage. Used on multiple pages and reduces code repetition -->
            <div class="flex_container">
                <div class="flex_content">
                    <form class="static_form">
                        <div class="static_header">
                            <h2>Log in</h2>
                        </div>
                        <?php include 'login_input.php' ?> <!-- this file contains login input fields. Used on multiple pages and reduces code repetition -->
                        <div class="input_container">
                            <button type="button" onclick="window.location.href = 'index.php';" class="cancel_button">Home</button> <!-- opens index.php file on click -->
                            <span class="user_password">Not a member? <a href="register.php">Sign Up</a>!</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?> <!-- displays the footer on the webpage. Used on multiple pages and reduces code repetition -->
    </div>
</body>

</html>