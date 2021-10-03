<?php 

/**
    * Author: Maksym Hanushchak, 000776919
    *
    * Date: November 22, 2019
    *
    * This material is original work of the authors stated above. 
    * No other person's work has been used without due acknowledgement and the 
    * authors have not made this work  available to anyone else.
    * 
    * This is a system file that contains definition of Core class which functions define
    * main functionality of the website (login, signup, username check, adding comments, liking/disliking etc)
 */

if(!isset($_SESSION)) { session_start(); } // Starts session if not yet started

class Core // Definition of Core class
{
    /**
     * This function accepts user email, password and name as its parameters,
     * Hashes the password, and tries to insert a new record into the users table
     * using SQL command
     *
     * @param  mixed $user_email User's email
     * @param  mixed $user_password User's password
     * @param  mixed $display_name Username
     *
     * @return bool true or false
     */
    public function Register($user_email, $user_password, $display_name)
    {
        try
        {
            require 'connect.php'; // Connects to the database

            $hash = password_hash($user_password, PASSWORD_DEFAULT); // Hashes the password

            $command = "INSERT INTO users (user_email, user_password, display_name) VALUES (?, ?, ?);"; // SQL command with ? as placeholders to be replaced with values when the command is run
            $stmt = $dbh->prepare($command); // Prepares command
            $success = $stmt->execute([$user_email, $hash, $display_name]); // Executes command replacing ? placeholders with received values
            if ($success && $stmt->rowCount() == 1){ // If one record is inserted:
                return true; // Success
            }
            else
            {
                return false; // Inserting error
            }
        }
        catch(Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    /**
     * Login function
     * Accepts user password and username OR email,
     * Executes SQL command to retrieve users with specified username or email
     * Compares the passwords
     * Sets up session and returns true if hashed passwords are identical
     * Returns false - incorrect credentials
     *
     * @param  mixed $user_password User's password
     * @param  mixed $username_or_email User's name or email
     *
     * @return bool true or false
     */
    public function Login($user_password, $username_or_email)
    {
        try
        {
            require 'connect.php'; // Connects to the database

            $command = "SELECT user_id, user_email, user_password, display_name, user_admin, user_enabled FROM users WHERE display_name = ? OR user_email = ?;"; // SQL command with ? as placeholders to be replaced with values when the command is run
            $stmt = $dbh->prepare($command); // Prepares command
            $success = $stmt->execute([$username_or_email, $username_or_email]); // Executes command replacing ? placeholders with received values
            
            if($success)
            {
                $user = $stmt->fetch(); // Fetches data about selected user into associative array $user
                if(password_verify($user_password, $user["user_password"])) // Compares received password and password in the databse
                {
                    $_SESSION["user_id"] = $user["user_id"]; // Sets up session global variable to store user id
                    $_SESSION["user_email"] = $user["user_email"]; // Sets up session global variable to store user email
                    $_SESSION["display_name"] = $user["display_name"]; // Sets up session global variable to store user display name
                    $_SESSION["user_admin"] = $user["user_admin"]; // Sets up session global variable to store user status
                    $_SESSION["user_enabled"] = $user["user_enabled"]; // Sets up session global variable to store user status
                    return true; // Logged in successfully
                }
                else
                {
                    return false; // Logging in error
                }
            }
        }
        catch(Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }

    }
    
    /**
     * Logout function
     * Unsets and destroys current session and reloads current page
     */
    public function Logout()
    {
        session_unset();
        session_destroy();
        unset($_POST['logout']);
        sleep(1000);
        header('Location: '.$_SERVER['REQUEST_SCHEME'] .'://'.$_SERVER['HTTP_HOST'].explode('?', $_SERVER['REQUEST_URI'], 2)[0]); // Reloads current page
        die();
        exit();
    }

    /**
     * Checks if user with requested username exists
     * Receives username as a parameter and uses SQL command to look it up in the users table
     *
     * @param  mixed $display_name Username to look up
     *
     * @return bool true (username exists) or false
     */
    public function isDisplayName($display_name)
    {
        try
        {
            require 'connect.php'; // Connects to the database

            $command = "SELECT * FROM users WHERE display_name = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            $stmt = $dbh->prepare($command); // Prepare command
            $success = $stmt->execute([$display_name]); // Execute command, replacing placeholder ? with the username value

            if($success && $stmt->rowCount() == 1) // If one row is selected - user exists
            {
                return true; // Exists
            }
            else // User doesn't exist
            {
                return false; // Doesn't exist
            }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }

    } 
    
    /**
     * Checks if user with requested email exists
     * Receives email as a parameter and uses SQL command to look it up in the users table
     *
     * @param  mixed $user_email User's email to look up
     *
     * @return bool true or false
     */
    public function isEmail($user_email)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "SELECT * FROM users WHERE user_email = ?"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            $stmt = $dbh->prepare($command); // Prepares command
            $success = $stmt->execute([$user_email]); // Execute command, replacing placeholder ? with the email value

            if($success && $stmt->rowCount() == 1) // If one row is selected - user with this email exists
            {
                return true;
            }
            else // User with this email doesn't exist
            {
                return false;
            }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }
   
    /**
     * Function accepts image_id parameter and counts like records for image with that id
     * (this value is displayed next to like button on view page)
     *
     * @param  mixed $image_id Image ID for which likes should be counted
     *
     * @return mixed like_count(int) or false(boolean)
     */
    public function countLikes($image_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "SELECT COUNT(*) FROM likes WHERE image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            
            $stmt = $dbh->prepare($command); // Prepares command
            $stmt->execute([$image_id]); // Executes command replacing placeholder with a received parameter
            $like_count = $stmt->fetch()["COUNT(*)"]; // Fetches the COUNT value and stores in a variable

            return $like_count; // Returns the received value

        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    
    /**
     * Function accepts image_id parameter and counts comments for image with that id
     * (this value is displayed next to comment button on view page)
     *
     * @param  mixed $image_id
     *
     * @return mixed comments count(int) or false(boolean)
     */
    public function countComments($image_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "SELECT COUNT(*) FROM comments WHERE image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            
            $stmt = $dbh->prepare($command); // Prepares command
            $stmt->execute([$image_id]); // Executes command replacing placeholder with a received parameter
            $comments_count = $stmt->fetch()["COUNT(*)"]; // Fetches the COUNT value and stores in a variable

            return $comments_count; // Returns the received value
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    /**
     * isLikedByCurrentUser
     * 
     * Accepts image_id and user_id parameters and checks if the iamge with this id has been liked by the user with received id
     *
     * @param  mixed $image_id Image ID to check
     * @param  mixed $user_id User ID to check
     *
     * @return bool true/false (liked/not liked)
     */
    public function isLikedByCurrentUser($image_id, $user_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "SELECT COUNT(*) FROM likes WHERE user_id = ? AND image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            
            $stmt = $dbh->prepare($command); // Prepares command
            $stmt->execute([$user_id, $image_id]); // Executes command replacing placeholders with a received parameters
            $like_count = $stmt->fetch()["COUNT(*)"]; // Fetches the COUNT value and stores in a variable

            if($like_count > 0) // If selected any rows, image is liked by current user
            {
                return true;
            }
            else // No rows selected, image is not liked by current user
            {
                return false;
            }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }
    
    /**
     * Like
     * 
     * Receives image_id and user_id parameters and inserts a like record for this user and image into likes table
     *
     * @param  mixed $image_id ID of image to be liked
     * @param  mixed $user_id  ID of user that liked the image
     *
     * @return bool true (like record inserted) OR false (error inserting like record)
     */
    public function Like($image_id, $user_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "INSERT INTO likes (user_id, image_id) VALUES (?, ?);"; // INSERT SQL command to execute. Placeholders ? will be replaced with passed to the fuinction values
            
            $stmt = $dbh->prepare($command); // Prepares the command
            $success = $stmt->execute([$user_id, $image_id]); // Executes command replacing placeholders with a received parameters

            if ($success && $stmt->rowCount() == 1){ // If one record is inserted:
                return true; // Success
            }
            else // No records are inserted - error
            {
                return false;
            }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    /**
     * Dislike
     * 
     * Receives image_id and user_id parameters and removes the like record for this user and image from the likes table
     *
     * @param  mixed $image_id ID of image to remove the like record for
     * @param  mixed $user_id ID of user that like record belongs to
     *
     * @return bool true (like record removed) OR false (error removing like record)
     */
    public function Dislike($image_id, $user_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "DELETE FROM likes WHERE user_id = ? AND image_id = ?;"; // DELETE SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            
            $stmt = $dbh->prepare($command); // Prepares the command
            $success = $stmt->execute([$user_id, $image_id]); // Executes command replacing placeholders with a received parameters

            if ($success && $stmt->rowCount() == 1){ // If one record is removed:
                return true; // Success
            }
            else
            {
                return false; // Error removing record
            }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    /**
     * removeImage
     * 
     * Validates if the current logged in user is the user that the image belongs to
     * Removes image with received as a parameter ID
     * Removes comments, likes related to the image and the image record from the images table, removes image and its thumbnail files from the server
     *
     * @param  mixed $image_id ID of the image to remove
     *
     * @return bool true (image removed) or false (error removing the image)
     */
    public function removeImage($image_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "SELECT * FROM images WHERE image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            
            $stmt = $dbh->prepare($command); // Prepares the command
            $stmt->execute([$image_id]); // Executes command replacing placeholders with a received parameters
            $image_to_remove = $stmt->fetch(); // Fetches the data about selected image into an associative array variable

            if($image_to_remove["user_id"] == $_SESSION["user_id"]) // If current logged in user is the user this image uploaded by
            {
                // Remove likes associated with the image
                $command = "DELETE FROM likes WHERE image_id = ?;";
                $stmt = $dbh->prepare($command);
                $stmt->execute([$image_id]);

                // Remove comments associate with the image
                $command = "DELETE FROM comments WHERE image_id = ?;";
                $stmt = $dbh->prepare($command);
                $stmt->execute([$image_id]);

                // Remove the image record from images table
                $command = "DELETE FROM images WHERE image_id = ?;";
                $stmt = $dbh->prepare($command);
                $success = $stmt->execute([$image_id]);
                $image_path = $image_to_remove["image_reference"];
                $thumbnail_path = "s_".$image_to_remove["image_reference"];

                // If image record is successfully removed from the images table
                // unlink functions remove image and its thumbnail files from the server (return true on success) 
                if ($success && $stmt->rowCount() == 1 && unlink("../uploads/$image_path") && unlink("../uploads/$thumbnail_path")){ // If one record is inserted:
                    return true; // Image removed successfully
                }
                else
                {
                    return false; // Error occured while removing the image
                }
            }
            else
            {
                return false; // Current user is not the user the image belongs to
            }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    /**
     * postComment
     * 
     * Receives image id (to post the comment for) and comment text and posts the comment for the image
     *
     * @param  mixed $image_id Image to post the comment for
     * @param  mixed $comment_text Comment's text
     *
     * @return bool true (success) / false (fail)
     */
    public function postComment($image_id, $comment_text)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "INSERT INTO comments (user_id, image_id, comment_text) VALUES (?, ?, ?);"; // INSERT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            
            $stmt = $dbh->prepare($command); // Prepares the command
            $success = $stmt->execute([$_SESSION["user_id"], $image_id, $comment_text]); // Executes command replacing placeholders with a received parameters

            if ($success && $stmt->rowCount() == 1){ // If one record is inserted:
                return true; // Success
            }
            else
            {
                return false; // Error occured
            }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    /**
     * removeComment
     * 
     * Removes comment with id received as a parameter
     *
     * @param  mixed $comment_id ID of a comment to remove
     *
     * @return bool ture(success) / false(error occured)
     */
    public function removeComment($comment_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "DELETE FROM comments WHERE comment_id = ?;"; // DELETE SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
                $stmt = $dbh->prepare($command); // Prepares the command
                $success = $stmt->execute([$comment_id]); // Executes command replacing placeholder with a received parameter
                if ($success && $stmt->rowCount() == 1){ // If one record is deleted:
                    return true; // Success
                }
                else
                {
                    return false; // Error occured
                }
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }

    /**
     * getComments
     * 
     * Retrieves all the comments for the image with received as a parameter id
     * Used to update comments block after posting or removing a comment without reloading the page
     *
     * @param  mixed $image_id ID of the image that the comments should be retrieved for
     *
     * @return mixed comments_output (empty string or string with all retrieved comments for the image)
     */
    public function getComments($image_id)
    {
        try
        {
            require 'connect.php'; // Connects to the databse

            $command = "SELECT display_name, comment_text, comment_id FROM comments JOIN users ON users.user_id = comments.user_id WHERE comments.image_id = ?;"; // SELECT SQL command to execute. Placeholder ? will be replaced with passed to the fuinction value
            $comments_output = ""; // Declaration of comments output variable
            $stmt = $dbh->prepare($command); // Prepares the command
            $stmt->execute([$image_id]); // Executes command replacing placeholder with a received parameter

            while($row = $stmt->fetch()) // For each retrieved row:
            {
                // Stores values in the variables
                $name = $row["display_name"]; // Stores values in the variables
                $text = $row["comment_text"];
                $id = $row["comment_id"];

                // If current logged in user is the user comment belongs to
                // (used to add remove comment button in that comment)
                if($_SESSION["display_name"] == $name)
                {
                    $comments_output .= "<div class='comment'><div style='display: block; height: 30px;'><div style='float:left;'><h5>$name <span style='opacity: 0.5;'>(You)</span></h5></div><div style='float:right; text-align: right;'><p title='Remove comment' class='remove_comment_icon' id='$id'>&#10006;</p></div></div><p style='overflow-wrap: break-word;'>$text</p></div>";
                }
                // If current logged in user is not the user the comment belongs to
                // Stores comment without remove button
                else
                {
                    $comments_output .= "<div class='comment'><div style='display: block; height: 30px;'><div style='float:left;'><h5>$name</h5></div></div><p style='overflow-wrap: break-word;'>$text</p></div>";
                }
                
            }

            if($comments_output == "") // If no comments were selected, return the message
            {
                $comments_output = "<div class='comment' style='text-align: center;'><p>No comments to display</p></div>";
            }
            
            return $comments_output;
        }
        catch (Exception $e)
        {
            echo "Error occured: " . $e; // Echoes error message
            die(); // Stopt code execution
        }
    }
}

?>