<?php
if (!$global_settings['maxmoviesperpage']){
	$maxperpage = 40;
} else {
	$maxperpage = $global_settings['maxmoviesperpage'];
}


$movies = $movie->getLatest($maxperpage,$language);

if (!count($movies)){
	$movies = '';
} else {
	
	if ($logged){
		$user = new User();		
		if (!isset($_SESSION['loggeduser_seen_movies'])){
			$seen = $user->getSeenMovies(null,true);
		} else {
			$seen = $_SESSION['loggeduser_seen_movies'];
		}
	} else {
		$seen = array();
	}
	
	foreach($movies as $key=>$val){
		$movies[$key]['title']=stripslashes(stripslashes($movies[$key]['title']));
		$movies[$key]['description']=stripslashes(stripslashes($movies[$key]['description']));
				
		if (in_array($key,$seen)){
			$movies[$key]['seen'] = 1;
		} else {
			$movies[$key]['seen'] = 0;
		}
		
		
	}
}

$smarty->assign("movies",$movies);
?>