<?php
session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/movie.class.php");

if (isset($_POST['movie_ids']) && $_POST['movie_ids']){
	$movie = new Movie();
	$movie_ids = explode(",",$_POST['movie_ids']);
	if (count($movie_ids)){
		foreach($movie_ids as $key => $movie_id){
			if ($movie_id){
				$movie->deleteMovie($movie_id);
			}
		}
	}
}