<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<?php
session_start();
require 'connect.php';

unset($_SESSION['1sttime']);
$username = $_SESSION["username"];
$stmt = $pdo->query("SELECT `balance` FROM `users`.`users` WHERE username='$username'");
$p = $stmt->fetch();
$balance = $p['balance'];


if (!empty($_GET)) {
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
          <p class="usernameMobile"><?=$username?></p>
            <img class="menuLogoMobile" onclick="myFunction()" src="img/menu.svg">
            <div id="myDropdown" class="dropdownMobile-content">
              <a href="#">My Team</a>
              <a href="#">Market</a>
              <a href="#">Leaderboard</a>
              <a href="#">Next Games</a>
              <a href="#">Last Games</a>
              <a href="#">Settings</a>
              <a href="#">Logout</a>
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
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse1">NiP</a>
            </h4>
            </div>
            <div id="collapse1" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">F0rest</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="F0rest" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/F0rest.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Get_Right</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Get_Right" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Get_Right.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Dennis</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Dennis" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Dennis.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Draken</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Draken" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Draken.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Rez</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Rez" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/NiP/Rez.png">
                </li>
            </ul>
            </div>
        </div>
        </div>
        


        
        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse2">Team Liquid</a>
            </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Steel</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Steel" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Steel.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Twistzz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Twistzz" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 170.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Twistzz.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Elige</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Elige" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 190.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Elige.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Nafly</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Nafly" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Nafly.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Nitro</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Nitro" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Liquid/Nitro.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse3">Na'vi</a>
            </h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Zeus</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Zeus" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 50.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Zeus.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">S1mple</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="S1mple" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/S1mple.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Flamie</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Flamie" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Flamie.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Edward</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Edward" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 90.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Edward.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Electronic</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Electronic" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Navi/Electronic.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse4">Mousesports</a>
            </h4>
            </div>
            <div id="collapse4" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Styko</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Styko" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 90.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Styko.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Chris J</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="ChrisJ" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 110.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/ChrisJ.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Sunny</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Sunny" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 140.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Sunny.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Oskar</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Oskar" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 170.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Oskar.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Ropz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Ropz" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 140.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Mousesports/Ropz.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse5">Cloud 9</a>
            </h4>
            </div>
            <div id="collapse5" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Tarik</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Tarik" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Tarik.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Stewie2K</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Stewie2K" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Stewie2K.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Autimatic</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Autimatic" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 170.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Autimatic.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Rush</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Rush" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Rush.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Skadoodle</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Skadoodle" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Cloud9/Skadoodle.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse6">SK</a>
            </h4>
            </div>
            <div id="collapse6" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Coldzera</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Coldzera" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 250.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Coldzera.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Fallen</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Fallen" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Fallen.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Taco</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Taco" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 90.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Taco.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Fer</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Fer" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 220.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Fer.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Boltz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Boltz" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 160.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/SK/Boltz.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse7">Astralis</a>
            </h4>
            </div>
            <div id="collapse7" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Dupreeh</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Dupreeh" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Dupreeh.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Device</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Device" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 230.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Device.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Glaive</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Glaive" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 80.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Glaive.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Magisk</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Magisk" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Magisk.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Xyp9x</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Xyp9x"id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Astralis/Xyp9x.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse8">Fnatic</a>
            </h4>
            </div>
            <div id="collapse8" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Lekro</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Lekro" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Lekro.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Flusha</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Flusha" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Flusha.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Krimz</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Krimz" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 130.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Krimz.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Golden</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Golden" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 80.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/Golden.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">JW</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="JW" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 100.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Fnatic/JW.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse9">G2</a>
            </h4>
            </div>
            <div id="collapse9" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Shox</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Shox" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 180.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/Shox.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">KennyS</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="KennyS" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/KennyS.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Apex</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Apex" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/Apex.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Bodyy</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Bodyy" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 120.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/Bodyy.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">NBK</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="NBK" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 150.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/G2/NBK.png">
                </li>
            </ul>
            </div>
        </div>
        </div>

        <div class="panel-group col-xs-10 col-xs-offset-1 col-xs-offset-right-1">
        <div class="panel panel-default">
            <div id="panel-heading-market" class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse10">Faze</a>
            </h4>
            </div>
            <div id="collapse10" class="panel-collapse collapse">
            <ul class="list-group">
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Olofmeister</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Olofmeister" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 160.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Olofmeister.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Guardian</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Guardian" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 200.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Guardian.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Niko</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Niko" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 230.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Niko.png">
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Rain</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Rain" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 210.000$?</button>
                    <img id="marketImgMobileCollapse" src="img/Faze/Rain.png">
                
                </li>
                <li class="list-group-item">
                <p class="playerNameCollapse" href="#">Karrigan</p>
                    <ul class="lastGamesMarketMobileCollapse">
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                        <li>NiP vs Sk <span>(+160)</span></li>
                    </ul>
                    <button value="Karrigan" id="btnMarketMobileCollapse" class="btn btn-success" type="button" onClick="buyButton(this)">Buy for 80.000$?</button>
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
