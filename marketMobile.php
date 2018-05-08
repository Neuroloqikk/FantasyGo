<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<?php
session_start();
require 'connect.php';

unset($_SESSION['1sttime']);
$username = $_SESSION["username"];
$stmt = $pdo->query("SELECT `balance` FROM `users`.`users` WHERE username='$username'");
$p = $stmt->fetch();
$balance = $p['balance'];

if($username == null){
    $balance="";
    $username="Login";
    $loginLink="signinMobile.php";
}
if (!empty($_GET) AND $username != null) {
   $playerName = $_GET["name"];
   $stmt = $pdo->query("SELECT `id`,`price`,`photo`,`team`,`team_photo`,`first_name`,`last_name` FROM `users`.`players` WHERE name='$playerName'");
   $t = $stmt->fetch();
   $id = $t['id'];
   $first_name = $t['first_name'];
   $last_name = $t['last_name'];
   $player_photo = $t['photo'];
   $team_photo = $t['team_photo'];
   $price = $t['price'];
   if ($_GET['buy'] != NULL) {
      $id = $_GET['buy'];
      $stmt = $pdo->query("SELECT `balance` FROM `users`.`users` WHERE username='$username'");
      $p = $stmt->fetch();
      $balance = $p['balance'];
      $stmt = $pdo->query("SELECT `price` FROM `users`.`players`WHERE id='$id'");
      $p = $stmt->fetch();
      $price = $p['price'];
      $stmt = $pdo->query("SELECT `player1_id`,`player2_id`,`player3_id`,`player4_id`,`player5_id` FROM `users`.`users_players` WHERE username='$username'");
      $p = $stmt->fetch();
      $player1 = $p['player1_id'];
      $player2 = $p['player2_id'];
      $player3 = $p['player3_id'];
      $player4 = $p['player4_id'];
      $player5 = $p['player5_id'];
      if ($player1 == NULL or $player2 == NULL or $player3 == NULL or $player4 == NULL or $player5 == NULL) {
         if ($player1 == $id or $player2 == $id or $player3 == $id or $player4 == $id or $player5 == $id) {
            displayAlert("You already have this player! Please select another one!", "danger");
         }
         else {
            if ($player1 == NULL) {
               $freeSlot = "player1_id";
            }
            else
            if ($player2 == NULL) {
               $freeSlot = "player2_id";
            }
            else
            if ($player3 == NULL) {
               $freeSlot = "player3_id";
            }
            else
            if ($player4 == NULL) {
               $freeSlot = "player4_id";
            }
            else
            if ($player5 == NULL) {
               $freeSlot = "player5_id";
            }

            $newBalance = $balance - $price;
            if ($newBalance < 0) {
               displayAlert("You do not have enough money!", "danger");
            }
            else {
               $sql = "UPDATE `users`.`users_players` SET $freeSlot=? WHERE username=?";
               $stmt = $pdo->prepare($sql);
               $stmt->execute([$id, $username]);
               $sql = "UPDATE `users`.`users` SET balance=? WHERE username=?";
               $stmt = $pdo->prepare($sql);
               $stmt->execute([$newBalance, $username]);
               displayAlert("The player was added to your team!", "success");
            }
         }
      }
      else {
         displayAlert("You already have five players!", "danger");
      }
   }
   else {
      echo '<script>$(document).ready(function(){ $("#myModal").modal("show"); });</script>';
   }
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
          <p class="balanceMobile"><?=$balance?></p>
          <p class="usernameMobile"><a href="<?=$loginLink?>"><?=$username?></a></p>
            <img class="menuLogoMobile" onclick="myFunction()" src="img/menu.svg">
            <div id="myDropdown" class="dropdownMobile-content">
              <a href="#">My Team</a>
              <a href="#">Market</a>
              <a href="#">Leaderboard</a>
              <a href="#">Next Games</a>
              <a href="#">Last Games</a>
              <a href="#">Settings</a>
              <a href="logoutMobile.php">Logout</a>
            </div>
          <a id="logoMobile" class="navbar-brand">
            <img src="img/logo.png">
          </a>
        </div>
    </nav>
    
        </nav>
        <div class="marketInfoMobile">
            <p> Welcome to the market!</p>
            <p> Here you can buy players to join your team.</p>
        </div>
        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse1">NiP</a>
            </h4>
            </div>
            <div id="collapse1" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">F0rest</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="F0rest"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='F0rest' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="F0rest" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/F0rest.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Get_Right</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Get_Right"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Get_Right' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Get_Right" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Get_Right.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Dennis</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Dennis"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Dennis' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Dennis" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Dennis.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Draken</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Draken"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Draken' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Draken" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Draken.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Rez</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Rez"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Rez' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Rez" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Rez.png">
                </li>
            </ul>
            </div>
        </div>
        </div>
        


        
        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse2">Team Liquid</a>
            </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Steel</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Steel"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Steel' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Steel" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Steel.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Twistzz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Twistzz"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Twistzz' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Twistzz" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 170.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Twistzz.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Elige</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Elige"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Elige' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Elige" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 190.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Elige.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Nafly</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Nafly"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Nafly' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Nafly" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Nafly.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Nitro</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Nitro"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Nitro' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Nitro" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Nitro.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse3">Na'vi</a>
            </h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Zeus</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Zeus"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Zeus' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Zeus" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 50.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Zeus.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">S1mple</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="S1mple"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='S1mple' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="S1mple" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/S1mple.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Flamie</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Flamie"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Flamie' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Flamie" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Flamie.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Edward</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Edward"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Edward' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Edward" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 90.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Edward.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Electronic</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Electronic"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Electronic' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Electronic" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Electronic.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse4">Mousesports</a>
            </h4>
            </div>
            <div id="collapse4" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Styko</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Styko"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Styko' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Styko" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 90.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Styko.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Chris J</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="ChrisJ"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='ChrisJ' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="ChrisJ" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 110.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/ChrisJ.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Sunny</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Sunny"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Sunny' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Sunny" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 140.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Sunny.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Oskar</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Oskar"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Oskar' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Oskar" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 170.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Oskar.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Ropz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Ropz"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Ropz' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Ropz" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 140.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Ropz.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse5">Cloud 9</a>
            </h4>
            </div>
            <div id="collapse5" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Tarik</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Tarik"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Tarik' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Tarik" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Tarik.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Stewie2K</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Stewie2K"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Stewie2K' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Stewie2K" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Stewie2K.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Autimatic</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Autimatic"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Autimatic' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Autimatic" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 170.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Autimatic.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Rush</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Rush"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Rush' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Rush" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Rush.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Skadoodle</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Skadoodle"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Skadoodle' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Skadoodle" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Skadoodle.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse6">SK</a>
            </h4>
            </div>
            <div id="collapse6" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Coldzera</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Coldzera"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Coldzera' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Coldzera" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 250.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Coldzera.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Fallen</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Fallen"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Fallen' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Fallen" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Fallen.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Taco</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Taco"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Taco' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Taco" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 90.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Taco.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Fer</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Fer"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Fer' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Fer" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 220.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Fer.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Boltz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Boltz"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Boltz' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Boltz" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 160.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Boltz.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse7">Astralis</a>
            </h4>
            </div>
            <div id="collapse7" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Dupreeh</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Dupreeh"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Dupreeh' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Dupreeh" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Dupreeh.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Device</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Device"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Device' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Device" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 230.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Device.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Glaive</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Glaive"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Glaive' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Glaive" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 80.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Glaive.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Magisk</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Magisk"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Magisk' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Magisk" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Magisk.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Xyp9x</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Xyp9x"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Xyp9x' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Xyp9x" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Xyp9x.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse8">Fnatic</a>
            </h4>
            </div>
            <div id="collapse8" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Lekro</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Lekro"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Lekro' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Lekro" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Lekro.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Flusha</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Flusha"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Flusha' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Flusha" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Flusha.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Krimz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Krimz"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Krimz' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Krimz" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Krimz.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Golden</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Golden"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Golden' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Golden" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 80.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Golden.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">JW</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="JW"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='JW' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="JW" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 100.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/JW.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse9">G2</a>
            </h4>
            </div>
            <div id="collapse9" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Shox</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Shox"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Shox' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Shox" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/Shox.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">KennyS</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="KennyS"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='KennyS' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="KennyS" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/KennyS.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Apex</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Apex"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Apex' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Apex" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/Apex.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Bodyy</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Bodyy"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Bodyy' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Bodyy" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/Bodyy.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">NBK</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="NBK"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='NBK' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="NBK" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/NBK.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div data-toggle="collapse" href="#collapse1" id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse10">Faze</a>
            </h4>
            </div>
            <div id="collapse10" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Olofmeister</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Olofmeister"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Olofmeister' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Olofmeister" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 160.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Olofmeister.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Guardian</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Guardian"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Guardian' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Guardian" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Guardian.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Niko</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Niko"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Niko' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Niko" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 230.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Niko.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Rain</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Rain"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Rain' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Rain" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 210.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Rain.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Karrigan</p>
                    <ul class="lastGamesMarketMobileCollapse">
                    <?php
                        $stmt = $pdo->query('SELECT Team FROM players WHERE name="Karrigan"');
                        $p = $stmt->fetch();
                        $team = implode($p);

                        $stmt = $pdo->query("SELECT team1,team2,next_game_id FROM results WHERE team1='$team' OR team2='$team' ORDER BY timestamp DESC LIMIT 5");
                        $p = $stmt->fetchAll();


                        foreach($p as $row){
                            $id=$row['next_game_id'];
                            $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$id' AND player_name='Karrigan' ORDER BY timestamp DESC LIMIT 5");
                            $p = $stmt->fetch();
                            echo '<li>'.$row['team1'].' vs '.$row['team2'].' <span>(+'.implode($p).')</span></li>';
                        }

                    ?>
                    </ul>
                    <button value="Karrigan" class="btn btn-success text-center" type="button" onClick="buyButton(this)">Buy for 80.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Karrigan.png">
                </li>
            </ul>
            </div>
        </div>
        </div>
        <script>
            function idPlayer(elem) {
                var name = elem.id;
                window.location.href="market.php?name=" + name;

            }
            function buyButton(id) {
                var buy = id.id;
                if (confirm("Are you sure you wanna buy this player?")) {
                    window.location.href="market.php?buy=" + buy;
                } else {
                    window.location.href = "market.php";
                }
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
</div>
</body>

</html>
