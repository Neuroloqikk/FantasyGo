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
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />
    <title>Fantasy GO</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/img/icon.png">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
     <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<div class="container-example">

    <body class="bg">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
        <p class="balanceMobile col-xs-4 col-xs-offset-4 col-xs-offset-right-4"><?=$balance?></p>
          <div class="usernameMobile col-xs-4 col-xs-offset-4 col-xs-offset-right-4">
            <p><a href="userSettings.php"><?=$username?></a></p>
          </div>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
          <div class="menuLogoMobile">
            <img onclick="myFunction()" src="img/menu.svg" style="width: inherit;">
          </div>  
            <div id="myDropdown" class="dropdownMobile-content">
              <a href="myTeamMobile.php">My Team</a>
              <a href="marketMobile.php">Market</a>
              <a href="leaderboardMobile.php">Leaderboard</a>
              <a href="NextGamesMobile.php">Next Games</a>
              <a href="LastGamesMobile.php">Last Games</a>
              <a href="GraphInfoMobile.php">Market Stats</a>
              <a href="userSettingsMobile.php">Settings</a>
              <a href="logoutMobile.php">Logout</a>
            </div>
          <a id="logoMobile" class="navbar-brand">
            <img src="img/logo.png">
          </a>
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
<div id="50Info" style="margin-top: -20px;">
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
</div>
<div id="50Info" style="margin-top: 3px;">
<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
</div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


</body>
</html>