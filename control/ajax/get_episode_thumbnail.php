<?php
@session_start();
@set_time_limit(0);

$res = array();
if (isset($_SESSION['admin_user_id']) && $_SESSION['admin_user_id']){

	@extract($_POST);
	@extract($_GET);
	
	require_once("../../vars.php");
	require_once("../../includes/show.class.php");
	require_once("../../includes/sidereel.class.php");
	
	$show = new Show();
	$thisshow = $show->getShow($showid,1,"en");
	$sidereel = new Sidereel();
	
	$thumb = $sidereel->getThumbnail($thisshow[$showid],$season,$episode,$basepath);
	$thumb = trim($thumb);
	
	$res = array();
	if (!$thumb){
		$res['status'] = 0;
		$res['message'] = "Can't find episode thumbnail";
	} else {
		$res['status'] = 1;
		$res['message'] = $thumb;
	}

} else {
	$res['status'] = 0;
	$res['message'] = "Session expired. Please login again";
}

print(json_encode($res));

?>