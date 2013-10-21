<?php

if (isset($_POST['action']) && $_POST['action']=="submit_link"){
	$errors = $misc->validateLink($_POST);
	
	if ($global_settings['captchas']){
		if (empty($_SESSION['captcha']) || !isset($_REQUEST['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']){
			$errors[7] = $lang['submit_error_invalid_captcha'];
		}
	}
	
	if (!count($errors)){
		$misc->saveLink($_POST);
		$smarty->assign("submit_success",true);		
	} else {
		$smarty->assign("submit_errors",$errors);
		$smarty->assign("submit_data",$_POST);
	}
}

$smarty->assign("random_number", rand(0,999));