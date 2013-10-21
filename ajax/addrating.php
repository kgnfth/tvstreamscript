<?php
 require_once("../vars.php");
 require_once("../includes/show.class.php");
 @extract($_GET);
 @extract($_POST);

 $ip = @$_SERVER['REMOTE_ADDR'];
 if (@$rating && @$episode){
	$show = new Show();
	$show->addRating($episode,$rating,$ip); 
 }
?>