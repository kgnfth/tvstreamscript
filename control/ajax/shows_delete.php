<?php
session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/show.class.php");

if (isset($_POST['show_ids']) && $_POST['show_ids']){
	$show = new Show();
	$show_ids = explode(",",$_POST['show_ids']);
	if (count($show_ids)){
		foreach($show_ids as $key => $show_id){
			if ($show_id){
				$show->deleteShow($show_id);
			}
		}
	}
}