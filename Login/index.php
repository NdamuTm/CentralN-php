<!-- 
 // File: index.html
// Description: used for logging in and logging out
// Author: Ndamulelo Rasendedza
// Created: 2024-10-23
// License: MIT License 

-->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Signing/Signup</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/CSS/style.css">
    <link rel="stylesheet" href="assets/CSS/styles.css">
</head>
<body>
<div class="container" id="container">
    <style>
        form .flex{
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }
    </style>
<div class="form-container sign-up-container contact-form">
    <form action="/assets/Utils/Account.php" class="contact-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="signup"/>
        <h1 class="form-title">Create Account</h1>
        <br>
        <span>Use your email for registration</span><br>
        <div class="flex">
        <label>
            <input class="input-wrapper form-input" type="text" placeholder="Name" name="name" required/>
        </label>
        <label>
            <input class="input-wrapper form-input" type="text" placeholder="Surname" name="surname" required/>
        </label>
        </div>
        <select class="input-wrapper form-input" name="gender" required>
                <option class="form-input" value="" disabled selected>Select Gender</option>
                <option class="form-input" value="Male">Male</option>
                <option class="form-input" value="Female">Female</option>
                <option class="form-input" value="Other">Other</option>
            </select>
   

           
            <input class="input-wrapper form-input" type="date" name="dob" placeholder="Date of Birth" required/>
            <label>
                <input class="input-wrapper form-input" type="file" name="photo" accept="image/*" required/>
            </label>

        <div class="flex">
        <label>
            <input class="input-wrapper form-input" type="email" placeholder="Email" name="email" required/>
        </label>
        <label>
            <input class="input-wrapper form-input" type="text" placeholder="Contact" name="contact" required/>
        </label>
        </div>
        
        <button class="form-btn">Sign Up</button>
        <button class="ghost mobile" id="signInb"> Have an account <span>Sign in</span></button>
    </form>
</div>

    <div class="form-container contact-form sign-in-container">
        <form action="/assets/Utils/Account.php" class="contact-form" method="post" id="loginform">
            <input type="hidden" name="action" value="login"/>
            <h1>Sign in</h1>
            <br>
            <span>Use your account</span><br>
            <label>
                <input class="input-wrapper form-input" type="email" placeholder="Email" name="email"/>
            </label>
            <label >
                <input class="input-wrapper form-input" type="password" placeholder="Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"  title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required/>

            </label>
            <a id="forgotPasswordLink">Forgot your password?</a>
            <button class="form-btn">Sign In</button>
            <button class="ghost mobile" id="signUpa">Don't Have an account <span>Sign up</span></button>
        </form>
        <form action="/assets/Utils/Account.php" class="contact-form" method="post" id="forgotform" style="display: none;">
            <input type="hidden" name="action" value="forgotPassword"/>
            <h1>Forgot Password</h1>
            <br>
            <span>Enter your email to reset your password</span><br>
            <label>
                <input class="input-wrapper form-input" type="email" placeholder="Email" name="email" required/>
            </label>
            <label>
                <input class="input-wrapper form-input" type="password" placeholder="Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"  title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
            </label>
             <button class="form-btn">Reset Password</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Hello, Friend!</h1>
                <p>Enter your personal details and start your journey with us</p>
                <button class="ghost" id="signIn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Welcome Back!</h1>
                <p>To keep connected with us please log in with your personal info</p>
                <button class="ghost" id="signUp">Sign Up</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get references to the forms
        const loginForm = document.getElementById('loginform');
        const forgotForm = document.getElementById('forgotform');

        // Get the link that triggers the "Forgot Password" form
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');

        // Handle the click event on the "Forgot Password" link
        forgotPasswordLink.addEventListener('click', function(event) {
            event.preventDefault();

            // Hide the login form and show the forgot password form
            loginForm.style.display = 'none';
            forgotForm.style.display = 'flex';
        });
    });

</script>
<script src="/Login/assets/JS/main.js"></script>
</body>
</html>
