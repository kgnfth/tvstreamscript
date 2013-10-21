<?php

session_start();
if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id']){ 
	$return = array("status" => 99);
	print(json_encode($return));
	exit(); 
}

if (isset($_POST['id'])){
	$id = $_POST['id'];
} elseif (isset($_GET['id'])){
	$id = $_GET['id'];
} else {
	$id = false;
}

if (!$id){
	$return = array("status" => 99);
	print(json_encode($return));
	exit(); 
}

require_once("../../vars.php");
require_once("../../includes/curl.php");
require_once("../../includes/show.class.php");
require_once("../../includes/settings.class.php");
require_once("../../includes/tvlinks.class.php");

  
$settings = new Settings();
$shows = new Show();

$curl = new Curl();
$curl->setCookieFile($basepath."/cachefiles/tvlinkscookie.txt");

$tv = new TVlinks($curl);

sleep(2);

$return = array();
  
if (isset($id) && $id && is_numeric($id)){	
	$show = $shows->getEpisodeById($id,"en");	
	if (count($show)){
		$sr = $settings->getSetting("tvlinks");
		if ($tv->login($sr->username,$sr->password)){
			
			$url = $baseurl.$show['url'];
			
			$link = $tv->submit($show['showtitle'],$show['season'],$show['episode'],$url);
			if ($link){
				$shows->addSubmit($id,7,$link);
				$return['status'] = 1;
				$return['link'] = $link;
			} else {
				$return['status'] = 2;
			}
		} else {
			$return['status'] = 0;
		}
	} else {
		$return['status'] = 99;
	}
} else {
	$return['status'] = 99;
}

print(json_encode($return));
?>