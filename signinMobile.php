<?php
session_start();
require 'connect.php';

if (isset($_POST['login'])) {
  $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
  $psw = !empty($_POST['psw']) ? trim($_POST['psw']) : null;
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:id");
  $stmt->execute(['id' => $username]);
  $user = $stmt->fetch();
  if ($user && password_verify($psw, $user['psw'])) {
    if ($user['verified'] == 0) {
      displayAlert("Please verify your account!", "danger");
    }
    else {
      $_SESSION["username"] = $username;
      echo '<script>location="myteamMobile.php"</script>';
    }
  }
  else {
    displayAlert("Username and/or Password incorrect.", "danger");
  }
}

function displayAlert($text,$type)
{
   echo "<div class=\"col-xs-10 col-xs-offset-1 col-xs-offset-right-1 alert alert-".$type."\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\" style=\"float: right;\">&times;</span></button>
            <p>" . $text . "</p>
          </div>";
}
?>
<html>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta 
     name='viewport' 
     content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' 
/>
  <title>Fantasy GO</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/app.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="/img/icon.png">
</head>

<div class="container-example">

  <body class="bg">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a id="logoSigninMobile" class="navbar-brand">
            <img src="img/logo1.png">
          </a>
        </div>
    </nav>
    <form class="SignIn" action="signinMobile.php" method="POST" style="border:1px solid #ccc">
        <div class="txtcolor">
          <div class="container">
            <h1>Sign In</h1>
            <hr>

            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>

            <p>Forgot your username/password? <a href="index.php" style="color:dodgerblue">Click here!</a></p>
            <p>Don't have an account? <a href="indexMobile.php" style="color:dodgerblue">Register</a></p>
            <div class="clearfix">
              <button type="button" class="cancelbtn">Cancel</button>
              <button type="submit" class="signupbtn" name="login">Sign In</button>
            </div>
          </div>
        </div>
      </form>
  </body>
  </html>
