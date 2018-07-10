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
if (isset($_POST['register'])) {
  $name = !empty($_POST['name']) ? trim($_POST['name']) : null;
  $price = !empty($_POST['price']) ? trim($_POST['price']) : null;
  $team = !empty($_POST['teamname']) ? trim($_POST['teamname']) : null;
  $firstname = !empty($_POST['firstname']) ? trim($_POST['firstname']) : null;
  $lastname = !empty($_POST['lastname']) ? trim($_POST['lastname']) : null;
  $target_dir = "img/$team/";
  $target_file = $target_dir . basename($_FILES["Photo"]["name"]);
  move_uploaded_file($_FILES["Photo"]["tmp_name"], $target_file);

  $photo = "/$team/$name.png";
  $teamphoto = "/$team/$team.svg";

  $sql = "INSERT INTO `users`.`players` (name,price,team,photo,first_name,last_name,team_photo) VALUES ('$name','$price','$team','$photo','$firstname','$lastname','$teamphoto')";
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute();
  if ($result == 1) {
    displayAlert("Player Inserted", "success");
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
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li id="usernameInsertGame" class="font">
              <a>
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
                <a href="leaderboard.php">LeaderBoard</a>
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
                <hr>
                <li style="text-align:  center;margin-bottom: 8%;font-weight: 600;">Admin</li>
                <li>
                <a href="insertNextGame.php">Insert Next Game</a>
                </li>
                <li>
                <a href="insertGame.php">Insert Last Game</a>
                </li>
                <li>
                <a href="adminPanel.php">Roles/Tournaments</a>
                </li>
                <li>
                <a href="editPlayers.php">Edit Player's Prices</a>
                </li>
                <li>
                <a href="teamInsert.php">Insert new team</a>
                </li>
                <li>
                <a href="playerInsert.php">Insert new player</a>
                </li>
                <li>
                <a href="updateMarketTeams.php">Update available teams</a>
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
    <form class="SignUp" method="POST" style="border:1px solid #ccc" action="playerInsert.php" enctype="multipart/form-data">
      <div class="txtcolor">
        <div class="container">
          <h1>Insert a new player</h1>
          <hr>

          <label for="name"><b>Name</b></label>
          <input class="txtcolorinput" type="text" placeholder="Enter name" name="name" required>

          <label for="price"><b>Price</b></label>
          <input class="txtcolorinput" type="text" placeholder="Enter price" name="price" required>

          <label for="firstname"><b>First Name</b></label>
          <input class="txtcolorinput" type="text" placeholder="Enter Player First Name" name="firstname" required>

          <label for="lastname"><b>Last Name</b></label>
          <input class="txtcolorinput" type="text" placeholder="Enter Player Last Name" name="lastname" required>

          <label for="Team"><b>Team</b></label>
          <select id="teamname" class="form-control" name="teamname" style="width: 152px;height: 39px;margin-left:0%;">
            <option value="#" selected="">Choose One:</option>
            
            <?php 
              $stmt = $pdo->query("SELECT team from teams ");
              $p = $stmt->fetchAll();
              foreach($p as $row){
            ?>
              <option value=<?=$row['team']?>><?=$row['team']?></option>
              <?php }?>
          </select>

          <label for="Photo"><b>Photo</b></label>
          <input class="txtcolorinput" type="file" name="Photo" id="Photo">
          <div class="clearfix">
            <button type="button" class="cancelbtn">Cancel</button>
            <button type="submit" value="Register" class="signupbtn" name="register">Insert</button>
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
  </div>
</body>


</html>
