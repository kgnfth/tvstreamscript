<div class="row-fluid">
    <div class="span12">
        <h3 class="heading">Upgrade from version 1.8</h3>
        <br /><br />
        <div class="row-fluid">
            <div class="span1"></div>
                <div class="span10">

                    <form method="post" class="stepy-wizzard form-horizontal" id="simple_wizard" method="post">
                        <fieldset title="" class="step" id="simple_wizard-step-0">
                            <?php 
                                if (isset($_POST['doupgrade']) && $_POST['doupgrade']){

                                    require_once("../vars.php");
                                    print("Updating database schema...");
                                    
                                    $check = mysql_query("SELECT 1 FROM activity");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `activity` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `user_id` int(11) NOT NULL,
                                                                  `target_id` int(11) NOT NULL,
                                                                  `user_data` text NOT NULL,
                                                                  `target_data` text NOT NULL,
                                                                  `target_type` tinyint(4) NOT NULL,
                                                                  `event_date` datetime NOT NULL,
                                                                  `event_type` tinyint(4) NOT NULL,
                                                                  `event_comment` varchar(500) NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `user_id` (`user_id`),
                                                                  KEY `target_id` (`target_id`)
                                                                ) DEFAULT CHARSET=utf8") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM broken_episodes");
                                    if ($check === false){
                                        $upd = mysql_query("RENAME TABLE `brokenlinks` TO `broken_episodes`") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `broken_episodes` ADD `user_id` INT NOT NULL , ADD `user_agent` VARCHAR( 255 ) NOT NULL") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM broken_movies");
                                    if ($check === false){
                                        $upd = mysql_query("RENAME TABLE `brokenmovies` TO `broken_movies`") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `broken_movies` ADD `user_id` INT NOT NULL , ADD `user_agent` VARCHAR( 255 ) NOT NULL") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM shows");
                                    if ($check === false){
                                        $upd = mysql_query("RENAME TABLE `categories` TO `shows`") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `shows` DROP `yidio_url`") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `shows` ADD `imdb_id` VARCHAR( 20 ) NOT NULL AFTER `sidereel_url`") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `shows` ADD INDEX ( `imdb_id` )") or die(mysql_error());                                    
                                        $upd = mysql_query("ALTER TABLE `shows` ADD `featured` TINYINT NOT NULL ,ADD `last_episode` DATETIME NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `shows` ADD INDEX ( `featured` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `shows` ADD INDEX ( `last_episode` )") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT imdb_rating FROM shows LIMIT 1");
                                    if ($check == false){
                                        $upd = mysql_query("ALTER TABLE `shows` ADD `imdb_rating` FLOAT NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `shows` ADD INDEX ( `imdb_rating` )") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT meta FROM shows LIMIT 1");
                                    if ($check == false){
                                        $upd = mysql_query("ALTER TABLE `shows` ADD `meta` TEXT NOT NULL") or die(mysql_error());
                                    }
                                                                        
                                    $upd = mysql_query("DROP TABLE IF EXISTS `comments`") or die(mysql_error());                                    
                                    $upd = mysql_query("DROP TABLE IF EXISTS `countries`") or die(mysql_error());                                    
                                    $upd = mysql_query("DROP TABLE IF EXISTS `cpalead`") or die(mysql_error());
                                    
                                    $check = mysql_query("SELECT link FROM embeds LIMIT 1");
                                    if ($check === false){
                                        $upd = mysql_query("ALTER TABLE `embeds` ADD `link` VARCHAR( 255 ) NOT NULL ,ADD `lang` VARCHAR( 20 ) NOT NULL ,ADD `weight` INT NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `embeds` ADD INDEX ( `weight` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `embeds` ADD INDEX ( `lang` )") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT checked FROM episodes LIMIT 1");
                                    if ($check === false){
                                        $upd = mysql_query("ALTER TABLE `episodes` CHANGE `category_id` `show_id` INT( 11 ) NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `episodes` CHANGE `date_added` `date_added` DATETIME NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `episodes` ADD `checked` TINYINT NOT NULL ") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT * FROM episodes WHERE embed!=''") or die(mysql_error());
                                    if (mysql_num_rows($check)){
                                        while($s = mysql_fetch_assoc($check)){
                                            $ins = mysql_query("INSERT INTO embeds(episode_id, embed, link, lang, weight) VALUES('{$s['id']}','".mysql_real_escape_string(stripslashes($s['embed']))."','','ENG','0')") or die(mysql_error());
                                            $upd = mysql_query("UPDATE episodes SET embed='' WHERE id='{$s['id']}'") or die(mysql_error());
                                        }
                                    }
                                    
                                    $upd = mysql_query("UPDATE embeds SET lang='ENG' WHERE lang=''") or die(mysql_error());
                                    
                                    $check = mysql_query("SELECT 1 FROM friends");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `friends` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `user1` int(11) NOT NULL,
                                                                  `user2` int(11) NOT NULL,
                                                                  `date_added` datetime NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `user1` (`user1`),
                                                                  KEY `user2` (`user2`)
                                                                ) DEFAULT CHARSET=utf8");
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM likes");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `likes` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `user_id` int(11) NOT NULL,
                                                                  `target_id` int(11) NOT NULL,
                                                                  `target_type` tinyint(4) NOT NULL,
                                                                  `comment` varchar(500) NOT NULL,
                                                                  `vote` tinyint(4) NOT NULL,
                                                                  `date_added` datetime NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `user_id` (`user_id`),
                                                                  KEY `target_id` (`target_id`),
                                                                  KEY `target_type` (`target_type`),
                                                                  KEY `date_added` (`date_added`)
                                                                ) DEFAULT CHARSET=utf8");
                                    }
                                    
                                    $upd = mysql_query("DROP TABLE IF EXISTS `links`") or die(mysql_error());
                                    
                                    $check = mysql_query("SELECT 1 FROM movie_ratings");
                                    if ($check === false){
                                        $upd = mysql_query("RENAME TABLE `movieratings` TO `movie_ratings`") or die(mysql_error());   
                                    }
                                    
                                    $check = mysql_query("SELECT imdb_id FROM movies LIMIT 1");
                                    if ($check === false){
                                        $upd = mysql_query("ALTER TABLE `movies` ADD `imdb_id` VARCHAR( 20 ) NOT NULL ,ADD `imdb_rating` FLOAT NOT NULL ,ADD `date_added` DATETIME NOT NULL ,ADD `meta` TEXT NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `movies` ADD INDEX ( `title` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `movies` ADD INDEX ( `imdb_id` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `movies` ADD INDEX ( `perma` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `movies` ADD INDEX ( `imdb_rating` )") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT link FROM movie_embeds LIMIT 1");
                                    if ($check === false){
                                        $upd = mysql_query("ALTER TABLE `movie_embeds` ADD `link` VARCHAR( 255 ) NOT NULL ,ADD `lang` VARCHAR( 255 ) NOT NULL ,ADD `weight` INT NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `movie_embeds` ADD INDEX ( `lang` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `movie_embeds` ADD INDEX ( `weight` )") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT * FROM movies WHERE embed!=''") or die(mysql_error());
                                    if (mysql_num_rows($check)){
                                        while($s = mysql_fetch_assoc($check)){
                                            $ins = mysql_query("INSERT INTO movie_embeds(movie_id, embed, link, lang, weight) VALUES('{$s['id']}','".mysql_real_escape_string(stripslashes($s['embed']))."','','ENG','0')") or die(mysql_error());
                                            $upd = mysql_query("UPDATE movies SET embed='' WHERE id='{$s['id']}'") or die(mysql_error());
                                        }
                                    }
                                    
                                    $upd = mysql_query("UPDATE movie_embeds SET lang='ENG' WHERE lang=''") or die(mysql_error());
                                    
                                    $upd = mysql_query("ALTER TABLE `movie_tags` CHANGE `tag` `tag` VARCHAR( 100 ) NOT NULL") or die(mysql_error());
                                    
                                    $check = mysql_query("SELECT parent_id FROM pages LIMIT 1");
                                    if ($check === false){
                                        $upd = mysql_query("ALTER TABLE `pages` CHANGE `title` `title` TEXT  NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `pages` ADD `parent_id` INT NOT NULL ,ADD `visible` TINYINT NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `pages` ADD INDEX ( `permalink` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `pages` ADD INDEX ( `parent_id` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `pages` ADD INDEX ( `visible` )") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM requests");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `requests` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `user_id` int(11) NOT NULL,
                                                                  `request_date` datetime NOT NULL,
                                                                  `message` text NOT NULL,
                                                                  `response` text NOT NULL,
                                                                  `status` tinyint(4) NOT NULL,
                                                                  `votes` int(11) NOT NULL default '1',
                                                                  PRIMARY KEY  (`id`)
                                                                ) DEFAULT CHARSET=utf8");
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM request_votes");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `request_votes` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `request_id` int(11) NOT NULL,
                                                                  `user_id` int(11) NOT NULL,
                                                                  `vote_date` datetime NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `request_id` (`request_id`),
                                                                  KEY `user_id` (`user_id`)
                                                                ) DEFAULT CHARSET=utf8");
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM similar_movies");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `similar_movies` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `movie1` int(11) NOT NULL,
                                                                  `movie2` int(11) NOT NULL,
                                                                  `score` int(11) NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `movie1` (`movie1`),
                                                                  KEY `movie2` (`movie2`)
                                                                ) DEFAULT CHARSET=utf8 ");
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM similar_shows");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `similar_shows` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `show1` int(11) NOT NULL,
                                                                  `show2` int(11) NOT NULL,
                                                                  `score` int(11) NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `show1` (`show1`),
                                                                  KEY `show2` (`show2`)
                                                                ) DEFAULT CHARSET=utf8");
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM submitted_links");
                                    if ($check === false){                                    
                                        $table = mysql_query("CREATE TABLE `submitted_links` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `user_id` int(11) NOT NULL,
                                                                  `type` tinyint(4) NOT NULL,
                                                                  `imdb_id` varchar(20) NOT NULL,
                                                                  `season` int(11) NOT NULL,
                                                                  `episode` int(11) NOT NULL,
                                                                  `link` varchar(255) NOT NULL,
                                                                  `language` varchar(10) NOT NULL,
                                                                  `date_submitted` datetime NOT NULL,
                                                                  `status` tinyint(4) NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `user_id` (`user_id`),
                                                                  KEY `type` (`type`),
                                                                  KEY `imdb_id` (`imdb_id`),
                                                                  KEY `season` (`season`),
                                                                  KEY `episode` (`episode`),
                                                                  KEY `language` (`language`),
                                                                  KEY `status` (`status`)
                                                                ) DEFAULT CHARSET=utf8 ");
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM tv_submits");
                                    if ($check === false){
                                        $upd = mysql_query("RENAME TABLE `tvsubmits` TO `tv_submits` ;") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT fb_id FROM users LIMIT 1");
                                    if ($check === false){
                                        $upd = mysql_query("ALTER TABLE `users` ADD `fb_id` BIGINT NOT NULL , ADD `fb_session` VARCHAR( 200 ) NOT NULL ,ADD `avatar` VARCHAR( 100 ) NOT NULL ,ADD `notify_favorite` TINYINT NOT NULL ,ADD `notify_new` TINYINT NOT NULL ,ADD `language` VARCHAR( 2 ) NOT NULL") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `users` ADD INDEX ( `notify_favorite` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `users` ADD INDEX ( `notify_new` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `users` ADD INDEX ( `username` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `users` ADD INDEX ( `password` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `users` ADD INDEX ( `fb_id` )") or die(mysql_error());
                                        $upd = mysql_query("ALTER TABLE `users` ADD INDEX ( `language` )") or die(mysql_error());
                                    }
                                    
                                    $check = mysql_query("SELECT 1 FROM watches");
                                    if ($check === false){
                                        $table = mysql_query("CREATE TABLE `watches` (
                                                                  `id` int(11) NOT NULL auto_increment,
                                                                  `user_id` int(11) NOT NULL,
                                                                  `target_id` int(11) NOT NULL,
                                                                  `target_type` tinyint(4) NOT NULL,
                                                                  `date_added` datetime NOT NULL,
                                                                  PRIMARY KEY  (`id`),
                                                                  KEY `user_id` (`user_id`),
                                                                  KEY `target_id` (`target_id`),
                                                                  KEY `target_type` (`target_type`)
                                                                ) DEFAULT CHARSET=utf8");
                                    }
                                    $upd = mysql_query("DROP TABLE IF EXISTS `livetv_channels`") or die(mysql_error());
                                    $upd = mysql_query("DROP TABLE IF EXISTS `livetv_tags`") or die(mysql_error());
                                    $upd = mysql_query("DROP TABLE IF EXISTS `livetv_tags_join`") or die(mysql_error());

                                    print("<span style='color:#00aa00'>Success</span><br />");
                                    
                                    print("Updating movies...");
                                    
                                    $e = mysql_query("SELECT * FROM movies") or die(mysql_error());
                                    if (mysql_num_rows($e)){
                                        while($s = mysql_fetch_assoc($e)){
                                            $title = json_decode($s['title'], true);
                                            if (!is_array($title) || !$title){
                                                $title = array("en" => $s['title']);
                                                $title = mysql_real_escape_string(json_encode($title));
                                                
                                                $description = array("en" => $s['description']);
                                                $description = mysql_real_escape_string(json_encode($description));
                                                
                                                $up = mysql_query("UPDATE movies SET title='$title', description='$description' WHERE id='{$s['id']}'") or die(mysql_error());
                                            }
                                        }
                                    }
                                    
                                    print("<span style='color:#00aa00'>Success</span><br />");
                                    
                                    print("Updating shows...");
                                    
                                    $e = mysql_query("SELECT * FROM shows") or die(mysql_error());
                                    if (mysql_num_rows($e)){
                                        while($s = mysql_fetch_assoc($e)){
                                            $title = json_decode($s['title'], true);
                                            if (!is_array($title) || !$title){
                                                $title = array("en" => $s['title']);
                                                $title = mysql_real_escape_string(json_encode($title));
                                                
                                                $description = array("en" => $s['description']);
                                                $description = mysql_real_escape_string(json_encode($description));
                                                
                                                $up = mysql_query("UPDATE shows SET title='$title', description='$description' WHERE id='{$s['id']}'") or die(mysql_error());
                                            }
                                        }
                                    }
                                    
                                    print("<span style='color:#00aa00'>Success</span><br />");
                                    
                                    print("Updating embeds...");
                                    
                                    
                                    $up = mysql_query("UPDATE embeds SET lang='ENG' WHERE lang=''") or die(mysql_error());
                                    $up = mysql_query("UPDATE movie_embeds SET lang='ENG' WHERE lang=''") or die(mysql_error());
                                    
                                    print("<span style='color:#00aa00'>Success</span><br />");
                                    
                                    print("Updating categories...");
                                    
                                    $e = mysql_query("SELECT * FROM tv_tags") or die(mysql_error());
                                    if (mysql_num_rows($e)){
                                        while($s = mysql_fetch_assoc($e)){
                                            $tag = json_decode($s['tag'], true);
                                            if (!is_array($tag) || !$tag){
                                                $tag = mysql_real_escape_string(json_encode(array("en" => $s['tag'])));
                                                $up = mysql_query("UPDATE tv_tags SET tag='$tag' WHERE id='{$s['id']}'") or die(mysql_error());
                                            }
                                        }
                                    }
                                    
                                    $e = mysql_query("SELECT * FROM movie_tags") or die(mysql_error());
                                    if (mysql_num_rows($e)){
                                        while($s = mysql_fetch_assoc($e)){
                                            $tag = json_decode($s['tag'], true);
                                            if (!is_array($tag) || !$tag){
                                                $tag = mysql_real_escape_string(json_encode(array("en" => $s['tag'])));
                                                $up = mysql_query("UPDATE movie_tags SET tag='$tag' WHERE id='{$s['id']}'") or die(mysql_error());
                                            }
                                        }
                                    }
                                    
                                    print("<span style='color:#00aa00'>Success</span><br />");
                                    
                                    print("Updating SEO settings...");
                                    
                                    $e = mysql_query("SELECT * FROM settings WHERE title LIKE '%_description' OR title LIKE '%_keywords' OR title LIKE '%_title'") or die(mysql_error());
                                    if (mysql_num_rows($e)){
                                        while($s = mysql_fetch_assoc($e)){
                                            $value = json_decode($s['value'], true);
                                            if (!is_array($value) || !$value){
                                                $value = mysql_real_escape_string(json_encode(array("en" => $s['value'])));
                                                $up = mysql_query("UPDATE settings SET value='$value' WHERE id='{$s['id']}'") or die(mysql_error());
                                            }
                                        }
                                    }
                                    
                                    print("<span style='color:#00aa00'>Success</span><br />");
                                    
                                    print("Updating modules...");
                                    
                                    //$del = mysql_query("DELETE FROM modules");
                                    $e = mysql_query("SELECT * FROM modules WHERE perma='tv_shows'") or die(mysql_error());
                                    if (mysql_num_rows($e) == 0){
                                        $ins = mysql_query("INSERT INTO `modules` VALUES (1, 'tv_shows', 'TV shows', 1)");    
                                    }
                                    
                                    $e = mysql_query("SELECT * FROM modules WHERE perma='movies'") or die(mysql_error());
                                    if (mysql_num_rows($e) == 0){
                                        $ins = mysql_query("INSERT INTO `modules` VALUES (2, 'movies', 'Movies', 1)");    
                                    }
                                    
                                    $e = mysql_query("SELECT * FROM modules WHERE perma='submit_links'") or die(mysql_error());
                                    if (mysql_num_rows($e) == 0){
                                        $ins = mysql_query("INSERT INTO `modules` VALUES (7, 'submit_links', 'Submit links', 1)"); 
                                    }
                                    
                                    $e = mysql_query("SELECT * FROM modules WHERE perma='requests'") or die(mysql_error());
                                    if (mysql_num_rows($e) == 0){
                                        $ins = mysql_query("INSERT INTO `modules` VALUES (6, 'requests', 'Requests', 1)");  
                                    }
                                    
                                    print("<span style='color:#00aa00'>Success</span><br />");
                                    
                                    
                                    print("<br />All done");
                                    
                                } else {
                            ?>
                                <div class="alert">
                                      <button type="button" class="close" data-dismiss="alert">×</button>
                                      <strong>IMPORTANT: </strong> Before you start the upgrade process make a backup of your database and your files. 
                                </div>
                                <div class="center" style="text-align:center">
                                    <input type="hidden" name="menu" value="upgrade" />
                                    <input type="submit" name="doupgrade" value="Start upgrading" class="btn btn-primary btn-large" style="margin:0 auto; float:none;" />
                                </div>
                            <?php 
                                }
                            ?>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>