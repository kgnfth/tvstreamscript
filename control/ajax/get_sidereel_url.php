<?php
session_start();
if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id']){
	exit();
}

set_time_limit(0);
ob_start();
extract($_POST);
extract($_GET);

require_once("../../vars.php");
require_once("../../includes/show.class.php");
require_once("../../includes/sidereel.class.php");

if (@$showtitle){
	$sidereel = new Sidereel();
	$showurl = $sidereel->grabURL($showtitle);
	print($showurl);
} else {
	print("0");
}

?>