<?php
session_start();
require 'connect.php';
if (isset($_POST['login'])) {
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $psw = !empty($_POST['psw']) ? trim($_POST['psw']) : null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:id");
    $stmt->execute(['id' => $username]); 
    $user = $stmt->fetch();
    if ($user && password_verify($psw, $user['psw'])){
        if($user['verified'] == 0){
            $message = "You still need to verify your account!\\nCheck your spam folder if you can't find the email!";
            echo "<script type='text/javascript'>alert('$message');</script>";
            echo '<script>location="signin.php"</script>';
        }
        else{
            $_SESSION["username"] = $username;
            echo '<script>location="myteam.php"</script>';
        }
    } 
    else {
    $message = "Username and/or Password incorrect.\\nTry again.";
    echo "<script type='text/javascript'>alert('$message');</script>";
}
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fantasy GO</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
</head>
<div class="container-example">

    <body class="bg">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                        aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand">
                        <img src="img/logo.svg">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li >
                            <a href="signup.php">Sign Up</a>
                        </li>
                </div>
            </div>
        </nav>
        <!--Login-->
        <form class="SignIn" action="signin.php" method ="POST" style="border:1px solid #ccc">
            <div class="txtcolor">
            <div class="container">
              <h1>Sign In</h1>
              <hr>
          
              <label for="username"><b>Username</b></label>
              <input type="text" placeholder="Enter Username" name="username" required>
          
              <label for="psw"><b>Password</b></label>
              <input type="password" placeholder="Enter Password" name="psw" required>
          
              <label>
                <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
              </label>
              <p>Don't have an account? <a href="signup.php" style="color:dodgerblue">Register</a>.</p>
              <div class="clearfix">
                <button type="button" class="cancelbtn">Cancel</button>
                <button type="submit" class="signupbtn" name="login">Sign In</button>
              </div>
            </div>
            </div>
          </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</div>

</html>