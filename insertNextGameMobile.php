<?php
session_start();
require 'connect.php';

$username = $_SESSION["username"];
$img1 = "none";
$img2 = "none";
if($_SESSION["team1"] != null and $_SESSION["team2"] != null){
  $img1 = "block";
  $img2 = "block";
}
if (isset($_GET['t1'])){
  $_SESSION["team1"] = $_GET['t1'];
  $img1 = "block";
}
if (isset($_GET['t2'])){
  $_SESSION["team2"] = $_GET['t2'];
  $img2 = "block";
}
if (isset($_GET['hour']) AND isset($_GET['date'])){
  $_SESSION["hour"] = $_GET['hour'];
  $_SESSION["date"] = $_GET['date'];
}

$hour = $_SESSION["hour"];
$date = $_SESSION['date'];
$teamOne = $_SESSION["team1"];
$teamTwo = $_SESSION["team2"];
if ($hour != null and $date != null and $teamOne != null and $teamTwo != null){
  $sql = 'INSERT INTO `next_games` (team1,team2,Date,Hour) VALUES ("'.$teamOne.'","'.$teamTwo.'","'.$date.'","'.$hour.'")';
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute();
  unset($_SESSION['team1']);
  unset($_SESSION['team2']);
  unset($_SESSION['hour']);
  unset($_SESSION['date']);
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
  <script src="http://yui.yahooapis.com/3.18.1/build/yui/yui-min.js"></script>
</head>
<div class="container-example">

  <body class="yui3-skin-sam">
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
            <img src="img/logo.png">
          </a>
          
        </div>
        
      <div class="sidenav" id="sidebarShowBtn" style="display: block;">
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
    <div id="nextGameDrop1" class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Team 1
        <span class="caret"></span></button>
        <ul class="dropdown-menu dropdown-menu-center">
          <li><a href="#" id="NiP" onClick="return team(this.id)">NiP</a></li>
          <li><a href="#" id="SK" onClick="team(this.id)">SK</a></li>
          <li><a href="#" id="Liquid" onClick="team(this.id)">Liquid</a></li>
          <li><a href="#" id="Navi" onClick="team(this.id)">Navi</a></li>
          <li><a href="#" id="Mousesports" onClick="team(this.id)">Mousesports</a></li>
          <li><a href="#" id="Cloud9" onClick="team(this.id)">Cloud9</a></li>
          <li><a href="#" id="SK" onClick="team(this.id)">SK</a></li>
          <li><a href="#" id="Astralis" onClick="team(this.id)">Astralis</a></li>
          <li><a href="#" id="Fnatic" onClick="team(this.id)">Fnatic</a></li>
          <li><a href="#" id="G2" onClick="team(this.id)">G2</a></li>
          <li><a href="#" id="Faze" onClick="team(this.id)">Faze</a></li>
        </ul>
        <img class="imgTeamNext" style="display:<?=$img1?>;" src="img/<?=$teamOne?>/<?=$teamOne?>.svg">
      </div>
      <div id="nextGameDrop2" class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Team 2
          <span class="caret"></span></button>
          <ul class="dropdown-menu ">
            <li><a href="#" id="NiP" onClick="team2(this.id)">NiP</a></li>
            <li><a href="#" id="SK" onClick="team2(this.id)">SK</a></li>
            <li><a href="#" id="Liquid" onClick="team2(this.id)">Liquid</a></li>
            <li><a href="#" id="Navi" onClick="team2(this.id)">Navi</a></li>
            <li><a href="#" id="Mousesports" onClick="team2(this.id)">Mousesports</a></li>
            <li><a href="#" id="Cloud9" onClick="team2(this.id)">Cloud9</a></li>
            <li><a href="#" id="SK" onClick="team2(this.id)">SK</a></li>
            <li><a href="#" id="Astralis" onClick="team2(this.id)">Astralis</a></li>
            <li><a href="#" id="Fnatic" onClick="team2(this.id)">Fnatic</a></li>
            <li><a href="#" id="G2" onClick="team2(this.id)">G2</a></li>
            <li><a href="#" id="Faze" onClick="team2(this.id)">Faze</a></li>
          </ul>
          <img class="imgTeamNext" style="display:<?=$img2?>" src="img/<?=$teamTwo?>/<?=$teamTwo?>.svg">
        </div>
        <div id="panelNextGame" class="panel panel-default">
          <div class="panel-body" style="text-align:center;">Game Hour<input type="time" id="Hour" class="form-control" placeholder="Enter Hour">
        </div>
      
        
      </div>

      <div id="calendar" class="yui3-skin-sam yui3-g"> <!-- You need this skin class -->

        <div id="leftcolumn" class="yui3-u">
          <!-- Container for the calendar -->
          <div id="mycalendar"></div>
        </div>
        <div id="rightcolumn" class="yui3-u">
        
        </div>
      </div>
      <div id="DateNextGame" style="padding-left:20px;">
            <!-- The buttons are created simply by assigning the correct CSS class -->
            <label for="Date" style="display:none;">Selected date: <span id="selecteddate"></span></label>
            
        </div>
        
      <script type="text/javascript">
        YUI().use('calendar', 'datatype-date', 'cssbutton',  function(Y) {

            // Create a new instance of calendar, placing it in
            // #mycalendar container, setting its width to 340px,
            // the flags for showing previous and next month's
            // dates in available empty cells to true, and setting
            // the date to today's date.
            var calendar = new Y.Calendar({
              contentBox: "#mycalendar",
              width:'340px',
              showPrevMonth: true,
              showNextMonth: true,
              date: new Date()}).render();

            // Get a reference to Y.DataType.Date
            var dtdate = Y.DataType.Date;

            // Listen to calendar's selectionChange event.
            calendar.on("selectionChange", function (ev) {

              // Get the date from the list of selected
              // dates returned with the event (since only
              // single selection is enabled by default,
              // we expect there to be only one date)
              var newDate = ev.newSelection[0];

              // Format the date and output it to a DOM
              // element.
              Y.one("#selecteddate").setHTML(dtdate.format(newDate));
            });


            // When the 'Show Previous Month' link is clicked,
            // modify the showPrevMonth property to show or hide
            // previous month's dates
            Y.one("#togglePrevMonth").on('click', function (ev) {
              ev.preventDefault();
              calendar.set('showPrevMonth', !(calendar.get("showPrevMonth")));
            });

            // When the 'Show Next Month' link is clicked,
            // modify the showNextMonth property to show or hide
            // next month's dates
            Y.one("#toggleNextMonth").on('click', function (ev) {
              ev.preventDefault();
              calendar.set('showNextMonth', !(calendar.get("showNextMonth")));
            });
        });
      </script>
      <input type="submit" id="insertTeamsSucess" class="btn btn-primary" onClick="insert()" value="Submit">
      </div>
    <script>
      function team(team1){
        window.location.href="insertNextGame.php?t1=" + team1;
      }
      function team2(team2){
        window.location.href="insertNextGame.php?t2=" + team2;
      }
      function insert(){
        var DateGame = document.getElementById("selecteddate").innerText;
        var hour = document.getElementById('Hour').value;
        window.location.href="insertNextGame.php?hour=" + hour + "&date=" + DateGame;
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
