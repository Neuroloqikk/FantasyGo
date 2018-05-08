<?php
session_start();
require 'connect.php';

$username = $_SESSION["username"];


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
  <link rel="icon" type="image/png" href="/img/icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
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
          <a href="#" class="navbar-brand" id="sidebarShow" onclick="showGames()">
            <img src="img/eye.svg">
          </a>
          <a class="navbar-brand" id="balance">
            <h4>Balance</h4>
            <h2>300000$</h2>
          </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

          <ul class="nav navbar-nav navbar-right">

            <li class="usernameIndex">
              <a>
                <?= $username ?>
              </a>
            </li>

            <li class="dropdown">
              <a href="#" id="menuDropdown" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
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
                  <a href="signin.php">Logout</a>
                </li>
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
    <script>
    function showGames() {
      var x = document.getElementById("sidebar123");
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }
    </script>
  </body>
  </html>
