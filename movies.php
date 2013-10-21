<?php

if (!$global_settings['maxmoviesperpage']){
	$maxperpage = 40;
} else {
	$maxperpage = $global_settings['maxmoviesperpage'];
}

if (!isset($p)) $p = 1;
if (!isset($sortby)) $sortby = "date";



if ($maxperpage){
	$count = $movie->getRealMovieCount();
	
	$movielist = $movie->getRealMovies($language,$p,$maxperpage,$sortby);
	
	if ($global_settings['seo_links']){
		$pagination = $movie->getBasicPagination($count,$p,$maxperpage,$baseurl."/".$routes['movies']."/$sortby/");
	} else {
		$pagination = $movie->getBasicPagination($count,$p,$maxperpage,$baseurl."/index.php?menu=movies&sortby=$sortby&p=");
	}
} else {
	$movielist = $movie->getRealMovies($language);
	if ($global_settings['seo_links']){
		$pagination = '<a href="'.$baseurl.'/'.$routes['movies'].'">1</a>';
	} else {
		$pagination = '<a href="'.$baseurl.'/index.php?menu=movies">1</a>';
	}
}

if (count($movielist)){
	
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
	
	
	foreach($movielist as $key => $val){
		extract($val);
		$description = nl2br(stripslashes($description));
		$movielist[$key]['description']=$description;
		$movielist[$key]['title']=stripslashes(stripslashes($title));
		
		if (in_array($key,$seen)){
			$movielist[$key]['seen'] = 1;
		} else {
			$movielist[$key]['seen'] = 0;
		}
		
	}
} else {
	$movielist = '';
}

$smarty->assign("movielist",$movielist);
$smarty->assign("sortby",$sortby);
$smarty->assign("pagination",$pagination);

?>