<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
	if(!isset($_SESSION["reg_success"])){
		header("location: index.php");
		exit;
	}
?>
<h3>Sikeres regisztráció!</h3>
<p>Addig nem tudsz bejelentkezni, amíg egy admin nem aktiválja a fiókod.</p>