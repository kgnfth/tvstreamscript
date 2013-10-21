<?php
$license_key = "http://tvstreamscript.tk";

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "mysql";
$dbname = "tvss";
 
$baseurl = "http://localhost/tvss";
$basepath = "C:/Ampps/www/tvss";
$sitename = "tvs";

$siteslogan = "";
 
mysql_connect($dbhost,$dbuser,$dbpass); mysql_select_db($dbname);
 
mysql_query("SET NAMES 'utf8'");
mysql_set_charset('utf8'); 

?>