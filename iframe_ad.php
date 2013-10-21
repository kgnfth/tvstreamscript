<?php

session_start();
require_once("vars.php");
require_once("includes/settings.class.php");

$settings = new Settings();
$widgets = $settings->getWidgets();

if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id']){
	$logged = false;
} else {
	$logged = true;
}

?>
<html>
<head>
	<title></title>
</head>

	<body style="padding:0px; margin: 0px;">
		<?php
			if (isset($widgets['iframe_ad']) && isset($widgets['iframe_ad']['content']) && isset($widgets['iframe_ad']['logged']) && (!$logged || $widgets['iframe_ad']['logged'])){
				print($widgets['iframe_ad']['content']);
			}
		?>
	</body>
</html>