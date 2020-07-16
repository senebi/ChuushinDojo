<?php
  if(!isset($_POST["dojoSelect-submit"]) || !isset($_POST["dojoId"])){
	header("location: index.php?pid=5");
	exit;
  }

  $dojoId=sanitize($_POST["dojoId"]);
  $user=new User();
  
  echo "<p>Szerkeszthető felhasználók:</p>";

  $actMembers=$user->getMembers(1,$dojoId);
  if($actMembers) echo $actMembers;
  else echo "Nincsenek tagok.";
?>
  <a href="javascript:history.back()">Vissza</a>