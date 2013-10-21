<?php
session_start();
set_time_limit(0);

$res = array();
if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id'] && isset($_POST['embed']) && $_POST['embed']){
	require_once("../../includes/misc.class.php");	
	
	$misc = new Misc();
	
	$embed = urldecode($_POST['embed']);
	
	$link = $misc->buildLink($embed);
	if ($link){
		print($link);
	}
}