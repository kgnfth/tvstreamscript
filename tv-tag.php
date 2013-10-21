<?php
if (isset($tag) && $tag){
	
	$tag_perma = $tag;
	$tag = $show->getCategoryByPerma($tag,$language);
	if ($tag){
		
		if (!isset($_REQUEST['sortby']) || !$_REQUEST['sortby'] || !in_array($_REQUEST['sortby'], array("date", "abc", "imdb_rating"))){
			$sortby = "date";
		} else {
			$sortby = $_REQUEST['sortby'];
		}
		
		if (!isset($p) || !$p || !is_numeric($p)){
			$p = 1;
		}
		
		if (!$global_settings['maxtvperpage']){
			$maxperpage = 40;
		} else {
			$maxperpage = $global_settings['maxtvperpage'];
		}
		
		$tagged_shows = $show->getShowsByCategory($tag['id'], $language, $p, $maxperpage, $sortby);
		
		if (!count($tagged_shows)){
			$tagged_shows = false;
			$pagination = false;
		} else {
			$count = $show->getShowCountByCategory($tag['id']);
			if ($global_settings['seo_links']){
				$pagination = $misc->getBasicPagination($count,$p,$maxperpage,$baseurl."/".$routes['tv_tag']."/".$tag_perma."/$sortby/");
			} else {
				$pagination = $misc->getBasicPagination($count,$p,$maxperpage,$baseurl."/index.php?menu=tv-tag&perma=$tag_perma&sortby=$sortby&p=");
			}
			
			foreach($tagged_shows as $key => $val){
				$tagged_shows[$key]['description'] = nl2br(stripslashes($val['description']));
				$tagged_shows[$key]['title'] = stripslashes($val['title']);
			}
		}
		$smarty->assign("tagged_shows", $tagged_shows);
		$smarty->assign("tag_perma", $tag_perma);
		$smarty->assign("tag", $tag['tag']);
		$smarty->assign("sortby", $sortby);
		$smarty->assign("pagination", $pagination);
		
		$seodata['category'] = $tag['tag'];
		
	} else {
		$smarty->assign("tag", false);
	}
} else {
	$smarty->assign("tag", false);
}

?>