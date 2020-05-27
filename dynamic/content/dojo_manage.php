<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
  $dojo=new Dojo();
  
  if(isset($_POST["add-dojo-submit"])){
	$n=sanitize($_POST["dojoName"]);
	$c=sanitize($_POST["dojoCity"]);
	$dojo->addDojo($n, $c);
  }
  if(isset($_POST["mod-dojo-submit"])){
	$dojo->modDojos();
  }
?>
<fieldset>
  <legend>Új dojo létrehozása</legend>
  <form method="post" action="?pid=<?php echo $page->getPid(); ?>">
	Név: <input type="text" name="dojoName" /><br />
	<?php if(isset($_SESSION["dojoName"]["err_msg"])) echo $_SESSION["dojoName"]["err_msg"]; ?>
	Város: <input type="text" name="dojoCity" /><br />
	<?php if(isset($_SESSION["dojoCity"]["err_msg"])) echo $_SESSION["dojoCity"]["err_msg"]; ?>
	<p><input type="submit" name="add-dojo-submit" value="Hozzáadás" /></p>
	<?php
	  if(isset($_POST["add-dojo-submit"])){
		$user->getMsg();
		$user->clearMsg();
	  }
	?>
  </form>
</fieldset>

<p>
  <fieldset>
	<legend>Tárolt dojók</legend>
	<form method="post" action="?pid=<?php echo $page->getPid(); ?>">
	  <?php
		echo $dojo->getDojos("table",1);
	  ?>
	  <p>
		<input type="submit" name="mod-dojo-submit" value="Módosítás" />
	  </p>
	  <?php
		if(isset($_POST["mod-dojo-submit"])){
		  $user->getMsg();
		  $user->clearMsg();
		}
		clearErrors();
	  ?>
	</form>
  </fieldset>
</p>