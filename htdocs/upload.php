<!--
    Author: Maksym Hanushchak, 000776919

    Date: November 25, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    This page displays upload form to upload an image.
-->
<?php if(!isset($_SESSION)) { session_start(); } ?>
<!-- Starts session if not yet started -->

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upload - imagio - Photo Gallery</title>
    <?php include 'head.php'; ?>
    <!-- The rest of the code for <head> tag is defined in this file. Reduces code repetition -->
</head>

<body>
    <div class="main_container">
        <div class="content_container">
            <?php include 'header.php'; ?>
            <!-- displays the header on the webpage. Used on multiple pages and reduces code repetition -->


            <div class="flex_container">
                <div class="flex_content">
                    <!-- If user is not logged in, show the message -->
                    <?php if(!isset($_SESSION["user_id"])){ ?>
                    <h3 style='margin-top: 10px; color: #f44336'>You must be logged in to upload images</h3>
                    <!-- If user is logged in, show upload form -->
                    <?php } 
                        else { ?>

                    <form class="static_form" id="image_upload_form" action="components/upload_image_ajax.php"
                        method="post" enctype="multipart/form-data">
                        <div class="static_header">
                            <h2>Upload new image</h2>
                        </div>

                        <div class="input_container">
                            <span id='upload_success'>Image uploaded successfully!<br><br></span>
                            <div id="upload_preview"></div>
                            <span id='upload_error'></span>
                            <span id='loading_message' style='display:none; text-align: center;'></span>
                            <label for="upload_input"><b>Choose file to upload</b><br>Formats: jpg, jpeg. Maximum size:
                                1024 KB</label>
                            <input type="file" name="upload_input" id="upload_input" accept=".jpg, .jpeg" required>
                            <label for="image_title"><b>Title</b></label>
                            <input type="text" placeholder="Image title" maxlength="50" name="image_title"
                                id="image_title" class="image_title_input" required>
                            <label for="image_description"><b>Description</b></label>
                            <textarea name="image_description" id="image_description" maxlength="255"
                                style="resize: none;" rows="5" cols="30" placeholder="Describe your image"
                                required></textarea>
                            <button type="submit" id="image_submit">Upload</button>
                        </div>
                        <div class="input_container">
                            <button type="button" onclick="window.location.href = 'index.php';"
                                class="cancel_button">Home</button> <!-- opens index.php file on click -->
                        </div>
                    </form>
                    <?php } ?> <!-- END else -->
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
        <!-- displays the footer on the webpage. Used on multiple pages and reduces code repetition -->
    </div>
</body>

</html>