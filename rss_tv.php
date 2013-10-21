<?php

require_once("./vars.php");
require_once("./includes/show.class.php");
require_once("./includes/settings.class.php");

$show = new Show();
$settings = new Settings();

if (isset($_GET['all'])){
	$limit = 1000000;
	$all = true;
	$pics = true;
} else {
	$limit = 20;
	$all = false;
	$pics = true;
}

$default_language = $settings->getSetting("default_language", true);
if (!$default_language || (is_array($default_language) && empty($default_language))){
	$default_language = "en";
}

if (isset($_GET['language']) && file_exists($basepath."/language/".preg_replace("/[^a-z0-9A-Z]/","",$_GET['language'])."/general.php")){
	$language = $_GET['language'];
} elseif (isset($_SESSION['language']) && $_SESSION['language']){
	$language = $_SESSION['language'];
} elseif (isset($_COOKIE['language']) && $_COOKIE['language']) {
	$language = $_COOKIE['language'];
} else {
	$language = $default_language;
}

if (!file_exists("language/$language/general.php")){
	$language = $default_language;
}

require_once("language/$language/general.php");
require_once("language/$language/home.php");

$episodes = $show->getRealLatestEpisodes($limit,$language);

if (count($episodes)){
	$content = '';
	$content.= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$content.= '<?xml-stylesheet type="text/xsl" media="screen" href="/~d/styles/rss2full.xsl"?>'."\n";
	$content.= '<?xml-stylesheet type="text/css" media="screen" href="http://feeds.feedburner.com/~d/styles/itemcontent.css"?>'."\n";
	$content.= '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:georss="http://www.georss.org/georss" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" xmlns:media="http://search.yahoo.com/mrss/" xmlns:feedburner="http://rssnamespace.org/feedburner/ext/1.0" version="2.0">'."\n";
	
	foreach($episodes as $key => $val){
		$now = date("D, d M Y H:i:s T",strtotime($val['date_added']));
		break;
	}
	
	$content.= "<channel>\n";
	$content.= "	<title>$sitename</title>\n";
	$content.= "	<link>$baseurl</link>\n";
	$content.= "	<description>TV show and movie resource</description>\n";
	$content.= "	<lastBuildDate>$now</lastBuildDate>\n";
	$content.= "	<language>en</language>\n";
	$content.= "	<generator>http://tvstreamscript.com</generator>\n";

	foreach($episodes as $key=>$val){
		extract($val);
		$pubdate = date("D, d M Y H:i:s T",strtotime($date_added));
		
		if ($all) $description.="\n\nEMBED:\n$embed";
		
		$content.= "	<item>\n";
		if (isset($val['thumbnail']) && $val['thumbnail']){
			$thumb = $val['thumbnail'];
		} else {
			$thumb = $val['show_thumbnail'];
		}
		
		$thumb = $baseurl."/thumbs/".$thumb;
		
		if (!trim($title)){
			$title = str_replace(array("#season#","#episode#"),array($season,$episode),$lang['stream_season_episode_long']);
		} else {
			$title = htmlspecialchars(stripslashes(utf8_encode($title)));
		}
		
		$content.= "		<title>".htmlspecialchars(stripslashes(utf8_encode($showtitle)))." - $title</title>\n";
		$content.= "		<link>$baseurl/$permalink/season/$season/episode/$episode</link>\n";
		$content.= "		<comments>$baseurl/$permalink/season/$season/episode/$episode</comments>\n";
		$content.= "		<pubDate>$pubdate</pubDate>\n";
		$content.= "		<description><![CDATA[<p><img src=\"".$thumb."\" /></p><p>".htmlspecialchars(utf8_encode($description))."</p>]]></description>\n";
		$content.= "		<content:encoded><![CDATA[<p><img src=\"".$thumb."\" /></p><p>".htmlspecialchars(utf8_encode($description))."</p>]]></content:encoded>\n";
		$content.= "	</item>\n";
	}
	
	$content.="</channel>\n";
	$content.="</rss>";
	header ("content-type: text/xml");
	print($content);

}
?>