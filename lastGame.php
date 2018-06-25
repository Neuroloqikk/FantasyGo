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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fantasy GO</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/app.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="/img/icon.png">

</head>
<div class="container-example">

  <body class="bg" id="landingBG" style="overflow:visible;overflow-x:hidden;">
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
    <div id ="img1LastGame" class="imgLastGame">
          <img src="img/<?=$team1?>/<?=$team1?>.svg">
    </div>
    <div id ="img2LastGame" class="imgLastGame">
          <img src="img/<?=$team2?>/<?=$team2?>.svg">
    </div>
    <div id="infoLastGame">
      <h1> <?=$team1?> vs <?=$team2?> </h1>
      <h2> <span id="<?=$t1?>"><?=$score1?></span> - <span id="<?=$t2?>"><?=$score2?></span> </h2>
      <h3> <?=$day?>/<?=$month?>/<?=$year?> <?=$hour?> </h3>
    </div>
    
      <div id="team1InsertLastGame"class="team1Insert">
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
      </div>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
  </html>
