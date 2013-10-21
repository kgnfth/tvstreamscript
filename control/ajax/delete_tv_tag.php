<?php
session_start();
if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id'] && isset($_SESSION['admin_username'])){
	extract($_POST);
	if (isset($tagid) && $tagid){
		require_once("../../vars.php");
	   	require_once("../../includes/show.class.php");
	   	$show = new Show();
	   	$show->deleteTag($tagid);
	   	print("1");
	}
}
?>