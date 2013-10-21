<?php
session_start();

if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id']){
	exit();
}

require_once("../vars.php");
require_once("../includes/user.class.php");
require_once("../includes/phpmailer/class.phpmailer.php");

if (isset($_POST['user_id']) && isset($_POST['follow'])){
	$target_id = $_POST['user_id'];
	$user = new User();
	
	if ($_POST['follow']){
		$target_user = $user->follow($_SESSION['loggeduser_id'],$target_id);
		$email_settings = $settings->getSetting("email_settings", true);
		if ($target_user && $email_settings){
			// sending email
			
			$follower = $user->get($_SESSION['loggeduser_id']);
			
			$language = $target_user['language'];
			require_once("../language/".$language."/general.php");
			require_once("../language/".$language."/user.php");
			
			$mailsubject = $lang['user_follow_mail_subject'];
			$mailcontent = $lang['user_follow_mail_content'];
			
			$mailcontent = str_replace("#sitename#",$sitename,$mailcontent);
			$mailcontent = str_replace("#baseurl#",$baseurl,$mailcontent);
			$mailcontent = str_replace("#follower_username#",$follower['username'],$mailcontent);
			$mailcontent = str_replace("#target_username#",$target_user['username'],$mailcontent);
			$mailcontent = str_replace("#follower_profile_url#",$baseurl."/user/".$follower['username'],$mailcontent);
			
			$mailcontent = str_replace("#mailcontent#",$mailcontent,file_get_contents("../language/".$language."/email_template.html"));
            $mailcontent = str_replace("#sitename#",$sitename);
            $mailcontent = str_replace("#baseurl#",$baseurl);
			
			if (isset($email_settings['smtp']) && $email_settings['smtp']){
			
				$mail             = new PHPMailer();	
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->SMTPDebug  = 0;
				$mail->SMTPAuth   = true;                  
				if ($email_settings['smtp_security']=="ssl"){
					$mail->SMTPSecure = "ssl";
				}
				$mail->Host       = $email_settings['smtp_host'];
				$mail->Port       = $email_settings['smtp_port'];
				$mail->Username   = $email_settings['smtp_user'];
				$mail->Password   = $email_settings['smtp_password'];
				
				$mail->SetFrom($email_settings['sender_email'], $email_settings['sender_name']);
				
				$mail->Subject = "=?UTF-8?B?".base64_encode($mailsubject)."?=";
				//$mail->Subject    = $mailsubject;
				
				$mail->MsgHTML($mailcontent);
				$mail->IsHTML(true);
				$mail->AddAddress($target_user['email']);
				
				$mail->send();
			} else {
				$headers = "Content-type: text/plain\nFrom: {$email_settings['sender_name']} <{$email_settings['sender_email']}>";
				@mail($target_user['email'],$mailsubject,$mailcontent,$headers);
			}
		}
		
	} else {
		$user->unfollow($_SESSION['loggeduser_id'],$target_id);
	}
}

?>