<?php
	/*
		Xiaowen Feng
		CSE 154 
		Section AA
		May 6, 2015
		Assignment 4
		a file with shared php code by multiple files
		including HTML headings and footer
	*/

	# check if session is alreay set
	if (!isset($_SESSION)) {
		session_start();
	}

	# the top on all the displayed pages
	function heading() {
	?>
		<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8" />
				<title>Remember the Cow</title>
				<link href="https://webster.cs.washington.edu/css/cow-provided.css" 
				 type="text/css" rel="stylesheet" />
				<link href="cow.css" type="text/css" rel="stylesheet" />
				<link href="https://webster.cs.washington.edu/images/todolist/favicon.ico" 
				 type="image/ico" rel="shortcut icon" />
			</head>

			<body>
				<div class="headfoot">
					<h1>
						<img src="https://webster.cs.washington.edu/images/todolist/logo.gif" alt="logo" />
						Remember<br />the Cow
					</h1>
				</div>
	<?php
	}

	# the end of all the displayed pages
	function footer() {
	?>
				<div class="headfoot">
					<p>
						&quot;Remember The Cow is nice, but it's a total copy of another site.&quot; - PCWorld<br />
						All pages and content &copy; Copyright CowPie Inc.
					</p>

					<div id="w3c">
						<a href="https://webster.cs.washington.edu/validate-html.php">
							<img src="https://webster.cs.washington.edu/images/w3c-html.png" alt="Valid HTML" /></a>
						<a href="https://webster.cs.washington.edu/validate-css.php">
							<img src="https://webster.cs.washington.edu/images/w3c-css.png" alt="Valid CSS" /></a>
					</div>
				</div>
			</body>
		</html>
	<?php
	}

	# exit to the given page, if a flash message is passed, display it
	function redirect($url, $flash_text = NULL) {
		if ($flash_text) {
			$_SESSION["flash"] = $flash_text;
		}
		header("Location: $url.php");
		die();
	}

	# check whether user is logged in 
	function ensure_logged_in() {
		if(!isset($_SESSION["user"])) {
			redirect("start", "You must log in before you can view the page.");
		}
	}
?>