<?php

require_once("../vars.php");
require_once("../includes/movie.class.php");
@extract($_GET);
@extract($_POST);

$ip = @$_SERVER['REMOTE_ADDR'];
if (@$rating && @$movieid){
	$movie = new Movie();
	$movie->addRating($movieid,$rating,$ip);
}
?>