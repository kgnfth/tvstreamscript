<?php 

$user = new User();

if (isset($_SESSION['loggeduser_id']) && $_SESSION['loggeduser_id']){
	
	$favorite_shows = $user->getFavoriteShows($_SESSION['loggeduser_id'],true);

	if (!count($favorite_shows)){
		$smarty->assign("result_type","random");
		$smarty->assign("shows",$show->getRandomShow(10,$language));
	} else {
		$smarty->assign("result_type","favorites");
		$favorite_shows = $show->getSimilarShows($favorite_shows,20,$language);
		$smarty->assign("shows",$favorite_shows);	
	}

}
?>