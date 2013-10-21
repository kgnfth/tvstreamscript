<?php 
session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
	exit();
}

if (!isset($_GET['q'])){
	exit();
}

$query = $_GET['q'];
require_once("../../vars.php");
require_once("../../includes/user.class.php");

$user = new User();
$users = $user->search($query);

$res = array();
$res['availableTags'] = array();

if (count($users)){
	foreach($users as $key => $val){
		$res['availableTags'][] = $val['username'];
	}
}

print(json_encode($res));


?>