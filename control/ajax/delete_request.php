<?php
session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
	exit();
}

if (!isset($_POST['request_id']) || !$_POST['request_id']){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/request.class.php");

$request = new Request();
$request->delete($_POST['request_id']);
?>