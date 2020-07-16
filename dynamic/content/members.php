<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
  
  $dojo=new Dojo();
?>
<fieldset class="border p-2">
  <legend class="w-auto">Inaktív tagok</legend>
  <?php
	$inactMembers=$user->getMembers(0);
	if($inactMembers) echo $inactMembers;
	else echo "Nincsenek inaktív tagok.";
  ?>
</fieldset>
<fieldset class="border p-2">
  <legend class="w-auto">Aktív tagok</legend>
  <?php
	if(isset($_GET["sub1"])){
	  $subPage=$_GET["sub1"];
	  $allowedSubPages=array("show_member_details", "show_dojo_members");
	  if(in_array($subPage,$allowedSubPages))
		include_once($contentDir."/".$subPage.".php");
	}
	else{
		if(Dojo::getActiveDojoCount()>1 && $user->getRank()=="admin"){
		  ?>
		  <p>Válassz dojót.</p>
		  <form method="post" action="?pid=<?php echo $page->getPid(); ?>&sub1=show_dojo_members">
		  <?php
			echo $dojo->getDojos("radio",0);
		  ?>
		  <p><button type="submit" name="dojoSelect-submit" class="btn btn-primary mt-2" value="Tagok megjelenítése">Tagok megjelenítése</button></p>
		  </form>
		  <?php
		}
		else{
		  $actMembers=$user->getMembers(1);
		  if($actMembers) echo $actMembers;
		  else echo "Nincsenek tagok.";
		}
	}
  ?>
</fieldset>