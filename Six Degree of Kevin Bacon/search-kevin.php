<?php
/*
Xiaowen Feng
CSE 154 
Section AA
May 13, 2015
Assignment 5

The page showing search results for all 
films with the given actor and Kevin Bacon.
*/
include('common.php');
top();
$actor_id = get_id($first_name,$last_name, $db);
$movie_query = "SELECT DISTINCT m.name, m.year
					FROM movies m 
					JOIN roles r1 ON r1.movie_id = m.id
					JOIN actors a1 ON r1.actor_id = a1.id
					JOIN roles r2 ON r2.movie_id = m.id
					JOIN actors a2 ON r2.actor_id = a2.id
					WHERE a1.first_name ='Kevin'
						AND a1.last_name = 'Bacon'
						AND a2.id = $actor_id
					ORDER BY m.year DESC, m.name;";

if($actor_id) {
	# query to find movie has given actor and Kevin Bacon
	$rows = perform_query($movie_query, $actor_id, $db);

	if($rows && $rows->rowCount() > 0) { # if movie found
		movie_table($rows, $full_name, true);

	} else {
		?>
		<p><?= $full_name?> wasn't in any films with Kevin Bacon</p>
		<?php
	}

} else {
	error_message($full_name); #the actor id is not found
}

bottom(); 
?>