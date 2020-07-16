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
	$addDojoStatus=$dojo->addDojo($n, $c);
	if($addDojoStatus==3)
	  setMsg("<div class='success'>A dojo sikeresen hozzáadva.</div>");
	else if($addDojoStatus==1) setMsg("A dojo már létezik!");
  }
  if(isset($_POST["mod-dojo-submit"])){
	if($dojo->modDojos())
	  setMsg("<div class='success'>A dojók sikeresen módosítva.</div>");
  }
?>
<div class="container">
  <div class="row justify-content-md-center">
	<div class="col-md-2"></div>
	<div class="col-12 col-md-8">
	  <fieldset class="border p-2">
		<legend>Új dojo létrehozása</legend>
		<form method="post" action="?pid=<?php echo $page->getPid(); ?>">
		  <div class="form-group row justify-content-center">
			<label for="dojoName" class="col-sm-1 col-form-label">Név</label>
			<div class="col-sm-8">
			  <input type="text" class="form-control form-control-sm" name="dojoName" id="dojoName" />
			</div>
			<?php if(isset($_SESSION["dojoName"]["err_msg"])) echo $_SESSION["dojoName"]["err_msg"]; ?>
		  </div>
		  <div class="form-group row justify-content-center">
			<label for="dojoCity" class="col-sm-1 col-form-label">Város</label>
			<div class="col-sm-8">
			  <input type="text" class="form-control form-control-sm" name="dojoCity" id="dojoCity" />
			</div>
			<?php if(isset($_SESSION["dojoCity"]["err_msg"])) echo $_SESSION["dojoCity"]["err_msg"]; ?>
		  </div>
		  <div class="form-group row justify-content-center">
			<button type="submit" name="add-dojo-submit" class="btn btn-primary" value="Hozzáadás">Hozzáadás</button>
		  </div>
		  <?php
			if(isset($_POST["add-dojo-submit"])){
			  getMsg();
			  clearMsg();
			}
		  ?>
		</form>
	  </fieldset>
	</div>
	<div class="col-md-2"></div>
  </div>
  <div class="row justify-content-md-center">
	<div class="col-2"></div>
	<div class="col-12 col-md-8">
	  <fieldset class="border p-2">
		<legend>Tárolt dojók</legend>
		<form method="post" action="?pid=<?php echo $page->getPid(); ?>">
		  <?php
			echo $dojo->getDojos("table",1);
		  ?>
		  <div class="form-group row justify-content-center">
			<button type="submit" name="mod-dojo-submit" class="btn btn-primary" value="Módosítás">Módosítás</button>
		  </div>
		  <?php
			if(isset($_POST["mod-dojo-submit"])){
			  getMsg();
			  clearMsg();
			}
			clearErrors();
		  ?>
		</form>
	  </fieldset>
	</div>
	<div class="col-2"></div>
  </div>
</div>