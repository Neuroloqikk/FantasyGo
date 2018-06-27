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
$stmt = $pdo->query("SELECT Name,Place,Prize FROM Tournament WHERE Active='1'");
$p = $stmt->fetch();
$NameCurrent = $p['Name'];
$PlaceCurrent = $p['Place'];
$PrizeCurrent = $p['Prize'];
if (isset($_POST['insert'])) {
  $tournamentName = !empty($_POST['tournamentName']) ? trim($_POST['tournamentName']) : null;
  $tournamentPlace = !empty($_POST['tournamentPlace']) ? trim($_POST['tournamentPlace']) : null;
  $tournamentPrize = !empty($_POST['tournamentPrize']) ? trim($_POST['tournamentPrize']) : null;
  $Active = 1;
  $sql = "Update Tournament Set Active = 0;";
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute();
  $sql = "INSERT INTO Tournament (Name,Place,Prize,Active) VALUES ('$tournamentName','$tournamentPlace','$tournamentPrize','$Active');";
  $stmt = $pdo->prepare($sql);
  $result = $stmt->execute();
}
if (isset($_GET["role"]) && isset($_GET["username"])){
  if ($_GET['role'] == "User"){
    $isAdmin = 0;
  }
  else{
    $isAdmin = 1;
  }
  $sql = "UPDATE `users`.`users` SET isAdmin=? WHERE username=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$isAdmin, $_GET["username"]]);
  displayAlert("User role was updated!", "success");
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
  </nav>
  <div class="col-xs-6 col-md-4 adminPaneluserRole">
    <form class="formLeaderboard formSearchAdminPanel" action="adminPanel.php" method="POST" style="width: 50%;">
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
      echo '<div id="tableLeaderboard" class="container" style="width: 100%;">';
      echo '<div class="row">';
      echo '<div class="table-responsive">';
      echo '<table class="table table-hover">';
      echo
      '<thead>
      <tr>
      <th></th>
      <th>Username</th>
      <th>Role</th>
      </tr>
      </thead>
      <tbody id="myTable">';
      if (isset($_POST["search"])){
        $searchUser = !empty($_POST['nametxt']) ? trim($_POST['nametxt']) : null;
        $getUsers = $pdo->query('SELECT username,isAdmin FROM users Where username = "'.$searchUser.'"');
        $index = 0;
      }
      else if ($_POST["search"] == ""){
        $getUsers = $pdo->query("SELECT username,isAdmin FROM users ORDER BY isAdmin DESC");
        $index = 0;
      }
      else{
        $getUsers = $pdo->query("SELECT username,isAdmin FROM users ORDER BY isAdmin DESC");
        $index = 0;
      }
      foreach ($getUsers as $user) {
        $index++;
        if ($user['isAdmin'] == 1){
          $Admin = "Admin";
          $secondOption = "User";
        }
        else{
          $Admin = "User";
          $secondOption = "Admin";
        }
        echo "<tr>";
        echo "<td>".$index."</td>";
        echo '<td style="cursor:pointer"> <a href="leaderboard.php?username='.$user['username'].'">'.$user['username'].'</a></td>';
        echo '<td>';
        echo ' <select data-userid="'.$user['username'].'" onchange="changeRole(this)" style="width: 50%;"> id="roleChooser"';
        echo ' <option value="'.$Admin.'" selected="selected">'.$Admin.'</option>';
        echo'  <option value="'.$secondOption.'">'.$secondOption.'</option>';
        echo '</select>';
        echo '<span id="roleChoose" name=" class="glyphicon glyphicon-ok okUserRole"></span>';
        echo'</td>';
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
    </div>
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
    ;
    $(document).ready(function(){
      ;
      $('#myTable').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:10});
    });
  </script>
  <ul class="nav nav-tabs" style="margin-top: 1%;margin-left: 62%;width: 304px;">
    <li class="active"><a data-toggle="tab" href="#menu1" style="background-color: white;">Insert Tournament</a></li>
    <li><a data-toggle="tab" href="#menu2" style="background-color: white;">Update Tournament</a></li>
  </ul>
  <div class="tab-content">
    <div id="menu1" class="tab-pane fade in active">
      <form class="SignIn formAdminPanel " action="adminPanel.php" method="POST" style="border:1px solid #ccc">
        <div class="txtcolor">
          <div class="container containerAdminPanel">
            <h1>Insert Tournament</h1>
            <hr>
            <label for="tournamentName"><b>Tournament Name</b></label>
            <input type="text" placeholder="Enter Tournament Name" name="tournamentName" required>
            <label for="tournamentPlace"><b>Tournament Place</b></label>
            <input type="text" placeholder="Enter Tournament Place" name="tournamentPlace" required>
            <label for="tournamentPrize"><b>Tournament Prize</b></label>
            <input type="text" placeholder="Enter Tournament Prize" name="tournamentPrize" required>
            <div class="clearfix">
              <button type="submit" class="signupbtn btnAdminPanelInsert" name="insert">Insert</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div id="menu2" class="tab-pane fade">
      <form class="SignIn formAdminPanel " action="signin.php" method="POST" style="border:1px solid #ccc">
        <div class="txtcolor">
          <div class="container containerAdminPanel">
            <h1>Update Tournament</h1>
            <h3>Current Tournament:</h3><p> <?=$NameCurrent;?>, <?=$PlaceCurrent;?>, <?=$PrizeCurrent;?></p>
            <hr>
            <div class="dropdown" style="width: 50%;margin-left: 24%;">
              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Select a Tournament
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php
                $stmt = $pdo->query("SELECT Name,Place,Prize FROM Tournament WHERE Active !='1'");
                $p = $stmt->fetchAll();
                foreach($p as $row){?>
                  <li><a href="#"><?=$row['Name'];?> ,<?=$row['Place'];?>, <?=$row['Prize'];?></a></li>
                <?php }?>
              </ul>
            </div>
            <div class="clearfix">
              <button type="submit" class="signupbtn btnAdminPanel" name="login">Update</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
  function changeRole(sel){
    var role = sel.options[sel.selectedIndex].text;
    var username = $(sel).data('userid');
    if (confirm('Are you sure you want to save '+username+' as an '+role+'?')) {
      window.location.href="adminPanel.php?role="+role+"&username="+username;
    } else {
      location.reload();
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
</body>
</html>
