<?php
session_start();
require 'connect.php';

$username = $_SESSION["username"];

if (isset($_GET["r1"]) && isset($_GET["r2"]) && isset($_GET["r3"]) && isset($_GET["r4"]) && isset($_GET["r5"]) && isset($_GET["r6"]) && isset($_GET["r7"]) && isset($_GET["r8"]) && isset($_GET["r9"]) && isset($_GET["r10"]) && isset($_GET["t1_score"]) && isset($_GET["t2_score"])) {
   $team1 = $_SESSION['team1'];
   $team1_score = $_GET["t1_score"];
   $team1_player1_rating = $_GET["r1"] * 100;
   $team1_player2_rating = $_GET["r2"] * 100;
   $team1_player3_rating = $_GET["r3"] * 100;
   $team1_player4_rating = $_GET["r4"] * 100;
   $team1_player5_rating = $_GET["r5"] * 100;
   $team2 = $_SESSION['team2'];
   $team2_score = $_GET["t2_score"];
   $team2_player1_rating = $_GET["r6"] * 100;
   $team2_player2_rating = $_GET["r7"] * 100;
   $team2_player3_rating = $_GET["r8"] * 100;
   $team2_player4_rating = $_GET["r9"] * 100;
   $team2_player5_rating = $_GET["r10"] * 100;
   $timestamp = date("Y-m-d H:i:s");

   // team result

   if ($team1 == NULL or $team2 == NULL) {
      header('Location: /insertGame.php');
      unset($_SESSION['team1_player1']);
      unset($_SESSION['team1_player2']);
      unset($_SESSION['team1_player3']);
      unset($_SESSION['team1_player4']);
      unset($_SESSION['team1_player5']);
      unset($_SESSION['team1']);
      unset($_SESSION['team2']);
   }
   else {
      $sql = "INSERT INTO `users`.`results` (team1,team2,score_team1,score_team2,timestamp) VALUES ('$team1','$team2','$team1_score','$team2_score','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();
      $stmt = $pdo->query("SELECT MAX(id) FROM `users`.`results`");
      $t = $stmt->fetch();
      $id = implode(" ", $t);
      $won = 1;
      $lost = 0;
      if ($team1_score > $team2_score) {
         $sql = "INSERT INTO `users`.`results_team` (results_id,team_name,team_results,team_won) VALUES ('$id','$team1','$team1_score','$won')";
         $stmt = $pdo->prepare($sql);
         $result = $stmt->execute();
         $sql = "INSERT INTO `users`.`results_team` (results_id,team_name,team_results,team_won) VALUES ('$id','$team2','$team2_score','$lost')";
         $stmt = $pdo->prepare($sql);
         $result = $stmt->execute();
      }
      else {
         $sql = "INSERT INTO `users`.`results_team` (results_id,team_name,team_results,team_won) VALUES ('$id','$team1','$team1_score','$lost')";
         $stmt = $pdo->prepare($sql);
         $result = $stmt->execute();
         $sql = "INSERT INTO `users`.`results_team` (results_id,team_name,team_results,team_won) VALUES ('$id','$team2','$team2_score','$won')";
         $stmt = $pdo->prepare($sql);
         $result = $stmt->execute();
      }

      // Player1

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team1_player1'] . "','$team1_player1_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player2

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team1_player2'] . "','$team1_player2_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player3

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team1_player3'] . "','$team1_player3_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player4

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team1_player4'] . "','$team1_player4_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player5

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team1_player5'] . "','$team1_player5_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player6

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team2_player1'] . "','$team2_player1_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player7

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team2_player2'] . "','$team2_player2_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player8

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team2_player3'] . "','$team2_player3_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player9

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team2_player4'] . "','$team2_player4_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // Player10

      $sql = "INSERT INTO `users`.`results_player` (results_id,player_name,player_score,timestamp) VALUES ('$id','" . $_SESSION['team2_player5'] . "','$team2_player5_rating','$timestamp')";
      $stmt = $pdo->prepare($sql);
      $result = $stmt->execute();

      // User Players

      $sql = "SELECT * FROM `users`.`users_players`";
      $users = $pdo->query($sql);
      $team1_player1_id = $_SESSION['team1_player1_id'];
      $team1_player2_id = $_SESSION['team1_player2_id'];
      $team1_player3_id = $_SESSION['team1_player3_id'];
      $team1_player4_id = $_SESSION['team1_player4_id'];
      $team1_player5_id = $_SESSION['team1_player5_id'];
      $team2_player1_id = $_SESSION['team2_player1_id'];
      $team2_player2_id = $_SESSION['team2_player2_id'];
      $team2_player3_id = $_SESSION['team2_player3_id'];
      $team2_player4_id = $_SESSION['team2_player4_id'];
      $team2_player5_id = $_SESSION['team2_player5_id'];
      foreach($users as $row) {
         $user = $row['username'];
         $user_player1_id = $row['player1_id'];
         $user_player2_id = $row['player2_id'];
         $user_player3_id = $row['player3_id'];
         $user_player4_id = $row['player4_id'];
         $user_player5_id = $row['player5_id'];
         $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
         $p = $stmt->fetch();
         $score = implode($p);
         if ($team1_player1_id == $user_player1_id or $team1_player1_id == $user_player2_id or $team1_player1_id == $user_player3_id or $team1_player1_id == $user_player4_id or $team1_player1_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team1_player1_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team1_player2_id == $user_player1_id or $team1_player2_id == $user_player2_id or $team1_player2_id == $user_player3_id or $team1_player2_id == $user_player4_id or $team1_player2_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team1_player2_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team1_player3_id == $user_player1_id or $team1_player3_id == $user_player2_id or $team1_player3_id == $user_player3_id or $team1_player3_id == $user_player4_id or $team1_player3_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team1_player3_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team1_player4_id == $user_player1_id or $team1_player4_id == $user_player2_id or $team1_player4_id == $user_player3_id or $team1_player4_id == $user_player4_id or $team1_player4_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team1_player4_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team1_player5_id == $user_player1_id or $team1_player5_id == $user_player2_id or $team1_player5_id == $user_player3_id or $team1_player5_id == $user_player4_id or $team1_player5_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team1_player5_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team2_player1_id == $user_player1_id or $team2_player1_id == $user_player2_id or $team2_player1_id == $user_player3_id or $team2_player1_id == $user_player4_id or $team2_player1_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team2_player1_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team2_player2_id == $user_player1_id or $team2_player2_id == $user_player2_id or $team2_player2_id == $user_player3_id or $team2_player2_id == $user_player4_id or $team2_player2_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team2_player2_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team2_player3_id == $user_player1_id or $team2_player3_id == $user_player2_id or $team2_player3_id == $user_player3_id or $team2_player3_id == $user_player4_id or $team2_player3_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team2_player3_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team2_player4_id == $user_player1_id or $team2_player4_id == $user_player2_id or $team2_player4_id == $user_player3_id or $team2_player4_id == $user_player4_id or $team2_player4_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team2_player4_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }

         if ($team2_player5_id == $user_player1_id or $team2_player5_id == $user_player2_id or $team2_player5_id == $user_player3_id or $team2_player5_id == $user_player4_id or $team2_player5_id == $user_player5_id) {
            $stmt = $pdo->query("SELECT score FROM `users`.`users` WHERE username='$user'");
            $p = $stmt->fetch();
            $score = implode($p);
            $currentScore = $score + $team2_player5_rating;
            $sql = "UPDATE `users`.`users` SET score=? WHERE username=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$currentScore, $user]);
         }
      }

      echo '<script>alert("Game Inserted")</script>';
      echo '<script>location="insertGame.php"</script>';
      unset($_SESSION['team1_player1']);
      unset($_SESSION['team1_player2']);
      unset($_SESSION['team1_player3']);
      unset($_SESSION['team1_player4']);
      unset($_SESSION['team1_player5']);
      unset($_SESSION['team2_player1']);
      unset($_SESSION['team2_player2']);
      unset($_SESSION['team2_player3']);
      unset($_SESSION['team2_player4']);
      unset($_SESSION['team2_player5']);
      unset($_SESSION['team1_player1_id']);
      unset($_SESSION['team1_player2_id']);
      unset($_SESSION['team1_player3_id']);
      unset($_SESSION['team1_player4_id']);
      unset($_SESSION['team1_player5_id']);
      unset($_SESSION['team2_player1_id']);
      unset($_SESSION['team2_player2_id']);
      unset($_SESSION['team2_player3_id']);
      unset($_SESSION['team2_player4_id']);
      unset($_SESSION['team2_player5_id']);
      unset($_SESSION['team1']);
      unset($_SESSION['team2']);
   }
}

