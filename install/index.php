<?php

header('Content-Type:text/html; charset=UTF-8');
session_start();
set_time_limit(0);
ob_start();

error_reporting(E_ALL);
ini_set("display_errors","On");
ini_set('output_buffering','on');  
ini_set('zlib.output_compression', 0);
ini_set('default_charset', 'UTF-8');

require_once("header.php");

if (isset($_GET['menu'])){
	$menu = preg_replace("/[^a-zA-Z0-9_\-]/","",$_GET['menu']);
} elseif (isset($_POST['menu'])){
	$menu = preg_replace("/[^a-zA-Z0-9_\-]/","",$_POST['menu']);
} else {
	$menu = "install";
}

if (!file_exists($menu.".php")){
	$menu = "install";
}

require_once($menu.".php");

require_once("footer.php");

