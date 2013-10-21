<?php
set_time_limit(0);

require_once("../vars.php");
require_once("../includes/curl.php");
require_once("../includes/imdb.class.php");

$imdb = new IMDB();

print("updating shows\n");

$e = mysql_query("SELECT * FROM shows WHERE imdb_id!='' AND meta=''") or die(mysql_error());
if (mysql_num_rows($e)){
    while($s = mysql_fetch_assoc($e)){
        $imdb_data = $imdb->getById($s['imdb_id']);
        if ($imdb_data){
            if (isset($imdb_data['rating']) && $imdb_data['rating']){
                $imdb_rating = $imdb_data['rating'];
            } else {
                $imdb_rating = 0;
            }
            
            $meta = array();
        
            if (isset($imdb_data['year_started']) && $imdb_data['year_started']){
                $meta['year_started'] = (int) $imdb_data['year_started'];
            }
            
            if (isset($imdb_data['stars']) && $imdb_data['stars'] && is_array($imdb_data['stars']) && count($imdb_data['stars'])){
                $stars = array();
                foreach($imdb_data['stars'] as $key => $star){
                    if (trim($star)){
                        $stars[] = $star;
                    }
                }
                if (count($stars)){
                    $meta['stars'] = $stars;
                }
            }
            
            if (isset($imdb_data['creators']) && $imdb_data['creators'] && is_array($imdb_data['creators']) && count($imdb_data['creators'])){
                $creators = array();
                foreach($imdb_data['creators'] as $key => $creator){
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
            
            $up = mysql_query("UPDATE shows SET imdb_rating='$imdb_rating', meta='$meta' WHERE id='{$s['id']}'") or die(mysql_error());
            print("updated {$s['id']}\n");
        }
    }
}


print("updating movies\n");

$e = mysql_query("SELECT * FROM movies WHERE imdb_id!='' AND (meta='' OR imdb_rating=0)") or die(mysql_error());
if (mysql_num_rows($e)){
    while($s = mysql_fetch_assoc($e)){
        $imdb_data = $imdb->getById($s['imdb_id']);
        if ($imdb_data){
            if (isset($imdb_data['rating']) && $imdb_data['rating']){
                $imdb_rating = $imdb_data['rating'];
            } else {
                $imdb_rating = 0;
            }
            
            $meta = array();
        
            if (isset($imdb_data['year']) && $imdb_data['year']){
                $meta['year'] = (int) $imdb_data['year'];
            }
            
            if (isset($imdb_data['stars']) && $imdb_data['stars'] && is_array($imdb_data['stars']) && count($imdb_data['stars'])){
                $stars = array();
                foreach($imdb_data['stars'] as $key => $star){
                    if (trim($star)){
                        $stars[] = $star;
                    }
                }
                if (count($stars)){
                    $meta['stars'] = $stars;
                }
            }
            
            if (isset($imdb_data['director']) && $imdb_data['director'] && is_array($imdb_data['director']) && count($imdb_data['director'])){
                $meta['director'] = $imdb_data['director'];
            }
            
            if (count($meta)){
                $meta = mysql_real_escape_string(json_encode($meta));
            } else {
                $meta = '';
            }
            
            $up = mysql_query("UPDATE movies SET imdb_rating='$imdb_rating', meta='$meta' WHERE id='{$s['id']}'") or die(mysql_error());
            print("updated {$s['id']}\n");
        }
    }
}

?>