<?php
@session_start();
@extract($_POST);
@extract($_GET);
require_once("../vars.php");
require_once("../includes/show.class.php");
 
if (@$episode){
	$show = new Show();
	$ip = @$_SERVER['REMOTE_ADDR'];
	$user_agent = @$_SERVER['HTTP_USER_AGENT'];
	$show->reportEpisode($episode,$problem,$ip,$user_agent);
}
 
?>