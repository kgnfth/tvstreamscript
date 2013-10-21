<?php
@session_start();
@extract($_POST);
@extract($_GET);
require_once("../vars.php");
require_once("../includes/movie.class.php");

if (@$movie){
	$mov = new Movie();
	$ip = @$_SERVER['REMOTE_ADDR'];
	$user_agent = @$_SERVER['HTTP_USER_AGENT'];
	$mov->reportMovie($movie,$problem,$ip,$user_agent);
} 
?>