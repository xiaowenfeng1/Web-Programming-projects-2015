<?php
/*
Xiaowen Feng
CSE 154 
Section AA
May 13, 2015
Assignment 5

The page showing search results for all films by a given actor.
*/

include('common.php');
top();
$actor_id = get_id($first_name,$last_name, $db);
$movie_query = "SELECT m.name, m.year
				FROM movies m 
				JOIN roles r ON r.movie_id = m.id
				WHERE r.actor_id = $actor_id
				ORDER BY m.year DESC, m.name;";

if ($actor_id) {
	$rows = perform_query($movie_query, $actor_id, $db);
	movie_table($rows, $full_name, false); # false indicates not movies with K.B.
	
} else {
	error_message($full_name);
}

bottom(); 
?>