<?php

if (isset($_POST['action']) && $_POST['action']=="request"){
	$errors = $request->validate($_POST);
	if (!count($errors)){
		$request->save($_POST);
		$smarty->assign("success",true);
	} else {
		$smarty->assign("errors",$errors);
	}
}

$requests = $request->getActive();
$smarty->assign("requests",$requests);


?>