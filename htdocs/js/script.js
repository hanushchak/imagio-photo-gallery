/**
 * Author: Maksym Hanushchak, 000776919
 *
 * Date: December 3, 2019
 *
 * This material is original work of the authors stated above. 
 * No other person's work has been used without due acknowledgement and we 
 * (authors) have not made this work available to anyone else.
 * 
 * This file defines all Javascript and jQuery scripts used in the website.
 */
$(document).ready(function () { // Launches the script when the page is fully loaded

    /**
     * When Log In button in the header menu clicked,
     * Displays the modal login form
     * Clears all input fields in all forms
     */
    $('#login_button').on("click", function () {
        $('#login_form').show();
        clear_input();
    });

    /**
     * When Cancel button in the modal login form is clicked,
     * Hides the modal login form
     * Clears all input fields in all forms
     */
    $('.cancel_login').on("click", function () {
        $('#login_form').hide();
        clear_input();
    })

    /**
     * When Sign Up button in the header menu clicked,
     * Displays the modal signup form
     * Clears all input fields in all forms
     * Calls validation method to reset input fields validation statuses
     */
    $('#signup_button').on("click", function () {
        clear_input();
        validation_check();
        $('#signup_form').show();
    });

    /**
     * When Cancel button in the modal signup form is clicked,
     * Hides the modal signup form
     * Clears all input fields in all forms
     */
    $('.cancel_signup').on("click", function () {
        $('#signup_form').hide();
        clear_input();
    })

    /**
     * When area outside of a modal form clicked,
     * Hides hides the modal form
     * Clears all input fields in all forms
     */
    $(window).on("click", function (event) {
        if (event.target.id == "login_form") { // If currently displayed modal form is login form
            $('#login_form').hide();
            clear_input();
        }
        if (event.target.id == "signup_form") { // If currently displayed modal form is signup form
            $('#signup_form').hide();
            clear_input();
        }

    });

    /**
     * Resets all input fields, validation markers and labels when called
     */
    let clear_input = function () {
        $('.username_login_input').val("");
        $('.username_password_input').val("");
        $('.username_input').val("");
        $('.password_input').val("");
        $('.email_input').val("");
        password_validated = false;
        username_validated = false;
        email_validated = false;
        $(".username_label").html("&nbsp;&nbsp;&#10006;&nbsp;minimum 6 characters").css("color", "#f44336");
        $(".email_label").html("&nbsp;&nbsp;&#10006;&nbsp;email is incomplete").css("color", "#f44336");
        $(".password_min_label").html("&nbsp;&nbsp;&#10006;&nbsp;minimum 8 characthers").css("color", "#f44336");
        $(".password_upper_label").html("&nbsp;&nbsp;&#10006;&nbsp;uppercase and lowercase letter").css("color", "#f44336");
        $(".password_char_label").html("&nbsp;&nbsp;&#10006;&nbsp;special character").css("color", "#f44336");
    }

    // SIGN UP VALIDATION STARTS HERE

    // Validation boolean markers
    let password_validated = false;
    let username_validated = false;
    let email_validated = false;

    /**
     * Function for real-time check if username in the input field exists in the users table
     * Uses AJAX to send a request to check_username_exists_ajax.php file that handles it
     * After receiving a response, passes it to callback function getResponse
     * 
     * @param {*} username Username to look up in the table
     * @param {*} getResponse Callback function to call after response is received
     */
    let check_username_exists = function (username, getResponse) {
        let params = "username=" + username; // Define POST parameters to send 
        fetch("components/check_username_exists_ajax.php", { // Send AJAX request
                method: 'POST',
                credentials: 'include',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params // Include the parameters in body of the request
            })
            .then(response => response.text()) // Receive response as text
            .then(success) // Call success function

        /**
         * Success function that is called after AJAX request gets a response
         * Calls getResponse callback function and passes response to it
         * 
         * @param {*} exists response from AJAX request (true or false)
         */
        function success(exists) {
            getResponse(exists);
        }
    }

    /**
     * Function for real-time check if email in the input field exists in the users table
     * Uses AJAX to send a request to check_email_exists_ajax.php file that handles it
     * After receiving a response, passes it to callback function getResponse
     * 
     * @param {*} email User's email to look up in the table
     * @param {*} getResponse Callback function to call after response is received
     */
    let check_email_exists = function (email, getResponse) {
        let params = "email=" + email; // Define POST parameters to send 
        fetch("components/check_email_exists_ajax.php", { // Send AJAX request
                method: 'POST',
                credentials: 'include',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params // Include the parameters in body of the request
            })
            .then(response => response.text()) // Receive response as text
            .then(success) // Call success function

        /**
         * Success function that is called after AJAX request gets a response
         * Calls getResponse callback function and passes response to it
         * 
         * @param {*} exists response from AJAX request (true or false)
         */
        function success(exists) {
            getResponse(exists);
        }
    }

    /**
     * Checks the state of validation bool markers
     * And if all fields are validated (all markers are true)
     * Enables Signup button
     * If not all fields are validated
     * Disables Signup button
     */
    let validation_check = function () {
        if (password_validated && username_validated && email_validated) {
            $(".signup_submit").css({ // Enable signup button
                "background-color": "#54AE79",
                "color": "white",
                "border": "none",
                "cursor": "pointer",
                "pointer-events": "auto"
            });
        } else {
            $(".signup_submit").css({ // Disable signup button
                "background-color": "white",
                "color": "#54AE79",
                "border": "1px solid #54AE79",
                "cursor": "not-allowed",
                "pointer-events": "none"
            });
        }
    }

    validation_check(); // Call validation check function to reset all validation values

    /**
     * When focus on username input field in signup form,
     * Show username validation label container
     * 
     * Checks if username is validated and changes color of the validation labels
     */
    $(".username_input").on("focus", function () {
        $(".username_validation_label").show(); // Show validation labels

        if (username_validated) { // If username_validated marker is true
            $(".username_input").css("outline-color", "#54AE79"); // Change label color to green
            $(".username_exists_label").hide(); // Hide username exists label
        } else {
            $(".username_input").css("outline-color", "#f44336"); // Change validation labels color to red
            if (this.value.length === 0) { // If the field is empty, hide username exists label
                $(".username_exists_label").hide();
            }
        }
    });

    /**
     * When username input field is not in focus,
     * Hide container with username validation labels
     */
    $(".username_input").on("blur", function () {
        $(".username_validation_label").hide();
    });

    /**
     * When focus on email input field in signup form,
     * Show email validation label container
     * 
     * Checks if email is validated and changes colors of validation labels (red/green)
     */
    $(".email_input").on("focus", function () {
        $(".email_validation_label").show(); // Show validation labels

        if (email_validated) { // If email_validated marker is true
            $(".email_input").css("outline-color", "#54AE79"); // Change label color to green
            $(".email_exists_label").hide(); // Hide email exists label
        } else {
            $(".email_input").css("outline-color", "#f44336"); // Change validation labels color to red
            if (this.value.length === 0) { // If the field is empty, hide email exists label
                $(".email_exists_label").hide();
            }
        }
    });

    /**
     * When email input is not in focus,
     * Hides container with email validation labels
     */
    $(".email_input").on("blur", function () {
        $(".email_validation_label").hide();
    });

    /**
     * When focus on password input field
     * Shows password validation label container
     * 
     * Checks if password is validated and changes colors of validation labels (red/green)
     */
    $(".password_input").on("focus", function () {
        $(".password_validation_label").show(); // Show validation labels
        if (password_validated) { // If password_validated marker is true
            $(".password_input").css("outline-color", "#54AE79"); // Change label color to green
        } else {
            $(".password_input").css("outline-color", "#f44336"); // Change validation labels color to red
        }
    });

    /**
     * When password input is not in focus,
     * Hides container with password validation labels
     */
    $(".password_input").on("blur", function () {
        $(".password_validation_label").hide();
    });

    /**
     * When value in username input field changed, validates it:
     * 
     * 1) Calls function that checks if current value is existent username in the users table
     * 2) Checks length of current input (? > 6)
     */
    $(".username_input").on("keyup", function () {

        // Call function that sends ajax request to check if current username exists
        check_username_exists(this.value, function (response) {
            if (response == 1) { // If response is 1 (true)
                $(".username_exists_label").show(); // Show 'username exists label'
                $(".username_input").css("outline-color", "#f44336"); // Username input border is red
                username_validated = false; // Username is not validated
                validation_check(); // Disable submit button
            } else {
                $(".username_exists_label").hide(); // Hide username exists label
            }
        })
        if (this.value.length >= 6) { // If value length is >= 6, length is validated
            $(".username_input").css("outline-color", "#54AE79"); // Green input field's border
            $(".username_label").html("&nbsp;&nbsp;&#10004;&nbsp;minimum 6 characters").css("color", "#54AE79"); // Green length validation label
            username_validated = true; // Username validated
        } else { // Username is not validated
            $(".username_input").css("outline-color", "#f44336"); // Change input border color to red
            $(".username_label").html("&nbsp;&nbsp;&#10006;&nbsp;minimum 6 characters").css("color", "#f44336"); // Change validation label color to red
            username_validated = false;
        }
        validation_check(); // Check if current input is valid
    });

    /**
     * When value in email input field changed, validates it:
     * 
     * 1) Calls function that checks if current value is existent email in the users table
     * 2) Checks if the value contains symbols @ and .(dot)
     */
    $(".email_input").on("keyup", function () {

        // Call function that sends ajax request to check if current email exists
        check_email_exists(this.value, function (response) {
            if (response == 1) { // If response is 1 (true)
                $(".email_exists_label").show(); // Shows 'email already exists' label
                $(".email_input").css("outline-color", "#f44336"); // Change border color to red
                email_validated = false; // Changes validation marker to false (not validated)
                validation_check(); // Disables submit button
            } else {
                $(".email_exists_label").hide(); // Hides email exists label
            }
        })
        if ((this.value.indexOf("@") > -1) && (this.value.indexOf(".") > -1)) { // If value contains @ and .(dot)
            $(".email_input").css("outline-color", "#54AE79"); // Changes border color to green
            $(".email_label").html("&nbsp;&nbsp;&#10004;&nbsp;email is complete").css("color", "#54AE79"); //Changes validation label color to green
            email_validated = true; // Changes validation boolean marker to true
        } else {
            $(".email_input").css("outline-color", "#f44336"); // Changes border color to red
            $(".email_label").html("&nbsp;&nbsp;&#10006;&nbsp;email is incomplete").css("color", "#f44336"); // Changes validation label color to red
            email_validated = false; // Changes validation boolean marker to false (not validated)
        }
        validation_check(); // Check if current input is valid
    });

    /**
     * When value in password input field changed, validates it:
     * 
     * 1) Length is 6 or longer
     * 2) Contains lower and upper-case letter
     * 3) Contains at least one special character
     */
    $(".password_input").on("keyup", function () {

        // Checks length of current input and alters validation label colors
        if (this.value.length >= 6) {
            $(".password_min_label").html("&nbsp;&nbsp;&#10004;&nbsp;minimum 8 characthers").css("color", "#54AE79");
        }
        if (this.value.length < 6) {
            $(".password_min_label").html("&nbsp;&nbsp;&#10006;&nbsp;minimum 8 characthers").css("color", "#f44336");
        }

        // Checks if upper and lower-case letters are present and alters label colors
        if (this.value.match(/[a-z]/) && this.value.match(/[A-Z]/)) {
            $(".password_upper_label").html("&nbsp;&nbsp;&#10004;&nbsp;uppercase and lowercase letter").css("color", "#54AE79");
        }
        if (!this.value.match(/[a-z]/) || !this.value.match(/[A-Z]/)) {
            $(".password_upper_label").html("&nbsp;&nbsp;&#10006;&nbsp;uppercase and lowercase letter").css("color", "#f44336");
        }

        // Checks if at least one special character is present and alters label colors
        if (this.value.match(/[.,!@#$%^&*]/)) {
            $(".password_char_label").html("&nbsp;&nbsp;&#10004;&nbsp;special character").css("color", "#54AE79");
        }
        if (!this.value.match(/[.,!@#$%^&*]/)) {
            $(".password_char_label").html("&nbsp;&nbsp;&#10006;&nbsp;special character").css("color", "#f44336");
        }

        // Checks all three conditions
        if ((this.value.length >= 6) && (this.value.match(/[a-z]/) && this.value.match(/[A-Z]/)) && (this.value.match(/[.,!@#$%^&*]/))) {
            $(".password_input").css("outline-color", "#54AE79"); // Change border color to green
            password_validated = true; // Password is validated (marker is set to true)
        } else {
            $(".password_input").css("outline-color", "#f44336"); // Changes border color to red
            password_validated = false; // Validation falis ( marker is false )
        }
        validation_check(); // Check if current input is valid 
    });

    // SIGN UP VALIDATION ENDS HERE

    // REGISTERING (SIGNING UP), LGGIN IN AND LOGGING OUT FUNCTIONALITY BEGINS HERE

    /**
     * When Signup form is submitted
     * (signup button is clicked)
     */
    $(".signup_submit").on("click", function (event) {
        event.preventDefault(); // Prevent default behavior (prevent reloading the page)

        // Define local variables to hold the credentials from input fields
        let signup_username = "";
        let signup_password = "";
        let signup_email = "";

        /**
         * Since there are two identical signup forms (modal form and form on the static page register.php),
         * This code is needed to check which of these two form is currently in use
         * It compares length of input fields of both forms and if length is > 0, then this value is used
         * to sign up.
         */
        // START of weird piece of code
        $(".username_input").each(function () {
            if ($(this).val().length > 0) {
                signup_username = $(this).val();
            }
        });
        $(".email_input").each(function () {
            if ($(this).val().length > 0) {
                signup_email = $(this).val();
            }
        });
        $(".password_input").each(function () {
            if ($(this).val().length > 0) {
                signup_password = $(this).val();
            }
        });
        // END of weird piece of code

        // Defines variable with signup parameters to send as POST parameters
        let signup_params = "username=" + signup_username + "&email=" + signup_email + "&password=" + signup_password;

        // Uses AJAX Fetch to send the values to signup_ajax.php file that will handle the request
        fetch("components/signup_ajax.php", {
                method: 'POST',
                credentials: 'include',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: signup_params // Includes parameters in the body of the request
            })
            .then(response => response.text()) // Receives response as text
            .then(success) // Calls success function

        function success(signed_up) {
            if (signed_up !== false && signed_up !== null && signed_up > 0) { // If the response is positive (registration successful)
                $("#signup_form").hide(); // Hide signup form
                $("#modal_loading_message").html("<div class='modal_form' style='padding-top: 20px; padding-bottom: 20px; display: flex; align-items: center; justify-content: center;'><img style='vertical-align: middle; height: 40px;' src='images/loading.gif' alt='Loading animation'>&nbsp;&nbsp;<h2>Success! Signing You Up...</h2></div>").show();
                setTimeout(function () {
                    window.location.replace("login.php"); // Redirect to login page
                }, 1000)

            } else { // If received response is false (registration failed)
                $(".signup_error").css("display", "block"); // Show signup error message
                setTimeout(function () { // Keep error message for 2 seconds, then hide it
                    $(".signup_error").fadeOut(function () {
                        $(".signup_error").hide();
                    });
                }, 2000)
            }
        }

    });


    /**
     * When Sign In form is submitted
     * (sign in button is clicked)
     */
    $(".login_submit").on("click", function (event) {

        event.preventDefault(); // Prevent default behavior (prevent reloading the page)

        // Define local variables to hold the credentials from input fields
        let password = "";
        let username = "";

        /**
         * Since there are two identical sign in forms (modal form and form on the static page login.php),
         * This code is needed to check which of these two form is currently in use
         * It compares length of input fields of both forms and if length is > 0, then this value is used
         * to log in.
         */
        // START of weird piece of code
        $(".username_login_input").each(function () {
            if ($(this).val().length > 0) {
                username = $(this).val();
            }
        });
        $(".username_password_input").each(function () {
            if ($(this).val().length > 0) {
                password = $(this).val();
            }
        });
        // END of weird piece of code

        // Defines variable with signup parameters to send as POST parameters
        let params = "username=" + username + "&password=" + password;

        // Uses AJAX Fetch to send the values to login_ajax.php file that will handle the request
        fetch("components/login_ajax.php", {
                method: 'POST',
                credentials: 'include',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params // Sends parameters in body of the request
            })
            .then(response => response.text()) // Receives response as text
            .then(success) // Calls success function

        function success(logged_in) {
            if (logged_in == 1) { // If response is 1 (true), log in succeeded
                $("#login_form").hide(); // Hide log in form
                // Show success message for 1 second, then reload current page
                $("#modal_loading_message").html("<div class='modal_form' style='padding-top: 20px; padding-bottom: 20px; display: flex; align-items: center; justify-content: center;'><img style='vertical-align: middle; height: 40px;' src='images/loading.gif' alt='Loading animation'>&nbsp;&nbsp;<h2>Logging You In...</h2></div>").show();
                setTimeout(function () {
                    location.reload();
                }, 1000)

            } else { // If response is not 1 (true), log in failed
                // Show wrong credentials message for 2 seconds, then hide
                $(".incorrect_credentials").css("display", "block");
                setTimeout(function () {
                    $(".incorrect_credentials").fadeOut(function () {
                        $(".incorrect_credentials").hide();
                    });
                }, 2000)
            }
        }
    })

    /**
     * When Log Out button is clicked
     */
    $("#logout_button").on("click", function (e) {

        e.preventDefault();

        let params = "logout=true";
        // Uses AJAX Fetch to send the values to login_ajax.php file that will handle the request
        fetch("components/logout_ajax.php", {
            method: 'POST',
            credentials: 'include',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: params // Sends parameters in body of the request
        })



        // Show logging out message for 1 second, then reload current page (the logging out function itself is handled in header.php file)
        $("#modal_loading_message").html("<div class='modal_form' style='padding-top: 20px; padding-bottom: 20px; display: flex; align-items: center; justify-content: center;'><img style='vertical-align: middle; height: 40px;' src='images/loading.gif' alt='Loading animation'>&nbsp;&nbsp;<h2>Logging You Out...</h2></div>").show();
        setTimeout(function () {
            location.reload();
        }, 1000)

    })

    // END OF REGISTERING (SIGNING UP), LGGIN IN AND LOGGING OUT FUNCTIONALITY

    // UPLOAD IMAGE BEGINS HERE (TODO COMMENTS)

    let file_extension; // Declaration of variable to store extension of the file in the input
    let file_size; // Declaration of variable to store size of the file in the input

    /**
     * When value in the file input field changes (new file selected)
     * Stores selected file's extension and size in variables
     */
    $("#upload_input").on("change", function () {
        if (this.files[0]) { // If any file is selected
            file_extension = this.files[0].name.split('.').pop(); // Retrieves file name and splits on last "." to get an extension
            file_size = this.files[0].size / 1024; // Retrieves file size
        }
    })

    /**
     * When Upload button is clicked
     * Validates selected file checking file_extension and file_size variables (shows error message if validation fails)
     * Upon successful validation launches AJAX request to upload_image_ajax.php and passes the file, title and description to it
     */
    $("#image_upload_form").on("submit", function (event) { // When Form is submitted
        event.preventDefault(); // Prevent default behavior

        if (!(file_extension === "jpg" || file_extension === "jpeg")) { // Validates file extension
            // Show file format error for 2 seconds, then hide it
            $("#upload_success").hide();
            $("#upload_preview").hide();
            $("#upload_error").html("Unsupported file format!<br><br>").css("display", "block");
            setTimeout(function () {
                $("#upload_error").fadeOut(function () {
                    $("#upload_error").hide();
                });
            }, 2000)
        } else if (file_size > 1024) { // Validates file extension
            // Show file size error for 2 seconds, then hide it
            $("#upload_success").hide();
            $("#upload_preview").hide();
            $("#upload_error").html("File size exceeds 1 MB (1024 KB)!<br><br>").css("display", "block");
            setTimeout(function () {
                $("#upload_error").fadeOut(function () {
                    $("#upload_error").hide();
                });
            }, 2000)
        } else { // File passed validation - launch ajax request
            $.ajax({
                url: "components/upload_image_ajax.php",
                type: "POST",
                data: new FormData(this), // Defines data types used by the upload form
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () { // While request is being processed, displays the loading image (gif)
                    $("#loading_message").html("<img src='images/loading.gif' style='width: 50px;'><br><br>").css("display", "block");
                },
                complete: function () { // When request is completed, hides loading image
                    $("#loading_message").hide();
                },
                success: function (response) { // On success
                    if (response == 'format_error') { // If response from upload_image_ajax.php is string 'format_error'
                        // Show file format error for 2 seconds, then hide it
                        $("#upload_success").hide();
                        $("#upload_preview").hide();
                        $("#upload_error").html("Unsupported file format!<br><br>").css("display", "block");
                        setTimeout(function () {
                            $("#upload_error").fadeOut(function () {
                                $("#upload_error").hide();
                            });
                        }, 2000)
                    } else if (response == 'size_error') { // If response from upload_image_ajax.php is string 'size_error'
                        // Show file size error for 2 seconds, then hide it
                        $("#upload_success").hide();
                        $("#upload_preview").hide();
                        $("#upload_error").html("File size exceeds 1 MB (1024 KB)!<br><br>").css("display", "block");
                        setTimeout(function () {
                            $("#upload_error").fadeOut(function () {
                                $("#upload_error").hide();
                            });
                        }, 2000)
                    } else if (response) { // If response is true (request completed successfully - no errors)

                        $("#upload_error").hide(); // Hides all errors
                        $("#upload_success").css("display", "block"); // Displays success message
                        $("#upload_preview").html(response).fadeIn(); // Displays preview of uploaded image
                        $("#image_upload_form")[0].reset(); // Resets the form inputs
                    } else { // If response is not true or defined error types - shows general error message
                        // Show general error message for 2 seconds, then hide it
                        $("#upload_success").hide();
                        $("#upload_preview").hide();
                        $("#upload_error").html("Something went wrong... Try again!<br><br>").css("display", "block");
                        setTimeout(function () {
                            $("#upload_error").fadeOut(function () {
                                $("#upload_error").hide();
                            });
                        }, 2000)
                    }
                },
                /**
                 * When AJAX request fails, launches function that displays error message
                 * @param {*} e Received error
                 */
                error: function (e) { // If AJAX request execution fails, shows error message for 2000 seconds, then hides it
                    $("#upload_success").hide();
                    $("#upload_error").html(e + " Try again!");
                    $("#upload_error").css("display", "block");
                    setTimeout(function () {
                        $("#upload_error").fadeOut(function () {
                            $("#upload_error").hide();
                        });
                    }, 2000)
                }
            });
        }
    })

    // UPLOAD IMAGE ENDS HERE

    // LIKES FUNCTIONALITY BEGINS HERE

    /**
     * If like icon is clicked
     * Launches AJAX request to like_dislike_ajax.php file
     */
    $(".like_icon").on("click", function () {

        let params = "image_id=" + $(this).attr("id") + "&request=" + $(this).attr("value"); // Defines parameters to send with the request

        fetch("components/like_dislike_ajax.php", {
                method: 'POST',
                credentials: 'include',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params // Sends parameters in body of the request
            })
            .then(response => response.json()) // Receives json encrypted response
            .then(success) // Calls success function

        /**
         * This function is launched upon completion of the AJAX request
         * @param {*} response Associative array that has ["response"] 1 (imae liked successfully) or 2 (image disliked successfully) and ["like_count"] indexes
         */
        function success(response) {
            if (response) { // If response contains a value
                $(".like_icon").hide(); // Hides original like icon
                if (response["response"] == 1) { // If response is 1 - image liked successfully

                    $(".hidden_dislike").css("display", "inline-block"); // Shows dislike icon
                }
                if (response["response"] == 2) { // If response is 2 - image disliked successfully

                    $(".hidden_like").css("display", "inline-block"); // Shows like icon
                }
                $("#total_likes").html(response["like_count"]); // Updates like counter
            } else // If response is empty, show error message
            {
                alert("Error: Try again!");
            }
        }
    })

    // LIKES FUNCTIONALITY END HERE

    // REMOVING IMAGE FUNCTIONALITY BEGINS HERE

    /**
     * When Remove image (x) icon is clicked
     */
    $(".remove_link").on("click", function (e) {
        e.preventDefault(); // Prevents default behavior

        let confrimation = confirm("Are you sure you want to remove the image?"); // Show confirmation message
        if (confrimation) { // If user confirms the action (response is true)
            let image_id = $(this).attr("id"); // Gets ID of the image to remove

            let params = "image_id=" + image_id; // Defines the prameters to send with AJAX request

            /**
             * Sends remove image request to remove_image_ajax.php
             */
            fetch("components/remove_image_ajax.php", {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: params // Sends parameters in body of the request
                })
                .then(response => response.text()) // Receives response as text
                .then(success) // Calls success function

            /**
             * This function is called upon completion of the AJAX request
             * @param {*} response ture (image removed)
             */
            function success(response) {
                if (response) { // If response is true (image removed successfully)
                    location.reload(); // Reload current page
                } else { // If response is false, show error message
                    alert("Error: Coldn't remove the image");
                }
            }

        }

    })

    // REMOVING IMAGE FUNCTIONALITY ENDS HERE

    // COMMENT POSTING BEGINS HERE

    /**
     * Validates length of comment text on each change and disables/enables Post comment button
     */
    $("#comment_text").on("keyup", function () {
        if ($(this).val().length > 1) { // If comment input textbox value is greater than 1
            $("#comment_submit").css({ // Enable Post Comment button
                "background-color": "#54AE79",
                "color": "white",
                "border": "none",
                "cursor": "pointer",
                "pointer-events": "auto"
            });
        } else { // If comment input textbox value is less than 1
            $("#comment_submit").css({ // Enable Post Comment button
                "background-color": "rgba(255, 255, 255, 0.3)",
                "color": "#54AE79",
                "border": "1px solid #54AE79",
                "cursor": "not-allowed",
                "pointer-events": "none"
            });
        }
    })


    /**
     * If Post Comment button is clicked
     * Sends AJAX request to post_comment_ajax.php
     * Updates comments block to display a new posted comment
     */
    $("#comment_submit").on("click", function (e) {
        e.preventDefault(); // Prevents default behavior

        let comment_text = $("#comment_text").val(); // Retrieves comment input text
        let image_id = $("#image_id").attr("value"); // Retrieves id of the image to which the comment is being posted

        let params = "image_id=" + image_id + "&comment_text=" + comment_text; // Defines parameters to send with the AJAX request

        fetch("components/post_comment_ajax.php", {
                method: 'POST',
                credentials: 'include',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params // Sends parameters in body of the request
            })
            .then(response => response.json()) // Receives as JSON encoded associative array
            .then(success) // Calls success function

        /**
         * Function to be lauched upon receiving an AJAX response
         * @param {*} response associative array with indexes ["response"] and ["comments_count"]
         */
        function success(response) {
            if (response["response"] && response["comments_count"] > 0) { // If response has values in it

                $("#total_comments").html(response["comments_count"]); // Update total comments counter
                $("#comments_output").html(response["comments_output"]); // Output the updated comments to comments container
                $("#comment_text").val(""); // Reset comment input textbox
                $("#comment_submit").css({ // Disable Post Comment button
                    "background-color": "rgba(255, 255, 255, 0.3)",
                    "color": "#54AE79",
                    "border": "1px solid #54AE79",
                    "cursor": "not-allowed",
                    "pointer-events": "none"
                });
                $("#comment_success").css("display", "block"); // Display a success message for 2 seconds, then hide it
                setTimeout(function () {
                    $("#comment_success").fadeOut(function () {
                        $("#comment_success").hide();
                    });
                }, 2000)
            } else { // If one or both of the received values is empty
                // Show error message for 2 seconds, then hide it
                $("#comment_error").css("display", "block");
                setTimeout(function () {
                    $("#comment_error").fadeOut(function () {
                        $("#comment_error").hide();
                    });
                }, 2000)
            }
        }

    })
    // COMMENT POSTING FUNCTIONALITY ENDS HERE

    // COMMENT DELETING FUNCTIONALITY BEGINS HERE

    /**
     * Listens to clicks on the page and launces remove Comment AJAX request when target of the click is remove comment icon
     * (Event handlers to remove_comment_icons cannot be added since these icons are added dynamically)
     */
    $("body").on("click", ".remove_comment_icon", function (e) {
        let comment_id = e.currentTarget.id; // Retrieves comment id from the target HTML element's id and stores in comment_id variable
        let image_id = $("#image_id").attr("value"); // Retrieves image id from hidden input that contains image id as a value and stores in image_id variable

        let params = "image_id=" + image_id + "&comment_id=" + comment_id; // Defines parameters to send with AJAX request

        // Sends AJAX request that removes the comment
        fetch("components/remove_comment_ajax.php", {
                method: 'POST',
                credentials: 'include',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: params // Sends parameters in body of the request
            })
            .then(response => response.json()) // Receives response as JSON encoded associative array
            .then(success) // Calls success function

        /**
         * Function is launched upon receiving a response from AJAX request
         * @param {*} response Associative array with indexes ["response"] (true/false), ["comments_count"] total count of comments for the image, ["comments_output"] updated comments as <div> blocks
         */
        function success(response) {
            if (response["response"]) { // If response is not false (success)
                $("#total_comments").html(response["comments_count"]); // Update total comments count
                $("#comments_output").html(response["comments_output"]); // Populate comments container with the comments
            } else { // Error occured - show allert
                alert("Error: Coldn't remove the comment");
            }
        }

    })

    // COMMENT DELETING FUNCTIONALITY ENDS HERE

});