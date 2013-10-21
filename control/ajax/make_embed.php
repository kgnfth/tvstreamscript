<?php
session_start();
set_time_limit(0);

$res = array();
if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id'] && isset($_POST['link']) && $_POST['link']){
	require_once("../../includes/misc.class.php");	
	
	$misc = new Misc();
	
	$link = urldecode($_POST['link']);
	
	$embed = $misc->buildEmbed($link);
	print($embed);
}