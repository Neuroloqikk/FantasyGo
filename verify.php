<?php
session_start();
require 'connect.php';

if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])) {
  $email = ($_GET['email']);
  $hash = ($_GET['hash']);
  $sql = "SELECT count(*) FROM `users`.`users` WHERE email= '" . $email . "' AND hash='" . $hash . "' AND verified = '0'";
  $res = $pdo->query($sql);
  if ($res->fetchColumn() > 0) {
    $sql = "UPDATE `users`.`users` SET verified=? WHERE email=? AND hash=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['1', $email, $hash]);
    echo '<script>location="signinSucess.php"</script>';
  }
  else echo '<script>location="index.php"</script>';
}

?>
