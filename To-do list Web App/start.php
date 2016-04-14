<!--
Xiaowen Feng
CSE 154 
Section AA
May 6, 2015
Assignment 4

The initial page of the site with a form for user to log in or register
Extra feature #1
-->
<?php
	include ("common.php");

	# set a cookie that store the last date user logged in
	# from a particular computer 
	$expire_time = time() + 60* 60 * 24 * 7;
	date_default_timezone_set('America/Los_Angeles');
	$last_login = date("D y M d, g:i:s a");
	setcookie("login", "$last_login", $expire_time);	
	
	heading();
	?>
		<div id="main">
			<p>
				The <span>best</span> way to manage your tasks. <br />
				Never forget the cow (or anything else) again!
			</p>

			<p>
				Log in now to manage your to-do list. <br />
				If you do not have an account, one will be created for you.
			</p>

			<form id="loginform" action="login.php" method="post">
				<?php
					# display a flash error message if a flash session exists
					if (isset($_SESSION["flash"])) {
						?>
						<div id ="error"> <?= $_SESSION["flash"] ?> </div>
						<?php
						unset($_SESSION["flash"]);
					}
				?>
				<fieldset>
					<div>
						<strong>User Name:</strong>
						<input name="name" type="text" size="8" autofocus="autofocus" />
					</div>
					<div>
						<strong>Password:</strong>
						<input name="password" type="password" size="8" />
					</div>
					<div><input type="submit" value="Log in" /></div>
				</fieldset>
			</form>

			<p>	
			<?php
				# display the last logged in time, if one has used 
			    # a particular computer to log in before.
				if (isset($_COOKIE["login"])) {
				?>
					<em>(last login from this computer was <?= $_COOKIE["login"] ?>)</em>
				<?php
				}
				?>
			</p>
		</div>

		<?php footer(); ?>