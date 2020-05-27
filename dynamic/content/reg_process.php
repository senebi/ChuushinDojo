<?php
if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
	session_start();
	include_once("../includes/config/dbnames.inc.php");
	include_once("../includes/config/connect.inc.php");
	include_once("../includes/config/vars.php");
	include_once("../includes/utility/functions.php");
	include_once("../includes/classes/user.class.php");
	
	if(isset($_POST["reg_id"])){
		$user=new User();
		$validated=$user->validateRegister($_POST);
		if($validated){
			$user->register($validated);
		}
		else{
			header("location: ../index.php?pid=".$_POST["reg_id"]);
		}
	}
	else header("location: ../index.php?pid=404");
?>