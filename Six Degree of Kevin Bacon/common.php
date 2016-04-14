<?php 
/* 
Xiaowen Feng
CSE 154 
Section AA
May 13, 2015
Assignment 5

The common codes used among pages for the MyMDb site.
*/

$db = new PDO("mysql:dbname=imdb;host=localhost;charset=utf8", "xfeng1", "2lONCt9wlB");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$first_name = $_GET["firstname"];
$last_name = $_GET["lastname"];
$full_name = "$first_name $last_name";

# the top half of the common display
function top() {
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>My Movie Database (MyMDb)</title>
			<meta charset="utf-8" />
			<link href="https://webster.cs.washington.edu/images/kevinbacon/favicon.png" 
			type="image/png" rel="shortcut icon" />

			<!-- Link to your CSS file that you should edit -->
			<link href="bacon.css" type="text/css" rel="stylesheet" />
		</head>
		<body>
			<div id="frame">
				<div id="banner">
					<a href="mymdb.php">
					<img src="https://webster.cs.washington.edu/images/kevinbacon/mymdb.png" alt="banner logo" /></a>
					My Movie Database
				</div>
				<div id="main">
	<?php
}

# outputs a table of movies found with the given query
# indicates whether if it's a table with all films or with K.B.
function movie_table($rows, $full_name, $moive_with_kevin) {
					?>
					<h1>Reuslts for <?= $full_name ?></h1> 
					<table>
					<?php
					if ($moive_with_kevin) { # if it's a table for movies with K.B.
						?>
						<caption>Films with <?= $full_name?> and Kevin Bacon</caption>
						<?php
					} else {
						?>
						<caption>All Films</caption>
						<?php
					}
					?>
						<tr><th>&num;</th><th>Title</th><th>Year</th></tr>
						<?php
						foreach ($rows as $row) {
							$count++; # count the number of the movies
							?>
							<tr><td><?= $count ?></td><td><?= $row["name"] ?></td><td><?= $row["year"] ?></td></tr>
							<?php
						}
						?>
					</table>
<?php
}

# two search bars, one for all movies, one for movies with Kevin Bacon
function search_bar() {
				?>
				<!-- form to search for every movie by a given actor -->
					<form action="search-all.php" method="get">
						<fieldset>
							<legend>All movies</legend>
							<div>
								<input name="firstname" type="text" size="12" placeholder="first name" autofocus="autofocus" /> 
								<input name="lastname" type="text" size="12" placeholder="last name" /> 
								<input type="submit" value="go" />
							</div>
						</fieldset>
					</form>

					<!-- form to search for movies where a given actor was with Kevin Bacon -->
					<form action="search-kevin.php" method="get">
						<fieldset>
							<legend>Movies with Kevin Bacon</legend>
							<div>
								<input name="firstname" type="text" size="12" placeholder="first name" /> 
								<input name="lastname" type="text" size="12" placeholder="last name" /> 
								<input type="submit" value="go" />
							</div>
						</fieldset>
					</form>
				</div> <!-- end of #main div -->
				<?php
}

# the bottom half of the common display, including the search bars
function bottom() {
	search_bar();
?>
				<div id="w3c">
					<a href="https://webster.cs.washington.edu/validate-html.php">
						<img src="https://webster.cs.washington.edu/images/w3c-html.png" alt="Valid HTML5" /></a>
					<a href="https://webster.cs.washington.edu/validate-css.php">
						<img src="https://webster.cs.washington.edu/images/w3c-css.png" alt="Valid CSS" /></a>
				</div>
			</div> <!-- end of #frame div -->
		</body>
	</html>
<?php
}

# perform a query with the given query, 
# and possible an actor id (depends on whether if it's the movie query)
function perform_query($query, $id, $db) {
	try {
		$rows = $db->query($query);
		return $rows;
	} catch (PDOException $ex){
		?>
		<p>Sorry, a database error occurred.</p>
		<?php
		return NULL;
	}
}

# find and return the ID for a given actor's first name and last name 
function get_id($first_name, $last_name, $db) {
	$first_name .= "%";
	$first_name = $db->quote($first_name);
	$last_name = $db->quote($last_name);
	
	$actor_id_query = "SELECT id 
						FROM actors 
						WHERE first_name LIKE $first_name
							AND last_name = $last_name
					    ORDER BY film_count DESC, id LIMIT 1;";
	$rows = perform_query($actor_id_query, "", $db);

	if ($rows) {
		foreach ($rows as $row) {
			return $row["id"];
		}
	} else { 
		return NULL;
	}
}

# prints a error message if an actor is not found
function error_message($full_name) {
	?>
	<p>Actor <?= $full_name ?> not found. </p>
	<?php
}
?>