if (isset($_GET['t1'])) {
   $team1 = $_GET['t1'];
   $stmt = $pdo->query("SELECT name FROM `users`.`players` WHERE team='$team1'");
   $p = $stmt->fetchAll();
   $_SESSION['team1'] = $team1;
   $_SESSION['team1_player1'] = implode($p[0]);
   $_SESSION['team1_player2'] = implode($p[1]);
   $_SESSION['team1_player3'] = implode($p[2]);
   $_SESSION['team1_player4'] = implode($p[3]);
   $_SESSION['team1_player5'] = implode($p[4]);
   $stmt = $pdo->query("SELECT id FROM `users`.`players` WHERE team='$team1'");
   $p = $stmt->fetchAll();
   $_SESSION['team1_player1_id'] = implode($p[0]);
   $_SESSION['team1_player2_id'] = implode($p[1]);
   $_SESSION['team1_player3_id'] = implode($p[2]);
   $_SESSION['team1_player4_id'] = implode($p[3]);
   $_SESSION['team1_player5_id'] = implode($p[4]);
   $team1_player1 = $_SESSION['team1_player1'];
   $team1_player2 = $_SESSION['team1_player2'];
   $team1_player3 = $_SESSION['team1_player3'];
   $team1_player4 = $_SESSION['team1_player4'];
   $team1_player5 = $_SESSION['team1_player5'];
}

