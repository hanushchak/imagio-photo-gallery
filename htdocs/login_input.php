<!--
    Author: Maksym Hanushchak, 000776919

    Date: November 29, 2019

    This material is original work of the authors stated above. 
    No other person's work has been used without due acknowledgement and the 
    authors have not made this work  available to anyone else.

    This is a modular file that defines input fields used on multiple pages. 
    Reduces code repetition.
-->
<div class="input_container">
    <span class='incorrect_credentials'>Incorrect credentials!<br><br></span>
    <label for="username"><b>Username or Email</b></label>
    <input type="text" placeholder="Your Username or Email" name="username" class="username_login_input" required>
    <label for="user_password"><b>Password</b></label>
    <input type="password" placeholder="Your Password" name="user_password" class="username_password_input" required>
    <button type="submit" class="login_submit">Log In</button>
</div>

