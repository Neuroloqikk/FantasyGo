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
if (isset($_POST['update'])) {
  $team1 = !empty($_POST['team1']) ? trim($_POST['team1']) : null;
  $team2 = !empty($_POST['team2']) ? trim($_POST['team2']) : null;

  $sql = "UPDATE `users`.`teams` SET active=? WHERE team=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(["1", $team2]);

  $sql = "UPDATE `users`.`teams` SET active=? WHERE team=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(["0", $team1]);

  displayAlert("Teams Updated", "success");

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
  <script src="http://yui.yahooapis.com/3.18.1/build/yui/yui-min.js"></script>
</head>
<div class="container-example">

  <body class="yui3-skin-sam" style="background-image: url('img/nuke.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
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
                  <a href="LastGames.php">Change Player Prices</a>
                </li>
                <li>
                  <a href="LastGames.php">Change Available Teams</a>
                </li>
                <li>
                  <a href="LastGames.php">Insert New Team</a>
                </li>
                <li>
                  <a href="LastGames.php">Update Tournament</a>
                </li>
                <li>
                  <a href="adminPanel.php">Change Roles/Tournaments</a>
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
    <form class="SignUp" method="POST" style="border:1px solid black;width: 70%;margin-left: 16%;">
    <div class="txtcolor">
      <div class="container" style="width: 83%;">
        <h1>Updating Market,
          <h2>pick a team to become inactive and another team to replace!</h2>
        </h1>
        <hr>
        <div class="pickPlayerTeam" style="margin-left: 27%;">
        <label for="teamname"><b>Team active</b></label><br>
        
        <div name="teamname" class="col-lg-2" class="selectpicker control-label" >
          <select id="team1" class="form-control" name="team1" style="width: 152px;height: 39px;margin-left:-15%;">
            <option value="#" selected="">Choose One:</option>
            
            <?php 
              $stmt = $pdo->query("SELECT team from teams where active = '1'");
              $p = $stmt->fetchAll();
              foreach($p as $row){
            ?>
              <option value=<?=$row['team']?>><?=$row['team']?></option>
              <?php }?>
          </select>
        </div>
        
        <label for="playername" style="margin-top: -5%;margin-left: 16%;"><b>Team inactive</b></label><br>
        <div name="playername" class="col-lg-2" class="selectpicker control-label" style="margin-left: 15%;margin-top: 0%;">
          <select class="form-control" id="team2" name="team2" style="width: 152px;height: 39px;margin-left:-15%;">
          <option value="#" selected="">Choose One:</option>
            
            <?php 
              $stmt = $pdo->query("SELECT team from teams where active = '0'");
              $p = $stmt->fetchAll();
              foreach($p as $row){
            ?>
              <option value=<?=$row['team']?>><?=$row['team']?></option>
              <?php }?>
          </select>
        </div>
        </div>
        <br>
        <br>
          <br>
          <div id="playerInfo">
          </div>
        
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" value="update" class="signupbtn" name="update">Update</button>
          </div>
        </div>
      </div>
    </form>
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
