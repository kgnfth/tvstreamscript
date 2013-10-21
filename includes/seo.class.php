<?php

class SEO{
	
	function __construct(){
		
	}
	
	function getSeo($data){
		global $sitename;
		
		$settings = new Settings();
		if (!isset($_SESSION['language'])){
			$language = "en";
		} else {
			$language = $_SESSION['language'];
		}
		
		if (!isset($data['menu'])){
			$data['menu'] = "home";
		}
		
		if ($data['menu']=='tv-shows'){
			$title = $settings->getSetting("tvshows_title",true);			
			$keywords = $settings->getSetting("tvshows_keywords",true);
			$description = $settings->getSetting("tvshows_description",true);
		} elseif ($data['menu']=='tv-tag'){
			$title = $settings->getSetting("tvcategory_title",true);
			$keywords = $settings->getSetting("tvcategory_keywords",true);
			$description = $settings->getSetting("tvcategory_description",true);
		} elseif ($data['menu']=='show'){
			$title = $settings->getSetting("show_title",true);
			$keywords = $settings->getSetting("show_keywords",true);
			$description = $settings->getSetting("show_description",true);
		} elseif ($data['menu']=='episode'){
			$title = $settings->getSetting("episode_title",true);
			$keywords = $settings->getSetting("episode_keywords",true);
			$description = $settings->getSetting("episode_description",true);
		} elseif ($data['menu']=='movies'){
			$title = $settings->getSetting("movies_title",true);
			$keywords = $settings->getSetting("movies_keywords",true);
			$description = $settings->getSetting("movies_description",true);
		} elseif ($data['menu']=='movie-tag'){
			$title = $settings->getSetting("moviecategory_title",true);
			$keywords = $settings->getSetting("moviecategory_keywords",true);
			$description = $settings->getSetting("moviecategory_description",true);
		} elseif ($data['menu']=='watchmovie'){
			$title = $settings->getSetting("watchmovie_title",true);
			$keywords = $settings->getSetting("watchmovie_keywords",true);
			$description = $settings->getSetting("watchmovie_description",true);
		} elseif ($data['menu']=='live-channels'){
			$title = $settings->getSetting("livechannels_title",true);
			$keywords = $settings->getSetting("livechannels_keywords",true);
			$description = $settings->getSetting("livechannels_description",true);
		} elseif ($data['menu']=='channel'){
			$title = $settings->getSetting("channel_title",true);
			$keywords = $settings->getSetting("channel_keywords",true);
			$description = $settings->getSetting("channel_description",true);
		} elseif ($data['menu']=='search'){
			$title = $settings->getSetting("search_title",true);
			$keywords = $settings->getSetting("search_keywords",true);
			$description = $settings->getSetting("search_description",true);
		} else {
			$title = $settings->getSetting("default_title",true);			
			$keywords = $settings->getSetting("default_keywords",true);		
			$description = $settings->getSetting("default_description",true);
		}
		
		if (isset($title[$language])){
			$title = $title[$language];
		} elseif (isset($title['en'])) {
			$title = $title['en'];
		} else {
			$title = '';
		}
		
		if (isset($keywords[$language])){
			$keywords = $keywords[$language];
		} elseif (isset($keywords['en'])) {
			$keywords = $keywords['en'];
		} else {
			$keywords = '';
		}
		
		if (isset($description[$language])){
			$description = $description[$language];
		} elseif (isset($description['en'])) {
			$description = $description['en'];
		} else {
			$description = '';
		}
		
		if (empty($title)) $title = "Watch movies and TV shows for free | %SITENAME%";
		if (empty($keywords)) $keywords = "tv shows, free shows online, online movie streaming";
		if (empty($description)) $description = "Watch all the latest tv shows and movies online without downloading them";
		
		if (@$sitename){
			$title = str_replace("%SITENAME%",$sitename,$title);
			$keywords = str_replace("%SITENAME%",$sitename,$keywords);
			$description = str_replace("%SITENAME%",$sitename,$description);
		}
		
		foreach($data as $key=>$val){
			$title = str_replace("%".strtoupper($key)."%",$val,$title);
			$keywords = str_replace("%".strtoupper($key)."%",$val,$keywords);
			$description = str_replace("%".strtoupper($key)."%",$val,$description);
		}
		
		return array("title"=>$title,"keywords"=>$keywords,"description"=>$description);
	}
	
}

?>