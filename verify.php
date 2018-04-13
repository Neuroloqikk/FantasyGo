<?php
session_start();
require 'connect.php';
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
        // Verify data
        $email = mysql_escape_string($_GET['email']); // Set email variable
        $hash = mysql_escape_string($_GET['hash']); // Set hash variable
    }
    $q = $pdo->query("SELECT email,hash FROM `users`.`users` WHERE email= '".$email."' AND hash='".$hash."' AND verified = '0'");
    $t = $q->fetch();
    $rows_found = $t->rowCount();
    /*
    if $rows_found > 0{
        
        $sql = "UPDATE `users`.`users` SET verified=? WHERE email=? AND hash=?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute(['1', $email,$hash]);
        echo '<script>location="signinSuccess.php"</script>';
    }
    else
        echo 'Your account is already verified!';
    
*/
?>
