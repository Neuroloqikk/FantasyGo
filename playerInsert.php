<?php
session_start();
require 'connect.php';
$username= $_SESSION["username"];
if (isset($_POST['register'])) {
    $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
    $price = !empty($_POST['price']) ? trim($_POST['price']) : null;
    $team =!empty($_POST['Team']) ? trim($_POST['Team']) : null;
    $photo =!empty($_POST['Photo']) ? trim($_POST['Photo']) : null;
    

    $sql = "INSERT INTO `users`.`players` (name,price,team,photo) VALUES ('$name','$price','$team','$photo')";
    $stmt = $pdo->prepare($sql);
 

    $result = $stmt->execute();
    

    if($result == 1){

        echo 'Thank you for registering a new player.';
        header('Location: /playerInsert.php');
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
                    <a class="navbar-brand" href="index.php">
                        <img src="img/logo.svg">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li id="usernameInsertGame" class="font">
                            <a ><?= $username ?></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img class="menu-icon" src="img/menu.svg">
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="index.php">My Team</a>
                                </li>
                                <li>
                                    <a href="#">Market</a>
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
                                    <a href="logout.php">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <form class ="SignUp" method ="POST" style="border:1px solid #ccc">
        <div class="txtcolor">
        <div class="container">
          <h1>Insert a new player</h1>
          <hr>
      
          <label for="name"><b>Name</b></label>
          <input class ="txtcolorinput" type="text" placeholder="Enter name" name="name" required>
      
          <label for="price"><b>price</b></label>
          <input class ="txtcolorinput" type="text" placeholder="Enter price" name="price" required>
      
          <label for="Team"><b>Team</b></label>
          <input class ="txtcolorinput" type="text" placeholder="Enter Team" name="Team" required>
      
          <label for="Photo"><b>Photo</b></label>
          <input class ="txtcolorinput" type="text" placeholder="Enter Photo" name="Photo" required>
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" value ="Register" class="signupbtn" name="register">Insert</button>
          </div>
        </div>
        </div>
      </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</div>

</html>