if (isset($_GET['t2'])) {
   $team2 = $_GET['t2'];
   $stmt = $pdo->query("SELECT name FROM `users`.`players` WHERE team='$team2'");
   $p = $stmt->fetchAll();
   $_SESSION['team2'] = $team2;
   $team1_player1 = $_SESSION['team1_player1'];
   $team1_player2 = $_SESSION['team1_player2'];
   $team1_player3 = $_SESSION['team1_player3'];
   $team1_player4 = $_SESSION['team1_player4'];
   $team1_player5 = $_SESSION['team1_player5'];
   $_SESSION['team2_player1'] = implode($p[0]);
   $_SESSION['team2_player2'] = implode($p[1]);
   $_SESSION['team2_player3'] = implode($p[2]);
   $_SESSION['team2_player4'] = implode($p[3]);
   $_SESSION['team2_player5'] = implode($p[4]);
   $stmt = $pdo->query("SELECT id FROM `users`.`players` WHERE team='$team2'");
   $p = $stmt->fetchAll();
   $_SESSION['team2_player1_id'] = implode($p[0]);
   $_SESSION['team2_player2_id'] = implode($p[1]);
   $_SESSION['team2_player3_id'] = implode($p[2]);
   $_SESSION['team2_player4_id'] = implode($p[3]);
   $_SESSION['team2_player5_id'] = implode($p[4]);
   $team2_player1 = $_SESSION['team2_player1'];
   $team2_player2 = $_SESSION['team2_player2'];
   $team2_player3 = $_SESSION['team2_player3'];
   $team2_player4 = $_SESSION['team2_player4'];
   $team2_player5 = $_SESSION['team2_player5'];
}

