<?php
session_start();
extract($_POST);
require_once("../vars.php");
require_once("../includes/facebook/facebook.php");
require_once("../includes/user.class.php");
require_once("../includes/settings.class.php");

$settings = new Settings();
$facebook_details = $settings->getMultiSettings(array("facebook"),true);
if (!$facebook_details['facebook']){
	$response = array("status" => 0);
	print(json_encode($response));
	exit();
}

$facebook_details = $facebook_details['facebook'];

$facebook = new Facebook(array(
  'appId'  => $facebook_details['app_id'],
  'secret' => $facebook_details['app_secret'],
));

$fb_user = $facebook->getUser();
$user = new User();

$response = array();

if ($fb_user) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		
		$user_profile = $facebook->api('/me');
		
		try{
			$token =  $facebook->getAccessToken();
		} catch(Exception $e){
			$token = '';
		}
		
		$uid = $user_profile['id'];
		$user_details = $user->facebookLogin($uid,$token);
		
		if ($user_details){
			// already have this facebook user
			$response['status'] = 1;
		} else {
			// new facebook user
			$response['status'] = 2;
			if (isset($user_profile['username'])){
				$response['username'] = $user_profile['username'];
			} else {
				$response['username'] = str_replace(" ","",$user_profile['name']);
			}
			
			if (isset($user_profile['email'])){
				$response['email'] = $user_profile['email'];
			} else {
				$response['email'] = '';
			}
			
			$_SESSION['fb_username'] = $response['username'];
			$_SESSION['fb_email'] = $response['email'];
			$_SESSION['fb_id'] = $uid;
			$_SESSION['fb_session'] = $token;
		}
		
	} catch (FacebookApiException $e) {
		$response['status'] = 0;
	}
} else {
	$response['status'] = 0;
}

print(json_encode($response));

?>