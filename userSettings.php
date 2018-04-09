<?php
session_start();

require 'connect.php';
    $username= $_SESSION["username"];
    $q = $pdo->query("SELECT email,psw FROM `users`.`users` WHERE username= '".$username."'");
    $t = $q->fetch();
    $email = $t['email'];
    $psw = $t['psw'];


    if (isset($_POST['register'])) {
        $email =!empty($_POST['email']) ? trim($_POST['email']) :null;
        $pass = !empty($_POST['psw']) ? trim($_POST['psw']) : null;
        $passVerify = !empty($_POST['psw-repeat']) ? trim($_POST['psw-repeat']) : null;
        $passCurrent = !empty($_POST['psw-current']) ? trim($_POST['psw-current']) : null;
        if(password_verify($passCurrent,$psw))
        if($pass ==""){
            $updatePass = false;
        }
        else{
            if($pass == $passVerify){
                $updatePass = true;
                $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
            }
            else{
                echo '<script>alert("Please verify your password!")</script>';
                echo '<script>location="userSettings.php"</script>';
            }
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<script>alert("Please type a valid email!")</script>';
            echo '<script>location="userSettings.php"</script>';
        }
        else{
            if($email == ""){
                $updateEmail = false;
            }
            else{
                $updateEmail = true;
            }
        }
        if($updatePass && $updateEmail){
            $sql = "UPDATE `users`.`users` SET email=?,psw=? WHERE username=?";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$email, $passwordHash,$username]);
            echo '<script>alert("Your email and password have been updated!")</script>';
            echo '<script>location="userSettings.php"</script>';
        }
        else if($updatePass){
            $sql = "UPDATE `users`.`users` SET psw=? WHERE username=?";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$passwordHash,$username]);
            echo '<script>alert("Your password have been updated!")</script>';
            echo '<script>location="userSettings.php"</script>';
        }
        else if($updateEmail){
            $sql = "UPDATE `users`.`users` SET email=? WHERE username=?";
            $stmt= $pdo->prepare($sql);
            $stmt->execute([$email,$username]);
            echo '<script>alert("Your email have been updated!")</script>';
            echo '<script>location="userSettings.php"</script>';
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
                <a class="navbar-brand" href="#">
                    <img src="img/logo.svg">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li id="usernameInsertGame" class="font">
                       <a href="userSettings.php"><?= $username ?></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <img class="menu-icon" src="img/menu.svg">
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="market.php">Market</a>
                            </li>
                            <li>
                                <a href="#">Next Games</a>
                            </li>
                            <li>
                                <a href="#">Last Games</a>
                            </li>
                            <li>
                                <a href="#">Settings</a>
                            </li>
                            <li>
                                <a href="logout.php" onClick>Logout</a>
                            </li>
                            
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <form class ="SignUp" method ="POST" style="border:1px solid black">
        <div class="txtcolor">
        <div class="container">
          <h1>Account Information,<h2>fill the fields which you want to update!</h2></h1>
          <hr>
          <label for="username"><b>Username</b></label>
          <input class ="txtcolorinput" type="text" placeholder="<?=$username?>" name="username" disabled>

          <label for="currentEmail"><b>Current email</b></labek>
          <input class ="txtcolorinput" type="text" placeholder="<?=$email?>" name="currentEmail" disabled>

          <label for="email"><b>New email</b></label>
          <input class ="txtcolorinput" type="text" placeholder="Enter new email" name="email" required>
      
          <label for="psw"><b>New password</b></label>
          <input class ="txtcolorinput" type="password" placeholder="Enter new password" name="psw" required>
      
          <label for="psw-repeat"><b>Repeat new password</b></label>
          <input class ="txtcolorinput" type="password" placeholder="Repeat new password" name="psw-repeat" required>

          <label for="psw"><b>Current password</b></label>
          <input class ="txtcolorinput" type="password" placeholder="Enter your current password" name="psw-current" required>«
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" value ="Register" class="signupbtn" name="register">Update</button>
          </div>
        </div>
        </div>
      </form>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>