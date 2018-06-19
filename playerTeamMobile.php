<?php
session_start();
require 'connect.php';

$user = $_SESSION["user"];
$username = $_SESSION["username"];
$stmt = $pdo->query("SELECT `score`,`balance`,timestamp FROM `users`.`users` WHERE username='$user'");
$p = $stmt->fetch();
$balance = $p['balance'];
$score = $p['score'];
$timestamp = $p['timestamp'];
$stmt = $pdo->query("SELECT player1_id, player2_id, player3_id, player4_id, player5_id FROM `users`.`users_players` WHERE username = '$user'");
$stmt->execute([20]);
$arr = $stmt->fetch(PDO::FETCH_NUM);
list($player1_Id, $player2_Id, $player3_Id, $player4_Id, $player5_Id) = $arr;

//

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player1_Id . "'");
$t = $q->fetch();
$player1Name = $t['name'];
$player1Photo = $t['photo'];

if ($player1Name == NULL) {
  $player1Name = "Player Missing";
  $player1Photo = "/BlackPlayer.png";
}

//

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player2_Id . "'");
$t = $q->fetch();
$player2Name = $t['name'];
$player2Photo = $t['photo'];

if ($player2Name == "") {
  $player2Name = "Player Missing.";
  $player2Photo = "/BlackPlayer.png";
}

//

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player3_Id . "'");
$t = $q->fetch();
$player3Name = $t['name'];
$player3Photo = $t['photo'];

if ($player3Name == "") {
  $player3Name = "Player Missing.";
  $player3Photo = "/BlackPlayer.png";
}

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player4_Id . "'");
$t = $q->fetch();
$player4Name = $t['name'];
$player4Photo = $t['photo'];

if ($player4Name == "") {
  $player4Name = "Player Missing.";
  $player4Photo = "/BlackPlayer.png";
}

$q = $pdo->query("SELECT name,photo FROM `users`.`players` WHERE id= '" . $player5_Id . "'");
$t = $q->fetch();
$player5Name = $t['name'];
$player5Photo = $t['photo'];

if ($player5Name == "") {
  $player5Name = "Player Missing.";
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
<div class="container-example">

    <body class="bg">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <p class="balanceMobile col-xs-4 col-xs-offset-4 col-xs-offset-right-4"><?=$user?></p>
          <div class="usernameMobile col-xs-4 col-xs-offset-4 col-xs-offset-right-4">
          <p>Player</p>
          </div>
          <div class="menuLogoMobile">
            <img onclick="myFunction()" src="img/menu.svg" style="width: inherit;">
          </div>  
            <div id="myDropdown" class="dropdownMobile-content">
              <a href="myTeamMobile.php">My Team</a>
              <a href="marketMobile.php">Market</a>
              <a href="leaderboardMobile.php">Leaderboard</a>
              <a href="NextGamesMobile.php">Next Games</a>
              <a href="TastGamesMobile.php">Last Games</a>
              <a href="GraphInfoMobile.php">Market Stats</a>
              <a href="userSettingsMobile.php">Settings</a>
              <a href="logoutMobile.php">Logout</a>
            </div>
          <a id="logoMobile" class="navbar-brand">
            <img src="img/logo.png">
          </a>
        </div>
        </nav>
        <div class="marketInfoMobile">
            <p> Welcome to <?=$user?>'s' team!<br>Here's his score from this season</p>
            <p> <?=$score?></p>
        </div>
        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a><div class="playerNameMobile"><?=$player1Name?></div><div class="playerScoreMobile"><span class="MobileTeamPoints">+<?=$player1_score?></span></div></a>
            </h4>
            </div>
            <div>
            <ul class="list-group">
                <li class="list-group-item">
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query("SELECT Team FROM players WHERE name='$player1Name'");
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='$player1Name' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <img id="myTeamImgMobileCollapse" src="img<?= $player1Photo ?>">
                
                </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse2" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
            <a><div class="playerNameMobile"><?=$player2Name?></div><div class="playerScoreMobile"><span class="MobileTeamPoints">+<?=$player2_score?></span></div></a>
            </h4>
            </div>
            <div>
            <ul class="list-group">
                <li class="list-group-item">
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query("SELECT Team FROM players WHERE name='$player2Name'");
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='$player2Name' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <img id="myTeamImgMobileCollapse" src="img<?= $player2Photo ?>">
                
                </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse3" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
            <a><div class="playerNameMobile"><?=$player3Name?></div><div class="playerScoreMobile"><span class="MobileTeamPoints">+<?=$player3_score?></span></div></a>
            </h4>
            </div>
            <div>
            <ul class="list-group">
                <li class="list-group-item">
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query("SELECT Team FROM players WHERE name='$player3Name'");
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='$player3Name' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <img id="myTeamImgMobileCollapse" src="img<?= $player3Photo ?>">
                
                </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse4" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
            <a><div class="playerNameMobile"><?=$player4Name?></div><div class="playerScoreMobile"><span class="MobileTeamPoints">+<?=$player4_score?></span></div></a>
            </h4>
            </div>
            <div>
            <ul class="list-group">
                <li class="list-group-item">
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query("SELECT Team FROM players WHERE name='$player4Name'");
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='$player4Name' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <img id="myTeamImgMobileCollapse" src="img<?= $player4Photo ?>">
                
                </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse5" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
            <a><div class="playerNameMobile"><?=$player5Name?></div><div class="playerScoreMobile"><span class="MobileTeamPoints">+<?=$player5_score?></span></div></a>
            </h4>
            </div>
            <div>
            <ul class="list-group">
                <li class="list-group-item">
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query("SELECT Team FROM players WHERE name='$player5Name'");
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='$player5Name' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <img id="myTeamImgMobileCollapse" src="img<?= $player5Photo ?>">
                
                </li>
                </ul>
            </div>
        </div>
        </div>
        <script>
            jQuery(window).bind('orientationchange', function(e) {
            switch ( window.orientation ) {
            case 0:
                $('.turnDeviceNotification').css('display', 'none');
                // The device is in portrait mode now
            break;

            case 180:
                $('.turnDeviceNotification').css('display', 'none');
                // The device is in portrait mode now
            break;

            case 90:
                // The device is in landscape now
                $('.turnDeviceNotification').css('display', 'block');
            break;

            case -90:
                // The device is in landscape now
                $('.turnDeviceNotification').css('display', 'block');
            break;
            }
            });
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
</div>
</body>

</html>
