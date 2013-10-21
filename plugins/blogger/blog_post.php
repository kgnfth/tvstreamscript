<?php

require_once("includes/blogger.class.php");
if (file_exists($basepath."/plugins/blogger/language/".$language."/general.php")){
    require_once($basepath."/plugins/blogger/language/".$language."/general.php");
} else {
    require_once($basepath."/plugins/blogger/language/".$default_language."/general.php");
}
$blogger = new Blogger();

$post = $blogger->getPosts(1,10,array("perma" => $perma));
if (count($post)){
    foreach($post as $post_id => $post_data){
        $post_data['tags'] = $blogger->getTags($post_id);
        
        if ($post_data['original_url']){
            $post_data['content'].= "<div class=\"clear\"></div><br /><a href=\"".$post_data['original_url']."\" target=\"_blank\" class=\"blog_read_more\">(Source)</a><div class=\"clear\"></div>";  
        }
        
        $smarty->assign("post_data", $post_data);
        $smarty->assign("post_id", $post_id);
    }
} else {
    $smarty->assign("post_id", 0);
}
