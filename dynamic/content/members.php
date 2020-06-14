<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
?>
<fieldset>
  <legend>Inaktív tagok</legend>
  <?php
	$inactMembers=$user->getMembers(0);
	if($inactMembers) echo $inactMembers;
	else echo "Nincsenek inaktív tagok.";
  ?>
</fieldset>

<fieldset>
  <legend>Aktív tagok</legend>
  <?php
	$actMembers=$user->getMembers(1);
	if($actMembers) echo $actMembers;
	else echo "Nincsenek tagok.";
  ?>
</fieldset>