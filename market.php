<script type="text/javascript">
  if (screen.width <= 800) {
  document.location = "signinMobile.php";
  }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<?php
session_start();
require 'connect.php';

unset($_SESSION['1sttime']);
$username = $_SESSION["username"];
$stmt = $pdo->query("SELECT `isAdmin` FROM `users`.`users` WHERE username='$username'");
$p = $stmt->fetch();
$Admin = $p['isAdmin'];
if($username != null){
$stmt = $pdo->query("SELECT `balance` FROM `users`.`users` WHERE username='$username'");
$p = $stmt->fetch();
$balance = $p['balance']."$";
$BalanceText = "Balance";
}
if($username == null){
    $balance="";
    $username="Login";
    $loginLink="signinMobile.php";
}
if (!empty($_GET)) {
   $playerName = $_GET["name"];
   $stmt = $pdo->query("SELECT `id`,`price`,`photo`,`team`,`team_photo`,`first_name`,`last_name` FROM `users`.`players` WHERE name='$playerName'");
   $t = $stmt->fetch();
   $id = $t['id'];
   $first_name = $t['first_name'];
   $last_name = $t['last_name'];
   $player_photo = $t['photo'];
   $teamPlayerModal = $t['team'];
   $team_photo = $t['team_photo'];
   $price = $t['price'];
   if ($_GET['buy'] != NULL and $username != null) {
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
                        <h4><?=$BalanceText?></h4>
                        <h2>
                            <?= $balance ?></h2>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                    <ul class="nav navbar-nav navbar-right">

                        <li id="usernameInsertGame" class="font">
                        <?php if ($username == "Login"):?>
                            <a href="signin.php">
                                <?= $username ?>
                            </a>
                            <?php else:?>
                            <a href="userSettings.php">
                                <?= $username ?>
                            </a>
                            <?php endif;?>
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
                                    <?php
                                            $stmt = $pdo->query("SELECT team1,team2,score_team1,score_team2,next_game_id FROM results WHERE team1='$teamPlayerModal' OR team2='$teamPlayerModal' ORDER BY timestamp DESC LIMIT 4");
                                            $p = $stmt->fetchAll();
                                            foreach($p as $row){
                                                $resultsid=$row['next_game_id'];
                                                $stmt = $pdo->query("SELECT player_score FROM results_player WHERE results_id ='$resultsid' AND player_name='$playerName' ORDER BY timestamp DESC LIMIT 4");
                                                $p = $stmt->fetch();
                                                echo '<tr>';
                                                echo '<td>'.$row['team1'].' vs '.$row['team2'].'</td>';
                                                echo '<td>'.$row['score_team1']. ' vs '.$row['score_team2'].'</td>';
                                                echo '<td>+<span id="win">'.implode($p).'</span></td>';

                                                echo '</tr>';
                                            }
                                        ?>
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
                                    <?php
                                        $stmt = $pdo->query("SELECT * FROM next_games WHERE team1='$teamPlayerModal' OR team2='$teamPlayerModal' AND Inserted IS NULL ORDER BY Date DESC LIMIT 4");
                                        $p = $stmt->fetchAll();
                                        foreach($p as $row){
                                            echo '<tr>';
                                            echo '<td>'.$row['team1'].' vs '.$row['team2'].'</td>';
                                            echo '<td>'.$row['Date'].'</td>';
                                            echo '<td>'.$row['Hour'].'</td>';
                                            echo '</tr>';
                                        }
                                    ?>
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
        <?php 
         $stmt = $pdo->query("SELECT team FROM teams where active = '1'");
         $p = $stmt->fetchAll();
         $team1 = implode($p[0]);
         $team2 = implode($p[1]);
         $team3 = implode($p[2]);
         $team4 = implode($p[3]);
         $team5 = implode($p[4]);
         $team6 = implode($p[5]);
         $team7 = implode($p[6]);
         $team8 = implode($p[7]);
         $team9 = implode($p[8]);
         $team10 = implode($p[9]);
         ?>
        <div class="marketGrid">
            <div class="nipContainer">
                <ul id="nipList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team1'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam" id="nip">
                    <div class="nipLogo">
                        <h3 class="teamName"><?=$team1?></h3>
                        <img src="img/<?=$team1?>/<?=$team1?>.svg" />
                    </div>
                </div>

            </div>
            <div class="liquidContainer">
                <ul id="liquidList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team2'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="liquidLogo">
                        <h3 class="teamName"><?=$team2?></h3>
                        <img src="img/<?=$team2?>/<?=$team2?>.svg" />
                    </div>
                </div>
            </div>
            <div class="naviContainer">
                <ul id="naviList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team3'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="naviLogo">
                        <h3 class="teamName"><?=$team3?></h3>
                        <img src="img/<?=$team3?>/<?=$team3?>.svg" />
                    </div>
                </div>
            </div>
            <div class="mouseContainer">
                <ul id="mouseList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team4'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="mouseLogo">
                        <h3 class="teamName"><?=$team4?></h3>
                        <img src="img/<?=$team4?>/<?=$team4?>.svg" />
                    </div>
                </div>
            </div>
            <div class="cloud9Container">
                <ul id="cloud9List">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team5'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="cloud9Logo">
                        <h3 class="teamName"><?=$team5?></h3>
                        <img src="img/<?=$team5?>/<?=$team5?>.svg" />
                    </div>
                </div>
            </div>
            <div class="skContainer">
                <ul id="skList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team6'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="skLogo">
                        <h3 class="teamName"><?=$team6?></h3>
                        <img src="img/<?=$team6?>/<?=$team6?>.svg" />
                    </div>
                </div>
            </div>
            <div class="astralisContainer">
                <ul id="astralisList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team7'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="astralisLogo">
                        <h3 class="teamName"><?=$team7?></h3>
                        <img src="img/<?=$team7?>/<?=$team7?>.svg" />
                    </div>
                </div>
            </div>
            <div class="fnaticContainer">
                <ul id="fnaticList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team8'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="fnaticLogo">
                        <h3 class="teamName"><?=$team8?></h3>
                        <img src="img/<?=$team8?>/<?=$team8?>.svg" />
                    </div>
                </div>
            </div>
            <div class="g2Container">
                <ul id="g2List">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team9'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="g2Logo">
                        <h3 class="teamName"><?=$team9?></h3>
                        <img src="img/<?=$team9?>/<?=$team9?>.svg" />
                    </div>
                </div>
            </div>
            <div class="fazeContainer">
                <ul id="fazeList">
                <?php 
                    $stmt = $pdo->query("SELECT name,price FROM players where team= '$team10'");
                    $p = $stmt->fetchAll();
                    foreach($p as $row){
                        ?>
                    <li><a href="#" id="<?=$row['name']?>" onClick="idPlayer(this)"><span><?=$row['name']?> | <?=$row['price']?> $<span></a></li>
                <?php }?>
                </ul>
                <div class="marketTeam">
                    <div class="fazeLogo">
                        <h3 class="teamName"><?=$team10?></h3>
                        <img src="img/<?=$team10?>/<?=$team10?>.svg" />
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
