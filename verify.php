<?php
session_start();
require 'connect.php';
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
        // Verify data
        $email = ($_GET['email']); // Set email variable
        $hash = ($_GET['hash']); // Set hash variable
    }
    $q = $pdo->query("SELECT count(*) FROM `users`.`users` WHERE email= '".$email."' AND hash='".$hash."' AND verified = '0'");
    $result = $con->prepare($q); 
    $result->execute(); 
    $rows_found = $result->fetchColumn();
    
    if ($rows_found > 0){
        
        $sql = "UPDATE `users`.`users` SET verified=? WHERE email=? AND hash=?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute(['1', $email,$hash]);
        echo '<script>location="signinSuccess.php"</script>';
    }
    else
        echo 'Your account is already verified!';
    

?>
