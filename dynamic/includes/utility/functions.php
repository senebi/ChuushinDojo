<?php
	/*
	 * Sanitizes the given $input string.
	 */
	function sanitize($input){
		global $conn;
		return $conn->real_escape_string(htmlspecialchars(stripslashes(trim($input))));
	}
	
	/*
	 * Clear all error messages
	 */
	function clearErrors(){
		foreach($_SESSION as $name => $val){
			if(isset($_SESSION[$name]["err_msg"]))
				unset($_SESSION[$name]);
			else{
				if(is_array($_SESSION[$name])){
					$i=0;
					while(isset($_SESSION[$name][$i]["err_msg"])){
						unset($_SESSION[$name][$i]["err_msg"]);
						$i++;
					}
				}
			}
		}
	}
	
	function setMsg($msg){
      $_SESSION["msg"]=$msg;
    }
    
    function getMsg(){
      if(isset($_SESSION["msg"])) echo $_SESSION["msg"];
    }
    
    function clearMsg(){
      unset($_SESSION["msg"]);
    }
	
	function getSpecificName($id=null){
		if(is_null($id)){
			setMsg("<div class='error'>A felhasználó azonosítóját kötelező megadni!</div>");
			return false;
		}
		else{
			global $conn;
			$sql="select nev from ".USERS." where id=".$id;
			$res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
			if($res->num_rows)
				$name=$res->fetch_assoc()["nev"];
			else $name="Nincs ilyen felhasználó.";
			return $name;
		}
	}
	
	function getSpecificMail($id=null){
		if(is_null($id)){
			setMsg("<div class='error'>A felhasználó azonosítóját kötelező megadni!</div>");
			return false;
		}
		else{
			global $conn;
			$sql="select email from ".USERS." where id=".$id;
			$res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
			if($res->num_rows)
				$mail=$res->fetch_assoc()["email"];
			else $mail="Nincs ilyen felhasználó.";
			return $mail;
		}
	}
?>