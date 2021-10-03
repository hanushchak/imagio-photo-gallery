<?php 

/**
    * Author: Maksym Hanushchak, 000776919
    *
    * Date: November 27, 2019
    *
    * This material is original work of the authors stated above. 
    * No other person's work has been used without due acknowledgement and we 
    * (authors) have not made this work available to anyone else.
    * 
    * This file is called by ajax script and used to execute logout function defined in core.php when receives POST ("logout") parameter
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

try
{  
    require 'core.php'; // Uses system Core.php file, which contains a Core class with methods that define main functionality (login, signup, logout, check username etc.)
    $core = new Core(); // Creates instance of Core class, defined in core.php system file

    if(isset($_POST['logout'])) // If 'logout' received as a post parameter (can be empty)
    {
        $core->Logout(); // Calls 'Logout' method defined in core.php. Logs out current user
    }
}
catch(Exception $e)
{
    echo "Error occured: " . $e; // Echoes error message
    die(); // Stopt code execution
}

?>

