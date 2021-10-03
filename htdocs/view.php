<!--
    Author: Maksym Hanushchak, 000776919

    Date: December 2, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    This page accepts GET parameter with image id and displays that image if exists.
-->
<?php if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

$image_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT); // Filters received GET parameter

if($image_id !== null && $image_id !== false) // Validates value of received get parameter
{
    try
    {
        require 'components/connect.php'; // Connects to the database

        $select_image_sql = "SELECT * FROM images WHERE image_id = ?;"; // SELECT SQL command to select image to view
        $select_user_sql = "SELECT display_name FROM users WHERE user_id = ?;"; // SELECT SQL command to select author of viewed image

        $stmt = $dbh->prepare($select_image_sql); // Prepares command
        $stmt->execute([$image_id]); // Executes command replacing ? placeholder with received value
        $selected_image = $stmt->fetch(); // Stores selected row in an associative array
        
        if($selected_image) // Checks if associative array is not empty (image was selected)
        { 
            $image_title = $selected_image["image_title"]; // Stores image title in a varaible
            $image_description = $selected_image["image_description"]; // Stores image description in a varaible
            $image_filename = $selected_image["image_reference"]; // Stores image URL reference in a variable
            $image_author_id = $selected_image["user_id"]; // Stores image's author id in a variable

            $stmt = $dbh->prepare($select_user_sql); // Prepares select_user_sql command
            $stmt->execute([$image_author_id]); // Executes command replacing ? placeholder with user id
            $selected_user = $stmt->fetch(); // Stores selected row in an associative array
            if($selected_user)  // Checks if associative array is not empty (user was selected)
            {
                $author_name = $selected_user["display_name"]; // Stores image's author name in a variable
            }
            else // If user for some reason was not selected
            {
                $author_name = "Unknown"; // Stores author's name as Unknown
            }

            $likes_count_sql = "SELECT COUNT(*) FROM likes WHERE image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            $stmt = $dbh->prepare($likes_count_sql); // Prepares likes_count_sql command
            $stmt->execute([$image_id]); // Executes command replacing ? placeholder with image id
            $like_count = $stmt->fetch()["COUNT(*)"]; // Stores count in the like_count variable

            $comments_load_sql = "SELECT display_name, comment_text, comment_id FROM comments JOIN users ON users.user_id = comments.user_id WHERE comments.image_id = ?;"; // SQL command to retrieve comments for this image
            $comments_output = ""; // Variable that will store comments in <div>s
            $stmt = $dbh->prepare($comments_load_sql); // Prepares the command
            $stmt->execute([$image_id]); // Executes command replacing ? placeholder with image id/ 
            while($row = $stmt->fetch()) // For each retrieved row
            {
                $name = $row["display_name"]; // Store name of the comment's author in a variable
                $text = $row["comment_text"]; // Store comment's text in a variable
                $id = $row["comment_id"]; // Store comment's id in a variable

                // If currently logged in user is an author of this comment
                if(isset($_SESSION["user_id"]) && $_SESSION["display_name"] == $name)
                {
                    // Add (x) Remove comment button to this comment
                    $comments_output .= "<div class='comment'><div style='display: block; height: 30px;'><div style='float:left;'><h5>$name <span style='opacity: 0.5;'>(You)</span></h5></div><div style='float:right; text-align: right;'><p title='Remove comment' class='remove_comment_icon' id='$id'>&#10006;</p></div></div><p style='overflow-wrap: break-word;'>$text</p></div>";
                }
                else // If not author of the comment
                {
                    // Display comment without (x) Remove comment button
                    $comments_output .= "<div class='comment'><div style='display: block; height: 30px;'><div style='float:left;'><h5>$name</h5></div></div><p style='overflow-wrap: break-word;'>$text</p></div>";
                }
                
            }

            // SQL command to get total count of the comments for this image
            // Displayed next to the comments icon
            $comments_count_sql = "SELECT COUNT(*) FROM comments WHERE image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            $stmt = $dbh->prepare($comments_count_sql);
            $stmt->execute([$image_id]);
            $comments_count = $stmt->fetch()["COUNT(*)"]; // Total comments count

            if(isset($_SESSION["user_id"])){ // If page is viewed by a logged in user

                // SQL command to check if the image is already liked by this user
                $is_liked_by_current_user_sql = "SELECT COUNT(*) FROM likes WHERE user_id = ? AND image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
                $stmt = $dbh->prepare($is_liked_by_current_user_sql);
                $stmt->execute([$_SESSION["user_id"], $image_id]);
                $row_count = $stmt->fetch()["COUNT(*)"];
                if($row_count > 0) // Retrieved rows count is not 0
                {
                    $is_liked_by_current_user = true; // Image is liked by this user
                }
                else // If row count is 0
                {
                    $is_liked_by_current_user = false; // Image is not liked by the user
                }
            }

        }
    }
    catch(Exception $e)
    {
        echo "Error occured: " . $e; // Echoes error message
        die(); // Stopt code execution
    }
}
else
{
    echo "Wrong parameters received!"; // Displays error message
    die(); // Stopt code execution
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <title> is altered when Requested image exists or not -->
    <?php if($selected_image){?>
        <title><?php echo "\"".$image_title."\" by ".$author_name ?> - imagio - Photo Gallery</title>
    <?php } else { ?>
        <title>404 - imagio - Photo Gallery</title>
    <?php } ?>

    <?php include 'head.php'; ?> <!-- The rest of the code for <head> tag is defined in this file. Reduces code repetition -->
    
</head>

<body>
    <div class="main_container">
        <div class="content_container">
            <?php include 'header.php'; ?> <!-- displays the header on the webpage. Used on multiple pages and reduces code repetition -->
            

            <div class="flex_container">
                <div class="flex_content">

                
                    <?php if($selected_image){?> <!-- If requested image (in GET parameter) exists -->
                    
                        <!-- Display the image itself -->
                        <img id="imageview" title="<?php echo $image_title ?> &copy; <?php echo $author_name ?>"
                        alt="<?php echo $image_description ?>" src="uploads/<?php echo $image_filename ?>" />
                    
                        <!-- Displays the image's info (title, description) -->
                        <div id="image_metadata">
                        <h2 style="display: inline-block; margin-bottom: 1vh;">"<?php echo $image_title ?>"&nbsp;</h2>
                        <h3 style="display: inline-block;">by <?php echo $author_name ?></h3>
                        <p style="margin-bottom: 1vh;"><?php echo $image_description ?></p>

                        <!-- Block that contains like and comment icons and counts -->
                        <div id="like_dislike" style="height: 35px;">

                            <?php if(!isset($_SESSION["user_id"])){ ?> <!-- If viewed by guest, display guest comments and likes icons -->

                            <a href="login.php"><img class="guest_like_icon" alt="like icon"
                                    title="Log in to like the image" src="images/like.png"></a>

                            <?php } else if($is_liked_by_current_user){?> <!-- If image is liked by current user, display appropriate "like" icon (filled red) -->

                            <img class="like_icon" id="<?php echo $image_id ?>" value="dislike" alt="liked icon"
                                title="Dislike <?php echo $image_title ?> by <?php echo $author_name ?>"
                                src="images/liked.png">

                            <?php } else { ?> <!-- If viewed by logged in user, but not liked, display appropriate "like icon" (filled white) -->

                            <img class="like_icon" id="<?php echo $image_id ?>" value="like"
                                title="Like <?php echo $image_title ?> by <?php echo $author_name ?>"
                                src="images/like.png">

                            <?php } ?> <!-- END if statement -->

                            <!-- These are hidden elements that are altered by Javascript DOM, this allows to change the values not reloading the page -->
                            <img class="like_icon hidden_dislike" id="<?php echo $image_id ?>" value="dislike"
                                alt="liked icon"
                                title="Dislike <?php echo $image_title ?> by <?php echo $author_name ?>"
                                src="images/liked.png" style="display: none;">
                            <img class="like_icon hidden_like" id="<?php echo $image_id ?>" value="like"
                                alt="disliked icon"
                                title="Like <?php echo $image_title ?> by <?php echo $author_name ?>"
                                src="images/like.png" style="display: none;">
                            <p id="total_likes" style="display: inline-block;"><?php echo $like_count ?></p>
                            &nbsp;&nbsp;&nbsp;
                            <a href="#comments_form"><img class="comment_icon" alt="comment icon"
                                title="Comment <?php echo $image_title ?> by <?php echo $author_name ?>"
                                src="images/comment.png"></a>
                                <p id="total_comments" style="display: inline-block;"><?php echo $comments_count ?></p>
                        </div>
                    </div>

                    <!-- Comments section begins here -->
                    <div id="image_comments">
                        <span id="comments_header">Comments</span>
                        <div id="comments_output">
                            <!-- If there are comments for this image (output is not empty), echoes output -->
                            <?php if($comments_output !== ""){
                                echo $comments_output;
                            } else { ?> <!-- If there is no comments for this image, show the message -->
                            <div class='comment' style='text-align: center;'><p>No comments to display</p></div>
                            <?php } ?>

                        </div>
                        <form id="comments_form">
                            <!-- If viewed by a logged in user, show comment posting form -->
                            <?php if(isset($_SESSION["user_id"])){ ?>


                            <div class="input_container">
                                <span id='comment_success'>Your comment posted successfully!<br><br></span>
                                <span id='comment_error'>Something went wrong... Try again!</span>

                                <label for="comment_text"><b>Leave a comment as <?php echo $_SESSION["display_name"]; ?></b></label>
                                <textarea name="comment_text" id="comment_text" maxlength="255"
                                    style="resize: none;" rows="3" cols="30" placeholder="Type your comment..."
                                    required></textarea>
                                <input type="hidden" id="image_id" value="<?php echo $image_id ?>">
                                <button type="submit" id="comment_submit">Leave Comment</button>
                            </div>

                            <!-- If viewed by guest, Show a message that the user should be logged in to post comments -->
                            <?php } else { ?>
                            <p style="padding-top: 1vh; padding-bottom: 1vh;"><a href="login.php">Log In</a> to post your comment!</p>
                            <?php } ?>

                        </form>
                    </div>
                    <!-- If requested image doesn't exist, show message -->
                    <?php } else { ?>
                    <h3 style='margin-top: 10px; color: #f44336'>Requested image doesn't exist</h3>
                    <?php } ?>


                </div>
            </div>


        </div>
        <?php include 'footer.php'; ?>
        <!-- displays the footer on the webpage. Used on multiple pages and reduces code repetition -->
    </div>
</body>

</html>