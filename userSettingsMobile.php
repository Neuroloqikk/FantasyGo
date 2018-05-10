<?php
session_start();
require 'connect.php';

$username = $_SESSION["username"];
$q = $pdo->query("SELECT email,psw FROM `users`.`users` WHERE username= '" . $username . "'");
$t = $q->fetch();
$email = $t['email'];
$psw = $t['psw'];

if (isset($_POST['register'])) {
  $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
  $pass = !empty($_POST['psw']) ? trim($_POST['psw']) : null;
  $passVerify = !empty($_POST['psw-repeat']) ? trim($_POST['psw-repeat']) : null;
  $passCurrent = !empty($_POST['psw-current']) ? trim($_POST['psw-current']) : null;
  if (password_verify($passCurrent, $psw))
  if ($pass == "") {
    $updatePass = false;
  }
  else {
    if ($pass == $passVerify) {
      $updatePass = true;
      $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array(
        "cost" => 12
      ));
    }
    else {
      displayAlert("Please verify your password!", "danger");
    }
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    displayAlert("Please type a valid email!", "danger");
  }
  else {
    if ($email == "") {
      $updateEmail = false;
    }
    else {
      $updateEmail = true;
    }
  }

  if ($updatePass && $updateEmail) {
    $sql = "UPDATE `users`.`users` SET email=?,psw=? WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $passwordHash, $username]);
    displayAlert("Your email and password have been updated!", "success");
  }
  else
  if ($updatePass) {
    $sql = "UPDATE `users`.`users` SET psw=? WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$passwordHash, $username]);
    displayAlert("Your password have been updated!", "success");
  }
  else
  if ($updateEmail) {
    $sql = "UPDATE `users`.`users` SET email=? WHERE username=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $username]);
    displayAlert("Your email have been updated!", "success");
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
<div class="turnDeviceNotification"></div>
<div class="container-example">

    <body class="bg">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <div class="usernameMobile col-xs-4 col-xs-offset-4 col-xs-offset-right-4">
            <p><a href="userSettings.php"><?=$username?></a></p>
          </div>
          <div class="menuLogoMobile">
            <img onclick="myFunction()" src="img/menu.svg" style="width: inherit;">
          </div>  
            <div id="myDropdown" class="dropdownMobile-content">
              <a href="myTeamMobile.php">My Team</a>
              <a href="marketMobile.php">Market</a>
              <a href="leaderboardMobile.php">Leaderboard</a>
              <a href="nextGamesMobile.php">Next Games</a>
              <a href="lastGamesMobile.php">Last Games</a>
              <a href="userSettingsMobile.php">Settings</a>
              <a href="logoutMobile.php">Logout</a>
            </div>
          <a id="logoMobile" class="navbar-brand">
            <img src="img/logo.png">
          </a>
        </div>
    </nav>
    <form class="SignUp" method="POST" style="border:1px solid black">
    <div class="txtcolor">
      <div class="container">
        <h1>Account Information,
          <h2>fill the fields which you want to update!</h2>
        </h1>
        <hr>
        <label for="username"><b>Username</b></label>
        <input class="txtcolorinput" type="text" placeholder="<?=$username?>" name="username" disabled>

        <label for="currentEmail"><b>Current email</b></labek>
          <input class ="txtcolorinput" type="text" placeholder="<?=$email?>" name="currentEmail" disabled>

          <label for="email"><b>New email</b></label>
          <input class="txtcolorinput" type="text" placeholder="Enter new email" name="email">

          <label for="psw"><b>New password</b></label>
          <input class="txtcolorinput" type="password" placeholder="Enter new password" name="psw" >

          <label for="psw-repeat"><b>Repeat new password</b></label>
          <input class="txtcolorinput" type="password" placeholder="Repeat new password" name="psw-repeat" >

          <label for="psw"><b>Current password</b></label>
          <input class="txtcolorinput" type="password" placeholder="Enter your current password" name="psw-current" required>
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" value="Register" class="signupbtn" name="register">Update</button>
          </div>
        </div>
      </div>
    </form>

</div>
  <script>
  jQuery(window).bind('orientationchange', function(e) {
  switch ( window.orientation ) {
  case 0:
      $('.turnDeviceNotification').css('display', 'none');
      // The device is in portrait mode now
  break;

  case 180:
      $('.turnDeviceNotification').css('display', 'none');
      // The device is in portrait mode now
  break;

  case 90:
      // The device is in landscape now
      $('.turnDeviceNotification').css('display', 'block');
  break;

  case -90:
      // The device is in landscape now
      $('.turnDeviceNotification').css('display', 'block');
  break;
  }
  });
  </script>
  <script>
      /* When the user clicks on the button, 
  toggle between hiding and showing the dropdown content */
  function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
  }

  // Close the dropdown menu if the user clicks outside of it
  window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

  var dropdowns = document.getElementsByClassName("dropdown-content");
  var i;
  for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
      openDropdown.classList.remove('show');
      }
  }
  }
  }
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</div>
</body>

</html>
