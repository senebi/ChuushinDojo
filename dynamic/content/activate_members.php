<?php
  session_start();
  include_once("../includes/config/dbnames.inc.php");
  include_once("../includes/config/connect.inc.php");
  include_once("../includes/config/vars.php");
  include_once("../includes/utility/functions.php");
  include_once("../includes/classes/user.class.php");
  
  if(!isset($_POST["activate-submit"])){
	header("location: ../index.php?pid=5");
	exit;
  }
  
  if(!isset($_POST["activateUser"])){
	setMsg("<div class='error'>Legalább egy jelölőnégyzetet kötelező kijelölni az aktiváláshoz!</div>");
	header("location: ../index.php?pid=5");
	exit;
  }
  
  $userCount=count($_POST["activateUser"]);
  $users=$_POST["activateUser"];
  $ranks=$_POST["rank"];
  $user=new User();
  
  echo "<h2>Aktiválandók:</h2>";
  for($i=0; $i<$userCount; $i++){
	$id=sanitize($users[$i]);
	$rank=sanitize($ranks[$i]);
	$name=getSpecificName($id);
	echo "<div>".$name." (".$id.") mint ".$rank."</div>";
	$sql="update ".USERS." set aktiv=1, jog='".$rank."' where id=".$id;
	$res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
	$userData=array("id" => $id, "nev" => $name, "email" => getSpecificMail($id));
	
	if($user->notifyByEmail($userData,"approve")){
	  setMsg("<div class='success'>Sikeres aktiválás.</div>");
	  header("location: ../index.php?pid=5");
	}
  }
?>