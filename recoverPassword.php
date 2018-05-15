<?php
session_start();
require 'connect.php';

if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
  $email = ($_GET['email']);
  $hash = ($_GET['hash']);
  $sql = "SELECT recover FROM `users`.`users` WHERE email= '" . $email . "' AND recover='" . $hash . "'";
  
  $res = $pdo->query($sql);
  if ($res->fetchColumn() > 0) {
    echo '<script>location="recoverPasswordForm.php?email='.$email.'&hash='.$hash.'"</script>';
  }
  else echo '<script>location="index.php"</script>';
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
    <form class="SignIn" action="recoverMobile.php" method="POST" style="border:1px solid #ccc">
        <div class="txtcolor">
          <div class="container">
            <h1>Recover your account</h1>
            <hr>

            <label for="username"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="username" required>

            <p>Don't have an account? <a href="indexMobile.php" style="color:dodgerblue">Register</a></p>
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
  </html>
