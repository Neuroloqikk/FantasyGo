<?php
session_start();
require 'connect.php';

$username = $_SESSION["username"];

if ($_GET['username'] != NULL) {
  $user = $_GET['username'];
  $_SESSION["user"] = $user;
  echo '<script>location="playerTeam.php"</script>';
}

if (isset($_POST['search'])) {
  $user = $_POST['nametxt'];
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:id");
  $stmt->execute(['id' => $user]);
  $userSearch = $stmt->fetch();
  if ($userSearch != NULL) {
    $_SESSION["user"] = $user;
    echo '<script>location="playerTeam.php"</script>';
  }
  else {
    displayAlert("That User does not exist!", "danger");
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
    <meta 
     name='viewport' 
     content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' 
     />
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
          <div class="menuLogoMobile">
            <img onclick="myFunction()" src="img/menu.svg" style="width: inherit;">
          </div>  
            <div id="myDropdown" class="dropdownMobile-content">
              <a href="myTeamMobile.php">My Team</a>
              <a href="marketMobile.php">Market</a>
              <a href="leaderboardMobile.php">Leaderboard</a>
              <a href="NextGamesMobile.php">Next Games</a>
              <a href="LastGamesMobile.php">Last Games</a>
              <a href="userSettingsMobile.php">Settings</a>
              <a href="logoutMobile.php">Logout</a>
            </div>
          <a id="logoMobile" class="navbar-brand">
            <img src="img/logo.png">
          </a>
        </div>
    </nav>

<?php



echo '<div id="tableLeaderboard" class="container" style="background-color:  white;">';
echo '<div class="row">';
echo '<div class="table-responsive">';
echo '<table class="table table-hover">';
echo
'<thead>
<tr>
<th>Team 1</th>
<th></th>
<th>Team 2</th>

<th>Score</th>
<th id="tdDateMobile">Date</th>
</tr>
</thead>
<tbody id="myTable">';
$getGames = $pdo->query("SELECT id,team1,team2,score_team1,score_team2,timestamp,next_game_id FROM results ORDER BY timestamp DESC");
          foreach ($getGames as $user) {
  $timestamp = $user['timestamp'];
  $datetime = new DateTime($timestamp);
  $date = $datetime->format('Y/m/d');
  
  echo "<tr>";
  echo '<td style="cursor:pointer"> <a href="lastGameMobile.php?id='.$user['next_game_id'].'">'.$user['team1'].'</a></td>';
  echo '<td style="cursor:pointer"> <a href="lastGameMobile.php?id='.$user['next_game_id'].'">vs</a></td>';
  echo '<td style="cursor:pointer"> <a href="lastGameMobile.php?id='.$user['next_game_id'].'">'.$user['team2'].'</a></td>';
  echo '<td>'.$user['score_team1'].'-'.$user['score_team2'].'</td>';
  echo '<td id="tdDateMobile">'.$date.'</td>';
  echo "</tr>";
}
echo '</tbody>';
echo '</table>';
echo '</div>';
echo '<div class="col-md-12 text-right">';
echo '<ul class="pagination pagination-lg pager" id="myPager"></ul>';
echo '</div>';
echo '</div>';
echo '</div>';
?>
<script>
$.fn.pageMe = function(opts){
  var $this = this,
  defaults = {
    perPage: 7,
    showPrevNext: false,
    hidePageNumbers: false
  },
  settings = $.extend(defaults, opts);

  var listElement = $this;
  var perPage = settings.perPage;
  var children = listElement.children();
  var pager = $('.pager');

  if (typeof settings.childSelector!="undefined") {
    children = listElement.find(settings.childSelector);
  }

  if (typeof settings.pagerSelector!="undefined") {
    pager = $(settings.pagerSelector);
  }

  var numItems = children.size();
  var numPages = Math.ceil(numItems/perPage);

  pager.data("curr",0);

  if (settings.showPrevNext){
    $('<li><a href="#" class="prev_link">«</a></li>').appendTo(pager);
  }

  var curr = 0;
  while(numPages > curr && (settings.hidePageNumbers==false)){
    $('<li><a href="#" class="page_link">'+(curr+1)+'</a></li>').appendTo(pager);
    curr++;
  }

  if (settings.showPrevNext){
    $('<li><a href="#" class="next_link">»</a></li>').appendTo(pager);
  }

  pager.find('.page_link:first').addClass('active');
  pager.find('.prev_link').hide();
  if (numPages<=1) {
    pager.find('.next_link').hide();
  }
  pager.children().eq(1).addClass("active");

  children.hide();
  children.slice(0, perPage).show();

  pager.find('li .page_link').click(function(){
    var clickedPage = $(this).html().valueOf()-1;
    goTo(clickedPage,perPage);
    return false;
  });
  pager.find('li .prev_link').click(function(){
    previous();
    return false;
  });
  pager.find('li .next_link').click(function(){
    next();
    return false;
  });

  function previous(){
    var goToPage = parseInt(pager.data("curr")) - 1;
    goTo(goToPage);
  }

  function next(){
    goToPage = parseInt(pager.data("curr")) + 1;
    goTo(goToPage);
  }

  function goTo(page){
    var startAt = page * perPage,
    endOn = startAt + perPage;

    children.css('display','none').slice(startAt, endOn).show();

    if (page>=1) {
      pager.find('.prev_link').show();
    }
    else {
      pager.find('.prev_link').hide();
    }

    if (page<(numPages-1)) {
      pager.find('.next_link').show();
    }
    else {
      pager.find('.next_link').hide();
    }

    pager.data("curr",page);
    pager.children().removeClass("active");
    pager.children().eq(page+1).addClass("active");

  }
};

$(document).ready(function(){

  $('#myTable').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:10});

});
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
</body>
</html>