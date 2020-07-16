<?php
  session_start();
  include_once("../includes/config/dbnames.inc.php");
  include_once("../includes/config/connect.inc.php");
  include_once("../includes/config/vars.php");
  include_once("../includes/utility/functions.php");
  include_once("../includes/classes/user.class.php");
  
  if(!isset($_POST["editSelected-submit"])){
	header("location: ../index.php?pid=5");
	exit;
  }
  
  $userCount=count($_POST["editUser"]);
  $users=$_POST["editUser"];
  if(isset($_POST["rank"]))
	$ranks=$_POST["rank"];
  $startDates=$_POST["startDate"];
  $beltDegrees=$_POST["beltDegree"];
  
  $user=new User();
  
  for($i=0; $i<$userCount; $i++){
	$id=sanitize($users[$i]);
	$startDate=htmlentities(sanitize($startDates[$i]));
	$startDate=date("Y-m-d", strtotime($startDate));
	$beltDegree=sanitize($beltDegrees[$i]);
	$name=getSpecificName($id);

	$sql="update ".USERS." set beiratkozas_datum=".(($startDate=="") ? "null" : "'".$startDate."'").", ovfokozat=".(($beltDegree=="none") ? "null" : "'".$beltDegree."'");
	if(isset($ranks))
	  $sql.=", jog='".sanitize($ranks[$i])."'";
	$sql.=" where id=".$id;
	$res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
  }
  setMsg("<div class='success'>Sikeres módosítás.</div>");
  header("location: ../index.php?pid=5&sub1=show_member_details&users=".implode(",",$users));
?>