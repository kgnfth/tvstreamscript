<?php 

$user = new User();

$favorite_movies = $user->getFavoriteMovies($_SESSION['loggeduser_id'],false,$language);

if (count($favorite_movies)){
	$smarty->assign("favorite_movies",$favorite_movies);
} else {
	$smarty->assign("favorite_movies",0);
}

$favorite_shows = $user->getFavoriteShows($_SESSION['loggeduser_id'],false,$language);

if (count($favorite_shows)){
	$smarty->assign("favorite_shows",$favorite_shows);
} else {
	$smarty->assign("favorite_shows",0);
}
?>
