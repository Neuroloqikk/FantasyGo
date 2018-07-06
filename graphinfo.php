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
$Select1 = false;
$Select2 = false;
$Select3 = false;
$Select4 = false;
$Select5 = false;
$Select6 = false;
$Select7 = false;
$Player1 = false;
$Player2 = false;
$Player3 = false;
$Player4 = false;
$Player5 = false;
$stmt = $pdo->query("SELECT
  a.name,
  (SELECT
    COUNT(player1_id)
    FROM
    users.users_players b
    WHERE
    (a.id = b.player1_id
      OR a.id = b.player2_id
      OR a.id = b.player3_id
      OR a.id = b.player4_id
      OR a.id = b.player5_id)) AS 'Total'
      FROM
      users.players a
      ORDER BY Total DESC
      LIMIT 5;");
    $p = $stmt->fetchAll();
    foreach($p as $row){
      if ($Player1 == false){
        $player1_name_bought = $row["name"];
        $player1_times = $row["Total"];
        $Player1 = true;
      }
      else if ($Player2 == false){
        $player2_name_bought = $row["name"];
        $player2_times = $row["Total"];
        $Player2 = true;
      }
      else if ($Player3 == false){
        $player3_name_bought = $row["name"];
        $player3_times = $row["Total"];
        $Player3 = true;
      }
      else if ($Player4 == false){
        $player4_name_bought = $row["name"];
        $player4_times = $row["Total"];
        $Player4 = true;
      }
      else if ($Player5 == false){
        $player5_name_bought = $row["name"];
        $player5_times = $row["Total"];
        $Player5 = true;
      }
    }
    $dataPoints2 = array(
      array("label"=> $player1_name_bought, "y"=> $player1_times),
      array("label"=> $player2_name_bought, "y"=> $player2_times),
      array("label"=> $player3_name_bought, "y"=> $player3_times),
      array("label"=> $player4_name_bought, "y"=> $player4_times),
      array("label"=> $player5_name_bought, "y"=> $player5_times)
    );
    $stmt = $pdo->query("SELECT player_name,sum(player_score) as total FROM results_player GROUP BY player_name ORDER BY total DESC LIMIT 7");
    $p = $stmt->fetchAll();
    foreach($p as $row){
      if ($Select1 == false){
        $player1_name = $row["player_name"];
        $player1_score = $row["total"];
        $Select1 = true;
      }
      else if ($Select2 == false){
        $player2_name = $row["player_name"];
        $player2_score = $row["total"];
        $Select2 = true;
      }
      else if ($Select3 == false){
        $player3_name = $row["player_name"];
        $player3_score = $row["total"];
        $Select3 = true;
      }
      else if ($Select4 == false){
        $player4_name = $row["player_name"];
        $player4_score = $row["total"];
        $Select4 = true;
      }
      else if ($Select5 == false){
        $player5_name = $row["player_name"];
        $player5_score = $row["total"];
        $Select5 = true;
      }
      else if ($Select6 == false){
        $player6_name = $row["player_name"];
        $player6_score = $row["total"];
        $Select6 = true;
      }
      else if ($Select7 == false){
        $player7_name = $row["player_name"];
        $player7_score = $row["total"];
        $Select7 = true;
      }
    }
    $dataPoints = array(
      array("label"=> $player1_name, "y"=> $player1_score),
      array("label"=> $player2_name, "y"=> $player2_score),
      array("label"=> $player3_name, "y"=> $player3_score),
      array("label"=> $player4_name, "y"=> $player4_score),
      array("label"=> $player5_name, "y"=> $player5_score),
      array("label"=> $player6_name, "y"=> $player6_score),
      array("label"=> $player7_name, "y"=> $player7_score)
    );
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
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body class="bg" style="">
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
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
          <script src="js/bootstrap.min.js"></script>
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
  <script>
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
      animationEnabled: true,
      exportEnabled: true,
      title:{
        text: "Players with most points"
      },
      data: [{
        type: "pie",
        showInLegend: "true",
        legendText: "{label}",
        indexLabelFontSize: 16,
        indexLabel: "{label}",
        yValueFormatString: "#,##0",
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
      }]
    });
    var chart1 = new CanvasJS.Chart("chartContainer2", {
      animationEnabled: true,
      exportEnabled: true,
      title:{
        text: "Most bought players"
      },
      data: [{
        type: "pie",
        showInLegend: "true",
        legendText: "{label}",
        indexLabelFontSize: 16,
        indexLabel: "{label}",
        yValueFormatString: "#,##0",
        dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
      }]
    });
    chart.render();
    chart1.render();
  }
  </script>
</head>
<body>
  <div id="50Info" style="width: 49%;display: inline-block;margin-left: 27%;margin-top: -11px;">
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
  </div>
  <div id="50Info" style="width: 49%;display: inline-block;margin-right: 10px;margin-top: 10px;margin-left: 27%;">
    <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
  </div>
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
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
</body>
</html>
