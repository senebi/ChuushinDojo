<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
?>
<p>
  A bejelentkezett felhasználó adatai:
</p>
<?php
  $userData=$user->getData();
  //var_dump($userData);
  //exit;
?>
<table>
  <tr><td>Felhasználónév: </td>
	<td><?php echo $userData["felhasznalonev"]; ?></td>
  </tr>
  <tr><td>Név: </td>
	<td><?php echo $userData["nev"]; ?></td>
  </tr>
  <tr><td>E-mail cím: </td>
	<td><?php echo $userData["email"]; ?></td>
  </tr>
  <tr><td>Születési dátum: </td>
	<td><?php echo $userData["szuletesi_datum"]; ?></td>
  </tr>
  <tr><td>Dojo: </td>
	<td><?php echo $userData["dojoNev"]." (".$userData["varos"].")"; ?></td>
  </tr>
  <tr><td>Beiratkozási dátum: </td>
	<td><?php echo ($userData["beiratkozas_datum"]!="") ? $userData["beiratkozas_datum"] : "nincs adat"; ?></td>
  </tr>
  <tr><td>Regisztráció dátuma: </td>
	<td><?php echo ($userData["reg_datum"]!="") ? $userData["reg_datum"] : "nincs adat"; ?></td>
  </tr>
  <tr><td>Övfokozat: </td>
	<td><?php echo ($userData["ovfokozat"]!="") ? $userData["ovfokozat"] : "nincs"; ?></td>
  </tr>
  <tr><td>Rang: </td>
	<td><?php echo $userData["jog"]; ?></td>
  </tr>
</table>