
<!DOCTYPE html>
<html>
	<!--
	Xiaowen Feng
	CSE 154 
	Section AA
	April 22, 2015
	Assignment 3

	This is a movie review (Rancid Tomatoes) webpage for a variety of movies.
	Extra features: page title, error message, and meta tags.
	-->

	<?php
		$movie = $_GET["film"];
		# searches for all review files for the movie
		$files = glob("{$movie}/review*.txt"); 
		$url = "https://webster.cs.washington.edu/images/";
		list($title, $year, $rating)= file("$movie/info.txt");
		$year = trim($year);

		# if the film parameter is empty or the file of flim doesn't
		# exist, redirect the page to a error message
		if (!isset($movie) || !file_exists($movie)) {
			header("Location: error.html");
		}
	?>

	<head>
		<title>Rancid Tomatoes - <?= $title ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="A website for film overview and reviews" />
		<meta name="keywords" content="movie, review, rating, critic" />
		<link href="movie.css" type="text/css" rel="stylesheet" />
		<link href="<?= $url ?>rotten.gif" type ="image/gif" rel="icon" />
	</head>

	<body>
		<div class="banner">
			<img src="<?= $url ?>rancidbanner.png" alt="Rancid Tomatoes" />
		</div>

		<h1>
			<?= "$title($year)" ?>	
		</h1>

		<div id="main">
			<div class="rate">
				<?php rating($rating, $url); ?>	
			</div>
			<div id="overview">
				<div>
					<img src="<?= $movie ?>/overview.png" alt="general overview" />
				</div>
				<dl>
					<?php overview($movie); ?>
				</dl>
			</div>
			
			<div id="reviews">
				<div class="column">
					<?php 
						# outputs first half of the reviews in the left column
						# if a movie has an odd number of reviews, an extra one goes
						# to the left column
						for ($i = 0; $i < ceil(count($files) / 2); $i++) {
							critics($movie, $files, $i, $url);
						} 
					?>
				</div>

				<div class="column">
					<?php
						# outputs the rest of the reviews in the right column
						for ($i = ceil(count($files) / 2); $i < count($files); $i++) {
							critics($movie, $files, $i, $url);
						}
					?>
				</div>
			</div>

			<p id="footer">
				(1-<?= count($files) ?>) of <?= count($files) ?>
			</p>

			<div class="rate">
				<?php rating($rating, $url); ?>	
			</div>
		</div>

		<div id="validators">
			<a href="https://webster.cs.washington.edu/validate-html.php">
				<img src="<?= $url ?>w3c-html.png" alt="Valid HTML5" /></a><br />
			<a href="https://webster.cs.washington.edu/validate-css.php">
				<img src="<?= $url ?>w3c-css.png" alt="Valid CSS" /></a>
		</div>

		<div class="banner">
			<img src="<?= $url ?>rancidbanner.png" alt="Rancid Tomatoes" />
		</div>
	</body>
</html>

<?php
	# prints a set of critic, comment and publication with 
	# icons of persons, good/bad review based on the passed in
	# movie, its reviews files and the number of reviews.
	function critics($movie, $files, $i, $url) {
		list($comment, $icon, $reviewer, $publication) = file($files[$i]);
		$comment = trim($comment);
		$icon = trim(strtolower($icon));
	?>
		<p class="quote">
			<img src="<?= $url.$icon ?>.gif" alt="<?= $icon ?>"/>
			<q><?= $comment ?></q>
		</p>
		<p class="reviewer">
			<img src="<?= $url ?>critic.gif" alt="Critic" />
			<?= $reviewer ?><br />
			<span class="publication"><?= $publication ?></span>
		</p>
	<?php	
	}
	
	# prints each title and its value read from 
	# the file of passed in movie in a list element
	function overview($movie) {
		$lines = file("$movie/overview.txt");
		foreach ($lines as $line) {
			list($title, $value) = explode(":", $line);
	?>
			<dt><?= $title ?></dt>
			<dd><?= $value ?></dd>
	<?php
		}
	}

	# outputs a image based on the the passed in rating
	# and prints the rating percentage 
	function rating($rating, $url) {
		if($rating < 60) {
	?>
			<img src="<?= $url ?>rottenlarge.png" alt="Rotten" />
	<?php
		} else {
	?>
			<img src="<?= $url ?>freshlarge.png" alt="Fresh" />
	<?php 
		}
	?>
		<?= "$rating%" ?>
	<?php
	}
?>