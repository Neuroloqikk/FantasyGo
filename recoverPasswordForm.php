<?php
session_start();
require 'connect.php';

if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
  $email = ($_GET['email']);
  $hash = ($_GET['hash']);
  $sql = "SELECT recover FROM `users`.`users` WHERE email= '" . $email . "' AND recover='" . $hash . "'";
}
if($email == null){
  echo '<script>location="signin.php"</script>';
}
if (isset($_POST['register'])) {
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $pass = !empty($_POST['psw']) ? trim($_POST['psw']) : null;
  $passVerify = !empty($_POST['psw-repeat']) ? trim($_POST['psw-repeat']) : null;

  if ($pass == $passVerify){
    $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array(
      "cost" => 12
    ));
    $sql = "UPDATE `users`.`users` SET psw=?,recover=? WHERE email=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$passwordHash, $recover,$email]);
    echo '<script type="text/javascript">alert("'.$passwordHash.'");</script>';
    echo '<script type="text/javascript">alert("'.$recover.'");</script>';
    echo '<script type="text/javascript">alert("'.$email.'");</script>';
    displayAlert("Your password was updated!", "success");
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
    <form class="SignUp" method="POST" style="border:1px solid black">
    <div class="txtcolor">
      <div class="container">
        <h1>Password Recover</h1>
        <hr>
          <label for="currentEmail"><b>Current email</b></label>
          <input class ="txtcolorinput" type="text" value="<?$email?>" placeholder="<?=$email?>" name="currentEmail" disabled>
         
          <label for="psw"><b>New password</b></label>
          <input class="txtcolorinput" type="password" placeholder="Enter new password" name="psw" >

          <label for="psw-repeat"><b>Repeat new password</b></label>
          <input class="txtcolorinput" type="password" placeholder="Repeat new password" name="psw-repeat" >
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" value="Register" class="signupbtn" name="register">Update</button>
          </div>
        </div>
      </div>
    </form>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
  </body>
  </html>
