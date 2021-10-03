<!--
    Author: Maksym Hanushchak, 000776919

    Date: November 29, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    This is the main page of the website. Displays 50 recently uploaded pictures in a grid
        

    CHANGE LOG:

        Additional functionality, not mentioned in the report:
            - Gradual responsive layout supports screens as small as 300px in width
            - Log in is possible with either email or username (typed in the same field)
            - Sign Up username and email fields are checked for duplicate name/email in the database real time as the value is typed
            - File size validated when uploading image (must not exceed 1024kb)
            - File type validated when uploading image (allowed jpg, jpeg) 
            - Creates thumbnails (smaller versions of uploaded images) to display in the grids (reduces data usage)
            - My Favorites page displays all images liked by current user
            - My Album page displays all images uploaded by current user (user can delete their uploaded photo from the gallery on this page)
        
        Not (yet) implemented, but mentioned in the plan:
            - Users will be able to modify title or description of their previously uploaded images

-->
<?php if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started
    try
    {
        require 'components/connect.php'; // Connect to the database

        $output = ""; // Define output variable that will store <div> elements with photo thumbnails to be displayed in a grid

        $select_images_sql = "SELECT * FROM images ORDER BY image_id DESC LIMIT 50;"; // SELECT command to select 50 recently uploaded pictures

        $stmt = $dbh->prepare($select_images_sql); // Prepares command
        $stmt->execute(); // Executes the command
        while($selected_image = $stmt->fetch()) // For each selected row
        {
            // Append a div element of current image to the output
            $output .= "<div class='preview'><a href='view.php?id=".$selected_image["image_id"]."' target='_blank'><img title='".$selected_image["image_title"]."' alt='Image preview' src='uploads/s_".$selected_image["image_reference"]."'></a></div>";
        }
        if($output == "") // If no rows are selected
        {
            // Show message that there's no images to display
            $output = "<h3 style='margin-top: 10px; color: #f44336'>No images to display... <a href='upload.php'>Upload</a> yours!</h3>";
        }
    }
    catch(Exception $e)
    {
        echo "Error occured: " . $e; // Echoes error message
        die(); // Stopt code execution
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>imagio - Photo Gallery</title>
    <?php include 'head.php'; ?>
    <!-- head.php contains code inside <head> tag. Used on multiple pages and reduces code repetition -->
</head>

<body>
    <div class="main_container">
        <div class="content_container">
            <?php include 'header.php'; ?>
            <!-- displays the header on the webpage. Used on multiple pages and reduces code repetition -->
            <div class="flex_container">
                <div class="flex_content">
                    <div style='text-align: center; width: 100%; margin-top: 1vh; margin-bottom: 1vh;'>
                        <h2>Recent uploads</h2>
                    </div>
                    <!-- Displays the output (the grid with selected images) -->
                    <?php echo $output ?>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
        <!-- displays the footer on the webpage. Used on multiple pages and reduces code repetition -->
    </div>
</body>

</html>