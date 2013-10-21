<?php
$user = new User();
$stream = new Stream();

if (isset($doregister)){
	
	$errors = $user->validate(@$_POST);
	
	if ($global_settings['captchas']){
		if (empty($_SESSION['captcha']) || !isset($_REQUEST['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']){
			$errors[4] = $lang['register_invalid_captcha'];
		}
	}
	
	if (count($errors)){
		if (isset($errors[0]) && $errors[0]){ $smarty->assign("general_error",$errors[0]); } else { $smarty->assign("general_error",""); }
		if (isset($errors[1]) && $errors[1]){ $smarty->assign("username_error",$errors[1]); } else { $smarty->assign("username_error",""); }
		if (isset($errors[2]) && $errors[2]){ $smarty->assign("password_error",$errors[2]); } else { $smarty->assign("password_error",""); }
		if (isset($errors[3]) && $errors[3]){ $smarty->assign("email_error",$errors[3]); } else { $smarty->assign("email_error",""); }
		if (isset($errors[4]) && $errors[4]){ $smarty->assign("captcha_error",$errors[4]); } else { $smarty->assign("captcha_error",""); }
	} else {
		$_POST['language'] = $language;
		$userid = $user->save($_POST);
		
		$data = array();
		$data['user_id'] = $userid;
		$data['target_id'] = 0;
		$data['user_data'] = array("id" => $userid, "username" => $_POST['username'],"email" => $_POST['email']);
		
		if (isset($_POST['fb_id'])){
			// doing the avatar
			require_once("includes/curl.php");
			$curl = new Curl();
			$image_data = $curl->get("http://graph.facebook.com/".$_POST['fb_id']."/picture?type=large");
			
			$data['user_data']['fb_id'] = $_POST['fb_id'];
			$_SESSION['fb_justregistered'] = 1;
			
			if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
				$handle = fopen("$basepath/thumbs/users/$userid.jpg","w+");
				fwrite($handle,$image_data);
				fclose($handle);
				$user->update($userid,array("avatar" => "$userid.jpg"));
				$data['user_data']['avatar'] = "$userid.jpg";
			} else {
				$data['user_data']['avatar'] = "nopic.jpg";
			}
		} else {
			$data['user_data']['avatar'] = "nopic.jpg";
			$data['user_data']['fb_id'] = 0;
		}
		
		if (isset($_POST['fb_session'])){
			$data['user_data']['fb_session'] = $_POST['fb_session'];
		} else {
			$data['user_data']['fb_session'] = '';
		}
		
		$data['target_data'] = array();
		$data['target_type'] = 0;
		$data['event_date'] = date("Y-m-d H:i:s");
		$data['event_type'] = 3;
		$data['event_comment'] = "Üdv a körünkben!";
		
		$stream->addActivity($data);
		
		@session_register("loggeduser_id");
		@session_register("loggeduser_username");
		$_SESSION['loggeduser_id']=$userid;
		$_SESSION['loggeduser_username']=$_POST['username'];
		$_SESSION['loggeduser_details']=$data['user_data'];
		
		print("<script>window.location='$baseurl';</script>");
		exit();
	}
}

if (!isset($_POST['email']) && isset($_SESSION['fb_email'])){
	$smarty->assign("reg_email",$_SESSION['fb_email']);
	$smarty->assign("fb_reg",1);
} elseif (isset($_POST['email'])){
	$smarty->assign("reg_email",$_POST['email']);
} else {
	$smarty->assign("reg_email",'');
}

if (!isset($_POST['username']) && isset($_SESSION['fb_username'])){
	$smarty->assign("reg_username",$_SESSION['fb_username']);
	$smarty->assign("fb_reg",1);
} elseif (isset($_POST['username'])){
	$smarty->assign("reg_username",$_POST['username']);
} else {
	$smarty->assign("reg_username",'');
}

if (isset($_SESSION['fb_id'])){
	$smarty->assign("fb_id",$_SESSION['fb_id']);
} else {
	$smarty->assign("fb_id",0);
}

if (isset($_SESSION['fb_session'])){
	$smarty->assign("fb_session",$_SESSION['fb_session']);
} else {
	$smarty->assign("fb_session",'');
}

$smarty->assign("random_number", rand(0,999));
$smarty->assign("registerdone",0);


?>