function displayAlert($text, $type)
{
   echo "<div class=\"alert alert-" . $type . "\" role=\"alert\">
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

    <body class="bg">
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
                        <img src="img/logo.svg">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav navbar-right">

                        <li id="usernameInsertGame" class="font">
                            <a href="userSettings.php">
                                <?= $username ?>
                            </a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img class="menu-icon" src="img/menu.svg">
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="myteam.php">My Team</a>
                                </li>
                                <li>
                                    <a href="market.php">Market</a>
                                </li>
                                <li>
                                    <a href="leaderboard.php">Leaderboard</a>
                                </li>
                                <li>
                                    <a href="#">Next Games</a>
                                </li>
                                <li>
                                    <a href="#">Last Games</a>
                                </li>
                                <li>
                                    <a href="userSettings.php">Settings</a>
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
        <div id="team1_score" class="form-group">
            <input type="text" id="team1_score_input" class="form-control" placeholder="Enter final score">
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Team 1
                <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-center">
                <li><a href="#" id="NiP" onClick="return team(this.id)">NiP</a></li>
                <li><a href="#" id="SK" onClick="team(this.id)">SK</a></li>
                <li><a href="#" id="Liquid" onClick="team(this.id)">Liquid</a></li>
                <li><a href="#" id="Navi" onClick="team(this.id)">Navi</a></li>
                <li><a href="#" id="Mousesports" onClick="team(this.id)">Mousesports</a></li>
                <li><a href="#" id="Cloud9" onClick="team(this.id)">Cloud9</a></li>
                <li><a href="#" id="SK" onClick="team(this.id)">SK</a></li>
                <li><a href="#" id="Astralis" onClick="team(this.id)">Astralis</a></li>
                <li><a href="#" id="Fnatic" onClick="team(this.id)">Fnatic</a></li>
                <li><a href="#" id="G2" onClick="team(this.id)">G2</a></li>
                <li><a href="#" id="Faze" onClick="team(this.id)">Faze</a></li>
            </ul>
        </div>
        <div class="team1Insert">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Kills</th>
                        <th>Deaths</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="t1_player1">
                        <td>
                            <?=$team1_player1?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t1_player2">
                        <td>
                            <?=$team1_player2?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t1_player3">
                        <td>
                            <?= $team1_player3?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t1_player4">
                        <td>
                            <?= $team1_player4?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t1_player5">
                        <td>
                            <?= $team1_player5?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="team1_score" class="form-group">
            <input type="text" id="team2_score_input" class="form-control" placeholder="Enter final score">
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Team 2
            <span class="caret"></span></button>
            <ul class="dropdown-menu ">
                <li><a href="#" id="NiP" onClick="team2(this.id)">NiP</a></li>
                <li><a href="#" id="SK" onClick="team2(this.id)">SK</a></li>
                <li><a href="#" id="Liquid" onClick="team2(this.id)">Liquid</a></li>
                <li><a href="#" id="Navi" onClick="team2(this.id)">Navi</a></li>
                <li><a href="#" id="Mousesports" onClick="team2(this.id)">Mousesports</a></li>
                <li><a href="#" id="Cloud9" onClick="team2(this.id)">Cloud9</a></li>
                <li><a href="#" id="SK" onClick="team2(this.id)">SK</a></li>
                <li><a href="#" id="Astralis" onClick="team2(this.id)">Astralis</a></li>
                <li><a href="#" id="Fnatic" onClick="team2(this.id)">Fnatic</a></li>
                <li><a href="#" id="G2" onClick="team2(this.id)">G2</a></li>
                <li><a href="#" id="Faze" onClick="team2(this.id)">Faze</a></li>
            </ul>
        </div>
        <div class="team2Insert">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Kills</th>
                        <th>Deaths</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="t2_player1">
                        <td>
                            <?=$team2_player1?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t2_player2">
                        <td>
                            <?=$team2_player2?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t2_player3">
                        <td>
                            <?=$team2_player3?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t2_player4">
                        <td>
                            <?=$team2_player4?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                    <tr id="t2_player5">
                        <td>
                            <?=$team2_player5?>
                        </td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>15</td>
                        <td contenteditable='true'>1.20</td>
                    </tr>
                </tbody>
            </table>
        </div>
</div>
<input type="submit" id="insertTeamsSucess" class="btn btn-info" onClick="ratings()" value="Submit">
<script>
    function team(team1){
                window.location.href="insertGame.php?t1=" + team1;
            }
            function team2(team2){
                window.location.href="insertGame.php?t2=" + team2;
            }
            function ratings(){
                var team1_score = document.getElementById('team1_score_input').value;
                var team2_score = document.getElementById('team2_score_input').value;
                var Row = document.getElementById("t1_player1");
                var Cells = Row.getElementsByTagName("td");
                var t1_p1 = Cells[3].innerText;
                var Row = document.getElementById("t1_player2");
                var Cells = Row.getElementsByTagName("td");
                var t1_p2 = Cells[3].innerText;
                var Row = document.getElementById("t1_player3");
                var Cells = Row.getElementsByTagName("td");
                var t1_p3 = Cells[3].innerText;
                var Row = document.getElementById("t1_player4");
                var Cells = Row.getElementsByTagName("td");
                var t1_p4 = Cells[3].innerText;
                var Row = document.getElementById("t1_player5");
                var Cells = Row.getElementsByTagName("td");
                var t1_p5 = Cells[3].innerText;
                var Row = document.getElementById("t2_player1");
                var Cells = Row.getElementsByTagName("td");
                var t2_p1 = Cells[3].innerText;
                var Row = document.getElementById("t2_player2");
                var Cells = Row.getElementsByTagName("td");
                var t2_p2 = Cells[3].innerText;
                var Row = document.getElementById("t2_player3");
                var Cells = Row.getElementsByTagName("td");
                var t2_p3 = Cells[3].innerText;
                var Row = document.getElementById("t2_player4");
                var Cells = Row.getElementsByTagName("td");
                var t2_p4 = Cells[3].innerText;
                var Row = document.getElementById("t2_player5");
                var Cells = Row.getElementsByTagName("td");
                var t2_p5 = Cells[3].innerText;
                window.location.href="insertGame.php?r1=" + t1_p1 + "&r2=" + t1_p2 + "&r3=" + t1_p3 + "&r4=" + t1_p4 + "&r5=" + t1_p5 + "&r6=" + t2_p1 + "&r7=" + t2_p2 + "&r8=" + t2_p3 + "&r9=" + t2_p4 + "&r10=" + t2_p5 + "&t1_score=" + team1_score + "&t2_score=" + team2_score;
            }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>