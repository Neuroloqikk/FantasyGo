<?php
session_start();
require 'connect.php';

if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
  $email = ($_GET['email']);
  $hash = ($_GET['hash']);
  $sql = "SELECT recover FROM `users`.`users` WHERE email= '" . $email . "' AND recover='" . $hash . "'";
  echo '<script type="text/javascript">alert("'.$email.'");</script>';
  echo '<script type="text/javascript">alert("'.$hash.'");</script>';
  $res = $pdo->query($sql);
  //if ($res->fetchColumn() > 0) {
    echo '<script>location="recoverPasswordForm.php?email='.$email.'&hash='.$hash.'"</script>';
  //}
  //else echo '<script>location="index.php"</script>';
}

function displayAlert($text,$type)
{
   echo "<div class=\"col-xs-10 col-xs-offset-1 col-xs-offset-right-1 alert alert-".$type."\" role=\"alert\">
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\" style=\"float: right;\">&times;</span></button>
            <p>" . $text . "</p>
          </div>";
}
?>