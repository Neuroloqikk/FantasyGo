<?php
//<?=(($selected== $category['id'])?' selected':''); 
require 'connect.php';
$sectionid=$_POST['sectionID1'];
$stmt = $pdo->query("select price from players where name = '$sectionid'");
$p = $stmt->fetch();
$price = implode($p);
ob_start();
?>
  <label for="currentprice"><b>Current Price</b></label>
  <input class ="txtcolorinput" type="text" placeholder="<?=$price?>€" name="currentprice" disabled>
  <label for="newprice"><b>New Price</b></label>
  <input class ="txtcolorinput" type="text" placeholder="Introduce a new price" name="newprice">




<?php echo ob_get_clean(); 
?>
