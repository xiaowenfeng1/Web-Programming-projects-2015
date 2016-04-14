<?php
	/*
		Xiaowen Feng
		CSE 154 
		Section AA
		May 6, 2015
		Assignment 4
		
		the page shows the user's to-do list and 
		allows them add/delete items from it.
	*/
	include ("common.php");
	ensure_logged_in();
	$name = $_SESSION["user"];

	function todolist($name) {
		$lists = file("todo_$name.txt");

		for ($i = 0; $i < count($lists); $i++) {
		?>
		<li>
			<?= htmlspecialchars($lists[$i]) ?>
			<form action="submit.php" method="post">
				<input type="hidden" name="action" value="delete" />
				<input type="hidden" name="index" value="<?= $i ?>" />
				<input type="submit" value="Delete" />
			</form>
		</li>
		<?php	
		}
	}
	heading(); 
	?>
		<div id="main">
			<h2><?= $name ?>'s To-Do List</h2>

			<ul id="todolist">
				<?php todolist($name); ?>
				
				<li>
				<form action="submit.php" method="post">
					<input type="hidden" name="action" value="add" />
					<input name="item" type="text" size="25" autofocus="autofocus" />
					<input type="submit" value="Add" />
				</form>
				</li>
			</ul>

			<div>
				<a href="logout.php"><strong>Log Out</strong></a>
				<em>(logged in since <?= $_COOKIE["login"] ?>)</em>
			</div>
		</div>

<?php footer(); ?>
