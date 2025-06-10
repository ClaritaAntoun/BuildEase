<!-- Clarita Antoun -->
<?php 
session_start();
include("conx.php"); ?>
<?php include("header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Font Awesome -->
    <style>
        body {
            background-color: #f8f9fa;
            background: url('images/b1.jpg') no-repeat center center fixed; 
            background-size: cover;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper i {
            position: absolute;
            right: 10px;
            top: 80%; /* Align the icon vertically to the center */
            transform: translateY(-50%); /* Fine-tune the vertical position */
            cursor: pointer;
        }
        .input-container input {
            width: 100%;
            padding: 10px;
            padding-right: 40px; /* Space for the icon */
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }




        .password-container {
    position: relative;
    display: flex;
    align-items: center;
}

.password-container input {
    width: 100%;
    padding-right: 40px; /* Space for eye icon */
    padding-left: 10px;
}

#eye-icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%); /* Center the icon */
    cursor: pointer;
    color: gray;
    font-size: 18px;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h3 class="text-center">Login</h3>
          
            <?php
if(isset($_SESSION['logIn_user_error'])){
    if(isset( $_SESSION['logIn_user_error']['email'])){
        echo "<p class='text-danger text-center'>". $_SESSION['logIn_user_error']['email']."</p>" ;
        unset( $_SESSION['logIn_user_error']['email']);//delete this session 
    }
 if(isset($_SESSION['logIn_user_error']['password'])){
    echo "<p class='text-danger text-center'>".$_SESSION['logIn_user_error']['password']."</p>" ;
        unset($_SESSION['logIn_user_error']['password']);//delete this session 
 }
 if(isset($_SESSION['logIn_user_error']['email_password'])){
    echo "<p class='text-danger text-center'>".$_SESSION['logIn_user_error']['email_password']."</p>" ;
        unset($_SESSION['logIn_user_error']['email_password']);//delete this session 
 }
}


?>
           <form method="POST" action="logInProcess.php">
    <div class="mb-3">
        <label for="email" class="form-label"><b><i>Email address</i></b></label>
        <input type="email" class="form-control" id="email" name="email">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label"><b><i>Password</i></b></label>
        <div class="password-container">
            <input type="password" class="form-control" id="password" name="password">
            <i class="fas fa-eye" id="eye-icon" onclick="togglePassword()"></i>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>
            <div class="mt-3 text-center">
                <p><b>Don't have an account?</b></p>
                <a href="signUp.php" class="btn btn-outline-primary btn-sm">Sign up</a>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('eye-icon').addEventListener('click', function () {
            var passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include("footer.php"); ?>
