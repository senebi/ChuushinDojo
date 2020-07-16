<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
  
  if($user->isLoggedIn()){
    $page->redirect(1, $user->getPermissions());
    header("location: ".$_SERVER["PHP_SELF"]."?pid=1");
    exit();
  }
?>
<p>
  A tagoknak szánt tartalom bejelentkezés után lesz elérhető.
  Jelentkezz be az alábbi űrlapon keresztül.
</p>
<form method="post" action="?pid=3" class="p-2">
  <div class="form-group row justify-content-center">
    <label for="user" class="col-sm-2 col-form-label">Felhasználónév</label>
	<div class="col-sm-4">
	  <input type="text" class="form-control form-control-sm" id="user" name="user" placeholder="Felhasználónév">
	</div>
  </div>
  <div class="form-group row justify-content-center">
    <label for="pass" class="col-sm-2 col-form-label">Jelszó</label>
	<div class="col-sm-4">
	  <input type="password" name="pass" class="form-control form-control-sm" id="pass" placeholder="Jelszó">
	</div>
  </div>
  <div class="form-group row justify-content-center">
	<div class="form-check pl-5">
	  <input type="checkbox" aria-describedby="saveCredentialsHelp" class="form-check-input" id="saveCredentials">
	  <label class="form-check-label" for="saveCredentials">Emlékezz rám</label>
	  <small id="saveCredentialsHelp" class="form-text text-muted">Ha bejelölöd, a bejelentkezési adataid mentésre kerülnek, így nem kell mindig beírnod őket.</small>
	</div>
  </div>
  <div class="form-group row justify-content-center">
	<button type="submit" name="submit-login" class="btn btn-primary">Bejelentkezés</button>
  </div>
  
</form>
<a href="forgotten-password">Elfelejtett jelszó</a>
<?php
  getMsg();
  clearMsg();
?>