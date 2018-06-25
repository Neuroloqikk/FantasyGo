<script type="text/javascript">
  if (screen.width <= 800) {
  document.location = "signinMobile.php";
  }
</script>
<?php
session_start();
require 'connect.php';

$username = $_SESSION["username"];

$stmt = $pdo->query("SELECT `isAdmin` FROM `users`.`users` WHERE username='$username'");
$p = $stmt->fetch();
$Admin = $p['isAdmin'];


$stmt = $pdo->query("SELECT `score`,`balance`,timestamp FROM `users`.`users` WHERE username='$username'");
$p = $stmt->fetch();
$balance = $p['balance'];
$score = $p['score'];
$timestamp = $p['timestamp'];
$stmt = $pdo->query("SELECT player1_id, player2_id, player3_id, player4_id, player5_id FROM `users`.`users_players` WHERE username = '$username'");
$stmt->execute([20]);
$arr = $stmt->fetch(PDO::FETCH_NUM);

if (!$arr) {
  echo '<script>location="signin.php"</script>';
}

list($player1_Id, $player2_Id, $player3_Id, $player4_Id, $player5_Id) = $arr;

//

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player1_Id . "'");
$t = $q->fetch();
$player1Name = $t['name'];
$player1Photo = $t['photo'];

if ($player1Name == NULL) {
  $player1Name = "Buy another player.";
  $player1Photo = "/BlackPlayer.png";
}

//

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player2_Id . "'");
$t = $q->fetch();
$player2Name = $t['name'];
$player2Photo = $t['photo'];

if ($player2Name == "") {
  $player2Name = "Buy another player.";
  $player2Photo = "/BlackPlayer.png";
}

//

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player3_Id . "'");
$t = $q->fetch();
$player3Name = $t['name'];
$player3Photo = $t['photo'];

if ($player3Name == "") {
  $player3Name = "Buy another player.";
  $player3Photo = "/BlackPlayer.png";
}

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player4_Id . "'");
$t = $q->fetch();
$player4Name = $t['name'];
$player4Photo = $t['photo'];

if ($player4Name == "") {
  $player4Name = "Buy another player.";
  $player4Photo = "/BlackPlayer.png";
}

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player5_Id . "'");
$t = $q->fetch();
$player5Name = $t['name'];
$player5Photo = $t['photo'];

if ($player5Name == "") {
  $player5Name = "Buy another player.";
  $player5Photo = "/BlackPlayer.png";
}

$sql = "SELECT player_score,timestamp FROM `users`.`results_player` WHERE player_name='" . $player1Name . "'";
$player1 = $pdo->query($sql);

foreach($player1 as $row) {
  if ($timestamp < $row['timestamp']) {
    $player1_score+= $row['player_score'];
  }
}

if ($player1_score == 0) {
  $player1_score = 0;
}

$sql = "SELECT player_score,timestamp FROM `users`.`results_player` WHERE player_name='" . $player2Name . "'";
$player2 = $pdo->query($sql);

foreach($player2 as $row) {
  if ($timestamp < $row['timestamp']) {
    $player2_score+= $row['player_score'];
  }
}

if ($player2_score == 0) {
  $player2_score = 0;
}

$sql = "SELECT player_score,timestamp FROM `users`.`results_player` WHERE player_name='" . $player3Name . "'";
$player3 = $pdo->query($sql);

foreach($player3 as $row) {
  if ($timestamp < $row['timestamp']) {
    $player3_score+= $row['player_score'];
  }
}

if ($player3_score == 0) {
  $player3_score = 0;
}

$sql = "SELECT player_score,timestamp FROM `users`.`results_player` WHERE player_name='" . $player4Name . "'";
$player4 = $pdo->query($sql);

foreach($player4 as $row) {
  if ($timestamp < $row['timestamp']) {
    $player4_score+= $row['player_score'];
  }
}

if ($player4_score == NULL) {
  $player4_score = 0;
}

$sql = "SELECT player_score,timestamp FROM `users`.`results_player` WHERE player_name='" . $player5Name . "'";
$player5 = $pdo->query($sql);

foreach($player5 as $row) {
  if ($timestamp < $row['timestamp']) {
    $player5_score+= $row['player_score'];
  }
}

if ($player5_score == 0) {
  $player5_score = 0;
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fantasy GO</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/app.css" rel="stylesheet">
</head>

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
        <a href="#" class="navbar-brand" id="sidebarShow" onclick="showGames()">
          <img src="img/eye.svg">
        </a>
        <a class="navbar-brand" id="balance">
          <h4>Balance</h4>
          <h2>
            <?= $balance ?>$</h2>
          </div>
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
              <li id="usernameIndex" class="font">
                <a href="userSettings.php">
                  <?= $username ?>
                </a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  <img class="menu-icon" src="img/menu.svg">
                </a>
                <ul class="dropdown-menu">
                <?php if ($Admin == 0):?>
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
                  <a href="NextGames.php">Next Games</a>
                </li>
                <li>
                  <a href="LastGames.php">Last Games</a>
                </li>
                <li>
                  <a href="graphinfo.php">Informational graphs</a>
                </li>
                <li>
                  <a href="userSettings.php">Settings</a>
                </li>
                <li>
                  <a href="logout.php">Logout</a>
                </li>
                <?php elseif ($Admin == 1):?>
                <li>
                  <a href="myteam.php">My Team</a>
                </li>
                <li>
                  <a href="market.php">Market</a>
                </li>
                  <li>
                  <a href="insertNextGame.php">Insert Next Game</a>
                </li>
                <li>
                  <a href="insertGame.php">Insert Last Game</a>
                </li>
                <li>
                  <a href="leaderboard.php">LeaderBoard</a>
                </li>
                <li>
                  <a href="NextGames.php">Next Games</a>
                </li>
                <li>
                  <a href="LastGames.php">Last Games</a>
                </li>
                <li>
                  <a href="adminPanel.php">Roles/Tournaments</a>
                </li>
                <li>
                  <a href="graphinfo.php">Informational graphs</a>
                </li>
                <li>
                  <a href="userSettings.php">Settings</a>
                </li>
                <li>
                  <a href="logout.php">Logout</a>
                </li>
              <?php endif;?>
              </ul>
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
      <div class="info">
        <div id="score">
          
          <p id="demo"></p>
          <p>
            Score
          </p>
          <h1>
            <?= $score ?>
          </h1>
        </div>
      </div>
      <div class="container-table">
        <table class="playerTable" style="width:100%">
          <tr>
            <td>
              <img src="img<?= $player1Photo ?>"></img>
              <p>
                <?= $player1Name ?>
              </p>
              <p>Score:
                <?= $player1_score ?>
              </p>
            </td>
            <td>
              <img src="img<?= $player2Photo ?>"></img>
              <p>
                <?= $player2Name ?>
              </p>
              <p>Score:
                <?= $player2_score ?>
              </p>
            </td>
            <td>
              <img src="img<?= $player3Photo ?>"></img>
              <p>
                <?= $player3Name ?>
              </p>
              <p>Score:
                <?= $player3_score ?>
              </p>
            </td>
            <td>
              <img src="img<?= $player4Photo ?>"></img>
              <p>
                <?= $player4Name ?>
              </p>
              <p>Score:
                <?= $player4_score ?>
              </p>
            </td>
            <td>
              <img src="img<?= $player5Photo ?>"></img>
              <p>
                <?= $player5Name ?>
              </p>
              <p>Score:
                <?= $player5_score ?>
              </p>
            </td>
          </tr>
        </table>
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
    </body>

    </html>
