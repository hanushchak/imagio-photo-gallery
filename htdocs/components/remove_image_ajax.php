<?php 

/**
    * Author: Maksym Hanushchak, 000776919
    *
    * Date: November 29, 2019
    *
    * This material is original work of the authors stated above. 
    * No other person's work has been used without due acknowledgement and we 
    * (authors) have not made this work available to anyone else.
    * 
    * Receives post parameter (image_id) from AJAX request and calls removeImage function defined in Core.php passing the parameter to it
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

try
{  
    require 'core.php'; // Uses system Core.php file, which contains a Core class with methods that define main functionality (login, signup, logout, check username etc.)
    $core = new Core(); // Creates instance of Core class, defined in core.php system file

    $image_id = filter_input(INPUT_POST, "image_id", FILTER_VALIDATE_INT); // Filters and sanitizes input
    
    if($image_id !== false && $image_id !== null) // Validate parameter value
    {
        echo $core->removeImage($image_id); // Call removeImage function that returns true(successfully removed) or false(error)
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

