<?php 

/**
    * Author: Maksym Hanushchak, 000776919
    *
    * Date: November 27, 2019
    *
    * This material is original work of the authors stated above. 
    * No other person's work has been used without due acknowledgement and the 
    * authors have not made this work  available to anyone else.
    * 
    * This is a system file that receives post parameters from ajax call in javacript
    * Uses 'isEmail' method of Core class in core.php file to check if this email exists. 
    * Echoes true if response is positive, or false if negative.
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

try
{
    require 'core.php'; // Uses system Core.php file, which contains a Core class with methods that define main functionality (login, signup, logout, check username etc.)
    $core = new Core(); // Creates instance of Core class, defined in core.php system file
    
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS); // Filters received input
    
    if($email !== null && $email !== false) // Validates value of received input
    {
        echo $core->isEmail($email); // Calls 'isEmail' method that looks up passed to it email address and returns true (if email exists) or false
    }
    else
    {
        echo false; // Echoes false if receives wrong parameters
    }
}
catch(Exception $e)
{
    echo "Error occured: " . $e; // Echoes error message
    die(); // Stopt code execution
}

?>