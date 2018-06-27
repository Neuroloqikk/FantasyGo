<?php
//<?=(($selected== $category['id'])?' selected':'');
require 'connect.php';
$sectionid=$_POST['sectionID'];
$selected = $_POST['selected'];
$stmt = $pdo->query("select name from players where team = '$sectionid'");
$p = $stmt->fetchAll();
ob_start();
?>
<option value="">Pick one</option>
<?php foreach($p as $row){ ?>
  <option value="<?=$row['name'] ?>"><?=$row['name'] ?></option>
<?php } ?>
<?php echo ob_get_clean();
?>
