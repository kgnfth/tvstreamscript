<?php

class Movie{

    public function __construct(){
        
    }
    
    
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
    
    public function deleteEmbed($movieid,$embedid){
        $embedid = mysql_real_escape_string($embedid);
        $movieid = mysql_real_escape_string($movieid);
        if ($embedid==0){
            $e = mysql_query("UPDATE movies SET embed='' WHERE id='$movieid'") or die(mysql_error());
        } else {
            $e = mysql_query("DELETE FROM movie_embeds WHERE movie_id='$movieid' AND id='$embedid'") or die(mysql_error());
        }
    }
    
    public function deleteAllEmbeds($movie_id){
        $movie_id = mysql_real_escape_string($movie_id);
        $e = mysql_query("DELETE FROM movie_embeds WHERE movie_id='$movie_id'") or die(mysql_error());
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
        
        
        $e = mysql_query("SELECT * FROM movies $search_term ORDER BY $sortby $sortdir $limit") or die(mysql_error());
        $shows = array();
        $ids = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                extract($s);
                $shows[$id]=$s;
                $shows[$id]['title'] = json_decode($shows[$id]['title'],true);
                $shows[$id]['description'] = json_decode($shows[$id]['description'],true);
                $shows[$id]['embed_count'] = 0;
                $ids[] = $id;                
            }
            
            
            $e = mysql_query("SELECT count(*) as embed_count, movie_id FROM movie_embeds WHERE movie_id IN (".implode(",",$ids).") GROUP BY movie_id") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    $shows[$s['movie_id']]['embed_count'] = $s['embed_count'];
                }
            }
        }
        return $shows;
    }
    
    public function checkMovie($title,$year){
        $test1 = strtolower(trim($title));
        $test1 = str_replace(" ","",$test1);
        $test1 = preg_replace("/[^A-Za-z0-9]/","%",$test1);
        
        $test2 = strtolower($title." ($year)");
        $test2 = str_replace(" ","",$test2);
        $test2 = preg_replace("/[^A-Za-z0-9]/","%",$test2);
        
        $test3 = strtolower($title." $year");
        $test3 = str_replace(" ","",$test3);
        $test3 = preg_replace("/[^A-Za-z0-9]/","%",$test3);
        
        $check = mysql_query("SELECT * FROM movies WHERE REPLACE(LOWER(`title`),' ','') LIKE '$test1' OR REPLACE(LOWER(`title`),' ','') LIKE '$test2' OR REPLACE(LOWER(`title`),' ','') LIKE '$test3'") or die(mysql_error());
        if (mysql_num_rows($check)){
            return 1;
        } else {
            return 0;
        }
    }
    
    public function getNewest($date_from,$limit=10){
        $date_from = mysql_real_escape_string($date_from);
        $res = array();
        $e = mysql_query("SELECT id,title,thumb,perma FROM movies WHERE date_added>='$date_from' LIMIT $limit") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $s;
            }
        }
        
        return $res;
    }
    
    public function updateEmbedCode($movieid,$embedid,$embedcode){
        $embedid = mysql_real_escape_string($embedid);
        $movieid = mysql_real_escape_string($movieid);
        $embedcode = mysql_real_escape_string(stripslashes(stripslashes(urldecode($embedcode))));
        if ($embedid==0){
            $e = mysql_query("UPDATE movies SET embed='$embedcode' WHERE id='$movieid'") or die(mysql_error());
        } else {
            $e = mysql_query("UPDATE movie_embeds SET embed='$embedcode' WHERE movie_id='$movieid' AND id='$embedid'") or die(mysql_error());
        }
    }
    
    public function getEmbeds($movieid){
        $embeds = array();
        $counter = 0;

        // getting the rest
        $e = mysql_query("SELECT * FROM movie_embeds WHERE movie_id=$movieid ORDER BY weight DESC") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                if ($embed){
                    
                    $embeds[$counter]['type'] = $this->getEmbedType($embed, $link);
                    if (substr_count($embed,"<span id='flvplayer'>")){
                        $embed = '<iframe src="/embed.php?id='.$id.'&movie='.$movieid.'" width="620" height="400" frameborder="0" scrolling="no"></iframe>';
                    }
                    $embeds[$counter]['embed']=stripslashes(stripslashes(urldecode($embed)));
                    
                    $embeds[$counter]['lang']=$lang;
                    $embeds[$counter]['id']=$id;
                    $embeds[$counter]['weight']=$weight;
                    $embeds[$counter]['link']=$link;
                    $counter++;
                }
            }
        }
        
        return $embeds;
    }

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
    
    public function addCategory($category){

        $perma = $this->makePerma($category['en']);
        $category = mysql_real_escape_string(json_encode($category));
        
        $e = mysql_query("SELECT * FROM movie_tags WHERE tag='$category' OR perma='$perma'") or die(mysql_error());
        if (mysql_num_rows($e)==0){
            
            $e = mysql_query("INSERT INTO movie_tags(tag,perma) VALUES('$category','$perma')") or die(mysql_error());
            return 1;
            
        } else {
            return 0;
        }
    }
    
    public function getCategoryCount($tag_id){
        $tag_id = mysql_real_escape_string($tag_id);
        $e = mysql_query("SELECT count(*) as category_count FROM movies WHERE id IN (SELECT movie_id FROM movie_tags_join WHERE tag_id='$tag_id')") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            return $category_count;
        } else {
            return 0;
        }
    }
    
    public function getMoviesByCategory($tagid, $sortby=null, $lang=null, $page = 1, $limit = 40){
        $tagid = mysql_real_escape_string($tagid);
        
        if (!$sortby || $sortby=='abc'){
            $order = " ORDER BY title ASC ";
        } elseif ($sortby=='imdb_rating') {
            $order = " ORDER BY imdb_rating DESC ";
        } else {
            $order = " ORDER BY id DESC ";
        }
        
        $page = (int) $page;
        if (!$page){
            $page = 1;
        }
        
        $limit = (int) $limit;
        if (!$limit){
            $limit = 40;
        }
        
        $start = ($page-1)*$limit;
        
        $e = mysql_query("SELECT * FROM movies WHERE id IN (SELECT movie_id FROM movie_tags_join WHERE tag_id='$tagid') $order LIMIT $start, $limit") or die(mysql_error());
        $movies = array();
        if (mysql_num_rows($e)){
            $ids = array();
            
            while($s = mysql_fetch_array($e)){
                $movies[$s['id']] = $this->formatMovieData($s, $lang);
                $ids[] = $s['id'];
            }
            
            if (count($ids)){
                $flags = $this->getFlags($ids);                
                if (count($flags)){
                    foreach($movies as $movie_id => $val){
                        if (array_key_exists($movie_id,$flags)){
                            $movies[$movie_id]['languages'] = $flags[$movie_id];
                        } else {
                            $movies[$movie_id]['languages'] = array();
                        }    
                    }
                }
            }
        }
        return $movies;
    }
    
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
    
    public function getLink($link_id){
        $link_id = mysql_real_escape_string($link_id);

        $e = mysql_query("SELECT * FROM submitted_links WHERE submitted_links.type = 2 AND id='$link_id'") or die(mysql_error());
        if (mysql_num_rows($e)){
            return mysql_fetch_assoc($e);            
        } else {
            return false;
        }

    }
    
    public function getLinks($status = null, $lang=false){
        global $baseurl;
        
        if ($status !== null){
            $status_add = " AND submitted_links.status='$status'";
        } else {
            $status_add = "";
        }
        
        $links = array();
        $e = mysql_query("SELECT submitted_links.*, users.username, movies.title as movie_title, movies.id as movie_id 
                            FROM submitted_links 
                            LEFT JOIN movies ON movies.imdb_id=submitted_links.imdb_id 
                            LEFT JOIN users ON users.id=submitted_links.user_id
                            WHERE submitted_links.type = 2 $status_add
                            ORDER BY submitted_links.date_submitted DESC") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $links[$s['id']] = $s;
                
                if ($s['movie_title']){
                    $links[$s['id']]['movie_title'] = json_decode($links[$s['id']]['movie_title'],true);
                    if ($lang){
                        $links[$s['id']]['movie_title'] = $links[$s['id']]['movie_title'][$lang];                        
                    } 
                }
            }
        }
        
        return $links;
    }
        
    public function getCategoryByPerma($perma,$lang=null){
        $perma = mysql_real_escape_string($perma);
        $e = mysql_query("SELECT id,tag FROM movie_tags WHERE perma='$perma'") or die(mysql_error());
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
    
    public function saveCategories($movieid,$categories){
        $movieid = mysql_real_escape_string($movieid);
        $e = mysql_query("DELETE FROM movie_tags_join WHERE movie_id=$movieid") or die(mysql_error());
        foreach($categories as $key=>$val){
            $e = mysql_query("INSERT INTO movie_tags_join(movie_id,tag_id) VALUES($movieid,$val)") or die(mysql_error());
        }
    }
    
    /* returns a list of category ids for the given movie */
    
    public function getMovieCategories($movieid){
        $movieid = mysql_real_escape_string($movieid);
        $e = mysql_query("SELECT * FROM movie_tags_join WHERE movie_id=$movieid") or die(mysql_error());
        $tags = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $tags[]=$tag_id;
            }
        }
        return $tags;
    }
    
    /* returns a list of categories (including ids and details) for the given movie */
    
    public function getMovieCategoryDetails($movieid,$lang=null){
        $movieid = mysql_real_escape_string($movieid);
        $e = mysql_query("SELECT * FROM movie_tags WHERE id IN (SELECT tag_id FROM movie_tags_join WHERE movie_id=$movieid)") or die(mysql_error());
        $tags = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                $s['tag'] = json_decode($s['tag'],true);
                if ($lang){
                    $s['tag'] = $s['tag'][$lang];
                }
                
                $tags[$s['id']] = $s;
            }
        }
        return $tags;
    }
    
    public function deleteTag($tagid){
        $tagid = mysql_real_escape_string($tagid);
        $e = mysql_query("DELETE FROM movie_tags_join WHERE tag_id='$tagid'") or die(mysql_error());
        $e = mysql_query("DELETE FROM movie_tags WHERE id='$tagid'") or die(mysql_error());
    }
    
    public function getCategories($lang=null,$limit=0,$order="tag ASC"){
        $categories = array();
        
        if ($limit){ $add = "LIMIT $limit"; } else { $add = ''; }
        
        $e = mysql_query("SELECT * FROM movie_tags ORDER BY $order") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                
                $tag = json_decode($tag,true);
                if (!$lang){
                    $categories[$id]['name'] = $tag;
                } else {
                    $categories[$id]['name'] = $tag[$lang];
                }
                $categories[$id]['perma']=$perma;
            }
        }
        
        return $categories;
    }
   
    public function getRatingCount(){
        $e = mysql_query("SELECT count(*) as total FROM movie_ratings") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
    
    public function getLinkCount(){
        $e = mysql_query("SELECT count(*) as `cnt` FROM submitted_links WHERE type=2") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $cnt;
    }
   
    public function getRatings($page,$lang=false){
        $start = ($page-1)*100;
        $e = mysql_query("SELECT movie_ratings.id as ratingid,movies.title,movies.id as movieid,movie_ratings.rating,movie_ratings.ip,movie_ratings.ratingdate FROM movie_ratings,movies WHERE movie_ratings.movieid=movies.id ORDER BY ratingdate DESC LIMIT $start,100") or die(mysql_error());
        $ratings = array();
        if (mysql_num_rows($e)){
            while($sor = mysql_fetch_array($e)){
                extract($sor);
                $ratings[$ratingid]=array();
                if (!$lang){
                    $ratings[$ratingid]['title'] = json_decode($title,true);
                } else {
                    $ratings[$ratingid]['title'] = json_decode($title,true);
                    if (isset($ratings[$ratingid]['title'][$lang])){
                        $ratings[$ratingid]['title'] = $ratings[$ratingid]['title'][$lang];
                    } else {
                        $ratings[$ratingid]['title'] = $ratings[$ratingid]['title']['en'];
                    }
                }
                $ratings[$ratingid]['rating']=$rating;
                $ratings[$ratingid]['date']=$ratingdate;
                $ratings[$ratingid]['ip']=$ip;
                $ratings[$ratingid]['movieid']=$movieid;
            }
        }
        return $ratings;
    }

    public function deleteRating($id){
        $id = mysql_real_escape_string($id);
        $e = mysql_query("DELETE FROM movie_ratings WHERE id=$id") or die(mysql_error());
    }
   
    public function validate($params, $update = false, $no_embeds = false){
        $errors = array();
        
        if (!isset($_SESSION['global_languages'])){
            return array("99" => "Session expired");
        }
        
        foreach ($_SESSION['global_languages'] as $lang_code => $lang_name){
            if (!isset($params['title'][$lang_code]) || !$params['title'][$lang_code]){
                $errors[1][$lang_code] = "Please enter the $lang_name title for this movie";
            }
            
            if (!isset($params['description'][$lang_code]) || !$params['description'][$lang_code]){
                $errors[3][$lang_code] = "Please enter the $lang_name description for this movie";
            }
        }
        
        if (!isset($params['thumb']) || !$params['thumb']){
            $errors[2]='Please upload a thumbnail image';
        }
        if (!isset($params['imdb_id']) || !$params['imdb_id']){
            $errors[4] = "Please enter the movie's IMDB id";
        } else if (!$update) {            
            $e = mysql_query("SELECT id as movie_id FROM movies WHERE imdb_id='".mysql_real_escape_string($params['imdb_id'])."'") or die(mysql_error());
            if (mysql_num_rows($e)){
                extract(mysql_fetch_assoc($e));
                $errors[4] = "Movie with the same IMDB id already exists. <a href='index.php?menu=movies&movieid=$movie_id'>Click here to edit it</a>";
            }
        }

        if (isset($params['imdb_rating']) && $params['imdb_rating'] && !is_numeric($params['imdb_rating'])){
            $errors[6] = "Rating must be numeric";       
        }
        
        if (isset($params['year']) && $params['year'] && !is_numeric($params['year'])){
            $errors[7] = "Year of release must be numeric";       
        }
        
        // checking for real embeds
        if (!$no_embeds){
            if (isset($params['from']) && $params['from']=="admin"){
                if (!isset($params['embed_enabled']) || !is_array($params['embed_enabled']) || !count($params['embed_enabled'])){
                    $errors[5] = "Please add at least one embed";
                } else {
                    $found = false;
                    foreach($params['embed_enabled'] as $embed_id => $val){
                        if (isset($params['embeds'][$embed_id]) && $params['embeds'][$embed_id]){
                            $found = true;
                            break;
                        }
                    }
                    
                    if (!$found){
                        $errors[5] = "Please add at least one embed";
                    }
                }
            } elseif (!isset($params['embeds']) || !is_array($params['embeds']) || !count($params['embeds'])){
                $errors[5] = "Please add at least one embed";
            }
        }
        
        return $errors;
    }

    public function getMovieCount($search_term = null){
        if ($search_term){
            $search_term = "WHERE title LIKE '%".mysql_real_escape_string($search_term)."%'";
        } else {
            $search_term = "";
        }
        $e = mysql_query("SELECT count(*) as total FROM movies $search_term") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
   
    public function getCounts($page, $lang=false, $limit = 100){
        $limit = (int) $limit;
        $start = ($page-1)*$limit;
        $e = mysql_query("SELECT movies.id, movies.title, movies.views, movies.thumb FROM movies ORDER BY views DESC limit $start,$limit") or die(mysql_error());
        $counts = array();
        if (mysql_num_rows($e)){
            while($sor = mysql_fetch_array($e)){
                extract($sor);
                $counts[$id]=array();
                if (!$lang){
                    $counts[$id]['title'] = json_decode($title,true);
                } else {
                    $counts[$id]['title'] = json_decode($title,true);
                    if (isset($counts[$id]['title'][$lang])){
                        $counts[$id]['title'] = $counts[$id]['title'][$lang];
                    } else {
                        $counts[$id]['title'] = $counts[$id]['title']['en'];
                    }
                }
                
                $counts[$id]['views'] = $views;
                $counts[$id]['thumb'] = $thumb;
            }
        }
        return $counts;
    }

    public function deleteMovie($movie_id){
        global $basepath;
        
        $movie_id = mysql_real_escape_string($movie_id);
        $check = mysql_query("SELECT thumb FROM movies WHERE id='$movie_id'") or die(mysql_error());
        if (mysql_num_rows($check)){
            extract(mysql_fetch_assoc($check));
            
            if (file_exists($basepath."/thumbs/".$thumb)){
                unlink($basepath."/thumbs/".$thumb);
            }
            
            mysql_query("DELETE FROM movies WHERE id='$movie_id'") or die(mysql_error());
            mysql_query("DELETE FROM movie_embeds WHERE movie_id='$movie_id'") or die(mysql_error());
            mysql_query("DELETE FROM movie_tags_join WHERE movie_id='$movie_id'") or die(mysql_error());
            mysql_query("DELETE FROM movie_ratings WHERE movieid='$movie_id'") or die(mysql_error());
            mysql_query("DELETE FROM similar_movies WHERE movie1='$movie_id' OR movie2='$movie_id'") or die(mysql_error());
        }
    }
   
    public function save($params){
        if (!isset($params['perma'])){
            $perma = $this->makePerma($params['title']['en']);
        } else {
            $perma = mysql_real_escape_string($params['perma']);
        }
        $title = mysql_real_escape_string(json_encode($params['title']));
        $description = mysql_real_escape_string(json_encode($params['description']));
        $thumb = mysql_real_escape_string(trim($params['thumb']));
        $imdb = mysql_real_escape_string(trim($params['imdb_id']));
        
        $meta = array();
        if (isset($params['year']) && $params['year']){
            $meta['year'] = $params['year'];
        }
        
        if (isset($params['director']) && $params['director']){
            $meta['director'] = $params['director'];
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
        
        if (count($meta)){
            $meta = mysql_real_escape_string(json_encode($meta));
        } else {
            $meta = '';
        }
        
        if (isset($params['imdb_rating']) && $params['imdb_rating']){
            $imdb_rating = mysql_real_escape_string($params['imdb_rating']);
        } else {
            $imdb_rating = 0;
        }

        $e = mysql_query("INSERT INTO movies(title,description,thumb,perma,date_added,imdb_id,imdb_rating,meta) VALUES('$title','$description','$thumb','$perma',NOW(),'$imdb','$imdb_rating','$meta')") or die(mysql_error());
        return mysql_insert_id();
    }
    
    function makePerma($str, $replace=array(), $delimiter='-') {
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }
    
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
    
        return $clean;
    }
    
    public function updateMeta($id,$meta){
        if (isset($meta['imdb_rating'])){
            $imdb_rating = $meta['imdb_rating'];
        } else {
            $imdb_rating = 0;
        }
        $meta = json_encode($meta);
        $meta = mysql_real_escape_string($meta);
        $id = mysql_real_escape_string($id);
        
        $up = mysql_query("UPDATE movies SET meta='$meta',imdb_rating='$imdb_rating' WHERE id='$id'") or die(mysql_error());
    }
    
    public function updateThumbnail($movie_id,$thumbnail){
        $movie_id = mysql_real_escape_string($movie_id);
        $thumbnail = mysql_real_escape_string($thumbnail);
        
        $up = mysql_query("UPDATE movies SET thumb='$thumbnail' WHERE id='$movie_id'") or die(mysql_error());
    }
    
    public function update($id,$params){
        
        $perma = $this->makePerma($params['title']['en']);
        $title = mysql_real_escape_string(json_encode($params['title']));
        $description = mysql_real_escape_string(json_encode($params['description']));
        $thumb = mysql_real_escape_string($params['thumb']);
        $id = mysql_real_escape_string($id);
        
        $meta = array();
        if (isset($params['year']) && $params['year']){
            $meta['year'] = $params['year'];
        }
        
        if (isset($params['director']) && $params['director']){
            $meta['director'] = $params['director'];
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
        
        if (count($meta)){
            $meta = mysql_real_escape_string(json_encode($meta));
        } else {
            $meta = '';
        }
        
        if (isset($params['imdb_rating']) && $params['imdb_rating']){
            $imdb_rating = mysql_real_escape_string($params['imdb_rating']);
        } else {
            $imdb_rating = 0;
        }
        
        $e = mysql_query("UPDATE movies SET title='$title',description='$description',thumb='$thumb',perma='$perma',date_added=NOW(), imdb_rating='$imdb_rating', meta='$meta' WHERE id=$id") or die(mysql_error());
        return true;
    }

    public function searchByMeta($params, $lang=null){
        if (isset($params['director'])){
            $params['director'] = preg_replace("/[^a-zA-Z0-9 ]/i","%",$params['director']);
            $query = "meta LIKE '%\"director\":\"".mysql_real_escape_string($params['director'])."\"%'";
        } elseif (isset($params['star'])){
            $params['star'] = preg_replace("/[^a-zA-Z0-9 ]/i","%",$params['star']);
            $query = "meta LIKE '%\"stars\":[%\"".mysql_real_escape_string($params['star'])."\"%]%'";
        } elseif (isset($params['year'])){
            $params['year'] = (int) $params['year'];
            $query = "meta LIKE '%\"year\":\"".mysql_real_escape_string($params['year'])."\"%' OR meta LIKE '%\"year\":".mysql_real_escape_string($params['year'])."%'";            
        }
        
        $movies = array();
        
        $query = "SELECT * FROM movies WHERE $query ORDER BY title ASC";       
        
        $e = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($e)>0){
            $ids = array();
            while($s = mysql_fetch_assoc($e)){
                $movies[$s['id']] = $this->formatMovieData($s, $lang);
                $ids[] = $s['id'];
            }
            
            if (count($ids)){                
                $flags = $this->getFlags($ids);        
                if (count($flags)){
                    foreach($movies as $movie_id => $val){
                        if (array_key_exists($movie_id,$flags)){
                            $movies[$movie_id]['languages'] = $flags[$movie_id];
                        } else {
                            $movies[$movie_id]['languages'] = array();
                        }    
                    }
                }
            }
        }
        return $movies;
    }
    
    public function search($query, $lang=null){
        
        $query = mysql_real_escape_string($query);        
        $query = preg_replace("/[^a-zA-Z0-9 ]/i","%",$query);        
        $movies = array();
        
        $query = "SELECT * FROM movies WHERE title LIKE '%$query%' OR description LIKE '%$query%' ORDER BY title ASC";        
        
        $e = mysql_query($query) or die(mysql_error());
        if (mysql_num_rows($e)>0){
            $ids = array();
            while($s = mysql_fetch_assoc($e)){
                $movies[$s['id']] = $this->formatMovieData($s, $lang);
                $ids[] = $s['id'];
            }
            
            if (count($ids)){                
                $flags = $this->getFlags($ids);        
                if (count($flags)){
                    foreach($movies as $movie_id => $val){
                        if (array_key_exists($movie_id,$flags)){
                            $movies[$movie_id]['languages'] = $flags[$movie_id];
                        } else {
                            $movies[$movie_id]['languages'] = array();
                        }    
                    }
                }
            }
        }
        return $movies;
    }
   
    public function getMovies($lang=false, $limit = 0, $page = 0){
        $movies = array();
        
        if ($limit){
            if ($page){
                $start = ($page-1)*$limit;
            } else {
                $start = 0;
            }
            
            $limit = " LIMIT $start,$limit";
        } else {
            $limit = "";
        }
        
        $e = mysql_query("SELECT * FROM movies ORDER BY title ASC $limit") or die(mysql_error());
        if (mysql_num_rows($e)>0){
            while($s=mysql_fetch_array($e)){
                $movies[$s['id']] = $this->formatMovieData($s, $lang);
            }
        }
        return $movies;
    }
   
    public function getLatest($limit,$lang=null){
        $movies = array();
        $e = mysql_query("SELECT * FROM movies ORDER BY date_added DESC LIMIT 0,$limit") or die(mysql_error());
        if (mysql_num_rows($e)>0){
            $ids = array();
            
            while($s = mysql_fetch_array($e)){
                $movies[$s['id']] = $this->formatMovieData($s, $lang);
                $ids[] = $s['id'];
            }
            
            if (count($ids)){
                
                $flags = $this->getFlags($ids);
                
                if (count($flags)){
                    foreach($movies as $movie_id => $val){
                        if (array_key_exists($movie_id,$flags)){
                            $movies[$movie_id]['languages'] = $flags[$movie_id];
                        } else {
                            $movies[$movie_id]['languages'] = array();
                        }    
                    }
                }
            }
        }
        return $movies;
    }
   
    public function addView($id){
        $e = mysql_query("UPDATE movies SET views=views+1 WHERE id=$id") or die(mysql_error());
    }
    
    public function getByImdb($imdb_id,$lang=null){
        $imdb_id = mysql_real_escape_string($imdb_id);
        $movie = false;
        $e = mysql_query("SELECT * FROM movies WHERE imdb_id='$imdb_id'") or die(mysql_error());
        if (mysql_num_rows($e)>0){
            $movie = $this->formatMovieData(mysql_fetch_assoc($e), $lang);
        }
        return $movie;
    }
   
    public function getByPerma($perma, $lang=null){
        $perma = mysql_real_escape_string($perma);
        $movie = array();
        $e = mysql_query("SELECT * FROM movies WHERE perma='$perma'") or die(mysql_error());
        if (mysql_num_rows($e)>0){
            $movie = $this->formatMovieData(mysql_fetch_assoc($e), $lang);
        }
        return $movie;
    }
    
    public function getRealMovieCount(){
        $e = mysql_query("SELECT count(*) as cnt FROM movies WHERE (embed!='' AND embed IS NOT NULL) OR id IN (SELECT movie_id FROM movie_embeds)") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $cnt;
    }
    
    public function getBasicPagination($total_pages, $page, $limit, $targetpage, $current_class = 'current') {
        $adjacents = 2;
        if ($page == 0) $page = 1;                    //if no page var is given, default to 1.
        $prev = $page - 1;                            //previous page is page - 1
        $next = $page + 1;                            //next page is page + 1
        $lastpage = ceil($total_pages/$limit);        //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                        //last page minus 1

        $pagination = "";
        if($lastpage > 1){
                
            //previous button
            if ($page > 1) 
                $pagination.= " <li><a href=\"{$targetpage}{$prev}\">&laquo;</a></li> ";
            else
                $pagination.= " ";    
            
            //pages    
            if ($lastpage < 7 + ($adjacents * 2)){    
                for ($counter = 1; $counter <= $lastpage; $counter++)    {
                    if ($counter == $page)
                        $pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
                    else
                        $pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";                    
                    if ($counter < $lastpage)
                        $pagination.= " ";
                }
            } elseif($lastpage > 5 + ($adjacents * 2)){
                //close to beginning; only hide later pages
                if($page < 1 + ($adjacents * 2)){
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
                        else
                            $pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
                        if ($counter <  3 + ($adjacents * 2))
                            $pagination.= " ";
                    }
                    $pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
                    $pagination.= " <li><a href=\"{$targetpage}{$lpm1}\">$lpm1</a></li> ";
                    $pagination.= " <li><a href=\"{$targetpage}{$lastpage}\">$lastpage</a></li> ";
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
                    $pagination.= " <li><a href=\"{$targetpage}1\">1</a ></li> ";
                    $pagination.= " <li><a href=\"{$targetpage}2\">2</a></li> ";
                    $pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
                    
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)    {
                        if ($counter == $page)
                            $pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
                        else
                            $pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
                        if ($counter < $page + $adjacents)
                            $pagination.= " ";
                    }
                    $pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
                    $pagination.= " <li><a href=\"{$targetpage}{$lpm1}\">$lpm1</a></li> ";
                    $pagination.= " <li><a href=\"{$targetpage}{$lastpage}\">$lastpage</a></li> ";
                }
                //close to end; only hide early pages
                else
                {
                    $pagination.= " <li><a href=\"{$targetpage}1\">1</a></li> ";
                    $pagination.= " <li><a href=\"{$targetpage}2\">2</a></li> ";
                    $pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
                        if ($counter == $page)
                            $pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
                        else
                            $pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
                        if ($counter < $lastpage)
                            $pagination.= " ";
                    }
                }
            }
            
            //next button
            if ($page < $counter - 1) 
                $pagination.= " <li><a href=\"{$targetpage}{$next}\">&raquo;</a></li> ";
            else
                $pagination.= " ";
        }
        if (empty($pagination))
            $pagination = " <li><a href=\"{$targetpage}1\">1</a></li> ";
        return $pagination;
    }
   
    public function getFlags($ids){
        $flags = array();
        // getting embed languages
        if (count($ids)){
            $e = mysql_query("SELECT movie_id,lang FROM movie_embeds WHERE movie_id IN (".implode(",",$ids).")") or die(mysql_error());
            if (mysql_num_rows($e)){
                while($s = mysql_fetch_assoc($e)){
                    if (!array_key_exists($s['movie_id'],$flags)){
                        $flags[$s['movie_id']] = array();
                    }
                    
                    if (!in_array($s['lang'],$flags[$s['movie_id']])){
                        $flags[$s['movie_id']][] = $s['lang'];
                    }
                }
            }
        }        
        return $flags;
    }
    
    public function getRealMovies($lang=null, $p=null, $l=null, $sortby=null){
        $movies = array();
            
        if (($p) && ($l)){
            $start = ($p-1)*$l;
            $limit = " LIMIT $start,$l";
        } else {
            $limit = '';
        }
        
        if (!$sortby || $sortby=='abc'){
            $order = "ORDER BY title ASC";
        } elseif ($sortby=='date'){
            $order = "ORDER BY date_added DESC";
        } elseif ($sortby=='imdb_rating'){
            $order = "ORDER BY imdb_rating DESC";
        }
        
        $e = mysql_query("SELECT * FROM movies WHERE id IN (SELECT movie_id FROM movie_embeds) $order $limit") or die(mysql_error());
        if (mysql_num_rows($e)>0){
            
            $ids = array();
            
            while($s = mysql_fetch_array($e)){
                $movies[$s['id']] = $this->formatMovieData($s, $lang);
                $ids[] = $s['id'];
            }
            
            if (count($ids)){
                
                $flags = $this->getFlags($ids);
                
                if (count($flags)){
                    foreach($movies as $movie_id => $val){
                        if (array_key_exists($movie_id,$flags)){
                            $movies[$movie_id]['languages'] = $flags[$movie_id];
                        } else {
                            $movies[$movie_id]['languages'] = array();
                        }    
                    }
                }
            }
            
        }
        return $movies;
    }
    
    public function setDate($movie_id){
        $movie_id = mysql_real_escape_string($movie_id);
        
        $up = mysql_query("UPDATE movies SET date_added=NOW() WHERE id='$movie_id'") or die(mysql_error());
    }
    
    public function saveEmbed($movie_id, $embed_code, $lang="ENG", $weight=10, $link=''){
        $embed_code = urldecode($embed_code);
        $embed_code = mysql_real_escape_string($embed_code);
        $lang = mysql_real_escape_string($lang);
        $movie_id = mysql_real_escape_string($movie_id);
        $weight = mysql_real_escape_string($weight);
        $link = mysql_real_escape_string($link);
        
        $e = mysql_query("INSERT INTO movie_embeds(movie_id, embed, `lang`, weight, link) VALUES('$movie_id', '$embed_code', '$lang', '$weight', '$link')") or die(mysql_error());
        return mysql_insert_id();
    }
    
    public function formatMovieData($data, $lang = false){
        if (!$lang){
            $data['title'] = json_decode($data['title'],true);
            $data['description'] = json_decode($data['description'],true);
        } else {
            $data['title'] = json_decode($data['title'],true);
            $data['title'] = $data['title'][$lang];
            
            $data['description'] = json_decode($data['description'],true);
            $data['description'] = $data['description'][$lang];
        }

        $data['meta'] = json_decode($data['meta'],true);
        
        return $data;
    }
    
    public function getMovie($id,$lang=false){
        $movie = array();
        
        $id = mysql_real_escape_string($id);
        $e = mysql_query("SELECT * FROM movies WHERE id='$id'") or die(mysql_error());
        if (mysql_num_rows($e)>0){
            return $this->formatMovieData(mysql_fetch_assoc($e), $lang);
        }
        return $movie;
    }

    public function reportMovie($movieid,$problem,$ip,$user_agent = ''){
        $movieid = mysql_real_escape_string($movieid);
        $problem = mysql_real_escape_string($problem);
        $user_agent = mysql_real_escape_string($user_agent);
        
        if (isset($_SESSION['loggeduser_id']) && $_SESSION['loggeduser_id']){
            $user_id = $_SESSION['loggeduser_id'];
        } else {
            $user_id = 0;
        }
        
        $e = mysql_query("SELECT id FROM broken_movies WHERE ip='$ip' AND movieid='$movieid'") or die(mysql_error());
        if (mysql_num_rows($e)==0){
            $today = date("Y-m-d H:i:s");
            $e = mysql_query("INSERT INTO broken_movies(movieid,reportdate,problem,ip,user_id,user_agent) VALUES('$movieid','$today','$problem','$ip','$user_id','$user_agent')") or die(mysql_error());
        }
    }
   
    public function getBroken($page,$lang=false){
        $start = ($page-1)*100;
        $e = mysql_query("SELECT     broken_movies.user_agent,
                                    broken_movies.user_id,
                                    broken_movies.id as brokenid,
                                    broken_movies.problem, 
                                    movies.views,
                                    movies.perma,
                                    movies.imdb_id,
                                    movies.id as movieid,
                                    movies.title,
                                    broken_movies.reportdate as `date`,
                                    broken_movies.ip 
                                    
                                    FROM movies,broken_movies WHERE broken_movies.movieid=movies.id ORDER BY broken_movies.id DESC limit $start,100") or die(mysql_error());
        $broken = array();
        if (mysql_num_rows($e)){
            $user_ids = array();
            $user_map = array();
            while($sor = mysql_fetch_array($e)){
                extract($sor);
                $broken[$sor['brokenid']] = $sor;
                if (!$lang){
                    $broken[$sor['brokenid']]['title'] = json_decode($broken[$sor['brokenid']]['title'],true); 
                } else {
                    $broken[$sor['brokenid']]['title'] = json_decode($broken[$sor['brokenid']]['title'],true);
                    if (isset($broken[$sor['brokenid']]['title'][$lang])){
                        $broken[$sor['brokenid']]['title'] = $broken[$sor['brokenid']]['title'][$lang];
                    } else {
                        $broken[$sor['brokenid']]['title'] = $broken[$sor['brokenid']]['title']['en'];
                    }
                }

                $broken[$sor['brokenid']]['url']='/watch/'.$perma;
                $broken[$sor['brokenid']]['user'] = array();
                
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
    
    public function getCommentCount(){
        $e = mysql_query("SELECT count(*) as `cnt` FROM comments WHERE type=2") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $cnt;
    }
    
    public function getAllComments($page,$lang=false){
        global $baseurl;
        
        $page = mysql_real_escape_string($page);
        $start = ($page-1)*50;
        
        $e = mysql_query("SELECT comments.*,users.username,movies.id as movieid,movies.perma,movies.title as movietitle FROM comments,users,movies WHERE comments.user_id=users.id AND comments.type=2 AND comments.target_id=movies.id ORDER BY comments.id DESC LIMIT $start,50") or die(mysql_error());
        $comments = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_array($e)){
                extract($s);
                $comments[$id] = array();
                $comments[$id]['comment'] = stripslashes($comment);
                $comments[$id]['user_id'] = $user_id;
                $comments[$id]['username'] = $username;
                if (!$lang){
                    $comments[$id]['movietitle'] = json_decode($movietitle,true);
                } else {
                    $comments[$id]['movietitle'] = json_decode($movietitle,true);
                    if (isset($comments[$id]['movietitle'][$lang])){
                        $comments[$id]['movietitle'] = $comments[$id]['movietitle'][$lang];
                    } else {
                        $comments[$id]['movietitle'] = $comments[$id]['movietitle']['en'];
                    }
                }
                $comments[$id]['movieid'] = $movieid;
                $comments[$id]['movielink'] = $baseurl."/watch/".$perma;
                $comments[$id]['date_added'] = $date_added;
            }
        }
        
        return $comments;
        
    }
         
    public function getBrokenCount(){
        $e = mysql_query("SELECT count(*) as total FROM broken_movies") or die(mysql_error());
        extract(mysql_fetch_array($e));
        return $total;
    }
   
    public function getRating($movieid){
        $movieid = mysql_real_escape_string($movieid);
        $average = 0;
        $e = mysql_query("SELECT AVG(`rating`) as average FROM movie_ratings WHERE movieid='$movieid'") or die(mysql_error());
        if (mysql_num_rows($e)>0){
          extract(mysql_fetch_array($e));
        }
        return $average;
    }

    public function deleteBroken($id){
        $id = mysql_real_escape_string($id);
        $e = mysql_query("DELETE FROM broken_movies WHERE id=$id") or die(mysql_error());
    }

    public function addRating($movieid,$rating,$ip){
        $movieid = mysql_real_escape_string($movieid);
        $rating = mysql_real_escape_string($rating);
        $e = mysql_query("SELECT id FROM movie_ratings WHERE movieid='$movieid' AND ip='$ip'") or die(mysql_error());
        $today = date("Y-m-d H:i:s");
        if (mysql_num_rows($e)>0){
            extract(mysql_fetch_array($e));
            $e = mysql_query("UPDATE movie_ratings SET rating=$rating,ratingdate='$today' WHERE ip='$ip' AND movieid='$movieid'") or die(mysql_error());
        } else {
            $e = mysql_query("INSERT INTO movie_ratings(movieid,rating,ip,ratingdate) VALUES('$movieid','$rating','$ip','$today')") or die(mysql_error());
        }
        return 1;
    }
    
    public function getRandomMovies($limit, $lang=false, $excluded_ids = array()){
        $limit = (int) $limit;
        
        if (count($excluded_ids)){
            foreach($excluded_ids as $key => $val){
                $excluded_ids[$key] = mysql_real_escape_string($val);
            }
            
            $add = " AND id NOT IN (".implode(",",$excluded_ids).")";
        } else {
            $add = "";
        }
        
        $e = mysql_query("SELECT * FROM movies ORDER BY rand() $add LIMIT $limit") or die(mysql_error());
        $res = array();
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $this->formatMovieData($s, $lang);
            }
        }
        
        return $res;
    }
    
    public function getSimilarMovies($ids,$limit=20, $lang=false){
        $e = mysql_query("SELECT * FROM similar_movies WHERE movie1 IN (".implode(",",$ids).")") or die(mysql_error());
        $movies = array();
        if (mysql_num_rows($e)){
            $similar = array();
            while($s = mysql_fetch_assoc($e)){
                if (!in_array($s['movie2'],$ids)){
                    @$similar[$s['movie2']]+=$s['score'];
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
            
            if (count($similar_ids)){
            
	            $e = mysql_query("SELECT * FROM movies WHERE id IN (".implode(",",$similar_ids).")") or die(mysql_error());
	            if (mysql_num_rows($e)){
	                while($s = mysql_fetch_assoc($e)){
	                    $movies[$s['id']] = $this->formatMovieData($s, $lang);
	                }
	            }
            }
            
        }    
        return $movies;    
    }
}
?>