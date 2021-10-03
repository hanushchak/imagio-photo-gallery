<!--
    Author: Maksym Hanushchak, 000776919

    Date: November 29, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    This page displays grid of photos liked by currently logged in user
-->
<?php if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started
    try
    {
        if(isset($_SESSION["user_id"])){ // If user is logged in
            require 'components/connect.php'; // Connect to the database

            $output = ""; // Define output variable that will store <div> elements with photo thumbnails to be displayed in a grid

            $select_images_sql = "SELECT * FROM images JOIN likes ON likes.image_id = images.image_id WHERE likes.user_id = ? ORDER BY likes.image_id DESC;"; // SELECT command to select photos liked by currently logged in user

            $stmt = $dbh->prepare($select_images_sql); // Prepares command
            $stmt->execute([$_SESSION["user_id"]]); // Executes SELECT command replacing placeholder with user id
            while($selected_image = $stmt->fetch()) // For each selected row
            {
                // Append a div element of current image to the output
                $output .= "<div class='preview'><a href='view.php?id=".$selected_image["image_id"]."' target='_blank'><img title='".$selected_image["image_title"]."' alt='Image preview' src='uploads/s_".$selected_image["image_reference"]."'></a></div>";
            }
            if($output == "") // If no rows are selected
            {
                // Show message that there's no images to display
                $output = "<h3 style='margin-top: 10px; color: #f44336'>No images to display... <a href='index.php'>Find</a> your favorites!</h3>";
            }
        }
        else // Page is viewed as a guest - Show message 
        {
            $output = "<h3 style='margin-top: 10px; color: #f44336'><a href='login.php'>Log In</a> to view your favorites!</h3>";
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

    <!-- Alter the <title> based on log in status -->
    <?php if(isset($_SESSION["display_name"])){?>
    <title><?php echo $_SESSION["display_name"] ?>'s Favorites - imagio - Photo Gallery</title>
    <?php } else { ?>
    <title>Log In to view Favorites - imagio - Photo Gallery</title>
    <?php } ?>

    <?php include 'head.php'; ?> <!-- head.php contains code inside <head> tag. Used on multiple pages and reduces code repetition -->
    
</head>

<body>
    <div class="main_container">
        <div class="content_container">
            <?php include 'header.php'; ?> <!-- displays the header on the webpage. Used on multiple pages and reduces code repetition -->
            <div class="flex_container">
                <div class="flex_content">
                    <div style='text-align: center; width: 100%; margin-top: 1vh; margin-bottom: 1vh;'>
                    <!-- If user is logged in, displays the heading with user's name -->
                    <?php if(isset($_SESSION["display_name"])){?>
                    <h2><?php echo $_SESSION["display_name"]?>'s Favorites</h2>
                    <?php } ?>
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