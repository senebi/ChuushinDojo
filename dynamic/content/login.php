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
<form method="post" action="?pid=3">
  <table>
	<tr><td>Felhasználónév: </td><td><input type="text" name="user" /></td></tr>
	<tr><td>Jelszó: </td><td><input type="password" name="pass" /></td></tr>
	<tr><td colspan="2"><input type="submit" name="submit-login" value="Bejelentkezés" /></td></tr>
  </table>
</form>
<a href="forgotten-password">Elfelejtett jelszó</a>
<?php
  getMsg();
  clearMsg();
?>