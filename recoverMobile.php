<?php

require '/vendor/autoload.php';

use Mailgun\Mailgun;
session_start();
require 'connect.php';

if (isset($_POST['login'])) {
  $email = !empty($_POST['username']) ? trim($_POST['username']) : null;
  $hash = md5(rand(0, 1000));
  $sql = "UPDATE `users`.`users` SET recover=? WHERE email=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$hash,$email]);

  $q = $pdo->query("SELECT username FROM `users`.`users` WHERE email= '" . $email . "'");
  $t = $q->fetch();
  $username = $t['username'];

  $mg = new Mailgun("key-3d31f8fff100ea00947fc61bbc8b5a12");
  $domain = "mail.neuroloq1kk.me";
  $mg->sendMessage($domain, array(
        'from' => 'FantasyGo@FantasyLeague.com',
        'to' => '' . $email . '',
        'subject' => 'Password Reset FantasyGo',
        'text' => '

        ------------------------
        Username: ' . $username . '
        ------------------------

        In order to reset your password press the link below:
        

        Please click this link to recover your password:
        http://www.neuroloq1kk.me/FantasyGo/recoverPassword.php?email=' . $email . '&hash=' . $hash . ''
      ));


  displayAlert("An email was sent, you can reset your password thru it!", "Success");
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
              <button type="submit" value ="login" class="signupbtn" name="login">Send email</button>
            </div>
          </div>
        </div>
      </form>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
  </body>
  </html>
