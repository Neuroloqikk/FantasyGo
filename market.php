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
        </div>
        </nav>
        <div class="marketInfo">
            <h1> Welcome to the market!</h1>
            <h2> Here you can buy players to join your team.</br> Start by hovering a team icon and clicking the player you pretend to buy.</h2>
        </div>
        <!-- Modal -->

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Player Profile<br>
                            <?= $balance ?>$</h4>
                    </div>
                    <div class="modal-body">
                        <div class="marketModalPlayer">
                            <img id="playerPhoto" src="img<?= $player_photo ?>" style="background: url(img<?= $team_photo ?>);">
                            <div class="marketModalName">
                                <!--Nome-->
                                <h2>
                                    <?= $first_name ?> "
                                        <?= $playerName ?>"
                                            <?= $last_name ?>
                                </h2>
                            </div>
                            <div class="marketModalTableL">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Last Games</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>16-10</td>
                                            <td>+20</td>
                                        </tr>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>16-10</td>
                                            <td>+20</td>
                                        </tr>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>16-10</td>
                                            <td>+20</td>
                                        </tr>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>16-10</td>
                                            <td>+20</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="marketModalTableN">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Next Games</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>17/03/2018</td>
                                            <td>17:30</td>
                                        </tr>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>17/03/2018</td>
                                            <td>17:30</td>
                                        </tr>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>17/03/2018</td>
                                            <td>17:30</td>
                                        </tr>
                                        <tr>
                                            <td>NiP vs SK</td>
                                            <td>17/03/2018</td>
                                            <td>17:30</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="<?=$id?>" onClick="buyButton(this)" <?=$buy?>>Buy for <?= $price ?>$</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="marketGrid">
            <div class="nipContainer">
                <ul id="nipList">

                    <li><a href="#" id="F0rest" onClick="idPlayer(this)"><span>F0rest&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $180.000<span></a></li>
                    <li><a href="#" id="Get_Right" onClick="idPlayer(this)"><span>Get_Right&nbsp;| $180.000<span></a></li>
                    <li><a href="#" id="Dennis" onClick="idPlayer(this)"><span>Dennis&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $120.000<span></a></li>
                    <li><a href="#" id="Draken" onClick="idPlayer(this)"><span>Draken&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $130.000<span></a></li>
                    <li><a href="#" id="Rez" onClick="idPlayer(this)"><span>Rez&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $130.000<span></a></li>
                </ul>
                <div class="marketTeam" id="nip">
                    <div class="nipLogo">
                        <h3 class="teamName">NiP</h3>
                        <img src="img/NiP/NiP.svg" />
                    </div>
                </div>

            </div>
            <div class="liquidContainer">
                <ul id="liquidList">
                    <li><a href="#" id="Steel" onClick="idPlayer(this)"><span>Steel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $120.000<span></a></li>
                    <li><a href="#" id="Twistzz" onClick="idPlayer(this)"><span>Twistzz&nbsp;| $170.000<span></a></li>
                    <li><a href="#" id="Elige" onClick="idPlayer(this)"><span>Elige&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $190.000<span></a></li>
                    <li><a href="#" id="Nafly" onClick="idPlayer(this)"><span>Nafly&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $130.000<span></a></li>
                    <li><a href="#" id="Nitro" onClick="idPlayer(this)"><span>Nitro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $120.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="liquidLogo">
                        <h3 class="teamName">Liquid</h3>
                        <img src="img/Liquid/Liquid.svg" />
                    </div>
                </div>
            </div>
            <div class="naviContainer">
                <ul id="naviList">
                    <li><a href="#" id="Zeus" onClick="idPlayer(this)"><span>Zeus&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $50.000<span></a></li>
                    <li><a href="#" id="S1mple" onClick="idPlayer(this)"><span>S1mple&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $200.000<span></a></li>
                    <li><a href="#" id="Flamie" onClick="idPlayer(this)"><span>Flamie&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $150.000<span></a></li>
                    <li><a href="#" id="Edward" onClick="idPlayer(this)"><span>Edward&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $90.000<span></a></li>
                    <li><a href="#" id="Electronic" onClick="idPlayer(this)"><span>Electronic&nbsp;| $150.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="naviLogo">
                        <h3 class="teamName">Navi</h3>
                        <img src="img/Navi/Navi.svg" />
                    </div>
                </div>
            </div>
            <div class="mouseContainer">
                <ul id="mouseList">
                    <li><a href="#" id="Styko" onClick="idPlayer(this)"><span>Styko&nbsp;&nbsp;&nbsp;| $90.000<span></a></li>
                    <li><a href="#" id="ChrisJ" onClick="idPlayer(this)"><span>Chris J&nbsp;| $110.000<span></a></li>
                    <li><a href="#" id="Sunny" onClick="idPlayer(this)"><span>Sunny&nbsp;&nbsp;| $140.000<span></a></li>
                    <li><a href="#" id="Oskar" onClick="idPlayer(this)"><span>Oskar&nbsp;&nbsp;&nbsp;| $170.000<span></a></li>
                    <li><a href="#" id="Ropz" onClick="idPlayer(this)"><span>Ropz&nbsp;&nbsp;&nbsp;&nbsp;| $140.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="mouseLogo">
                        <h3 class="teamName">Mousesports</h3>
                        <img src="img/Mousesports/Mousesports.svg" />
                    </div>
                </div>
            </div>
            <div class="cloud9Container">
                <ul id="cloud9List">
                    <li><a href="#" id="Tarik" onClick="idPlayer(this)"><span>Tarik&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $120.000<span></a></li>
                    <li><a href="#" id="Stewie2K" onClick="idPlayer(this)"><span>Stewie2K&nbsp;&nbsp;| $130.000<span></a></li>
                    <li><a href="#" id="Autimatic" onClick="idPlayer(this)"><span>Autimatic&nbsp;&nbsp;| $170.000<span></a></li>
                    <li><a href="#" id="Rush" onClick="idPlayer(this)"><span>Rush&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $120.000<span></a></li>
                    <li><a href="#" id="Skadoodle" onClick="idPlayer(this)"><span>Skadoodle&nbsp;| $130.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="cloud9Logo">
                        <h3 class="teamName">Cloud9</h3>
                        <img src="img/Cloud9/Cloud9.svg" />
                    </div>
                </div>
            </div>
            <div class="skContainer">
                <ul id="skList">
                    <li><a href="#" id="Coldzera" onClick="idPlayer(this)"><span>Coldzera&nbsp;| $250.000<span></a></li>
                    <li><a href="#" id="Fallen" onClick="idPlayer(this)"><span>Fallen&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $200.000<span></a></li>
                    <li><a href="#" id="Taco" onClick="idPlayer(this)"><span>Taco&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $90.000<span></a></li>
                    <li><a href="#" id="Fer" onClick="idPlayer(this)"><span>Fer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $220.000<span></a></li>
                    <li><a href="#" id="Boltz" onClick="idPlayer(this)"><span>Boltz&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $160.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="skLogo">
                        <h3 class="teamName">SK</h3>
                        <img src="img/SK/SK.svg" />
                    </div>
                </div>
            </div>
            <div class="astralisContainer">
                <ul id="astralisList">
                    <li><a href="#" id="Dupreeh" onClick="idPlayer(this)"><span>Dupreeh&nbsp;| $200.000<span></a></li>
                    <li><a href="#" id="Device" onClick="idPlayer(this)"><span>Device&nbsp;&nbsp;&nbsp;&nbsp;| $230.000<span></a></li>
                    <li><a href="#" id="Glaive" onClick="idPlayer(this)"><span>Glaive&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $80.000<span></a></li>
                    <li><a href="#" id="Magisk" onClick="idPlayer(this)"><span>Magisk&nbsp;&nbsp;&nbsp;&nbsp;| $130.000<span></a></li>
                    <li><a href="#" id="Xyp9x" onClick="idPlayer(this)"><span>Xyp9x&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $180.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="astralisLogo">
                        <h3 class="teamName">Astralis</h3>
                        <img src="img/Astralis/Astralis.svg" />
                    </div>
                </div>
            </div>
            <div class="fnaticContainer">
                <ul id="fnaticList">
                    <li><a href="#" id="Lekro" onClick="idPlayer(this)"><span>Lekro&nbsp;&nbsp;&nbsp;| $130.000<span></a></li>
                    <li><a href="#" id="Flusha" onClick="idPlayer(this)"><span>Flusha&nbsp;| $150.000<span></a></li>
                    <li><a href="#" id="Krimz" onClick="idPlayer(this)"><span>Krimz&nbsp;&nbsp;&nbsp;| $130.000<span></a></li>
                    <li><a href="#" id="Golden" onClick="idPlayer(this)"><span>Golden&nbsp;| $80.000<span></a></li>
                    <li><a href="#" id="JW" onClick="idPlayer(this)"><span>JW&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $100.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="fnaticLogo">
                        <h3 class="teamName">Fnatic</h3>
                        <img src="img/Fnatic/Fnatic.svg" />
                    </div>
                </div>
            </div>
            <div class="g2Container">
                <ul id="g2List">
                    <li><a href="#" id="Shox" onClick="idPlayer(this)"><span>Shox&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $180.000<span></a></li>
                    <li><a href="#" id="KennyS" onClick="idPlayer(this)"><span>KennyS&nbsp;| $200.000<span></a></li>
                    <li><a href="#" id="Apex" onClick="idPlayer(this)"><span>Apex&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $150.000<span></a></li>
                    <li><a href="#" id="Bodyy" onClick="idPlayer(this)"><span>Bodyy&nbsp;&nbsp;&nbsp;&nbsp;| $120.000<span></a></li>
                    <li><a href="#" id="NBK" onClick="idPlayer(this)"><span>NBK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $150.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="g2Logo">
                        <h3 class="teamName">G2</h3>
                        <img src="img/G2/G2.svg" />
                    </div>
                </div>
            </div>
            <div class="fazeContainer">
                <ul id="fazeList">
                    <li><a href="#" id="Olofmeister" onClick="idPlayer(this)"><span>Olofmeister&nbsp;| $160.000<span></a></li>
                    <li><a href="#" id="Guardian" onClick="idPlayer(this)"><span>Guardian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $200.000<span></a></li>
                    <li><a href="#" id="Niko" onClick="idPlayer(this)"><span>Niko&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $230.000<span></a></li>
                    <li><a href="#" id="Rain" onClick="idPlayer(this)"><span>Rain&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $210.000<span></a></li>
                    <li><a href="#" id="Karrigan" onClick="idPlayer(this)"><span>Karrigan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| $80.000<span></a></li>
                </ul>
                <div class="marketTeam">
                    <div class="fazeLogo">
                        <h3 class="teamName">Faze</h3>
                        <img src="img/Faze/Faze.svg" />
                    </div>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</div>
</body>

</html>
