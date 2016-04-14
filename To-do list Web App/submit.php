<?php
	/*
		Xiaowen Feng
		CSE 154 
		Section AA
		May 6, 2015
		Assignment 4
		modify user's to-do list on file when he/she submits requests 
		to add/delete items on todolist.php.
	*/
	include("common.php");
	ensure_logged_in();
	$name = $_SESSION["user"];
	$lists = file("todo_$name.txt");

	if ($_POST["action"] == "add") {
		file_put_contents("todo_$name.txt", "$_POST[item]\n", FILE_APPEND);

	} else if ($_POST["action"] == "delete") {
		# index is not passed or out of bound
		if ($_POST["index"] >= count($lists) || !isset($_POST["index"])) {
			redirect("todolist", "Invilad parameter passed"); 
		}
		# delete item on file
		unset($lists[$_POST["index"]]);
		file_put_contents("todo_$name.txt", $lists);
	} else {
		redirect("todolist", "Invilad parameter passed");
	}
	redirect("todolist");
	
?>
