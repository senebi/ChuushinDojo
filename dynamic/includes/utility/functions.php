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
					}
				}
			}
		}
	}
?>