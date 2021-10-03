<!--
    Author: Maksym Hanushchak, 000776919

    Date: November 29, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    This is a modular header file, this file used with 'include' statement in all
    pages that are supposed to have a header. Reduces code repetition.
    Also contains code of modal sign in and sign up forms that can be displayed
    on any page.
-->
<?php
if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started
date_default_timezone_set("Canada/Pacific"); // Defines default timezone to display date correctly

require 'components/core.php'; // Uses system Core.php file, which contains a Core class with methods that define main functionality (login, signup, logout, check username etc.)
$core = new Core(); // Creates instance of Core class, defined in core.php system file
?>

<div class="header"> <!-- HEADER start -->
    <div class="logo"><a href="index.php"><img src="images/logo.png" alt="imagio Logo"></a></div>
    <ul class="navigation">
        <li><a href="index.php">Home</a></li>
        <?php if(isset($_SESSION["user_id"])){ ?> <!-- checks if user is logged in and displays appropriate menu items -->
                <li><a href="upload.php">Upload Photo</a></li>
                <li><a href="favorites.php">My Favorites</a></li>
                <li><a href="myalbum.php">My Album</a></li>
                <li><a href='#' id='logout_button'>Log Out (<?= ($_SESSION['display_name']) ?>)</a></li>
        <?php } 
            else { ?> <!-- if user is not logged in, displays following menu items: -->
                <li><a href='#' id='signup_button'>Sign Up</a></li>
                <li><a href='#' id='login_button'>Log In</a></li>
            <?php } ?>
    </ul>
</div> <!-- HEADER end -->

<div id="login_form" class="modal"> <!-- Login modal form START -->
    <form class="modal_form animate">
        <div class="modal_header">
            <span class="close cancel_login" title="Close">&times;</span>
            <h2>Log in</h2>
        </div>
        <?php include 'login_input.php' ?> <!-- this file input fields that are used in multiple places, reduces code repetiton -->
        <div class="input_container" style="background-color:#f1f1f1">
            <button type="button" class="cancel_button cancel_login">Cancel</button>
            <span class="user_password">Not a member? <a href="register.php">Sign Up</a>!</span>
        </div>
    </form>
</div> <!-- Login modal form END -->
<div id="signup_form" class="modal"> <!-- Signup modal form START -->
    <form class="modal_form animate">
        <div class="modal_header">
            <span class="close cancel_signup" title="Close">&times;</span>
            <h2>Sign Up</h2>
        </div>
        <?php include 'signup_input.php' ?> <!-- this file input fields that are used in multiple places, reduces code repetiton -->
        <div class="input_container" style="background-color:#f1f1f1">
            <button type="button" class="cancel_button cancel_signup">Cancel</button>
            <span class="user_password">Already a member? <a href="login.php">Log In</a>!</span>
        </div>
    </form>
</div> <!-- Signup modal form END -->
<div id="modal_loading_message"></div>