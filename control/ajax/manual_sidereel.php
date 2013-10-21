<?php
session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id']){ 
	exit(); 
}

extract($_POST);

require_once("../../vars.php");
require_once("../../includes/show.class.php");
require_once("../../includes/settings.class.php");
require_once("../../includes/sidereel.class.php");
require_once("../../includes/curl.php");
  
$settings = new Settings();
$shows = new Show();
$sidereel = new Sidereel();
  
if (isset($id) && $id && is_numeric($id)){	
	$show = $shows->getEpisodeById($id,'en');	
	if (count($show)){
				
		if (isset($authenticity_token) && isset($recaptcha_challenge_field) && isset($answer) && isset($sidereel_url)){
			// submitting the episode		
			
			$data = array();
			$data['authenticity_token'] = $authenticity_token;
			$data['recaptcha_challenge_field'] = $recaptcha_challenge_field;
			$data['sidereel_url'] = $sidereel_url;
			$data['recaptcha_response_field'] = trim($answer);
			$data['link[url]'] = $baseurl.$show['url'];
			
			if (isset($image_id) && file_exists($basepath."/cachefiles/".$image_id.".jpg")){
				unlink($basepath."/cachefiles/".$image_id.".jpg");
			}
			
			$message = $sidereel->submit($show['showtitle'],$show['season'],$show['episode'],$data);
			$result = array();
			$result['status'] = 0;
			$result['message'] = $message;
			

			if (substr_count($message,"Thanks for adding a link.")){
				$result['status'] = 1;
				$link = $sidereel_url."/season-{$show['season']}/episode-{$show['episode']}/search";
				$shows->addSubmit($id,2,$link);				
				$result['message'] .= "<br /><br />Your link must be visible <a href='$link' target='_blank'>here</a> shortly";
				$result['link'] = $link;
			} elseif (substr_count($message,"Thanks, but we already have this link!")){
				$result['status'] = 2;
				$link = $sidereel_url."/season-{$show['season']}/episode-{$show['episode']}/search";
				$shows->addSubmit($id,2,$link);				
				$result['message'] .= "<br /><br />Check your link <a href='$link' target='_blank'>here</a>";
				$result['link'] = $link;
			}
			
			print(json_encode($result));
			
		} else {
			$sr = $settings->getSetting("sidereel");
			if ($sidereel->checkLogged($sr->username) || $sidereel->login($sr->username,$sr->password)){
				// getting the captcha
				$data = $sidereel->getCaptcha($show['showtitle'],$show['season'],$show['episode']);
				if ($data){
					
				?>
					<div class="row-fluid">
						<div class="span11" style="margin:0 auto !important;">
							<img src='<?php print($baseurl); ?>/cachefiles/<?php print($data['image']); ?>.jpg' style="margin-bottom: 8px;" />
							
							<div class="clear"></div>
							
							<input type="hidden" id="recaptcha_challenge_field" value="<?php print($data['recaptcha_challenge_field']); ?>" />
							<input type="hidden" id="authenticity_token" value="<?php print($data['authenticity_token']); ?>" />
							<input type="hidden" id="sidereel_url" value="<?php print($data['sidereel_url']); ?>" />
							<input type="hidden" id="image_id" value="<?php print($data['image']); ?>" />
							
							<input type="text" id="answer" class="span8" style="margin:0px !important;" />
							<input type="button" id="submitbutton" value="Submit" class="btn" class="span4" onclick="doManualSidereel(<?php print($id); ?>);" />
							
						</div>
					</div>
				<?php
				} else {
					print("1");
				}
			} else {
				print("0");
			}				
		}

	} else {
		print("1");
	}
} else {
	print("1");
}
?>