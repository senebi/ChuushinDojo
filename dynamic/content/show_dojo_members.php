<?php
  if((!isset($_POST["dojoSelect-submit"]) || !isset($_POST["dojoId"])) && !isset($_GET["dojoId"])){
	header("location: index.php?pid=5");
	exit;
  }

  $dojoId=(isset($_POST["dojoId"]) ? sanitize($_POST["dojoId"]) : sanitize($_GET["dojoId"]));
  $user=new User();
  
  echo "<p>Szerkeszthető felhasználók:</p>";

  $actMembers=$user->getMembers(1,$dojoId);
  if($actMembers) echo $actMembers;
  else echo "Nincsenek tagok.";
?>
<a href="<?php echo (isset($_POST["from"])) ? "index.php?".$_POST["from"] : "index.php?pid=".$page->getPid(); ?>">Vissza</a>