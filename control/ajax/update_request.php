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

$data = array();

if (isset($_POST['response'])){
	$data['response'] = $_POST['response'];
}

if (isset($_POST['status'])){
	$data['status'] = $_POST['status'];
}

if (count($data)){
	$request->update($_POST['request_id'], $data);
}

?>