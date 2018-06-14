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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fantasy GO</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/app.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="/img/icon.png">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<div class="container-example">

  <body class="bg" >
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
                  <a href="userSettings.php">Settings</a>
                </li>
                <li>
                  <a href="logout.php">Logout</a>
                </li>
                <?php elseif ($Admin == 1):?>
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
                  <a href="LastGames.php">Change Player Prices</a>
                </li>
                <li>
                  <a href="LastGames.php">Change Available Teams</a>
                </li>
                <li>
                  <a href="LastGames.php">Insert New Team</a>
                </li>
                <li>
                  <a href="LastGames.php">Update Tournament</a>
                </li>
                <li>
                  <a href="adminPanel.php">Change Roles</a>
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
    </nav>
  </div>
</div>
  <form class="formLeaderboard" action="leaderboard.php" method="POST">
  <div id="rowLeaderboard" class="row">
    <div class="col-xs-6 col-md-4">
      <div id="inputLeaderboard" class="input-group">
        <input type="text" class="form-control" placeholder="Search" name="nametxt" id="txtSearch" />
        <div class="input-group-btn"  id="txtSearch" >
          <button class="btn btn-primary" type="submit" name="search" id="txtSearch" >
            <span class="glyphicon glyphicon-search"></span id="txtSearch" >
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
<?php



echo '<div id="tableLeaderboard" class="container">';
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
<th>Date</th>
</tr>
</thead>
<tbody id="myTable">';
$getGames = $pdo->query("SELECT id,team1,team2,score_team1,score_team2,timestamp,next_game_id FROM results ORDER BY timestamp DESC");
          foreach ($getGames as $user) {
  $timestamp = $user['timestamp'];
  echo "<tr>";
  echo '<td style="cursor:pointer"> <a href="lastGame.php?id='.$user['next_game_id'].'">'.$user['team1'].'</a></td>';
  echo '<td style="cursor:pointer"> <a href="lastGame.php?id='.$user['next_game_id'].'">vs</a></td>';
  echo '<td style="cursor:pointer"> <a href="lastGame.php?id='.$user['next_game_id'].'">'.$user['team2'].'</a></td>';
  echo '<td>'.$user['score_team1'].'-'.$user['score_team2'].'</td>';
  echo '<td>'.$timestamp.'</td>';
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
</body>
</html>