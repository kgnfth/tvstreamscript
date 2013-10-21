<?php 

class Blogger{

    public $valid = false;
    
    function __construct($license = null){
        
        $this->valid = true;
    }
    
    public function deletePost($post_id){
        $post_id = mysql_real_escape_string($post_id);
        
        $del = mysql_query("DELETE FROM blogger_post_tags WHERE post_id='$post_id'") or die(mysql_error());
        $del = mysql_query("DELETE FROM blogger_posts WHERE id='$post_id'") or die(mysql_error());
    }
    
    public function getTagByPerma($perma){
        $perma = mysql_real_escape_string($perma);
        $e = mysql_query("SELECT * FROM blogger_tags WHERE perma='$perma'") or die(mysql_error());
        if (mysql_num_rows($e)){
            return mysql_fetch_assoc($e);
        } else {
            return false;
        }
    }
    
    public function validatePost($params){
        $errors = array();
        
        if (!isset($params['title']) || !$params['title'] || !trim($params['title'])){
            $errors[1] = "Please enter the title of this post";
        }
        
        if (!isset($params['content']) || !$params['content'] || !trim($params['content'])){
            $errors[2] = "Please enter the content of this post";
        }
        
        return $errors;
    }
    
    public function getPostCountByTag($tag_id, $params = array()){
        if (count($params)){
            $criterias = array();
            foreach($params as $key => $val){
                $criterias[] = "`".mysql_real_escape_string($key)."`='".mysql_real_escape_string($val)."'";
            }
            
            $where = "AND ".implode(" AND ",$criterias);
        } else {
            $where = "";
        }
        
        $tag_id = mysql_real_escape_string($tag_id);
        
        $e = mysql_query("SELECT count(*) as cnt FROM blogger_posts WHERE id IN (SELECT post_id FROM blogger_post_tags WHERE tag_id='$tag_id') $where") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            return $cnt;
        } else {
            return 0;
        }
    }
    
    public function getPostCount($params = array()){
        if (count($params)){
            $criterias = array();
            foreach($params as $key => $val){
                $criterias[] = "`".mysql_real_escape_string($key)."`='".mysql_real_escape_string($val)."'";
            }
            
            $where = "WHERE ".implode(" AND ",$criterias);
        } else {
            $where = "";
        }
        
        $e = mysql_query("SELECT count(*) as cnt FROM blogger_posts $where") or die(mysql_error());
        if (mysql_num_rows($e)){
            extract(mysql_fetch_assoc($e));
            return $cnt;
        } else {
            return 0;
        }
    }
    
    public function getPostsByTag($perma, $page = 1, $limit = 50, $params = array()){
        $limit = (int) $limit;
        $page = (int) $page;
        $start = ($page-1) * $limit;
        
        $perma = mysql_real_escape_string($perma);
        
        $posts = array();       
        
        $check = mysql_query("SELECT id as tag_id FROM blogger_tags WHERE perma='$perma'") or die(mysql_error());
        if (mysql_num_rows($check)){
            extract(mysql_fetch_assoc($check));
        } else {
            return $posts;
        }
        
        if (count($params)){
            $criterias = array();
            foreach($params as $key => $val){
                $criterias[] = "`".mysql_real_escape_string($key)."`='".mysql_real_escape_string($val)."'";
            }
            
            $where = "AND ".implode(" AND ",$criterias);
        } else {
            $where = "";
        }
        
        
        $e = mysql_query("SELECT * FROM blogger_posts WHERE id IN (SELECT post_id FROM blogger_post_tags WHERE tag_id='$tag_id') $where ORDER BY id DESC LIMIT $start,$limit") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $posts[$s['id']] = $s;
            }
        }
        
        return $posts;
    }
    
    public function getPosts($page = 1, $limit = 50, $params = array()){
        $limit = (int) $limit;
        $page = (int) $page;
        $start = ($page-1) * $limit;
        
        $posts = array();
        
        if (count($params)){
            $criterias = array();
            foreach($params as $key => $val){
                $criterias[] = "`".mysql_real_escape_string($key)."`='".mysql_real_escape_string($val)."'";
            }
            
            $where = "WHERE ".implode(" AND ",$criterias);
        } else {
            $where = "";
        }
        
        
        $e = mysql_query("SELECT * FROM blogger_posts $where ORDER BY id DESC LIMIT $start,$limit") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $posts[$s['id']] = $s;
            }
        }
        
        return $posts;
    }
    
    public function addTags($post_id, $tags){
        $post_id = mysql_real_escape_string($post_id);
        $delete = mysql_query("DELETE FROM blogger_post_tags WHERE post_id='$post_id'") or die(mysql_error());
        foreach($tags as $key => $tag){
            $tag = trim($tag);
            if ($tag){
                
                $tag_perma = $this->makePerma($tag);
                $check = mysql_query("SELECT id as tag_id FROM blogger_tags WHERE perma='$tag_perma'") or die(mysql_error());
                if (mysql_num_rows($check)){
                    extract(mysql_fetch_assoc($check));
                } else {
                    $tag = mysql_real_escape_string($tag);
                    $ins = mysql_query("INSERT INTO blogger_tags(tag,perma) VALUES('$tag','$tag_perma')") or die(mysql_error());
                    $tag_id = mysql_insert_id();
                }
                
                $ins = mysql_query("INSERT INTO blogger_post_tags(post_id,tag_id) VALUES('$post_id','$tag_id')") or die(mysql_error());
            }
        }
    }
    
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
    
    public function getTags($post_id){
        $post_id = mysql_real_escape_string($post_id);
        $tags = array();
        $e = mysql_query("SELECT blogger_tags.* FROM blogger_tags, blogger_post_tags WHERE blogger_post_tags.tag_id = blogger_tags.id AND blogger_post_tags.post_id='$post_id'") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $tags[$s['id']] = $s;
            }
        }
        
        return $tags;
    }
    
    public function getPost($post_id){
        $post_id = mysql_real_escape_string($post_id);
        $check = mysql_query("SELECT * FROM blogger_posts WHERE id='$post_id'") or die(mysql_error());
        if (mysql_num_rows($check)){
            $post = mysql_fetch_assoc($check);
            $post['tags'] = $this->getTags($post_id);
            return $post;
        } else {
            return false;
        }
    }
    
    public function updatePost($post_id, $params){
        $post_id = mysql_real_escape_string($post_id);
        $thumbnail = mysql_real_escape_string($params['thumbnail']);
        $title = mysql_real_escape_string($params['title']);
        $content = mysql_real_escape_string($params['content']);
        $language = mysql_real_escape_string($params['language']);
        $perma = $this->makePerma($params['title']);
        
        $check = mysql_query("SELECT count(*) as perma_count FROM blogger_posts WHERE perma='$perma' AND id!='$post_id'") or die(mysql_error());
        if (mysql_num_rows($check)){
            extract(mysql_fetch_assoc($check));
            if ($perma_count){
                $perma = $perma."-".$perma_count;
            }
        }
        
        $upd = mysql_query("UPDATE blogger_posts SET title='$title', perma='$perma', content='$content', thumbnail='$thumbnail', language='$language' WHERE id='$post_id'") or die(mysql_error());
        
        $tags = explode(",",trim($params['tags']));
        
        if (count($tags)){
            $this->addTags($post_id, $tags);
        }
    }
    
    public function addPost($params){
        $thumbnail = mysql_real_escape_string($params['thumbnail']);
        $title = mysql_real_escape_string($params['title']);
        $content = mysql_real_escape_string($params['content']);
        $language = mysql_real_escape_string($params['language']);
        $perma = $this->makePerma($params['title']);
        
        if (isset($params['original_url']) && $params['original_url']){
            $original_url = mysql_real_escape_string($params['original_url']);
        } else {
            $original_url = "";
        }
        
        $check = mysql_query("SELECT count(*) as perma_count FROM blogger_posts WHERE perma='$perma'") or die(mysql_error());
        if (mysql_num_rows($check)){
            extract(mysql_fetch_assoc($check));
            if ($perma_count){
                $perma = $perma."-".$perma_count;
            }
        }
        
        $ins = mysql_query("INSERT INTO blogger_posts(title,perma,content,thumbnail,created,language,original_url) VALUES('$title','$perma','$content','$thumbnail',NOW(),'$language', '$original_url')") or die(mysql_error());
        $post_id = mysql_insert_id();
        
        $tags = explode(",",trim($params['tags']));
        if (count($tags)){
            $this->addTags($post_id, $tags);
        }
        
        return $post_id;
    }
    
    public function getFeeds(){
        $res = array();
        
        $e = mysql_query("SELECT * FROM blogger_feeds") or die(mysql_error());
        if (mysql_num_rows($e)){
            while($s = mysql_fetch_assoc($e)){
                $res[$s['id']] = $s;                
            }
        }
        
        return $res;
    }
    
    public function getFeed($feed_id){
        $feed_id = mysql_real_escape_string($feed_id);
        
        $check = mysql_query("SELECT * FROM blogger_feeds WHERE id='$feed_id'") or die(mysql_error());
        if (mysql_num_rows($check)){
            return mysql_fetch_assoc($check);
        } else {
            return array();
        }
    }
    
    public function deleteFeed($feed_id){
        $feed_id = mysql_real_escape_string($feed_id);
        
        $del = mysql_query("DELETE FROM blogger_feeds WHERE id='$feed_id'") or die(mysql_error());
    }
    
    public function setFeedDate($feed_id){
        $feed_id = mysql_real_escape_string($feed_id);
        
        $upd = mysql_query("UPDATE blogger_feeds SET last_checked=NOW() WHERE id='$feed_id'") or die(mysql_error());
    }
    
    public function updateFeed($params, $feed_id){
        $params['url'] = mysql_real_escape_string($params['url']);
        $params['frequency'] = (int) $params['frequency'];
        $params['language'] = mysql_real_escape_string($params['language']);
        
        $feed_id = mysql_real_escape_string($feed_id);
        
        $upd = mysql_query("UPDATE blogger_feeds SET url='{$params['url']}', frequency='{$params['frequency']}', language='{$params['language']}' WHERE id='$feed_id'") or die(mysql_error());
    }
    
    public function addFeed($params){
        $params['url'] = mysql_real_escape_string($params['url']);
        $params['frequency'] = (int) $params['frequency'];
        $params['language'] = mysql_real_escape_string($params['language']);
        
        $ins = mysql_query("INSERT INTO blogger_feeds(url, frequency, language) VALUES('{$params['url']}','{$params['frequency']}','{$params['language']}')") or die(mysql_error());
        return mysql_insert_id();
    }
    
    public function validateFeed($params, $update = false){
        $errors = array();
        
        if (!isset($params['url']) || !$params['url']){
            $errors[1] = "Please enter the feed's URL";
        } elseif (!$update){
            $params['url'] = mysql_real_escape_string($params['url']);
            $check = mysql_query("SELECT * FROM blogger_feeds WHERE url='{$params['url']}'") or die(mysql_error());
            if (mysql_num_rows($check)){
                $errors[1] = "This feed is already in the database";
            }
        }
        
        if (!isset($params['frequency']) || !$params['frequency'] || !is_numeric($params['frequency'])){
            $errors[2] = "Please enter a valid crawl frequency";
        }
        
        if (!isset($params['language']) || !$params['language'] || strlen($params['language'])!=2){
            $errors[2] = "Please select a language";
        }
        
        return $errors;
    }
    
    public function getThumbnail($content, $curl){
        global $basepath;
        
        $dom = new DOMDocument();
        @$dom->loadHTML($content);
        
        $images = $dom->getElementsByTagName('img');
        if ($images->length){
            for ($i=0;$i<$images->length;$i++){
                $src = $images->item($i)->getAttribute("src");

                $image_data = $curl->get($src);
                if ($image_data && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                    file_put_contents($basepath."/thumbs/blogger_".md5($src).".jpg", $image_data);
                    if (file_exists($basepath."/thumbs/blogger_".md5($src).".jpg")){
                        $image_size = getimagesize($basepath."/thumbs/blogger_".md5($src).".jpg");
                        if ($image_size && isset($image_size[0]) && isset($image_size[1]) && $image_size[0]>=150 && $image_size[1]>50){
                            return "blogger_".md5($src).".jpg";
                        }
                        unlink($basepath."/thumbs/blogger_".md5($src).".jpg");
                    }
                } 
            }
            
            return '';
        } else {
            return '';
        }
    }
    
}

?>