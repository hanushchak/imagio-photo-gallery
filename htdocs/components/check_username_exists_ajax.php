
<?php 

/**
    * Authors: Maksym Hanushchak, 000776919
    *     
    *
    * Date: November 28, 2019
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
    
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS); // Filters input
    
    if($username !== null && $username !== false) // Validates input value
    {
        echo $core->isDisplayName($username); // Calls 'isDisplayName' method that looks up passed to it username and returns true (if username exists) or false
    }
    else
    {
        echo false; // Echoes false if wrong parameters receved
    }
}
catch(Exception $e)
{
    echo "Error occured: " . $e; // Echoes error message
    die(); // Stopt code execution
}

?>