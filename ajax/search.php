<?php

session_start();
require_once("../vars.php");
require_once("../includes/show.class.php");
require_once("../includes/movie.class.php");
require_once("../includes/settings.class.php");
require_once("../language/language_mapping.php");

$settings = new Settings();

if (isset($_REQUEST['q'])){
    $query = trim($_REQUEST['q']);
    if (!$query){
        exit();
    }
} else {
    exit();
}

if (isset($_REQUEST['limit']) && is_numeric($_REQUEST['limit'])){
    $limit = (int) $_REQUEST['limit'];
} else {
    $limit = 5;
}

$default_language = $settings->getSetting("default_language", true);
if (!$default_language || (is_array($default_language) && empty($default_language))){
    $default_language = "en";
}

if (isset($_SESSION['language']) && $_SESSION['language']){
    $language = $_SESSION['language'];
} else if (isset($_COOKIE['language']) && $_COOKIE['language']) {
    $language = $_COOKIE['language'];
    $_SESSION['language'] = $_COOKIE['language'];
} else {
    if (isset($_SERVER['GEOIP_COUNTRY_CODE'])){
        $country_code = $_SERVER['GEOIP_COUNTRY_CODE'];
        
        if ($country_code && isset($language_mapping) && isset($language_mapping[$country_code])){
            $language = $language_mapping[$country_code];
            $_SESSION['language'] = $language_mapping[$country_code];
        } else {
            $_SESSION['language'] = $default_language;
            $language = $default_language;
        }

    } else {
        $_SESSION['language'] = $default_language;
        $language = $default_language;
    }
}

$data = array();
$show = new Show();

require_once("../language/$language/general.php");

$shows = $show->getList(null, null, $limit, "id", "DESC", $query);
if (count($shows)){
    foreach($shows as $key => $val){
        $current = array();
        
        $meta = "TV show";
                
        $current['permalink'] = $baseurl."/".$routes['show']."/".$val['permalink'];
        $current['image'] = $baseurl."/thumbs/".$val['thumbnail'];
        $current['title'] = $val['title'][$language];
        $current['meta'] = $meta;
        $data[] = $current;
    }
}


if (count($data) < $limit){
    $movie = new Movie();
    $movies = $movie->getList(null, null, $limit-count($data), "id", "DESC", $query);
    if (count($movies)){
        foreach($movies as $key => $val){
            $current = array();
            
            $meta = "Movie";
            if (isset($val['imdb_rating']) && $val['imdb_rating']){
                $meta.= " | Imdb: ".$val['imdb_rating'];
            }
            
            $current['permalink'] = $baseurl."/".$routes['movie']."/".$val['perma'];
            $current['image'] = $baseurl."/thumbs/".$val['thumb'];
            $current['title'] = $val['title'][$language];
            $current['meta'] = $meta;
            $data[] = $current;
        }
    }
}

print(json_encode($data));

?>