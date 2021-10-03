<!--
    Author: Maksym Hanushchak, 000776919

    Date: November 29, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    This is a modular file that defines input fields and input error messages used on multiple pages. 
    Reduces code repetition. Error messages are hidden by default.
-->
<div class="input_container">
    <span class='signup_error'>Something Went Wrong... Try again!<br><br></span>
    <label for="username"><b>Username</b></label>
    <div class="username_validation_label validation_label_div">
    <span class="label unvalid_label username_exists_label">&nbsp;&nbsp;&#10006;&nbsp;username already exists</span><span class="label unvalid_label username_label">&nbsp;&nbsp;&#10006;&nbsp;minimum 6 characters</span>
    <br>    
    </div>
    <input type="text" placeholder="Your Username" name="username" class="username_input" required>
    <label for="email"><b>Email</b></label>
    <div class="email_validation_label validation_label_div">
    <span class="label unvalid_label email_exists_label">&nbsp;&nbsp;&#10006;&nbsp;email already exists</span><span class="label unvalid_label email_label">&nbsp;&nbsp;&#10006;&nbsp;email is incomplete</span>
    <br>
    </div>
    <input type="email" placeholder="Your Email" name="email" class="email_input" required>
    <label for="user_password"><b>Password</b></label>
    <div class="password_validation_label validation_label_div">
    <span class="label unvalid_label password_min_label">&nbsp;&nbsp;&#10006;&nbsp;minimum 8 characthers</span><span class="label unvalid_label password_upper_label">&nbsp;&nbsp;&#10006;&nbsp;uppercase and lowercase letter</span><span class="label unvalid_label password_char_label">&nbsp;&nbsp;&#10006;&nbsp;special character</span>
    <br>
    </div>
    <input type="password" placeholder="Your Password" name="user_password" class="password_input" required>
    <button type="submit" class="signup_submit">Sign Up</button>
</div>