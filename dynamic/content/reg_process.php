<?php
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
			if($user->register($validated)){
				if($user->notifyByEmail($validated,"reg"))
					$_SESSION["reg_state"]="success";
				else $_SESSION["reg_state"]="fail_notify";
			}
			else $_SESSION["reg_state"]="fail_reg";
			header("location: ../index.php?redirect=reg_state");
		}
		else
			header("location: ../index.php?pid=".$_POST["reg_id"]);
	}
	else header("location: ../index.php?pid=404");
?>