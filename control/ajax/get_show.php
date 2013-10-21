<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors","On");

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id']){
    exit();
}

require_once("../../vars.php");
require_once("../../includes/curl.php");
require_once("../../includes/imdb.class.php");
require_once("../../includes/show.class.php");

if (isset($_POST['title'])){
    $title = urldecode($_POST['title']);
} elseif (isset($_GET['title'])) {
    $title = urldecode($_GET['title']);
} else {
    $title = false;
}

if (isset($_POST['imdb_id'])){
    $imdb_id = urldecode($_POST['imdb_id']);
} elseif (isset($_GET['imdb_id'])) {
    $imdb_id = urldecode($_GET['imdb_id']);
} else {
    $imdb_id = false;
}

if (!$imdb_id && !$title){
    print("0"); exit();
}

$imdb = new IMDB();
if ($imdb_id){
    $data = $imdb->getById($imdb_id);
} else {
    $data = $imdb->getShowDetails($title);
}


if ($data){
    
    $ret = array();
    if (isset($data['imdb_id'])){
        $ret['imdb_id'] = $data['imdb_id'];
    }
    
    if (isset($imdb_id) && $imdb_id){
        $ret['imdb_id'] = $imdb_id;
    }
    
    if (isset($data['title'])){
        $ret['title'] = $data['title'];
    }
    
    if (isset($data['summary'])){
        $ret['description'] = $data['summary'];
    }
    
    if (isset($data['stars'])){
        $ret['stars'] = $data['stars'];
    } else {
        $ret['stars'] = array();
    }
    
    if (isset($data['rating'])){
        $ret['rating'] = $data['rating'];
    } else {
        $ret['rating'] = 0;
    }
    
    if (isset($data['year_started'])){
        $ret['year_started'] = $data['year_started'];
    } else {
        $ret['year_started'] = 0;
    }
    
    if (isset($data['year_ended'])){
        $ret['year_ended'] = $data['year_ended'];
    } else {
        $ret['year_ended'] = 0;
    }
    
    if (isset($data['creators'])){
        $ret['creators'] = $data['creators'];
    } else {
        $ret['creators'] = array();
    }
    
    if (isset($data['genres']) && is_array($data['genres']) && count($data['genres'])){
        $show = new Show();
        $categories = $show->getCategories("en");
        $ret['categories'] = array();
        if (count($categories)){
            foreach($categories as $category_id => $val){
                foreach($data['genres'] as $key => $genre){
                    if (preg_replace("/[^a-z0-9]/","",strtolower($genre))==preg_replace("/[^a-z0-9]/","",strtolower($val['name']))){
                        $ret['categories'][] = $category_id;
                    }
                }
            }
        }
    }
    
    if (isset($data['image'])){
        $curl = new Curl();
        $image_data = $curl->get($data['image']);
        if ($image_data && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
            $image_name = "show_".md5($title.$imdb_id);
            
            $handle = fopen("../../thumbs/$image_name.jpg","w+");
            fwrite($handle,$image_data);
            fclose($handle);
            
            $ret['image']=$image_name.".jpg";
        } else {
            $ret['image'] = "0";
        }
    }
    
    
    print(json_encode($ret));
} else {
    print("0");
}
?>