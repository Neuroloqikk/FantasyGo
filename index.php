<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
  if (screen.width <= 800) {
  document.location = "indexMobile.php";
  }
</script>
<?php
require '/vendor/autoload.php';

use Mailgun\Mailgun;
require 'connect.php';

$username = $_SESSION["username"];

if ($username != NULL) {
  echo '<script>location="myteam.php"</script>';
}
else {
  session_destroy();
  $timestamp = date("Y-m-d H:i:s");
  if (isset($_POST['register'])) {
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $passVerify = !empty($_POST['password-repeat']) ? trim($_POST['password-repeat']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:id");
    $stmt->execute(['id' => $username]);
    $user = $stmt->fetch();
    // EMAIL

    $stmt = $pdo->prepare("SELECT COUNT(email) AS num FROM users.users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // USERNAME

    $stmt = $pdo->prepare("SELECT COUNT(username) AS num FROM users.users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] <= 0 and $pass == $passVerify and filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array(
        "cost" => 12
      ));
      $hash = md5(rand(0, 1000));
      $sql = "INSERT INTO `users`.`users` (username,email,`psw`,timestamp,hash) VALUES ('$username','$email','$passwordHash','$timestamp','$hash')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      $sql = "INSERT INTO `users`.`users_players` (username) VALUES ('$username');";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      $mg = new Mailgun("key-3d31f8fff100ea00947fc61bbc8b5a12");
      $domain = "mail.neuroloq1kk.me";
      $mg->sendMessage($domain, array(
        'from' => 'FantasyGo@FantasyLeague.com',
        'to' => '' . $email . '',
        'subject' => 'Welcome to FantasyGo!',
        'text' => '
        Thanks for signing up!
        Your account has been created, you can login with the following username after you have activated your account by pressing the url below.

        ------------------------
        Username: ' . $username . '
        ------------------------

        Please click this link to activate your account:
        http://www.neuroloq1kk.me/FantasyGo/verify.php?email=' . $email . '&hash=' . $hash . '.'
      ));
      displayAlert("An email was sent to your email, check it in order to verify your account!", "warning");
    }
    
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      displayAlert("Please enter a valid email!", "warning");
    }
    else if( $row['num'] > 0 ){
      displayAlert("That username already exists!", "warning");
    }
    else if($pass != $passVerify){
      displayAlert("Please verify your password!", "warning");
    }
  }
}

function displayAlert($text,$type)
{
   echo "<div class=\"col-xs-10 col-xs-offset-1 col-xs-offset-right-1 alert alert-".$type."\" role=\"alert\">
        <button type=\"button\" class=\"col-xs-10 col-xs-offset-1 col-xs-offset-right-1 close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\" style=\"float: right;\">&times;</span></button>
            <p>" . $text . "</p>
          </div>";
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

  <body class="bg" id="landingBG">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a class="navbar-brand">
            <img src="img/logo.svg" href="index.php" >
          </a>
          <a href="#" class="navbar-brand" id="sidebarShow" onclick="showGames()">
            <img src="img/eye.svg">
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
      <div class="sidenav" id="sidebarShowBtn" style="display: none;">
        <a id="SidebarTitle"><b>Coming Games</b></a>
        <?php 
         $stmt = $pdo->query("SELECT team1,team2,Date,Hour FROM next_games ORDER BY Date DESC LIMIT 5");
         $p = $stmt->fetchAll();
         foreach($p as $row){
            ?>
         <a><b><?=$row['team1'];?> vs <?=$row['team2'];?></b><br><?=$row['Date'];?>-<?=$row['Hour'];?></a>
         

         <?php }?>
        <hr>
        <a id="SidebarTitle"><b>Last Games</b></a>
        <?php 
         $stmt = $pdo->query("SELECT team1,team2,score_team1,score_team2,next_game_id FROM results ORDER BY timestamp DESC LIMIT 5");
         $p = $stmt->fetchAll();
         $team1score = "win";
         $team2score = "loose";
         foreach($p as $row){
           if($row['score_team1']>$row['score_team2']){
             $team1score = "win";
             $team2score = "loose";
           }
          else{
            $team2score = "win";
            $team1score = "loose";
          }
            ?>
         <a href="lastGame.php?id=<?=$row['next_game_id'];?>"><?=$row['team1'];?> vs <?=$row['team2'];?><br><span id="<?=$team1score?>"><?=$row['score_team1'];?></span>-<span id="<?=$team2score?>"><?=$row['score_team2'];?></span></a>
         

         <?php }?>
      </div>
    </nav>
    <div class="siteInfo">
      <div class="landingPart1">
        <div class="landingImgPart1">
          <img src="img/Landing/signin.jpg">
        </div>
        <div class="landingTxtPart1">
          <p><span>Enter your username and password after</span></p>
          <p><span>registering to immediately start playing.</span></p>
        </div>
      </div>
      <div class="landingPart2">
        <div class="landingTxtPart2">
          <p><span>Hover your favorite teams,pick a player</span></p>
          <p><span>and buy him with your available balance.</span></p>
        </div>
        <div class="landingImgPart2">
          <img src="img/Landing/market.jpg">
        </div>
      </div>
      <div class="landingPart3">
        <div class="landingImgPart3">
          <img src="img/Landing/team.jpg">
        </div>
        <div class="landingTxtPart3">
          <p><span>Increase your score accordingly to how</span></p>
          <p><span>your players perform in real matches.</span></p>
        </div>
      </div>
      <div class="landingPart4">
        <div class="landingTxtPart4">
          <p><span>Play with your friends and conquer the</span></p>
          <p><span>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;Fantasy Go leaderboards!</span></p>
        </div>
        <div class="landingImgPart4">
          <img src="img/Landing/leaderboard.png">
        </div>
      </div>
    </div>
    <div class="wrapperLanding2">
      <form class="form-signin" method ="POST" style="color:white;">
        <h2 class="form-signin-heading">Register now and build your team right away!</h2>
        <input id="passText" type="text" class="form-control" name="email" placeholder="Email Address" required="" autofocus="" />
        <input id="passText" type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
        <input id="passLanding" type="password" class="form-control" name="password" placeholder="Password" required=""/>
        <input id="passLanding" type="password" class="form-control" name="password-repeat" placeholder="Password" required=""/>
        <p>Already have an account? <a href="signin.php" style="color:dodgerblue">Login</a></p>
        <button id="btnLanding" class="btn btn-lg btn-primary btn-block" type="submit" value ="Register" name="register">Create Account</button>
      </form>
    </div>
    <script>
    function showGames() {
      var x = document.getElementById("sidebarShowBtn");
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </div>
</body>
</html>
