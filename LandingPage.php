<?php
session_start();
require 'connect.php';
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
                    
                        <li class="font">
                            <a >Sign In</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="imgLanding">
            <img src="img/Landing/2.png">
        </div>
        <div class="wrapperLanding ">
            <form class="form-signin">       
                <h2 class="form-signin-heading">Register now and build your team right away!</h2>
                <input id="passText" type="text" class="form-control" name="email" placeholder="Email Address" required="" autofocus="" />
                <input id="passText" type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
                <input id="passLanding" type="password" class="form-control" name="password" placeholder="Password" required=""/>      
                <input id="passLanding" type="password" class="form-control" name="password" placeholder="Password" required=""/>   
                <button class="btn btn-lg btn-primary btn-block" type="submit">Create Account</button>   
            </form>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</div>
</html>