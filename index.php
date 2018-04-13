<?php

require '/vendor/autoload.php';
use Mailgun\Mailgun;
require 'connect.php';
session_destroy ();
$timestamp = date("Y-m-d H:i:s");
if (isset($_POST['register'])) {
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $passVerify = !empty($_POST['password-repeat']) ? trim($_POST['password-repeat']) : null;
    $email =!empty($_POST['email']) ? trim($_POST['email']) :null;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:id");
    $stmt->execute(['id' => $username]); 
    $user = $stmt->fetch();
    //EMAIL
    $stmt = $pdo->prepare("SELECT COUNT(email) AS num FROM users.users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['num'] > 0){
        echo '<script>alert("That email already exists!")</script>';
        echo '<script>location="index.php"</script>';
    }

    //USERNAME
    $stmt = $pdo->prepare("SELECT COUNT(username) AS num FROM users.users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Please type a valid email!")</script>';
        echo '<script>location="index.php"</script>';
    }
    if($row['num'] > 0){
        echo '<script>alert("That username already exists!")</script>';
        echo '<script>location="index.php"</script>';
    }
    echo $pass;
    echo "<br>";
    echo $passVerify;
    if($pass == $passVerify){
        $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
    

        $sql = "INSERT INTO `users`.`users` (username,email,`psw`,timestamp) VALUES ('$username','$email','$passwordHash','$timestamp')";
        $stmt = $pdo->prepare($sql);
    
        $result = $stmt->execute();
            $sql = "INSERT INTO `users`.`users_players` (username) VALUES ('$username');";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute();
            
            //Your credentials
            $mg = new Mailgun("key-3d31f8fff100ea00947fc61bbc8b5a12");
            $domain = "sandbox24f037afa384475d9271bbd80cc44fc3.mailgun.org";
            //Customise the email - self explanatory
            $mg->sendMessage($domain, array(
            'from'=>'blockmaster12@gmail.com',
            'to'=> 'blockmaster12@gmail.com',
            'subject' => 'The PHP SDK is awesome!',
            'text' => 'It is so simple to send a message.'
                )
            )
            
            echo "Verify!";
            /*echo '<script>alert("Welcome to Fantasy GO!")</script>';
            echo '<script>location="signinSucess.php"</script>';*/
    }
    else{
        echo '<script>alert("Please verify your password!")</script>';
        echo '<script>location="index.php"</script>';
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
    <link rel="icon" type="image/png" href="/img/icon.png">
</head>
<div class="container-example">

    <body class="bg"  id="landingBG">
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
                        <img src="img/logo.svg" href="index.php" >
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    <ul class="nav navbar-nav navbar-right">
                    
                        <li class="font">
                            <a href="signin.php">Sign In</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="imgLanding">
            <img src="img/Landing/2.png">
        </div>
        <div class="wrapperLanding ">
            <form class="form-signin" method ="POST">       
                <h2 class="form-signin-heading">Register now and build your team right away!</h2>
                <input id="passText" type="text" class="form-control" name="email" placeholder="Email Address" required="" autofocus="" />
                <input id="passText" type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
                <input id="passLanding" type="password" class="form-control" name="password" placeholder="Password" required=""/>      
                <input id="passLanding" type="password" class="form-control" name="password-repeat" placeholder="Password" required=""/>   
                <p>Already have an account? <a href="signin.php" style="color:dodgerblue">Login</a></p>
                <button id="btnLanding" class="btn btn-lg btn-primary btn-block" type="submit" value ="Register" name="register">Create Account</button>   
            </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</div>
</html>