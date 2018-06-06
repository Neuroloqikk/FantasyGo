<?php
session_start();
require 'connect.php';

$username = $_SESSION["username"];

if (isset($_GET["id"])) {
  $gameId=$_GET["id"];
  $stmt = $pdo->query("SELECT team1,team2,score_team1,score_team2 FROM results WHERE next_game_id='$gameId'");
  $p = $stmt->fetch();
  $team1 = $p['team1'];
  $team2 = $p['team2'];
  $score1 = $p['score_team1'];
  $score2 = $p['score_team2'];
  $t1 = "winLastGame";
  $t2 = "loose";
  if ($score1 > $score2){
    $t1 = "winLastGame";
    $t2 = "loose";
  }
  else{
    $t1 = "loose";
    $t2 = "winLastGame";
  }

  $stmt = $pdo->query("SELECT year(Date),month(Date),day(Date),Hour FROM next_games WHERE id='$gameId'");
  $p = $stmt->fetch();
  $year = $p['year(Date)'];
  $month = $p['month(Date)'];
  $day = $p['day(Date)'];
  $hour = $p['Hour'];
  $stmt = $pdo->query('SELECT player_name from (users.results_player) inner join players on (players.name) = (results_player.player_name) where results_id = "'.$gameId.'" and (players.team) = "'.$team1.'"');
  $p = $stmt->fetchAll();
    $player1_name_t1 = implode($p[0]);
    $player2_name_t1 = implode($p[1]);
    $player3_name_t1 = implode($p[2]);
    $player4_name_t1 = implode($p[3]);
    $player5_name_t1 = implode($p[4]);
  $stmt = $pdo->query('SELECT player_name from (users.results_player) inner join players on (players.name) = (results_player.player_name) where results_id="'.$gameId.'" and (players.team) = "'.$team2.'"');
  $p = $stmt->fetchAll();
    $player1_name_t2 = implode($p[0]);
    $player2_name_t2 = implode($p[1]);
    $player3_name_t2 = implode($p[2]);
    $player4_name_t2 = implode($p[3]);
    $player5_name_t2 = implode($p[4]);
    $stmt = $pdo->query('SELECT player_score from (users.results_player) inner join players on (players.name) = (results_player.player_name) where results_id="'.$gameId.'" and (players.team) = "'.$team1.'"');
  $p = $stmt->fetchAll();
    $player1_score_t1 = implode($p[0]);
    $player2_score_t1 = implode($p[1]);
    $player3_score_t1 = implode($p[2]);
    $player4_score_t1 = implode($p[3]);
    $player5_score_t1 = implode($p[4]);
  $stmt = $pdo->query('SELECT player_score from (users.results_player) inner join players on (players.name) = (results_player.player_name) where results_id="'.$gameId.'" and (players.team) = "'.$team2.'"');
  $p = $stmt->fetchAll();
    $player1_score_t2 = implode($p[0]);
    $player2_score_t2 = implode($p[1]);
    $player3_score_t2 = implode($p[2]);
    $player4_score_t2 = implode($p[3]);
    $player5_score_t2 = implode($p[4]);

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
<!--<div class="container-example">-->

    <body id="landingBG" style="overflow: scroll;height: 115%;">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <p class="balanceMobile col-xs-4 col-xs-offset-4 col-xs-offset-right-4"><?=$balance?></p>
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
              <a href="LastGamesMobile.php">Last Games</a>
              <a href="NextGamesMobile.php">Next Games</a>
              <a href="userSettingsMobile.php">Settings</a>
              <a href="logoutMobile.php">Logout</a>
            </div>
          <a id="logoMobile" class="navbar-brand">
            <img src="img/logo.png">
          </a>
        </div>
    </nav>
    <div id ="img1LastGameMobile" class="imgLastGame">
          <img src="img/<?=$team1?>/<?=$team1?>.svg">
          
    </div>
    
    <div id ="img2LastGameMobile" class="imgLastGame">
          <img src="img/<?=$team2?>/<?=$team2?>.svg">
          
    </div>
    <div id="team1LastGameNameMobile" class="team1LastGameNameMobile" style="width: 206px;text-align: center;color: white;font-size: 22px;"><?=$team1?></div>
    <div class="vsLastGameMobile" style="text-align: center;margin-top: -27px;font-size: 18px;color: white;">vs</div>
    <div class="team2LastGameNameMobile" style="float: right;margin-right: 76px;color: white;font-size: 22px;margin-top: -28px;"><?=$team2?></div>
    <div id="infoLastGame">
      <h2 style="text-align: center;"> <span id="<?=$t1?>"><?=$score1?></span> - <span id="<?=$t2?>"><?=$score2?></span> </h2>
      <h3 style="text-align: center;color:white;"> <?=$day?>/<?=$month?>/<?=$year?> <?=$hour?> </h3>
    </div>
    
      <div id="team1InsertLastGame"class="team1InsertMobile">
        <table id="tableShadow" class="table table-hover">
          <thead>
            <tr>
              <th><?=$team1?></th>
              <th>Score</th>
            </tr>
          </thead>
          <tbody>
            <tr id="t1_player1">
              <td>
                <?=$player1_name_t1?>
              </td>
              <td>
                <?=$player1_score_t1?>
              </td>
            </tr>
            <tr id="t1_player2">
              <td>
                <?=$player2_name_t1?>
              </td>
              <td>
                <?=$player2_score_t1?>
              </td>
            </tr>
            <tr id="t1_player3">
              <td>
                <?=$player3_name_t1?>
              </td>
              <td>
                <?=$player3_score_t1?>  
              </td>
            </tr>
            <tr id="t1_player4">
              <td>
                <?=$player4_name_t1?>
              </td>
              <td>
                <?=$player4_score_t1?>  
              </td>
            </tr>
            <tr id="t1_player5">
              <td>
                <?=$player5_name_t1 ?>
              </td>
              <td>
                <?=$player5_score_t1?> 
              </td>
            </tr>
          </tbody>
        </table>
      </div>
        <div class="team2Insert">
          <table id="tableShadow" class="table table-hover">
            <thead>
              <tr>
                <th><?=$team2?></th>
                <th>Score</th>
              </tr>
            </thead>
            <tbody>
              <tr id="t2_player1">
                <td>
                  <?=$player1_name_t2 ?>
                </td>
                <td>
                  <?=$player1_score_t2?> 
                </td>
              </tr>
              <tr id="t2_player2">
                <td>
                  <?=$player2_name_t2?>
                </td>
                <td>
                  <?=$player2_score_t2?> 
                </td>
              </tr>
              <tr id="t2_player3">
                <td>
                  <?=$player3_name_t2?>
                </td>
                <td>
                  <?=$player3_score_t2?> 
                </td>
              </tr>
              <tr id="t2_player4">
                <td>
                  <?=$player4_name_t2?>
                </td>
                <td>
                  <?=$player4_score_t2?> 
                </td>
              </tr>
              <tr id="t2_player5">
                <td>
                  <?=$player5_name_t2?>
                </td>
                <td>
                  <?=$player5_score_t2?> 
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      <!--</div>-->
      <script>
      function team(teams){
        var input = teams;
        var fields = input.split('-');
        var team1 = fields[0];
        var team2 = fields[1];
        window.location.href="insertGame.php?t1=" + team1+"&t2="+team2;
      }
      </script>
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
  </body>
  </html>
