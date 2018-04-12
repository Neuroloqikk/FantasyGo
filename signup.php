<?php
session_start();
require 'connect.php';
session_destroy ();
$timestamp = date("Y-m-d H:i:s");
if (isset($_POST['register'])) {
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $pass = !empty($_POST['psw']) ? trim($_POST['psw']) : null;
    $passVerify = !empty($_POST['psw-repeat']) ? trim($_POST['psw-repeat']) : null;
    $email =!empty($_POST['email']) ? trim($_POST['email']) :null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:id");
    $stmt->execute(['id' => $username]); 
    $user = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT COUNT(username) AS num FROM users.users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Please type a valid email!")</script>';
        echo '<script>location="signup.php"</script>';
    }
    if($row['num'] > 0){
        echo '<script>alert("That username already exists!")</script>';
        echo '<script>location="signup.php"</script>';
    }
    if($pass == $passVerify){
        $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
    

        $sql = "INSERT INTO `users`.`users` (username,email,`psw`,timestamp) VALUES ('$username','$email','$passwordHash','$timestamp')";
        $stmt = $pdo->prepare($sql);
    
        $result = $stmt->execute();
            $sql = "INSERT INTO `users`.`users_players` (username) VALUES ('$username');";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            echo '<script>alert("Welcome to Fantasy GO!")</script>';
            echo '<script>location="signinSucess.php"</script>';
    }
    else{
        echo '<script>alert("Please verify your password!")</script>';
        echo '<script>location="signup.php"</script>';
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
                    <li class="font">
                        <a href="signin.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <form class ="SignUp" method ="POST" style="border:1px solid #ccc">
        <div class="alert alert-danger">Passwords must be equal!</div>
        <div class="txtcolor">
        <div class="container">
          <h1>Sign Up</h1>
          <hr>
          <label for="email"><b>Email</b></label>
          <input class ="txtcolorinput" type="text" placeholder="Enter Email" name="email" required>
          <label for="username"><b>Username</b></label>
          <input class ="txtcolorinput" type="text" placeholder="Enter Username" name="username" required>
          <label for="psw"><b>Password</b></label>
          <input class ="txtcolorinput" type="password" placeholder="Enter Password" name="psw" required>
          <label for="psw-repeat"><b>Repeat Password</b></label>
          <input class ="txtcolorinput" type="password" placeholder="Repeat Password" name="psw-repeat" required>
          <label>
            <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
          </label>
          <p>Already have an account? <a href="signin.php" style="color:dodgerblue">Login</a>.</p>
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" value ="Register" class="signupbtn" name="register">Sign Up</button>
          </div>
        </div>
        </div>
      </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</div>
</html>