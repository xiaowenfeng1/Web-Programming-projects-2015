<?php
	/*
		Xiaowen Feng
		CSE 154 
		Section AA
		May 6, 2015
		Assignment 4
		
		the login page that deals with user login proccess.
	*/

	include("common.php");
	
	if (isset($_POST["name"]) && isset($_POST["password"])) {
		$name = $_POST["name"];
		$password = $_POST["password"];

		if (user_login($name, $password)) {
			$_SESSION["user"] = $name;    # start session, remember user info
			# create/open a todo list file for the user 
			$lists = fopen("todo_$name.txt", w); 
			redirect("todolist");
		} 
	}

	# returns true if given password is correct password for 
	# this existing user name or a new user creates user name and password
	# that validate the rules for them.
	function user_login($name, $password) {
		$lines = file("users.txt", FILE_IGNORE_NEW_LINES);
		foreach ($lines as $line) {
			list($user_name, $user_password) = explode(":", $line);
			if ($name == $user_name) {
			 	if ($password == trim($user_password)) {
					return TRUE;
				}
				redirect("start", "Incorrect password.");
			} 
		}
		# check if the new user name and password validate.
		if (preg_match("/^[a-z][a-z0-9]{3,8}$/", $name)) {
			if (preg_match("/^\d.{4,10}[^a-zA-Z\d]$/", $password)) {
				file_put_contents("users.txt", "$name:$password\n", FILE_APPEND);
				return TRUE;
			} else {
				redirect("start", "Invalid password. Must consist of 6-12
						 characters, begin with a number, and end with a 
						 character that is not a letter or number.");
			}
		} else {
			redirect("start", "Invalid user name. Must begin with a letter 
				     and consist of 3-8 letters/numbers");
		}
		
	}
?>