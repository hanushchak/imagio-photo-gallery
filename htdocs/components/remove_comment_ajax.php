<?php 

/**
    * Author: Maksym Hanushchak, 000776919
    *
    * Date: December 1, 2019
    *
    * This material is original work of the authors stated above. 
    * No other person's work has been used without due acknowledgement and we 
    * (authors) have not made this work available to anyone else.
    * 
    * Called by AJAX script, receives post parameters (image id, comment id) and executes removeComment function to remove comment record with these parameters
    * Executes countComments and getComments functions to update comments counter and comments section without reloading the page
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

try
{  
    require 'core.php'; // Uses system Core.php file, which contains a Core class with methods that define main functionality (login, signup, logout, check username etc.)
    $core = new Core(); // Creates instance of Core class, defined in core.php system file

    $image_id = filter_input(INPUT_POST, "image_id", FILTER_VALIDATE_INT); // Filters and sanitizes input
    $comment_id = filter_input(INPUT_POST, "comment_id", FILTER_VALIDATE_INT); // Filters and sanitizes input
    
    if($comment_id !== false && $comment_id !== null && $image_id !== false && $image_id !== null) // Validate received values
    {
        $response = $core->removeComment($comment_id); // Execute removeComment function and store response from it in the response variable (true or false)
        $comments_count = $core->countComments($image_id); // Execute countComments function and store returned value (count) in a comments_count variable
        $comments_output = $core->getComments($image_id); // Execute getComments function and store returned value (string with comments <div>'s) in a comments_output variable

        // Store responses in an associative array
        $return = array("response"=> $response, 
                        "comments_count"=> $comments_count,
                        "comments_output"=> $comments_output);
        
        echo json_encode($return); // Encode and echo the array
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

