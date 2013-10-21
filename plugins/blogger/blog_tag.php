<?php 
require_once("includes/blogger.class.php");
if (file_exists($basepath."/plugins/blogger/language/".$language."/general.php")){
    require_once($basepath."/plugins/blogger/language/".$language."/general.php");
} else {
    require_once($basepath."/plugins/blogger/language/".$default_language."/general.php");
}

$blogger = new Blogger();

if (!isset($perma)){
    header("location: $baseurl/blog");
    exit();
}

$perpage = 10;
if (!isset($blog_page)){
    $blog_page = 1;
}

$tag = $blogger->getTagByPerma($perma);
if (!$tag){
    header("location: $baseurl/blog");
    exit();
}
$posts = $blogger->getPostsByTag($perma,$blog_page,$perpage, array("language" => $language));
if (!count($posts)){
    $posts = $blogger->getPostsByTag($perma,$blog_page,$perpage);    
    $total_posts = $blogger->getPostCountByTag($tag['id']);
    $max_pages = ceil($total_posts/$perpage);
} else {
    $total_posts = $blogger->getPostCountByTag($tag['id'],array("language" => $language));
    $max_pages = ceil($total_posts/$perpage);
}

if (count($posts)){
    foreach($posts as $key => $val){
        $content = $val['content'];
        $content = str_replace("<br />","\n",$content);
        $content = str_replace("<br/>","\n",$content);
        $content = strip_tags($content);
        $content = explode("\n",$content);
        
        $excerpt = '';
        foreach($content as $content_id => $chunk){
            if (strlen(trim($chunk))>50){
                $excerpt = $chunk;
                break;        
            }
        }
        
        if (!$excerpt){
            $excerpt = $content;
        }
        if (strlen($excerpt)>600){
            $posts[$key]['excerpt'] = substr($excerpt, 0, strpos(wordwrap($excerpt, 600), "\n"))."...";
        } else {
            $posts[$key]['excerpt'] = $excerpt;
        }
    }
}


if ($blog_page!=1){
    $previous = $blog_page-1;
    $smarty->assign("previous_url", "/blog/tag/$perma/page/".$previous);
} else {
    $smarty->assign("previous_url", 0);
}

if ($blog_page<$max_pages){
    $next = $blog_page+1;
    $smarty->assign("next_url", "/blog/tag/$perma/page/".$next);    
} else {
    $smarty->assign("next_url", 0);
}


$smarty->assign("blog_posts", $posts);
$smarty->assign("tag", $tag);

?>