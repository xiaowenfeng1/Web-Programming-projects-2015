<?php
	/*
		Xiaowen Feng
		CSE 154 
		Section AA
		May 6, 2015
		Assignment 4
		this page shows a login form for the user to log out.
	*/
	include("common.php");
	ensure_logged_in();
	session_destroy();
	session_regenerate_id(TRUE);
	session_start();
	redirect("start", "Logout successful");
?>