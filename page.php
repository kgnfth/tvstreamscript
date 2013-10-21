<?php

if (!isset($_GET['permalink'])){
	header("Location: $baseurl");
	exit();
}

$page_data = $page->getByPerma($_GET['permalink'], $language);

if (!count($page_data)){
	header("Location: $baseurl");
	exit();
}

$smarty->assign("page",$page_data); 
?>