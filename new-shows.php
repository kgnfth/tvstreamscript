<?php

if (!$global_settings['maxtvperpage']){
	$maxperpage = 40;
} else {
	$maxperpage = $global_settings['maxtvperpage'];
}

$episodes = $show->getRealLatestEpisodes($maxperpage,$language);

if (!count($episodes)){
	$episodes = '';
} else {
	
	if ($logged){
		$user = new User();
		if (!isset($_SESSION['loggeduser_seen_episodes'])){
			$seen = $user->getSeenEpisodes(null,true);
		} else {
			$seen = $_SESSION['loggeduser_seen_episodes'];
		}
	} else {
		$seen = array();
	}
	
	
	foreach($episodes as $key => $val){
		extract($val);
		$description = nl2br(stripslashes($description));
		if (substr_count($description,"Airdate:")>0){
			$tmp = explode("<br />",$description);
			$description = $tmp[2];
		}
		$episodes[$key]['showtitle']=stripslashes($showtitle);
		$episodes[$key]['title']=stripslashes(stripslashes($episodes[$key]['episodetitle']));
		$episodes[$key]['showtitle']=stripslashes(stripslashes($episodes[$key]['showtitle']));
		$episodes[$key]['description']=stripslashes(stripslashes($description));
		
		if (in_array($episodes[$key]['epid'],$seen)){
			$episodes[$key]['seen'] = 1;
		} else {
			$episodes[$key]['seen'] = 0;
		}
	}
}

$smarty->assign("episodes",$episodes);
?>