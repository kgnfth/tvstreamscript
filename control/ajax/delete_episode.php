<?php

session_start();
 
if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !isset($_POST['id'])){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/show.class.php");

$id = (int) $_POST['id'];

if (!$id){
	exit();
}

$show = new Show();
$show->deleteEpisode($id);

?>