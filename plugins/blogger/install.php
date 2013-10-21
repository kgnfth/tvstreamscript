<?php
if (!defined("IN_SCRIPT")){
    print("<script> window.location = 'index.php'; </script>");
    exit();
}

print("Creating database tables...");

mysql_query("CREATE TABLE IF NOT EXISTS `blogger_posts` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(500) NOT NULL,
  `perma` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `language` varchar(3) NOT NULL,
  `original_url` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `language` (`language`),
  KEY `perma` (`perma`),
  KEY `original_url` (`original_url`)
)") or die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `blogger_tags` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tag` VARCHAR( 100 ) NOT NULL ,
`perma` VARCHAR( 100 ) NOT NULL
)") or die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `blogger_post_tags` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`),
  KEY `tag_id` (`tag_id`)
)") or die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `blogger_feeds` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(500) NOT NULL,
  `language` varchar(2) NOT NULL,
  `frequency` int(11) NOT NULL,
  `last_checked` datetime NOT NULL,
  PRIMARY KEY  (`id`)
)") or die(mysql_error());

print("<span style=\"color:#00aa00\"><strong>SUCCESS</strong></span><br />");

?>
<br />