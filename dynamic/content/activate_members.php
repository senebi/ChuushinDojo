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
  $names=$_POST["names"];
  echo "<h2>Aktiválandók:</h2>";
  for($i=0; $i<$userCount; $i++){
	echo "<div>".$names[$i]." (".$users[$i].") mint ".$ranks[$i]."</div>";
  }
?>