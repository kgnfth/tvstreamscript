<?php

if (!isset($p) || !$p || !is_numeric($p)){
	$p = 1;
}
if (!isset($sortby) || !in_array($sortby, array("date","abc","imdb_rating"))){
	$sortby = "date";
}

if (!$global_settings['maxtvperpage']){
	$maxperpage = 40;
} else {
	$maxperpage = $global_settings['maxtvperpage'];
}

if ($maxperpage){
	$count = $show->getShowCountWithEpisodes();
	$tvshows = $show->getAllShowsWithEpisodes($language,$p,$maxperpage,$sortby);
	if ($global_settings['seo_links']){
		$pagination = $misc->getBasicPagination($count,$p,$maxperpage,$baseurl."/".$routes['tv_shows']."/$sortby/");
	} else {
		$pagination = $misc->getBasicPagination($count,$p,$maxperpage,$baseurl."/index.php?menu=tv-shows&sortby=$sortby&p=");
	}
} else {
	$tvshows = $show->getAllShowsWithEpisodes($language);
	if ($global_settings['seo_links']){
		$pagination = '<a href="'.$baseurl.'/'.$routes['tv_shows'].'">1</a>';
	} else {
		$pagination = '<a href="'.$baseurl.'/index.php?menu=tv-shows">1</a>';
	}
}

if (count($tvshows)){
	foreach($tvshows as $key => $val){
		@extract($val);
		$description = nl2br(stripslashes($description));
		if (strlen($description)>=220) $description = substr($description,0,220)."...";
		$tvshows[$key]['description']=$description;
		$tvshows[$key]['title']=stripslashes($title);
	}
} else {
	$tvshows = '';
}

$smarty->assign("pagination",$pagination);
$smarty->assign("tvshows",$tvshows);
$smarty->assign("sortby",$sortby);
?>