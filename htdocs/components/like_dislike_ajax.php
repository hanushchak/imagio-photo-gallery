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
    * This file receives post parameters, validates them and
    * Executes like/dislike functions defined in core.php, passing the parameters to the functions
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

try
{  
    require 'core.php'; // Uses system Core.php file, which contains a Core class with methods that define main functionality (login, signup, logout, check username etc.)
    $core = new Core(); // Creates instance of Core class, defined in core.php system file

    $image_id = filter_input(INPUT_POST, "image_id", FILTER_VALIDATE_INT); // Filters and sanitizes input
    $request = filter_input(INPUT_POST, "request", FILTER_SANITIZE_SPECIAL_CHARS); // Filters and sanitizes input
    if($image_id !== false && $image_id !== null && $request !== null && $request !== false) // Validates input values
    {
        if($request == "like") // If requested action is like
        {
            if($core->Like($image_id, $_SESSION["user_id"])) // If Like function returned (true)
            {
                $response = 1; // stores 1 in response variable - Liked successfully
            }
            else
            {
                $response = false; // The function returned false - error occured
            }
        }
        if($request == "dislike") // If requested action is dislike
        {
            if($core->Dislike($image_id, $_SESSION["user_id"])) // If Dislike function returned (true)
            {
                $response = 2; // stores 2 in response variable - Like removed successfully
            }
            else
            {
                $response = false; // The function returned false - error occured
            }
        }
        $like_count = $core->countLikes($image_id); // Stores count of likes returend from countLikes function defined in core.php
        
        // Stores response and like_count variables in associative array
        $return = array("response"=> $response, 
                        "like_count"=> $like_count);
        
        echo json_encode($return); // Encodes the associative array and echoes it
    }
    else
    {
        echo false; // Wrong parameters received, echoes false
    }
}
catch(Exception $e)
{
    echo "Error occured: " . $e; // Echoes error message
    die(); // Stopt code execution
}

?>

