<?php

$user = new User();

if (isset($profile_username) && $profile_username){
	
	$profile_user = $user->getByUsername($profile_username);
	if ($profile_user){
		$profile_favorite_shows = $user->getFavoriteShows($profile_user['id'],false,$language);
		$profile_favorite_movies = $user->getFavoriteMovies($profile_user['id'],false,$language);
		
		if (isset($_SESSION['loggeduser_id'])){
			$profile_followers = $user->getFollowers($profile_user['id']);
			if (array_key_exists($_SESSION['loggeduser_id'],$profile_followers)){
				$smarty->assign("is_follower",1);
			} else {
				$smarty->assign("is_follower",0);
			}
		}
		
		
		$smarty->assign("profile_user",$profile_user);
		$smarty->assign("profile_favorite_shows",$profile_favorite_shows);
		$smarty->assign("profile_favorite_movies",$profile_favorite_movies);
		
		
		
	} else {
		$smarty->assign("no_user",1);
	}
	
} else {
	$smarty->assign("no_user",1);
}

?>