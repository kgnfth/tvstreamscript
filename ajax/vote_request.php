<?php
@session_start();
require_once("../vars.php");
require_once("../includes/request.class.php");

if (!isset($_SESSION['loggeduser_id']) && isset($_COOKIE['guid'])){
	$user = new User();
	$res = $user->cookieLogin($_COOKIE['guid']);
	if (!$res){
		setcookie("guid","",time()-60*60, "/");
	}
}

if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id'] || !isset($_POST['request_id']) || !$_POST['request_id'] || !is_numeric($_POST['request_id'])){
	print('{"status":0}');
	exit();
}

$user_id = $_SESSION['loggeduser_id'];
$request = new Request();

$res = $request->addVote($_POST['request_id'],$user_id);
if ($res){
	print('{"status":1,"votes":'.$res.'}');
} else {
	print('{"status":0}');
}

?>