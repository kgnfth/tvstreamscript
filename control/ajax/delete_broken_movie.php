<?php

session_start();
if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !isset($_POST['report_id'])){
	exit();
}

$report_id = (int) $_POST['report_id'];

if (!$report_id){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/movie.class.php");
$movie = new Movie();
$movie->deleteBroken($report_id);

?>