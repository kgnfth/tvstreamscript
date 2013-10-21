<?php

session_start();
$response = array();
if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){ 
	 $response['status'] = 99;
	 print(json_encode($response));
	 exit();
}

if (!isset($_POST['id']) && !isset($_GET['id'])){
	 $response['status'] = 99;
	 print(json_encode($response));
	 exit();	
} elseif (isset($_POST['id'])){
	$id = $_POST['id'];
} elseif (isset($_GET['id'])){
	$id = $_GET['id'];
}

require_once("../../vars.php");
require_once("../../includes/curl.php");
require_once("../../includes/show.class.php");
require_once("../../includes/settings.class.php");
require_once("../../includes/sidereel.class.php");

  
$settings = new Settings();
$shows = new Show();
$sidereel = new Sidereel();
$curl = new Curl();

$decaptcher = $settings->getSetting("decaptcher");

if (!isset($decaptcher->url) || !$decaptcher->url){
	$decaptcher->url = "poster.decaptcher.com"; 
}

if (!isset($decaptcher->username) || !$decaptcher->username || !isset($decaptcher->password) || !$decaptcher->password){
	 $response['status'] = 3;
	 print(json_encode($response));
	 exit();
}

if (isset($decaptcher->port) && $decaptcher->port){
	
	require_once("../../includes/ccproto_client.php");
	$ccp = new ccproto();
	$ccp->init();
	
	if( $ccp->login($decaptcher->url, $decaptcher->port, $decaptcher->username, $decaptcher->password ) < 0 ) {
		 $response['status'] = 3;
		 print(json_encode($response));
		 exit();
	}
} else {
	require_once("../../includes/decaptcher.poster.php");
    define( 'HOST',     $decaptcher->url    );  // HOST
    define( 'PORT',     80          );  // PORT 80 or 443
    define( 'USERNAME', $decaptcher->username    );  // YOUR LOGIN
    define( 'PASSWORD', $decaptcher->password);    // YOUR PASSWORD
}
  
if (isset($id) && $id && is_numeric($id)){	
	$show = $shows->getEpisodeById($id,"en");	
	if (count($show)){
		$sr = $settings->getSetting("sidereel");
		if ($sidereel->checkLogged($sr->username) || $sidereel->login($sr->username,$sr->password)){
			// getting the captcha
			$data = $sidereel->getCaptcha($show['showtitle'],$show['season'],$show['episode']);
			if ($data){
				$text = '';
				if (isset($decaptcher->port) && $decaptcher->port){
				    $pict = file_get_contents("$basepath/cachefiles/".$data['image'].".jpg");
				    $res = $ccp->picture2( $pict, $pict_to, $pict_type, $text, $major_id, $minor_id );
				} else {
					$res = poster_curl( HOST, PORT, USERNAME, PASSWORD, "$basepath/cachefiles/".$data['image'].".jpg", NULL, ptUNSPECIFIED );
					if (!is_numeric($res)){
						$text = $res;
					}				
				}
				switch( $res ) {
					// most common return codes
					case ccERR_OK:
						break;
					case ccERR_BALANCE:
						print( "2" );
						exit();
						break;
					case ccERR_TIMEOUT:
						print( "2" );
						exit();
						break;
					case ccERR_OVERLOAD:
						print( "2" );
						exit();
						break;
				
					// network errors
					case ccERR_NET_ERROR:
						print( "2" );
						exit();
						break;
				
					// server-side errors
					case ccERR_TEXT_SIZE:
						print( "2" );
						exit();
						break;
					case ccERR_GENERAL:
						print( "2" );
						exit();
						break;
					case ccERR_UNKNOWN:
						print( "2" );
						exit();
						break;
				
					default:
						break;
				}
				
				if (file_exists("$basepath/cachefiles/".$data['image'].".jpg")){
					unlink("$basepath/cachefiles/".$data['image'].".jpg");
				}
				
				if ($text){
					$url = $baseurl."/show".$show['url'];
					
					$data['recaptcha_response_field'] = $text;
					$data['link[url]'] = $url;
					

					$message = $sidereel->submit($show['showtitle'],$show['season'],$show['episode'],$data);
					
					$link = $data['sidereel_url']."/season-{$show['season']}/episode-{$show['episode']}/search";
					
					if (substr_count($message,"Thanks for adding a link.")){
						$response['status'] = 1;
						$response['link']  = $link;
						print(json_encode($response));
						$shows->addSubmit($id,2,$link);
					} else if (substr_count($message,"Thanks, but we already have this link!")){
						// already have
						$response['status'] = 5;
						$response['link']  = $link;
						print(json_encode($response));
						$shows->addSubmit($id,2,$link);					
					} elseif (substr_count($message,"try the word verification response again")){
						// invalid captcha
						$response['status'] = 4;
						print(json_encode($response));
					} elseif (strlen($message)>300) {
						$response['status'] = 98;
						$response['debug'] = $message;
						print(json_encode($response));
					} else {
						$response['status'] = 98;
						$response['debug'] = $message;
						print(json_encode($response));
					}
					
				} else {
					// decaptcher error
					$response['status'] = 3;
					print(json_encode($response));
				}
			} else {
				// can't find tv show on sidereel
				$response['status'] = 2;
				print(json_encode($response));
			}
		} else {
			// can't login to sidereel
			$response['status'] = 0;
			print(json_encode($response));
		}
	} else {
		 $response['status'] = 99;
		 print(json_encode($response));
	}
} else {
	 $response['status'] = 99;
	 print(json_encode($response));
}
?>