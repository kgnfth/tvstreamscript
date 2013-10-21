<?php
/* Movie4k.to */
session_start();
set_time_limit(0);

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_POST['showid']) || !isset($_POST['season']) || !isset($_POST['episode'])){
	print(json_encode(array("status" => 0)));
	exit();
}

extract($_POST);

require_once("../../vars.php");
require_once("../../includes/curl.php");
require_once("../../includes/show.class.php");
require_once("../../includes/movie2k.class.php");
require_once("../../includes/misc.class.php");

$curl = new Curl();
$m2k = new Movie2k();
$show = new Show();
$ret = array();


$thisshow = $show->getShow($showid,1,"en");
if (!empty($thisshow)){
	$embeds = $m2k->getEmbeds($thisshow[$showid]['title'],$season,$episode);
	
	if (count($embeds)){
		$ret['status'] = 1;
		$ret['embeds'] = $embeds;
	} else {
		$ret['status'] = 0;
	}
	
} else {
	$ret['status'] = 0;
}
print(json_encode($ret));
