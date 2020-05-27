<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
?>
<p>
  Állítsd be a paramétereket az edzés statisztika megtekintéséhez.
</p>