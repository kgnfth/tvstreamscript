<?php

$user = new User();

if (isset($_SESSION['loggeduser_id']) && $_SESSION['loggeduser_id']){
	$favorite_movies = $user->getFavoriteMovies($_SESSION['loggeduser_id'], true);

	
	if (!count($favorite_movies)){
		$smarty->assign("result_type","random");
		$smarty->assign("movies",$movie->getRandomMovies(10,$language));
	} else {
		$smarty->assign("result_type","favorites");
		$favorite_movies = $movie->getSimilarMovies($favorite_movies,20,$language);
		$smarty->assign("movies",$favorite_movies);	
	}
	
}

?>