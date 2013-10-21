<?php

class Show{
    
    public $episode_schema = array("show_id","season","episode","title","description","date_added","thumbnail","views","checked");
    public $embed_schema = array("episode_id","embed","link","lang","weight");
    public $show_schema = array("title","description","thumbnail","permalink","sidereel_url","imdb_id","type","featured","imdb_rating","meta");
    
    public function __construct(){
        
    }
    
    
    public function getList($page = null, $start=null, $limit=null, $sortby="id", $sortdir="DESC", $search_term = null){
        if (!$limit){
            $limit = 50;
        }
        
        $sortby = mysql_real_escape_string($sortby);
        $sortdir = mysql_real_escape_string($sortdir);
        
        $limit = mysql_real_escape_string($limit);
        
        
        if ($page){
            $page = mysql_real_escape_string($page);
            $start = ($page-1)*$limit;
            $limit = "LIMIT $start,$limit";
        } elseif ($start) {
            $start = mysql_real_escape_string($start);
            $limit = "LIMIT $start,$limit";
        } else {
            $limit = "LIMIT $limit";
        }
        
        if ($search_term){
            $search_term = " WHERE title LIKE '%".mysql_real_escape_string($search_term)."%'";
        } else {
            $search_term = "";
        }
        
        
        $e = mysql_query("SELECT * FROM shows $search_term ORDER BY $sortby $sortdir $limit") or die(mysql_error());
        $shows = array();
        $ids = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                extract($s);
                $shows[$id]=$s;
                $shows[$id]['title'] = json_decode($shows[$id]['title'],true);
                $shows[$id]['description'] = json_decode($shows[$id]['description'],true);
                $shows[$id]['episode_count'] = 0;
                $ids[] = $id;                
            }
            
            
            $e = mysql_query("SELECT count(*) as episode_count, show_id FROM episodes WHERE show_id IN (".implode(",",$ids).") GROUP BY show_id") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $shows[$s['show_id']]['episode_count'] = $s['episode_count'];
                }
            }
        }
        return $shows;
    }
    
    /* Returns all the possible embed languages from the database */
    public function getEmbedLanguages(){
        if (isset($_SESSION['embed_languages'])){
            return $_SESSION['embed_languages'];
        } else {
            $res = array();
            $e = mysql_query("SELECT DISTINCT lang FROM embeds") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $res[] = $s['lang'];
                }
            }
            
            $_SESSION['embed_languages'] = $res;
            return $res;
        }
    }
    
    /* Gets $limit similar show to $show_id */
    public function getSimilar($show_id, $limit, $lang=false){
        $show_id = mysql_real_escape_string($show_id);
        $res = array();
        $query = "SELECT shows.* FROM shows, similar_shows WHERE similar_shows.show1 = '$show_id' AND similar_shows.show2 = shows.id ORDER BY score DESC LIMIT $limit";
        
        $e = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $this->formatShowData($s, false, $lang);
            }
        }
        return $res;
    }
    
    /* Returns the newest episodes, don't care about if it has embed or not */
    public function getNewestEpisodes($date_from,$limit=20){
        $date_from = date("Y-m-d",strtotime($date_from));
        $res = array();
        
        $e = mysql_query("SELECT episodes.id, episodes.season, episodes.episode, episodes.thumbnail, shows.title, shows.permalink FROM episodes,shows WHERE shows.id = episodes.show_id AND episodes.date_added>='$date_from' ORDER BY id DESC LIMIT $limit") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $s;
            }
        }
        
        return $res;
    }
    
    /* Method to add an embed code to an episode, doesn't add it if it's already there */
    public function addEmbed($episode_id, $embed, $lang='ENG', $link='', $weight=0){
        $episode_id = mysql_real_escape_string($episode_id);
        $embed = mysql_real_escape_string(stripslashes(stripslashes(urldecode($embed))));
        $link = mysql_real_escape_string($link);
        $weight = mysql_real_escape_string($weight);
        
        $check = mysql_query("SELECT * FROM embeds WHERE embed='$embed' AND episode_id='$episode_id'") or die(mysql_error());
        if (mysql_num_rows($check)==0){
            $insert = mysql_query("INSERT INTO embeds(episode_id,embed,link,lang,weight) VALUES('$episode_id','$embed','$link','$lang','$weight')") or die(mysql_error());
            return mysql_insert_id();
        } else {
            return false;
        }
    }
    
    /* Removes all embeds of an episode */
    public function deleteAllEmbeds($episode_id){
        $episode_id = mysql_real_escape_string($episode_id);
        
        $del = mysql_query("DELETE FROM embeds WHERE episode_id='$episode_id'") or die(mysql_error());
    }
    
    /* Removes an embed from an episode */
    public function deleteEmbed($episode_id, $embed_id){
        $embed_id = mysql_real_escape_string($embed_id);
        $episode_id = mysql_real_escape_string($episode_id);
        $e = mysql_query("DELETE FROM embeds WHERE episode_id='$episode_id' AND id='$embed_id'") or die(mysql_error());
    }
    
    /* Updates the thumbnail for the episode */
    public function updateEpisodeThumbnail($episode_id,$thumb_file){
        $episode_id = mysql_real_escape_string($episode_id);
        $thumb_file = mysql_real_escape_string($thumb_file);
        
        $up = mysql_query("UPDATE episodes SET thumbnail='$thumb_file' WHERE id='$episode_id'") or die(mysql_error());
    }
    
    /* Updates the show and the episode date */
    public function setEpisodeDate($episode_id,$show_id){
        $episode_id = (int) $episode_id;
        $show_id = (int) $show_id;
        
        $up = mysql_query("UPDATE episodes SET date_added=NOW() WHERE id='$episode_id'");
        $up = mysql_query("UPDATE shows SET last_episode=NOW() WHERE id='$show_id'");
    }
    
    /* List shows based on views */
    public function getPopularShows($lang=false, $limit = 20){
        
        $limit = (int) $limit;
        
        $e = mysql_query("SELECT shows.title AS showtitle, show_id AS showid, shows.thumbnail, SUM( `views` ) AS views
                              FROM episodes, shows
                            WHERE shows.id = episodes.show_id
                            GROUP BY `show_id`
                            ORDER BY SUM(`views`) desc LIMIT $limit") or die(mysql_error());
        $topshows = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $topshows[$showid] = array();
                $topshows[$showid]['views'] = $views;
                $topshows[$showid]['thumbnail'] = $thumbnail;
                if (!$lang){
                    $topshows[$showid]['showtitle'] = json_decode($showtitle,true);
                } else {
                    $topshows[$showid]['showtitle'] = json_decode($showtitle,true);
                    $topshows[$showid]['showtitle'] = $topshows[$showid]['showtitle'][$lang];
                }
            }
        }
        return $topshows;
    }
    
    /* Updates an embed code */
    public function updateEmbedCode($episode_id, $embed_id, $embed_code){
        $embed_id = mysql_real_escape_string($embed_id);
        $episode_id = mysql_real_escape_string($episode_id);
        $embed_code = mysql_real_escape_string(stripslashes(stripslashes(urldecode($embed_code))));
        $e = mysql_query("UPDATE embeds SET embed='$embed_code' WHERE episode_id='$episode_id' AND id='$embed_id'") or die(mysql_error());
    }
    
    /* Returns a string representation of the embed's provider */
    public function getEmbedType($embed, $link = false){
        global $basepath;
        
        include($basepath."/includes/filehost.list.php");
        
        $embed = strtolower(stripslashes(stripslashes(urldecode($embed))));
        foreach($filehosts as $match => $filehost_name){
            if (substr_count($embed,$match)){
                return $filehost_name;
            }
        }
        if ($link){
            $url_parts = parse_url($link);
            if (isset($url_parts['host']) && $url_parts['host']){
                if (strpos($url_parts['host'],"www.") === 0){
                    $url_parts['host'] = substr($url_parts['host'],4);
                }
                return $url_parts['host'];
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    
    /* Returns all the embeds for the given episode id */
    public function getEpisodeEmbeds($epid){
        $embeds = array();
        $counter = 0;
        
        $e = mysql_query("SELECT * FROM embeds WHERE episode_id=$epid ORDER BY weight DESC") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                if ($embed){
                    
                    $embeds[$counter]['type'] = $this->getEmbedType($embed, $link);
                    $embeds[$counter]['embed']=stripslashes(stripslashes(urldecode($embed)));
                    $embeds[$counter]['link'] = $link;
                    $embeds[$counter]['id']=$id;
                    $embeds[$counter]['lang'] = $lang;
                    $embeds[$counter]['weight'] = $weight;
                    $counter++;
                }
            }
        }
        
        return $embeds;
    }
    
    /* Returnss the list of episodes which hasn't been submitted to a given submit target */        
    public function getUnsubmitted($type){
        
        $e = mysql_query("SELECT episode_id FROM tv_submits WHERE `type`=$type") or die(mysql_error());
        if (mysql_num_rows($e)){
            $tmp = array();
            while($s = mysql_fetch_array($e)){
                extract($s);
                $tmp[]=$episode_id;
            }
            
            $add = " AND episodes.id NOT IN (".implode(",",$tmp).")";
        } else {
            $add = "";
        }
        
        $e = mysql_query("SELECT episodes.id,episodes.season,episodes.episode,episodes.title,episodes.description,shows.title as showtitle,shows.permalink,shows.sidereel_url FROM episodes,shows WHERE shows.id=episodes.show_id $add") or die(mysql_error());
        $episodes = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                extract($s);
                $episodes[$id]=$s;
            }
        }
        
        return $episodes;
    }
    
    /* Get 3 dimensional array of the existing episodes from the database */
    public function getExistingEpisodes($showid){
        $e = mysql_query("SELECT season,episode FROM episodes WHERE show_id=$showid") or die(mysql_error());
        $eps = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                if (!array_key_exists($season,$eps)) $eps[$season]=array();
                $eps[$season][]=$episode;
            }
        }
        return $eps;
    }
   
    /* Returns a list of shows organized by ABC */
    public function getAlphabet($lang=false){
        $e = mysql_query("SELECT SUBSTRING(LOWER(`shows`.`title`),1,1) as `letter` FROM shows,episodes
                            WHERE `shows`.`id`=`episodes`.`show_id` AND `shows`.`type`=1
                            GROUP BY `letter` ORDER BY letter");
        $alphabet = array();
        if (mysql_num_rows($e)>0){
            while($s=mysql_fetch_array($e)){
                extract($s);
                $alphabet[$letter]=array();
                $shows = mysql_query("SELECT id,permalink,thumbnail,title,description FROM shows WHERE type=1 AND LOWER(`title`) LIKE '$letter%'") or die(mysql_error());
                if (mysql_num_rows($shows)){
                    while($sh=mysql_fetch_array($shows)){
                        extract($sh);
                        $alphabet[$letter][$id]=array();
                        $alphabet[$letter][$id]['perma'] = $permalink;
                        $alphabet[$letter][$id]['thumbnail'] = $thumbnail;
                        
                        if ($lang){
                            $alphabet[$letter][$id]['title'] = json_decode($title, true);
                            $alphabet[$letter][$id]['title'] = $alphabet[$letter][$id]['title'][$lang];
                            $alphabet[$letter][$id]['description'] = json_decode($description, true);
                            $alphabet[$letter][$id]['description'] = $alphabet[$letter][$id]['description'][$lang];                            
                        } else {
                            $alphabet[$letter][$id]['title'] = json_decode($title, true);
                            $alphabet[$letter][$id]['description'] = json_decode($description, true);
                        }
                    }
                }
            }
        }
        return $alphabet;
    }
    
    /* Returns the domain from an URL */
    private function getDomain($url){
        $url = strtolower($url);
        $url = str_replace("https://","",$url);
        $url = str_replace("http://","",$url);
        $url = str_replace("www.","",$url);
        $tmp = explode("%3f",$url);
        $url = $tmp[0]; 
        $tmp = explode("%2f",$url);
        $url = $tmp[0];  
        $tmp = explode("/",$url);
        $url = $tmp[0];
        $tmp = explode("?",$url);
        $url = $tmp[0];
        return $url;
    } 
    
    /* Check if the given season/episode exists for the given show */
    public function exists($showid,$season,$episode){
        $showid = mysql_real_escape_string($showid);
        $season = mysql_real_escape_string($season);
        $episode = mysql_real_escape_string($episode);
        
        $e = mysql_query("SELECT id FROM episodes WHERE show_id='$showid' AND episode='$episode' AND season='$season'") or die(mysql_error());
        if (mysql_num_rows($e)){
            return true;
        } else {
            return false;
        }
    }    
    
    public function getLink($link_id){
        $link_id = mysql_real_escape_string($link_id);
        
        $res = mysql_query("SELECT submitted_links.*, shows.id as show_id FROM submitted_links, shows WHERE submitted_links.`type`='1' AND submitted_links.imdb_id = shows.imdb_id AND submitted_links.id='$link_id'") or die(mysql_error());
        if (mysql_num_rows($res)){
            return mysql_fetch_assoc($res);
        } else {
            return false;
        }
    }
    
    /* Returns a list of user submitted links for the given episode */
    public function getLinks($status = null, $lang=false){

        if ($status !== null){
            $status_add = " AND submitted_links.status='$status'";
        } else {
            $status_add = "";
        }
        
        $links = array();
        $e = mysql_query("SELECT submitted_links.*, users.username, shows.title as show_title, shows.id as show_id 
                            FROM submitted_links 
                            LEFT JOIN shows ON shows.imdb_id=submitted_links.imdb_id 
                            LEFT JOIN users ON users.id=submitted_links.user_id
                            WHERE submitted_links.type = 1 $status_add
                            ORDER BY submitted_links.date_submitted DESC") or die(mysql_error());
        
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $links[$s['id']] = $s;
                
                if ($s['show_title']){
                    $links[$s['id']]['show_title'] = json_decode($links[$s['id']]['show_title'],true);
                    if ($lang){
                        $links[$s['id']]['show_title'] = $links[$s['id']]['show_title'][$lang];                        
                    } 
                }
            }
        }

        return $links;
    }

    /* Removes a show */
    public function deleteShow($showid){
        global $basepath;
        
        $showid = mysql_real_escape_string($showid);
        $e = mysql_query("SELECT thumbnail FROM shows WHERE id=$showid") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_array($e));
            
            if (isset($thumbnail) && $thumbnail){
                if (file_exists($basepath."/thumbs/".$thumbnail)){
                    unlink($basepath."/thumbs/".$thumbnail);
                }
            }
            
            $e = mysql_query("DELETE FROM broken_episodes WHERE episodeid IN (SELECT id FROM episodes WHERE show_id=$showid)") or die(mysql_error());
            $e = mysql_query("DELETE FROM ratings WHERE episodeid IN (SELECT id FROM episodes WHERE show_id=$showid)") or die(mysql_error());
            $e = mysql_query("DELETE FROM tv_submits WHERE episode_id IN (SELECT id FROM episodes WHERE show_id=$showid)") or die(mysql_error());
            $e = mysql_query("DELETE FROM embeds WHERE episode_id IN (SELECT id FROM episodes WHERE show_id='$showid')") or die(mysql_error());
            $e = mysql_query("DELETE FROM episodes WHERE show_id=$showid") or die(mysql_error());
            $e = mysql_query("DELETE FROM shows WHERE id=$showid") or die(mysql_error());
            $e = mysql_query("DELETE FROM tv_tags_join WHERE show_id=$showid") or die(mysql_error());
        }
    }
    
    /* Removes an episode */
    public function deleteEpisode($episode_id){
        global $basepath;
        
        $episode_id = mysql_real_escape_string($episode_id);
        $e = mysql_query("SELECT thumbnail FROM episodes WHERE id='$episode_id'") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            if ($thumbnail && file_exists($basepath."/thumbs/".$thumbnail)){
                unlink($basepath."/thumbs/".$thumbnail);
            }
            $e = mysql_query("DELETE FROM embeds WHERE episode_id='$episode_id'") or die(mysql_error());
            $e = mysql_query("DELETE FROM broken_episodes WHERE episodeid='$episode_id'") or die(mysql_error());
            $e = mysql_query("DELETE FROM ratings WHERE episodeid='$episode_id'") or die(mysql_error());
            $e = mysql_query("DELETE FROM tv_submits WHERE episode_id='$episode_id'") or die(mysql_error());
            $e = mysql_query("DELETE FROM episodes WHERE id='$episode_id'") or die(mysql_error());
        }        
    }

    /* Returns $limit number random shows (with episodes) */
    public function getRandomShow($limit, $lang = false, $excluded_ids = array()){
        
        $limit = (int) $limit;
        
        if (count($excluded_ids)){
            foreach($excluded_ids as $key => $val){
                $excluded_ids[$key] = mysql_real_escape_string($val);
            }
            
            $add = " AND id NOT IN (".implode(",",$excluded_ids).")";
        } else {
            $add = "";
        }
        
        $e = mysql_query("SELECT * FROM shows WHERE type=1 AND id IN (SELECT DISTINCT show_id FROM episodes) $add ORDER BY rand() LIMIT $limit") or die(mysql_error());
        $shows = array();
        if (mysql_num_rows($e)>0){
            while($s = mysql_fetch_assoc($e)){
                $shows[$s['id']] = $this->formatShowData($s, false, $lang);
            }
        }
        return $shows;
    }
    
    /* Returns a list of featured shows */
    public function getFeatured($limit=10, $lang = null){
        $shows = array();
        $e = mysql_query("SELECT * FROM shows WHERE `type`=1 AND featured='1' ORDER BY rand() LIMIT $limit") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $shows[$s['id']] = $this->formatShowData($s, false, $lang);
            }
        }    
        return $shows;    
    }
    
    /* Returns all the shows which have episodes */
    public function getAllShowsWithEpisodes($lang=null,$p=null,$l=null,$sortby=null){
        
        if ($p && $l){
            $start = ($p-1)*$l;
            $limit = " LIMIT $start,$l";
        } else {
            $limit = "";
        }
        
        if (!$sortby || $sortby == 'abc'){
            $order = "ORDER BY title ASC";
        } elseif ($sortby == 'date') {
            $order = "ORDER BY last_episode DESC";
        } elseif ($sortby == 'imdb_rating'){
            $order = "ORDER BY imdb_rating DESC";
        }
        
        $e = mysql_query("SELECT * FROM shows WHERE type=1 AND id IN (SELECT show_id FROM episodes) $order $limit") or die(mysql_error());
        $shows = array();
        if (mysql_num_rows($e)>0){
            while($s = mysql_fetch_array($e)){
                $shows[$s['id']] = $this->formatShowData($s, false, $lang);
            }
        }
        
        return $shows;
    }   
    
    /* Returns all the shows */
    public function getAllShows($p=null, $l=null, $lang = null){
        
        if ($p && $l){
            $start = ($p-1)*$l;
            $limit = " LIMIT $start,$l";
        } else {
            $limit = "";
        }
        
        $e = mysql_query("SELECT * FROM shows WHERE type=1 ORDER BY title ASC $limit") or die(mysql_error());
        $shows = array();
        if (mysql_num_rows($e)>0){
            while($s = mysql_fetch_array($e)){
                $shows[$s['id']] = $this->formatShowData($s, false, $lang);
            }
        }
        return $shows;
    }   
    
    /* Returns the list of seasons for a given show */
    public function getSeasons($show_id){
        $show_id = mysql_real_escape_string($show_id);
        
        $seasons = array();
        $e = mysql_query("SELECT season FROM episodes WHERE show_id='$show_id' GROUP BY season ORDER BY season ASC");
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $seasons[]=$season;
            }
        }
        return $seasons;
    }
    
    /* Returns a show matching title in given language */
    public function getShowByTitle($title, $lang = "en"){
        $title = mysql_real_escape_string($title);
        $title = preg_replace("/[^a-zA-Z0-9 ]+/i","%",$title);
        
        $lang = mysql_real_escape_string($lang);        
        $query = "SELECT * FROM shows WHERE type=1 AND title LIKE '%\"$lang\":\"$title\"%' LIMIT 1";
        
        $e = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($e)){
            return $this->formatShowData(mysql_fetch_assoc($e), false, $lang);
        } else {
            return array();
        }
    }
    
    public function getShowByImdb($imdb_id, $lang = "en"){
        $imdb_id = mysql_real_escape_string($imdb_id);        
          
        $query = "SELECT * FROM shows WHERE type=1 AND imdb_id='$imdb_id' LIMIT 1";
        
        $e = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($e)){
            return $this->formatShowData(mysql_fetch_assoc($e), false, $lang);
        } else {
            return array();
        }
    }
    
    public function formatShowData($data, $nested = true, $lang = false){
        if ($nested){
            $data['meta'] = json_decode($data['meta'], true);
            
            $shows[$data['id']]=$data;
            
            if (!$lang){
                $shows[$data['id']]['title'] = json_decode($shows[$data['id']]['title'],true);
                $shows[$data['id']]['description'] = json_decode($shows[$data['id']]['description'],true);
            } else {
                $shows[$data['id']]['title'] = json_decode($shows[$data['id']]['title'],true);
                $shows[$data['id']]['title'] = $shows[$data['id']]['title'][$lang];
                
                $shows[$data['id']]['description'] = json_decode($shows[$data['id']]['description'],true);
                $shows[$data['id']]['description'] = $shows[$data['id']]['description'][$lang];
            }
            
            return $shows;
        } else {
            $data['meta'] = json_decode($data['meta'], true);
            
            if (!$lang){
                $data['title'] = json_decode($data['title'],true);
                $data['description'] = json_decode($data['description'],true);
            } else {
                $data['title'] = json_decode($data['title'],true);
                $data['title'] = $data['title'][$lang];
                
                $data['description'] = json_decode($data['description'],true);
                $data['description'] = $data['description'][$lang];
            }

            return $data;
        }
    }

    /* Returns show details matching permalink */
    public function getShowByPerma($perma, $lang=null){
        $perma = mysql_real_escape_string($perma);
        $e = mysql_query("SELECT * FROM shows WHERE type=1 AND permalink='$perma'") or die(mysql_error());
        
        if (mysql_num_rows($e)>0){
            return $this->formatShowData(mysql_fetch_assoc($e), true, $lang);
        }
        
        return array();
    }
    
    /* Validate a show */
    public function validate($params,$update=0){
        $errors = array();
        
        if (!isset($_SESSION['global_languages'])){
            return array("99" => "Session expired");
        } else {
            
            foreach($_SESSION['global_languages'] as $lang_code => $lang_name){
                if (!isset($params['title'][$lang_code]) || !$params['title'][$lang_code]){
                    $errors[1][$lang_code] = "Please enter the $lang_name title for this show";
                }
                
                if (!isset($params['description'][$lang_code]) || !$params['description'][$lang_code]){
                    $errors[3][$lang_code] = "Please enter the $lang_name description for this show";
                }
            }
        
            if (!isset($params['thumbnail']) || !$params['thumbnail']){
                $errors[2]='Please upload a default image';
            }

            if (isset($params['sidereel_url']) && $params['sidereel_url'] && !substr_count($params['sidereel_url'],"http://www.sidereel.com/")){
                $errors[4] = "Invalid Sidereel url. URL format must be http://www.sidereel.com/[SHOWNAME]";
            }
            
            if (isset($params['imdb_rating']) && $params['imdb_rating'] && !is_numeric($params['imdb_rating'])){
                $errors[7] = "IMDB rating must be numeric";          
            }
            
            if (isset($params['year_started']) && $params['year_started'] && !is_numeric($params['year_started'])){
                $errors[8] = "Year must be numeric";
            }
            
            if (!isset($params['imdb_id']) || substr_count($params['imdb_id'],"tt")==0){
                $errors[6] = "Invalid IMDB id. It should be in format: tt12345";
            } else {
                $imdb = mysql_real_escape_string($params['imdb_id']);
                if (!$update){
                    $check = mysql_query("SELECT * FROM shows WHERE imdb_id='$imdb'") or die(mysql_error());
                } else {
                    $update = mysql_real_escape_string($update);
                    $check = mysql_query("SELECT * FROM shows WHERE imdb_id='$imdb' AND id!='$update'") or die(mysql_error());
                }
                
                if (mysql_num_rows($check)){
                    $errors[6] = "This IMDB id is already in use";
                }
                
            }
            
            return $errors;
        }
    }
    
    /* Method to validate a multi language tag */    
    public function validateCategory($category){
        $errors = array();
        if (!isset($_SESSION['global_languages'])){
            return array("99" => "Session expired");
        }
        
        foreach($_SESSION['global_languages'] as $lang_code => $lang_name){
            if (!isset($category[$lang_code]) || !$category[$lang_code]){
                $errors[$lang_code] = "Please enter a $lang_name category title";
            }
        }
        
        return $errors;
    }
    
    /* Method to add a multi-language tag */    
    public function addCategory($category){        
        $perma = $this->makePerma($category['en']);

        $category = mysql_real_escape_string(json_encode($category));
        
        $e = mysql_query("SELECT * FROM tv_tags WHERE tag='$category' OR perma='$perma'") or die(mysql_error());
        if (mysql_num_rows($e)==0){
            
            $e = mysql_query("INSERT INTO tv_tags(tag,perma) VALUES('$category','$perma')") or die(mysql_error());
            return 1;
            
        } else {
            return 0;
        }
    }
    
    /* Add / update show categories */
    public function saveCategories($showid,$categories){
        $showid = mysql_real_escape_string($showid);
        
        $e = mysql_query("DELETE FROM tv_tags_join WHERE show_id='$showid'") or die(mysql_error());
        
        foreach($categories as $key => $category_id){
            $val = mysql_real_escape_string($category_id);
            $e = mysql_query("INSERT INTO tv_tags_join(show_id,tag_id) VALUES('$showid','$category_id')") or die(mysql_error());
        }
    }
    
    /* Remove a tag and all of it's members */
    public function deleteTag($tagid){
        $tagid = mysql_real_escape_string($tagid);
        $e = mysql_query("DELETE FROM tv_tags_join WHERE tag_id='$tagid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM tv_tags WHERE id='$tagid'") or die(mysql_error());
    }
    
    public function getShowCountByCategory($tag_id){
        $tag_id = mysql_real_escape_string($tag_id);
        $e = mysql_query("SELECT count(*) as show_count FROM shows WHERE id IN (SELECT show_id FROM tv_tags_join WHERE tag_id='$tag_id')") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            return $show_count;
        } else {
            return 0;
        }
    }
    
    /* Returns all the shows matching a tag */
    public function getShowsByCategory($tag_id, $lang = null, $page = 1, $limit = 40, $sortby = "date"){
        $tag_id = mysql_real_escape_string($tag_id);
        
        $page = (int) $page;
        $limit = (int) $limit;
        
        if (!$page) $page = 1;
        if (!$limit) $limit = 1;
        
        $start = ($page-1) * $limit;
        $limit = " LIMIT $start, $limit";
        
        if (!$sortby || $sortby == 'abc'){
            $order = "ORDER BY title ASC";
        } elseif ($sortby == 'date') {
            $order = "ORDER BY last_episode DESC";
        } elseif ($sortby == 'imdb_rating'){
            $order = "ORDER BY imdb_rating DESC";
        }
        
        $e = mysql_query("SELECT * FROM shows WHERE id IN (SELECT show_id FROM tv_tags_join WHERE tag_id='$tag_id') $order $limit") or die(mysql_error());
        
        $shows = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $shows[$s['id']] = $this->formatShowData($s, false, $lang);
            }
        }
        return $shows;
    }
    
    /* Returns a category matching a permalink */
    public function getCategoryByPerma($perma, $lang = null){
        $perma = mysql_real_escape_string($perma);
        $e = mysql_query("SELECT id,tag FROM tv_tags WHERE perma='$perma'") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_array($e));
            
            $tag = json_decode($tag,true);
            if ($lang){
                $tag = $tag[$lang];
            }            
            return array("id" => $id, "tag" => $tag);
        } else {
            return '';
        }
    }
    
    /* Method to retrieve all the tags for a given show */
    public function getShowCategories($show_id, $get_info = false, $lang = false){
        $show_id = mysql_real_escape_string($show_id);
        $tags = array();
        
        if (!$get_info){
            $e = mysql_query("SELECT tag_id FROM tv_tags_join WHERE show_id='$show_id'") or die(mysql_error());
            
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_array($e)){
                    extract($s);
                    $tags[]=$tag_id;
                }
            }        
        } else {
            $e = mysql_query("SELECT * FROM tv_tags, tv_tags_join WHERE tv_tags_join.show_id='$show_id' AND tv_tags_join.tag_id=tv_tags.id") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $tags[$s['id']] = $s;
                    $tags[$s['id']]['tag'] = json_decode($tags[$s['id']]['tag'], true);
                    
                    if ($lang){                        
                        if (isset($tags[$s['id']]['tag'][$lang])){
                            $tags[$s['id']]['tag'] = $tags[$s['id']]['tag'][$lang]; 
                        } elseif (isset($tags[$s['id']]['tag']['en'])) {
                            $tags[$s['id']]['tag'] = $tags[$s['id']]['tag']['en'];
                        }
                    }
                }
            }
        }
        return $tags;
    }
    
    /* Lists all the categories */
    public function getCategories($lang=null, $limit=0, $order="tag ASC"){
        $categories = array();
        
        if ($limit){ $add = "LIMIT $limit"; } else { $add = ''; }
        
        $e = mysql_query("SELECT * FROM tv_tags ORDER BY $order") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                
                $tag = json_decode($tag,true);
                if ($lang){
                    $tag = $tag[$lang];
                }
                
                $categories[$id]['name']=$tag;
                $categories[$id]['perma']=$perma;
            }
        }
        
        return $categories;
    }
    
    /* Returns $limit latest episodes */ 
    public function getLatestEpisodes($limit){
        $maxepisodes = mysql_query("SELECT MAX(`id`) as maxid FROM episodes GROUP BY show_id") or die(mysql_error());
        $episodes = array();
        
        if (mysql_num_rows($maxepisodes)){
            
            $maxids = array();
            while($s = mysql_fetch_array($maxepisodes)){
                $maxids[]=$s['maxid'];
            }
            
            $query = "SELECT shows.id as showid,shows.permalink,episodes.thumbnail,shows.thumbnail as category_thumbnail,shows.title as showtitle,episodes.episode, episodes.season, episodes.description, episodes.title as episodetitle
                            FROM episodes, shows
                            WHERE episodes.show_id=shows.id AND episodes.id IN (".implode(",",$maxids).")
                            GROUP by shows.id,episodes.season
                            ORDER BY episodes.id DESC LIMIT $limit";
                        
            $e = mysql_query($query) or die(mysql_error());
            
            if (mysql_num_rows($e)){
                while($s=mysql_fetch_array($e)){
                    extract($s);
                    $episodes[$showid]=array();
                    $episodes[$showid]['showtitle']=$showtitle;
                    $episodes[$showid]['showid']=$showid;
                    if ($thumbnail){
                        $episodes[$showid]['thumbnail']=$thumbnail;
                    } else {
                        $episodes[$showid]['thumbnail']=$category_thumbnail;
                    }
                    $episodes[$showid]['episode']=$episode;
                    $episodes[$showid]['season']=$season;
                    $episodes[$showid]['description']=$description;
                    $episodes[$showid]['permalink']=$permalink;
                    $episodes[$showid]['episodetitle']=$episodetitle;
                    if (!$episodes[$showid]['episodetitle']){
                        $episodes[$showid]['episodetitle'] = "Season $season, Episode $episode";
                    }
                    
                }
            }
        }
        return $episodes;
    }
    
    /* Get all the language flags for a given list of episode ids */    
    public function getEpisodeFlags($ids){
        $flags = array();
        // getting embed languages
        if (count($ids)){
            $e = mysql_query("SELECT episode_id,lang FROM embeds WHERE episode_id IN (".implode(",",$ids).")") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    if (!array_key_exists($s['episode_id'],$flags)){
                        $flags[$s['episode_id']] = array();
                    }
                    
                    if (!in_array($s['lang'],$flags[$s['episode_id']])){
                        $flags[$s['episode_id']][] = $s['lang'];
                    }
                }
            }
        }
        
        return $flags;
    }
    
    /* Method to return episodes where we have embed codes */    
    public function getRealLatestEpisodes($limit,$lang = null){
        $episodes = array();
        
        $query = "SELECT episodes.date_added, shows.id as showid,shows.permalink,episodes.thumbnail,shows.thumbnail as category_thumbnail,shows.title as showtitle,episodes.episode, episodes.season, episodes.description, episodes.title as episodetitle, episodes.id as epid
                        FROM episodes, shows
                        WHERE episodes.show_id=shows.id 
                        ORDER BY episodes.date_added DESC LIMIT $limit";
                    
        $e = mysql_query($query) or die(mysql_error());
        $counter = 0;
        if (mysql_num_rows($e)){
            
            $ids = array();
            while($s=mysql_fetch_array($e)){
                extract($s);
                $episodes[$counter]=array();
                
                if (!$lang){
                    $episodes[$counter]['showtitle'] = json_decode($showtitle,true);    
                } else {
                    $showtitle = json_decode($showtitle,true);
                    $episodes[$counter]['showtitle'] = $showtitle[$lang];
                }
                
                
                $episodes[$counter]['showid']=$showid;
                $episodes[$counter]['epid']=$epid;
                if ($thumbnail){
                    $episodes[$counter]['thumbnail']=$thumbnail;
                } else {
                    $episodes[$counter]['thumbnail']=$category_thumbnail;
                }
                $episodes[$counter]['episode']=$episode;
                $episodes[$counter]['season']=$season;
                $episodes[$counter]['description']=$description;
                $episodes[$counter]['permalink']=$permalink;
                $episodes[$counter]['episodetitle']=$episodetitle;
                $episodes[$counter]['date_added']=$date_added;
                if (!$episodes[$counter]['episodetitle']){
                    $episodes[$counter]['episodetitle'] = "Season $season, Episode $episode";
                }
                $counter++;
                
                $ids[] = $epid;
                
            }
            
            // getting embed languages
            $flags = $this->getEpisodeFlags($ids);
            foreach($episodes as $key => $val){
                if (array_key_exists($val['epid'],$flags)){
                    $episodes[$key]['languages'] = $flags[$val['epid']];
                } else {
                    $episodes[$key]['languages'] = array();
                }
            }
            
        }
        return $episodes;
    }
    
    /* Global validate function for a new episode */    
    public function validateEpisode($params, $no_embeds = false){
        $errors = array();
        
        if (!isset($params['show_id']) || !$params['show_id'] || !is_numeric($params['show_id'])){
            $errors[1] = "Please select a TV show";    
        }
        
        if (!isset($params['season']) || !$params['season'] || !is_numeric($params['season'])){
            $errors[2] = "Please enter the season number";
        }
        
        if (!isset($params['episode']) || !$params['episode'] || !is_numeric($params['episode'])){
            $errors[3] = "Please enter the episode number";
        }
        
        // checking for real embeds
        if (!$no_embeds){
            if (isset($params['from']) && $params['from']=="admin"){
                if (!isset($params['embed_enabled']) || !is_array($params['embed_enabled']) || !count($params['embed_enabled'])){
                    $errors[4] = "Please add at least one embed";
                } else {
                    $found = false;
                    foreach($params['embed_enabled'] as $embed_id => $val){
                        if (isset($params['embeds'][$embed_id]) && $params['embeds'][$embed_id]){
                            $found = true;
                            break;
                        }
                    }
                    
                    if (!$found){
                        $errors[4] = "Please add at least one embed";
                    }
                }
            } elseif (!isset($params['embeds']) || !is_array($params['embeds']) || !count($params['embeds'])){
                $errors[4] = "Please add at least one embed";
            }
        }
        
        return $errors;
    }
    
    /* Function to update episode data */
    public function updateEpisode($episode_id,$params){
        $episode_id = mysql_real_escape_string($episode_id);
        $updates = array();
        foreach($params as $key => $val){
            $updates[] = "`".mysql_real_escape_string($key)."`='".mysql_real_escape_string($val)."'";
        }
        
        $up = mysql_query("UPDATE episodes SET ".implode(",",$updates)." WHERE id='$episode_id'") or die(mysql_error());
    }
    
    /* Function to add a new episode with embed codes */    
    public function saveEpisode($params){
        $fields = array();
        $values = array();
        
        if (!isset($params['title']) || !$params['title']){
            $params['title'] = "Season ".$params['season']." Episode ".$params['episode'];
        }
        
        foreach($params as $key => $val){
            if (!is_array($val) && in_array($key,$this->episode_schema)){
                $fields[] = "`".mysql_real_escape_string($key)."`";
                $values[] = "'".mysql_real_escape_string($val)."'";
            }
        }
        
        $episode_id = $this->getEpisode($params['show_id'],$params['season'],$params['episode']);
        if (!$episode_id){
            $fields[] = "`date_added`";
            $values[] = "'".date("Y-m-d H:i:s")."'";
            
            $ins = mysql_query("INSERT INTO episodes(".implode(",",$fields).") VALUES(".implode(",",$values).")") or die(mysql_error());
            $episode_id = mysql_insert_id();
        }
        
        if (isset($params['embeds']) && is_array($params['embeds']) && count($params['embeds'])){
            foreach($params['embeds'] as $key => $val){
                $embed = mysql_real_escape_string(urldecode($val));
                if (isset($params['embed_langs'][$key])){
                    $language = mysql_real_escape_string($params['embed_langs'][$key]);
                } else {
                    $language = "ENG";
                }
                
                $this->addEmbed($episode_id,$embed,$language);
            }
        }
        
        $this->setEpisodeDate($episode_id,$params['show_id']);
        
        return $episode_id;
    }

    /* Makes a clean, url friendly representation of a string */    
    public function makePerma($str, $replace=array(), $delimiter='-') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }
    
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
    
        return $clean;
    }
    
    /* Saves a new show */
    public function save($params){
        
        $permalink = $this->makePerma($params['title']['en']);
        
        $title = mysql_real_escape_string(json_encode($params['title']));
        $description = mysql_real_escape_string(json_encode($params['description']));
        $thumbnail = mysql_real_escape_string($params['thumbnail']);
        if (isset($params['sidereel_url'])){
            $sidereel_url = mysql_real_escape_string($params['sidereel_url']);
        } else {
            $sidereel_url = '';
        }
        
        if (isset($params['featured']) && $params['featured']){
            $featured = 1;
        } else {
            $featured = 0;
        }
        
        if (isset($params['imdb_id'])){
            $imdb_id = mysql_real_escape_string($params['imdb_id']);
        } else {
            $imdb_id = "-1";
        }
        
        if (isset($params['imdb_rating']) && $params['imdb_rating']){
            $imdb_rating = mysql_real_escape_string($params['imdb_rating']);
        } else {
            $imdb_rating = 0;
        }
        
        $meta = array();
        
        if (isset($params['year_started']) && $params['year_started']){
            $meta['year_started'] = (int) $params['year_started'];
        }
        
        if (isset($params['stars']) && $params['stars'] && is_array($params['stars']) && count($params['stars'])){
            $stars = array();
            foreach($params['stars'] as $key => $star){
                if (trim($star)){
                    $stars[] = $star;
                }
            }
            if (count($stars)){
                $meta['stars'] = $stars;
            }
        }
        
        if (isset($params['creators']) && $params['creators'] && is_array($params['creators']) && count($params['creators'])){
            $creators = array();
            foreach($params['creators'] as $key => $creator){
                if (trim($creator)){
                    $creators[] = $creator;
                }
            }
            if (count($creators)){
                $meta['creators'] = $creators;
            }
        }
        
        if (count($meta)){
            $meta = mysql_real_escape_string(json_encode($meta));
        } else {
            $meta = '';
        }

        $e = mysql_query("INSERT INTO shows (title, imdb_id, description, thumbnail, permalink, type, sidereel_url, featured, imdb_rating, meta) 
                                            VALUES ('$title','$imdb_id','$description','$thumbnail','$permalink',1,'$sidereel_url','$featured','$imdb_rating','$meta')") or die(mysql_error());
        return mysql_insert_id();
    }
    
    /* Updates show_id with given parameters */
    public function update($params,$show_id){
        global $basepath;
        
        $show_id = mysql_real_escape_string($show_id);
        
        $check = mysql_query("SELECT thumbnail as thumbnail_to_delete FROM shows WHERE id='$show_id'") or die(mysql_error());
        if(mysql_num_rows($check)){
            
            extract(mysql_fetch_assoc($check));
            
            $title = mysql_real_escape_string(json_encode($params['title']));
            $description = mysql_real_escape_string(json_encode($params['description']));
            $thumbnail = mysql_real_escape_string($params['thumbnail']);
            
            if (isset($params['sidereel_url'])){
                $sidereel_url = mysql_real_escape_string($params['sidereel_url']);
            } else {
                $sidereel_url = '';
            }
            
            $imdb_id = mysql_real_escape_string($params['imdb_id']);
            
            if (isset($params['featured']) && $params['featured']){
                $featured = 1;
            } else {
                $featured = 0;
            }
            
            if (isset($params['imdb_rating']) && $params['imdb_rating']){
                $imdb_rating = mysql_real_escape_string($params['imdb_rating']);
            } else {
                $imdb_rating = 0;
            }
            
            $meta = array();
            
            if (isset($params['year_started']) && $params['year_started']){
                $meta['year_started'] = (int) $params['year_started'];
            }
            
            if (isset($params['stars']) && $params['stars'] && is_array($params['stars']) && count($params['stars'])){
                $stars = array();
                foreach($params['stars'] as $key => $star){
                    if (trim($star)){
                        $stars[] = $star;
                    }
                }
                if (count($stars)){
                    $meta['stars'] = $stars;
                }
            }
            
            if (isset($params['creators']) && $params['creators'] && is_array($params['creators']) && count($params['creators'])){
                $creators = array();
                foreach($params['creators'] as $key => $creator){
                    if (trim($creator)){
                        $creators[] = $creator;
                    }
                }
                if (count($creators)){
                    $meta['creators'] = $creators;
                }
            }
            
            if (count($meta)){
                $meta = mysql_real_escape_string(json_encode($meta));
            } else {
                $meta = '';
            }
    
            $e = mysql_query("UPDATE shows SET title='$title',description='$description',thumbnail='$thumbnail',sidereel_url='$sidereel_url', imdb_id='$imdb_id', featured='$featured', imdb_rating='$imdb_rating', meta='$meta' WHERE id=$show_id") or die(mysql_error());
            
            if ($thumbnail_to_delete!=$thumbnail && file_exists($basepath."/thumbs/".$thumbnail_to_delete)){
                unlink($basepath."/thumbs/".$thumbnail_to_delete);
            }            
            return true;
            
        } else {
            return false;
        }
    }   
    
    /* Returns a show based on id */
    public function getShow($id, $nested=1, $lang=false){
        $id = mysql_real_escape_string($id);
        $e = mysql_query("SELECT * FROM shows WHERE id='$id'") or die(mysql_error());
        if (mysql_num_rows($e)){
            return $this->formatShowData(mysql_fetch_assoc($e), $nested, $lang);
        } else {
            return array();
        }
    }   
    
    /* Returns all the shows having at least one episode */
    public function getShows($getcounts=null, $lang = false){
        $shows = array();
        
        if ($getcounts){
            $e = mysql_query("SELECT shows.*,count(*) as `cnt`,episodes.title as episodetitle FROM shows 
                                LEFT JOIN episodes ON shows.id=episodes.show_id WHERE shows.type=1 GROUP BY shows.id ORDER BY shows.title ASC") or die(mysql_error());
        } else {
            $e = mysql_query("SELECT * FROM shows WHERE type=1 ORDER BY title ASC") or die(mysql_error());
        }
        
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                extract($s);
                $shows[$s['id']]=$s;
                if (!$lang){
                    $shows[$s['id']]['title'] = json_decode($shows[$s['id']]['title'],true);
                    $shows[$s['id']]['description'] = json_decode($shows[$s['id']]['description'],true);
                } else {
                    $shows[$s['id']]['title'] = json_decode($shows[$s['id']]['title'],true);
                    $shows[$s['id']]['title'] = $shows[$s['id']]['title'][$lang];
                    
                    $shows[$s['id']]['description'] = json_decode($shows[$s['id']]['description'],true);
                    $shows[$s['id']]['description'] = $shows[$s['id']]['description'][$lang];
                }
                
                
                if ($getcounts){
                    if ((!$episodetitle) && ($cnt==1)){
                        $getcount = mysql_query("SELECT count(*) as cnt FROM episodes WHERE show_id=$id") or die(mysql_error());
                        extract(mysql_fetch_array($getcount));
                    }
                    $shows[$id]['episodecount']=$cnt;
                }
            }
            
        }
        return $shows;
    }
    
    /* Lists episodes belonging to a show / season */
    public function getEpisodes($showid, $season=null, $lang = false, $embed_lang = array()){
        $episodes = array();

        if ($season){
            $season = mysql_real_escape_string($season);
            $add = "AND season=$season";
        } else { 
            $add = ''; 
        }
        
        if (count($embed_lang)){
            foreach($embed_lang as $key => $val){
                $embed_lang[$key] = "'".$val."'";
            }
            $lang_add = " AND episodes.id IN (SELECT episode_id FROM embeds WHERE lang IN (".implode(",",$embed_lang)."))";
        } else {
            $lang_add = "";
        }

        $e = mysql_query("SELECT shows.id as showid,episodes.embed,episodes.title as episodetitle,shows.permalink,shows.thumbnail as category_thumbnail,episodes.thumbnail,shows.title as showtitle,episodes.id as epid,episodes.episode,episodes.description,episodes.season,episodes.title as episodetitle FROM episodes, shows WHERE episodes.show_id=shows.id AND episodes.show_id=$showid $add $lang_add ORDER BY episodes.season DESC,episodes.episode DESC") or die(mysql_error());
        if (mysql_num_rows($e)){
            $ids = array();
            while($s=mysql_fetch_array($e)){
                extract($s);
                $episodes[$epid]=array();
                $episodes[$epid]['episodetitle']=$episodetitle;
                
                if (!$lang){
                    $episodes[$epid]['title'] = json_decode($showtitle,true);
                } else {
                    $episodes[$epid]['title'] = json_decode($showtitle,true);
                    if (isset($episodes[$epid]['title'][$lang])){
                        $episodes[$epid]['title'] = $episodes[$epid]['title'][$lang];
                    } else {
                        $episodes[$epid]['title'] = $episodes[$epid]['title']['en'];
                    }
                }
                
                if ($thumbnail){
                    $episodes[$epid]['thumbnail'] = $thumbnail;
                } else {
                    $episodes[$epid]['thumbnail'] = $category_thumbnail;
                }
                
                if (!$episodes[$epid]['episodetitle']){
                    $episodes[$epid]['episodetitle'] = "Season $season, Episode $episode";
                }
                
                $episodes[$epid]['description']=$description;
                $episodes[$epid]['season']=$season;
                $episodes[$epid]['episode']=$episode;
                
                $episodes[$epid]['embed']=$embed;
                
                $ids[] = $epid;
            }
            
            // getting embed languages
            if (count($ids)){
                $flags = $this->getEpisodeFlags($ids);
                
                $flags = $this->getEpisodeFlags($ids);
                foreach($episodes as $episode_id => $val){
                    if (array_key_exists($episode_id,$flags)){
                        $episodes[$episode_id]['languages'] = $flags[$episode_id];
                    } else {
                        $episodes[$episode_id]['languages'] = array();
                    }
                }
            }
        }
        return $episodes;
    }
    
    /* Returns all episodes from the database */
    public function getAllEpisodes(){
        $episodes = array();
        $e = mysql_query("SELECT shows.id as showid,episodes.embed,episodes.title as episodetitle,shows.permalink,shows.thumbnail as category_thumbnail,episodes.thumbnail,shows.title as showtitle,episodes.id as epid,episodes.episode,episodes.description,episodes.season,episodes.title as episodetitle FROM episodes, shows WHERE episodes.show_id=shows.id ORDER BY episodes.season DESC,episodes.episode DESC") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s=mysql_fetch_array($e)){
                extract($s);
                $episodes[$epid]=array();
                $episodes[$epid]['title']=$showtitle;
                $episodes[$epid]['description']=$description;
                if ($thumbnail){
                    $episodes[$epid]['thumbnail']=$thumbnail;
                } else {
                    $episodes[$epid]['thumbnail']=$category_thumbnail;
                }
                $episodes[$epid]['season']=$season;
                $episodes[$epid]['episode']=$episode;
                $episodes[$epid]['episodetitle']=$episodetitle;
                if (!$episodes[$epid]['episodetitle']){
                    $episodes[$epid]['episodetitle'] = "Season $season, Episode $episode";
                }
                $episodes[$epid]['embed']=$embed;
            }
        }
        
        return $episodes;
    }
    
    /* Search by metadata */
    public function searchByMeta($params, $lang = null){
        if (isset($params['creator'])){
            $params['creator'] = preg_replace("/[^a-zA-Z0-9 ]/i","%",$params['creator']);
            $query = "meta LIKE '%\"creators\":[%\"".mysql_real_escape_string($params['creator'])."\"%]%'";
        } elseif (isset($params['star'])){
            $params['star'] = preg_replace("/[^a-zA-Z0-9 ]/i","%",$params['star']);
            $query = "meta LIKE '%\"stars\":[%\"".mysql_real_escape_string($params['star'])."\"%]%'";
        } elseif (isset($params['year_started'])){
            $params['year_started'] = (int) $params['year_started'];
            $query = "meta LIKE '%\"year_started\":\"".mysql_real_escape_string($params['year_started'])."\"%' OR meta LIKE '%\"year_started\":".mysql_real_escape_string($params['year_started'])."%'";           
        }
        
        $shows = array();
        
        $query = "SELECT * FROM shows WHERE $query ORDER BY last_episode DESC";
        $res = mysql_query($query) or die(mysql_error());

        if (mysql_num_rows($res)){
            while($s = mysql_fetch_assoc($res)){
                $shows[$s['id']] = $this->formatShowData($s, false, $lang);
            }
        }
        return $shows;
    }
    
    /* Search function */
    public function search($q, $lang=null){
        $episodes = array();
        
        $q = mysql_real_escape_string($q);
        $q = preg_replace("/[^a-zA-Z0-9 ]/i","%",$q);
        
        $query = "SELECT shows.id as show_id,episodes.embed,episodes.title as episodetitle,shows.permalink,shows.thumbnail as category_thumbnail,episodes.thumbnail,shows.title as showtitle,episodes.id as epid,episodes.episode,episodes.description,episodes.season,episodes.title as episodetitle FROM episodes, shows WHERE episodes.show_id=shows.id AND (episodes.title LIKE '%$q%' OR episodes.description LIKE '%$q%' OR shows.description LIKE '%$q%' OR shows.title LIKE '%$q%') ORDER BY episodes.season DESC,episodes.episode DESC";
        $e = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($e)){
            
            $ids = array();
            while($s=mysql_fetch_assoc($e)){
                extract($s);
                $episodes[$epid]=array();
                
                if (!$lang){
                    $episodes[$epid]['title'] = json_decode($showtitle,true);
                } else {
                    $showtitle = json_decode($showtitle,true);
                    $episodes[$epid]['title'] = $showtitle[$lang];
                }
                
                $episodes[$epid]['title'] = stripslashes($episodes[$epid]['title']);
                $episodes[$epid]['description'] = nl2br(stripslashes($description));
                
                if ($thumbnail){
                    $episodes[$epid]['thumbnail'] = $thumbnail;
                } else {
                    $episodes[$epid]['thumbnail'] = $category_thumbnail;
                }
                
                $episodes[$epid]['show_id']=$show_id;
                $episodes[$epid]['season']=$season;
                $episodes[$epid]['episode']=$episode;
                $episodes[$epid]['permalink']=$permalink;
                $episodes[$epid]['episodetitle']=$episodetitle;
                if (!$episodes[$epid]['episodetitle']){
                    $episodes[$epid]['episodetitle'] = "Season $season, Episode $episode";
                }
                $episodes[$epid]['embed']=$embed;
                
                $ids[] = $epid;
            }
            
            if (count($ids)){
                $flags = $this->getEpisodeFlags($ids);
                foreach($episodes as $episode_id => $val){
                    if (array_key_exists($episode_id,$flags)){
                        $episodes[$episode_id]['languages'] = $flags[$episode_id];
                    } else {
                        $episodes[$episode_id]['languages'] = array();
                    }
                }
            }
        }
        return $episodes;
    }
    
    /* Returns the first day of the given month */
    public function firstOfMonth($date) {
        return date("Y-m-d", strtotime(date('m',strtotime($date)).'/01/'.date('Y',strtotime($date)).' 00:00:00'));
    }
    
    /* Returns the last day of the given month */
    public function lastOfMonth($date) {
        return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m',strtotime($date)).'/01/'.date('Y',strtotime($date)).' 00:00:00'))));
    }
    
    /* Returns a from-to value for a period id */
    public function getPeriod($period){
        if ($period==1){ $from = date("Y-m-d"); $to = date("Y-m-d"); } // today
        if ($period==2){ $from = date("Y-m-d",strtotime("yesterday")); $to = date("Y-m-d",strtotime("yesterday")); } // yesterday
        if ($period==3){ $from = date("Y-m-d",strtotime("7 days ago")); $to = date("Y-m-d"); } // this week
        if ($period==4){ $from = date("Y-m")."-01"; $to = date("Y-m-d"); } // this month
        if ($period==5){ $from = "0000-00-00"; $to = date("Y-m-d"); } // all time
        if ($period==6){ $from = $this->firstOfMonth(date("Y-m-d",strtotime("1 month ago"))); $to = $this->lastOfMonth(date("Y-m-d",strtotime("1 month ago"))); } // last month
        if ($period==7){ $from = $this->firstOfMonth(date("Y-m-d",strtotime("2 month ago"))); $to = $this->lastOfMonth(date("Y-m-d",strtotime("2 month ago"))); } // two months ago
        
        return array("from" => $from, "to" => $to);
    }
    
    /* Returns all the episodes added in the given period */
    public function getByPeriod($period,$lang=false,$embed_lang=array()){
        $period = $this->getPeriod($period);
        extract($period);
        
        $from .= " 00:00:00";
        $to .= " 23:59:59";
        
        if (count($embed_lang)){
            foreach($embed_lang as $key => $val){
                $embed_lang[$key] = "'".$val."'";
            }
            $lang_add = " AND episodes.id IN (SELECT episode_id FROM embeds WHERE lang IN (".implode(",",$embed_lang)."))";
        } else {
            $lang_add = "";
        }

        $e = mysql_query("SELECT shows.id as showid,episodes.embed,episodes.title as episodetitle,shows.permalink,shows.thumbnail,shows.title as showtitle,episodes.id as epid,episodes.episode,episodes.description,episodes.season,episodes.title as episodetitle FROM episodes, shows WHERE episodes.show_id=shows.id AND episodes.date_added>='$from' AND episodes.date_added<='$to' $lang_add ORDER BY episodes.id ASC") or die(mysql_error());
        $episodes = array();
        if (mysql_num_rows($e)){
            while($s=mysql_fetch_array($e)){
                extract($s);
                $episodes[$epid]=array();
                if (!$lang){
                    $episodes[$epid]['title'] = json_decode($showtitle,true);
                    $episodes[$epid]['description'] = json_decode($description,true);
                } else {
                    $episodes[$epid]['title'] = json_decode($showtitle,true);
                    if (isset($episodes[$epid]['title'][$lang])){
                        $episodes[$epid]['title'] = $episodes[$epid]['title'][$lang];
                    } else {
                        $episodes[$epid]['title'] = $episodes[$epid]['title']['en'];
                    }
                    
                    $episodes[$epid]['description'] = json_decode($description,true);
                    if (isset($episodes[$epid]['description'][$lang])){
                        $episodes[$epid]['description'] = $episodes[$epid]['description'][$lang];
                    } else {
                        $episodes[$epid]['description'] = $episodes[$epid]['description']['en'];
                    }                
                }
                $episodes[$epid]['thumbnail']=$thumbnail;
                $episodes[$epid]['season']=$season;
                $episodes[$epid]['episode']=$episode;
                $episodes[$epid]['episodetitle']=$episodetitle;
                if (!$episodes[$epid]['episodetitle']){
                    $episodes[$epid]['episodetitle'] = "Season $season, Episode $episode";
                }
                $episodes[$epid]['embed']=$embed;
            }
        }
        return $episodes;
    }
       
    /* Gives a count of all the episodes in the db */
    public function getEpisodeCount(){
        $e = mysql_query("SELECT count(*) as total FROM episodes") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
    
    /* Counts all the shows with at least one episode */
    public function getShowCountWithEpisodes(){
        $e = mysql_query("SELECT count(*) as total FROM shows WHERE id IN (SELECT show_id FROM episodes)") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
    
    /* Total show count */
    public function getShowCount($search_term = null){
        if ($search_term){
            $search_term = "WHERE title LIKE '%".mysql_real_escape_string($search_term)."%'";
        } else {
            $search_term = "";
        }
        $e = mysql_query("SELECT count(*) as total FROM shows $search_term") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
   
    /* Number of episode ratings */
    public function getRatingCount(){
        $e = mysql_query("SELECT count(*) as total FROM ratings") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
    
    /* Number of broken episode reports */
    public function getBrokenCount(){
        $e = mysql_query("SELECT count(*) as total FROM broken_episodes") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
    
    /* Increments the view counter for given episode */
    public function addView($episodeid){
        $episodeid = mysql_real_escape_string($episodeid);
        $e = mysql_query("UPDATE episodes SET views=views+1 WHERE id='$episodeid'") or die(mysql_error());
    }
    
    /* Lists episodes based on views */
    public function getCounts($page,$lang=false){
        $start = ($page-1)*100;
        $e = mysql_query("SELECT episodes.title as episodetitle,shows.title as showtitle,episodes.id as epid,episodes.views,episodes.season,episodes.episode,shows.id as showid FROM episodes,shows WHERE shows.id=episodes.show_id ORDER BY views DESC limit $start,100") or die(mysql_error());
        $counts = array();
        if (mysql_num_rows($e)){
            while($sor = mysql_fetch_array($e)){
                extract($sor);
                $counts[$epid]=array();
                $counts[$epid]['episodetitle']=$episodetitle;
                if (!$counts[$epid]['episodetitle']){
                    $counts[$epid]['episodetitle'] = "Season $season, Episode $episode";
                }
                
                if (!$lang){
                    $counts[$epid]['showtitle'] = json_decode($showtitle,true);
                } else {
                    $counts[$epid]['showtitle'] = json_decode($showtitle,true);
                    $counts[$epid]['showtitle'] = $counts[$epid]['showtitle'][$lang];
                }
                $counts[$epid]['views']=$views;
                $counts[$epid]['showid']=$showid;
                $counts[$epid]['episode']=$episode;
                $counts[$epid]['season']=$season;
            }
        }
        return $counts;
    }
     
    /* Returns a list of broken episode reports */
    public function getBroken($page,$lang=false){
        $start = ($page-1)*5;
        $e = mysql_query("SELECT broken_episodes.user_id,broken_episodes.user_agent,broken_episodes.id as brokenid,broken_episodes.problem,shows.permalink,shows.id as showid,episodes.title as episodetitle,shows.title as showtitle,episodes.id as epid,episodes.views,episodes.season,episodes.episode,broken_episodes.reportdate,broken_episodes.ip FROM episodes,shows,broken_episodes WHERE broken_episodes.episodeid=episodes.id AND shows.id=episodes.show_id ORDER BY broken_episodes.id DESC limit $start,50") or die(mysql_error());
        $broken = array();
        if (mysql_num_rows($e)){
            $user_ids = array();
            $user_map = array();
            while($sor = mysql_fetch_array($e)){
                extract($sor);
                $broken[$brokenid]=array();
                $broken[$brokenid]['episodetitle']=$episodetitle;
                if (!$broken[$brokenid]['episodetitle']){
                    $broken[$brokenid]['episodetitle'] = "Season $season, Episode $episode";
                }
                
                if (!$lang){
                    $broken[$brokenid]['showtitle'] = json_decode($showtitle,true);
                } else {
                    $broken[$brokenid]['showtitle'] = json_decode($showtitle,true);
                    $broken[$brokenid]['showtitle'] = $broken[$brokenid]['showtitle'][$lang];
                }
                $broken[$brokenid]['views']=$views;
                $broken[$brokenid]['episode']=$episode;
                $broken[$brokenid]['date']=$reportdate;
                $broken[$brokenid]['ip']=$ip;
                $broken[$brokenid]['season']=$season;
                $broken[$brokenid]['problem']=$problem;
                $broken[$brokenid]['episodeid']=$epid;
                $broken[$brokenid]['showid']=$showid;
                $broken[$brokenid]['user_agent']=$user_agent;
                $broken[$brokenid]['url']='/'.$permalink.'/season/'.$season.'/episode/'.$episode;
                $broken[$brokenid]['user'] = array();
                
                if ($user_id && !in_array($user_id,$user_ids)){
                    $user_ids[] = $user_id;
                }
                
                if ($user_id){
                    if (!array_key_exists($user_id,$user_map)){
                        $user_map[$user_id] = array();
                    }
                    
                    $user_map[$user_id][] = $brokenid;
                }
            }
            
            if (count($user_ids)){
                $e = mysql_query("SELECT id as user_id,username,email FROM users WHERE id IN (".implode(",",$user_ids).")") or die(mysql_error());
                if (mysql_num_rows($e)){
                    while($s = mysql_fetch_assoc($e)){
                        
                        foreach($user_map[$s['user_id']] as $key => $val){
                            $broken[$val]['user'] = $s;    
                        }                        
                    }
                }
            }
        }
        return $broken;
    }
    
    /* Removes a broken episode report */
    public function deleteBroken($id){
        $id = mysql_real_escape_string($id);
        $e = mysql_query("DELETE FROM broken_episodes WHERE id=$id") or die(mysql_error());
    }

    /* Removes an episode rating */
    public function deleteRating($id){
        $id = mysql_real_escape_string($id);
        $e = mysql_query("DELETE FROM ratings WHERE id=$id") or die(mysql_error());
    }

    /* Lists episode ratings */
    public function getRatings($page,$lang=false){
        $start = ($page-1)*100;
        $e = mysql_query("SELECT ratings.id as ratingid,episodes.title as episodetitle,episodes.season,episodes.episode,shows.title as showtitle,episodes.id as epid,ratings.rating,ratings.ip,ratings.ratingdate FROM ratings,episodes,shows WHERE ratings.episodeid=episodes.id AND episodes.show_id=shows.id ORDER BY ratingdate DESC LIMIT $start,100") or die(mysql_error());
        $ratings = array();
        if (mysql_num_rows($e)){
            while($sor = mysql_fetch_array($e)){
                extract($sor);
                $ratings[$ratingid]=array();
                $ratings[$ratingid]['episodetitle']=$episodetitle;
                if (!$ratings[$ratingid]['episodetitle']){
                    $ratings[$ratingid]['episodetitle'] = "Season $season, Episode $episode";
                }
                
                if (!$lang){
                    $ratings[$ratingid]['showtitle'] = json_decode($showtitle,true);
                } else {
                    $ratings[$ratingid]['showtitle'] = json_decode($showtitle,true);
                    $ratings[$ratingid]['showtitle'] = $ratings[$ratingid]['showtitle'][$lang];
                }
                $ratings[$ratingid]['rating']=$rating;
                $ratings[$ratingid]['date']=$ratingdate;
                $ratings[$ratingid]['ip']=$ip;
                $ratings[$ratingid]['episodeid']=$epid;
            }
        }
        return $ratings;
    }

    /* Marks an episode to be submitted to a given submit target */
    public function addSubmit($epid,$type,$link){
        $link = mysql_real_escape_string($link);
        $e = mysql_query("SELECT id FROM tv_submits WHERE episode_id=$epid AND type=$type") or die(mysql_error());
        if (mysql_num_rows($e)==0){
            $today = date("Y-m-d H:i:s");
            $e = mysql_query("INSERT INTO tv_submits(episode_id,type,link,timestamp) VALUES($epid,$type,'$link','$today')") or die(mysql_error());
        }
    }

    /* Returns a list of all the submitted episodes to the given submit target */
    public function getAllSubmits($type){
        $submits = array();
        $e = mysql_query("SELECT * FROM tv_submits WHERE type=$type") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $submits[$episode_id]=array();
                $submits[$episode_id]['link']=$link;
                $submits[$episode_id]['timestamp']=$timestamp;
            }
        }
        return $submits;
    }
   
    /* Adds a broken episode report */
    public function reportEpisode($episode,$problem,$ip,$user_agent=''){
        $episode = mysql_real_escape_string($episode);
        $problem = mysql_real_escape_string($problem);
        $user_agent = mysql_real_escape_string($user_agent);
        $e = mysql_query("SELECT id FROM broken_episodes WHERE ip='$ip' AND episodeid='$episode'") or die(mysql_error());
        
        if (isset($_SESSION['loggeduser_id']) && $_SESSION['loggeduser_id']){
            $user_id = $_SESSION['loggeduser_id'];
        } else {
            $user_id = 0;
        }
        
        if (mysql_num_rows($e)==0){
            $today = date("Y-m-d H:i:s");
            $e = mysql_query("INSERT INTO broken_episodes(episodeid,reportdate,problem,ip,user_id,user_agent) VALUES('$episode','$today','$problem','$ip','$user_id','$user_agent')") or die(mysql_error());
        }
    }
    
    /* Returns the average rating for the episode */
    public function getRating($episodeid){
        $episodeid = mysql_real_escape_string($episodeid);
        $average = 0;
        $e = mysql_query("SELECT AVG(`rating`) as average FROM ratings WHERE episodeid='$episodeid'") or die(mysql_error());
        if (mysql_num_rows($e)>0){
            extract(mysql_fetch_array($e));
        }
        return $average;
    }
    
    /* Adds an episode rating */
    public function addRating($episodeid,$rating,$ip){
        $episodeid = mysql_real_escape_string($episodeid);
        $rating = mysql_real_escape_string($rating);
        $e = mysql_query("SELECT id FROM ratings WHERE episodeid='$episodeid' AND ip='$ip'") or die(mysql_error());
        $today = date("Y-m-d H:i:s");
        if (mysql_num_rows($e)>0){
            extract(mysql_fetch_array($e));
            $e = mysql_query("UPDATE ratings SET rating=$rating,ratingdate='$today' WHERE ip='$ip' AND episodeid='$episodeid'") or die(mysql_error());
        } else {
            $e = mysql_query("INSERT INTO ratings(episodeid,rating,ip,ratingdate) VALUES('$episodeid','$rating','$ip','$today')") or die(mysql_error());
        }
        return 1;
    }
   
    /* Returns episode details */
    public function getEpisodeById($id,$lang=false){
        $e = mysql_query("SELECT shows.id as showid,shows.sidereel_url,episodes.embed,episodes.title as episodetitle,shows.permalink,shows.thumbnail,shows.title as showtitle,episodes.thumbnail as episode_thumbnail, episodes.id as epid,episodes.episode,episodes.description,episodes.season,episodes.title as episodetitle FROM episodes, shows WHERE episodes.show_id=shows.id AND episodes.id=$id") or die(mysql_error());
        $ep = array();
        if (mysql_num_rows($e)){
            extract(mysql_fetch_array($e));
            $ep['title']=$episodetitle;
            $ep['description']=$description;
            if ($episode_thumbnail){
                $ep['thumbnail']=$episode_thumbnail;
            } else {
                $ep['thumbnail']=$thumbnail;
            }
            $ep['season']=$season;
            $ep['episode']=$episode;
            if (!$episodetitle) $episodetitle = "Season $season, Episode $episode";
            $ep['episodetitle']=$episodetitle;
            $ep['embed'] = $embed;
            if (!$lang){
                $ep['showtitle'] = json_decode($showtitle,true);
            } else {
                $ep['showtitle'] = json_decode($showtitle,true);
                $ep['showtitle'] = $ep['showtitle'][$lang];
            }
            $ep['show_sidereel']=$sidereel_url;
            $ep['showid']=$showid;
            $ep['show_perma']=$permalink;
            $ep['url']="/".$permalink."/season/".$season."/episode/".$episode;
        }
        return $ep;
    }
   
    /* Returns episode data based on show_id episode and season number */
    public function getEpisode($showid,$season,$episode){
        $season = mysql_real_escape_string($season);
        $episode = mysql_real_escape_string($episode);
        $showid = mysql_real_escape_string($showid);
        $e = mysql_query("SELECT * FROM episodes WHERE season=$season AND episode=$episode AND show_id=$showid") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            
            $ret = array();
            $ret['id'] = $id;
            $ret['title'] = $title;
            if (!$ret['title']){
                $ret['title']="Season $season, Episode $episode";
            }
            $ret['description'] = $description;
            $ret['episodeid'] = $id;
            $ret['thumbnail'] = $thumbnail;
            
            return $ret;
        } else {
            return 0;
        }
    }

    /* gets the season / episode number for the latest episode of the specified show */
    public function getLatestEpisodeDetails($showid){
        $e = mysql_query("SELECT season,episode FROM episodes WHERE show_id=$showid ORDER BY season DESC,episode DESC LIMIT 1") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_array($e));
            return array("season" => $season,"episode" => $episode);
        } else {
            return array("season" => 0,"episode" => 0);
        }
    }
    
    /* Get the latest episode for a given show */
    public function getLatestEpisode($showid=null){
        if (@$showid){ $add=" AND shows.id=$showid"; } else { $add=""; }
        $query = "SELECT episodes.show_id as `catid`,episodes.thumbnail,shows.thumbnail as category_thumbnail,shows.title,episodes.season,MAX(`episodes`.`season`) as `maxseason`,MAX(`episodes`.`episode`) as maxepisode 
                FROM episodes,shows
                WHERE episodes.show_id=shows.id $add
                GROUP BY episodes.show_id,episodes.season";
        $e = mysql_query($query) or die(mysql_error());
        $ret = array();
        if (mysql_num_rows($e)){
            while($s=mysql_fetch_array($e)){
                extract($s);
                if ($maxseason==$season){
                    $ret[$catid]=array();
                    $ret[$catid]['season']=$maxseason;
                    $ret[$catid]['episode']=$maxepisode;
                    $ret[$catid]['title']=$title;
                    if (!$ret[$catid]['title']){
                        $ret[$catid]['title'] = "Season $maxseason, Episode $maxepisode";
                    }
                    if ($thumbnail){
                        $ret[$catid]['thumbnail']=$thumbnail;
                    } else {
                        $ret[$catid]['thumbnail']=$category_thumbnail;
                    }
                }
            }
        }
        return $ret;
    }

    /* Gets the next episode from given position */
    public function getNextEpisode($show_id,$season,$episode){
        $show_id = mysql_real_escape_string($show_id);
        $season = mysql_real_escape_string($season);
        $episode = mysql_real_escape_string($episode);
        
        $e = mysql_query("SELECT season,episode FROM episodes WHERE ((season='$season' AND episode>'$episode') OR (season>$season AND episode<$episode)) AND show_id='$show_id' ORDER BY season ASC, episode ASC LIMIT 1") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            $res['season'] = $season;
            $res['episode'] = $episode;
        }
        
        return $res;
    }
    
    /* Gets the previous episode from given position */
    public function getPrevEpisode($show_id,$season,$episode){
        $show_id = mysql_real_escape_string($show_id);
        $season = mysql_real_escape_string($season);
        $episode = mysql_real_escape_string($episode);
        
        $e = mysql_query("SELECT season,episode FROM episodes WHERE ((season='$season' AND episode<'$episode') OR (season<$season AND episode>$episode)) AND show_id='$show_id' ORDER BY season DESC, episode DESC LIMIT 1") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            $res['season'] = $season;
            $res['episode'] = $episode;
        }
        
        return $res;
    }
    
    /* Returns the newest episodes */
    public function getLastAdditions($limit=20,$perma=null){
        $ret = array();
        if ($perma){ 
            $permaadd = " AND shows.permalink='".mysql_real_escape_string($perma)."' "; 
        } else { 
            $permaadd = ''; 
        }
        
        $query = "SELECT episodes.*,episodes.thumbnail,shows.title as showtitle,shows.id as showid,shows.permalink,shows.thumbnail as show_thumbnail FROM shows,episodes WHERE shows.id=episodes.show_id $permaadd ORDER BY episodes.id DESC LIMIT $limit";
        
        $e = mysql_query($query) or die(mysql_error());
        
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $ret[$id]=array();
                $ret[$id]['showtitle']=$showtitle;
                $ret[$id]['title']=$title;
                if (!$ret[$id]['title']){
                    $ret[$id]['title'] = "Season $season, Episode $episode";
                }
                $ret[$id]['showid']=$showid;
                $ret[$id]['season']=$season;
                $ret[$id]['episode']=$episode;
                $ret[$id]['description']=$description;
                $ret[$id]['embed']=$embed;
                $ret[$id]['thumbnail']=$thumbnail;
                $ret[$id]['show_thumbnail']=$show_thumbnail;
                $ret[$id]['thumbnail']=$thumbnail;
                $ret[$id]['perma']=$permalink;
                $ret[$id]['date_added']=$date_added;
            }
        }
        
        return $ret;
    }
    
    /* Returns similar shows to the given ids */
    public function getSimilarShows($ids,$limit=20,$lang=false){
        $e = mysql_query("SELECT * FROM similar_shows WHERE show1 IN (".implode(",",$ids).") AND show2 IN (SELECT DISTINCT show_id FROM episodes)") or die(mysql_error());
        $shows = array();
        if (mysql_num_rows($e)){
            $similar = array();
            while($s = mysql_fetch_assoc($e)){
                if (!in_array($s['show2'],$ids)){
                    @$similar[$s['show2']]+=$s['score'];
                }
            }
            
            arsort($similar);
            $similar_ids = array();
            foreach($similar as $key => $val){
                $similar_ids[] = $key;
                if (count($similar_ids)>=$limit){
                    break;
                }
            }
            
            $e = mysql_query("SELECT * FROM shows WHERE id IN (".implode(",",$similar_ids).")") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $shows[$s['id']] = $this->formatShowData($s, false, $lang);
                }
            }
            
        }    
        return $shows;    
    }   
}?>