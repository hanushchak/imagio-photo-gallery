<?php 

/**
    * Author: Maksym Hanushchak, 000776919
    *
    * Date: November 22, 2019
    *
    * This material is original work of the authors stated above. 
    * No other person's work has been used without due acknowledgement and we 
    * (authors) have not made this work available to anyone else.
    * 
    * This file is called by ajax login script, which passed POST username, email and password
    * parameters to the file. It uses Register function defined in Core.php to register a user
    * and echoes true (registered successfuly), or false (registration failed)
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

try
{
    require 'core.php'; // Uses system Core.php file, which contains a Core class with methods that define main functionality (login, signup, logout, check username etc.)
    $core = new Core(); // Creates instance of Core class, defined in core.php system file
    
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS); // Filters and sanitizes input
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL); // Filters and sanitizes input
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS); // Filters and sanitizes input
    
    if($username !== null && $username !== false && $password !== null && $password !== false && $email !== null & $email !== false) // Validates input values
    {
        echo $core->Register($email, $password, $username); // Calls Core Register method which returns true (success) or false (fail)
    }
    else
    {
        echo false; // Wrong parameters received
    }
}
catch(Exception $e)
{
    echo "Error occured: " . $e; // Echoes error message
    die(); // Stopt code execution
}

?>