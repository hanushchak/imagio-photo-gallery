<?php 

/**
    * Author: Maksym Hanushchak, 000776919
    *
    * Date: November 29, 2019
    *
    * This material is original work of the authors stated above. 
    * No other person's work has been used without due acknowledgement and the 
    * authors have not made this work  available to anyone else.
    * 
    * This is a system file that receives post parameters from ajax call in javacript,
    * Validates the uploaded file (size, type) and moves the file to the location where it is stored (uploads folder)
    * Inserts record in the images table with the infromation of the uploaded image
    * Creates thumbnail of the uploaded image
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started


/**
 * This function contains algorithm that resizes the image (Used to create thumbnails to display on the website)
 * Thumbnails are used to reduce loading time
 * 
 * @param  mixed $source_image Path of original image to resize
 * @param  mixed $destination_image Path of resized image
 * @param  mixed $desired_width Desired width of the thumbnail in px
 *
 * @return void No return
 */
function create_thumbnail($source_image, $destination_image, $desired_width) {

    // read the source image
    $source_image = imagecreatefromjpeg($source_image);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    // find the "desired height" of this thumbnail, relative to the desired width
    $desired_height = floor($height * ($desired_width / $width));

    // create a new virtual image
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    // copy source image at a resized size
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    // create the physical thumbnail image to its destination
    imagejpeg($virtual_image, $destination_image);
}

try
{ 
    $valid_extensions = array('jpeg', 'jpg'); // defines allowed file types
    $path = '../uploads/'; // defines upload directory

    $image_title = filter_input(INPUT_POST, "image_title", FILTER_SANITIZE_SPECIAL_CHARS); // Filters image title parameter
    $image_description = filter_input(INPUT_POST, "image_description", FILTER_SANITIZE_SPECIAL_CHARS); // Filters image description parameter
    $upload_input = $_FILES['upload_input']; // Stores uploaded image
    $user_id = $_SESSION["user_id"]; // Stores current user's id

    // Validates values of received parameters
    if($image_title !== null && $image_title !== false && $image_description !== null && $image_description !== false && $upload_input)
    {
        $original_filename = $upload_input['name']; // Original image file name, example 'img.jpg'
        $temporary_file_path = $upload_input['tmp_name']; // Temporary file storage path, example 'C:\xampp\tmp\phpD312.tmp'
        
        $extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION)); // Get extension of uploaded file. Example 'jpg'

        $final_image = rand(1,1000000).'_'.$original_filename; // Filename of the final image, random number + original filename + original extension. Example: 307067_DSC_5123.jpg

        if(!($upload_input['size'] <= 1050000)) // If file size is greater than allowed
        {
            echo 'size_error'; // Error - echoes file size error
            die(); // Stop code execution
        }
        else if(!in_array($extension, $valid_extensions)) // If file format doesn't match allowed format
        {
            echo 'format_error'; // Error - echoes file format error
            die(); // Stop code execution
        }
        else // Validate if the file has allowed type and size of the file doesn't exceed maximum allowed
        { 
            $path .= $final_image; // Path where uploaded image is stored
            
            if(move_uploaded_file($temporary_file_path,$path)) // If uploaded image is moved to its destination successfully (function returned true):
            {
                include_once 'connect.php'; // include database configuration file

                $command = "INSERT INTO images (user_id, image_reference, image_title, image_description) VALUES (?, ?, ?, ?);"; // SQL command with ? as placeholders to be replaced with values when the command is run
                $stmt = $dbh->prepare($command); // Prepares command
                $success = $stmt->execute([$user_id, $final_image, $image_title, $image_description]); // Executes command replacing ? placeholders with received values

                if ($success && $stmt->rowCount() == 1) // If one row is inserted - success
                {
                    $uploaded_image_id = $dbh->lastInsertId(); // Gets an id of uploaded image (last inserted row)
                    echo "<a href='view.php?id=$uploaded_image_id'><img style='width: 100%;' title='View image: $image_title'src='uploads/$final_image' /></a>"; // Echoes preview to show on upload.php page after image is successfully uploaded
                    create_thumbnail('../uploads/'.$final_image, '../uploads/s_'.$final_image, 300); // Creates thumbnail (smaller image) of the uploaded image with the width 300px
                }
                else
                {
                    echo 'general_error'; // Error occurs - general error marker returned
                    die();
                }
            }
        }
    }
    else
    {
        echo 'general_error'; // Wrong parameters received - general error marker returned
        die();
    }

}
catch(Exception $e)
{
    echo "Error occured: " . $e; // Echoes error message
    die(); // Stopt code execution
}

?>