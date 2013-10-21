<?php 
require_once("includes/blogger.class.php");
if (file_exists($basepath."/plugins/blogger/language/".$language."/general.php")){
    require_once($basepath."/plugins/blogger/language/".$language."/general.php");
} else {
    require_once($basepath."/plugins/blogger/language/".$default_language."/general.php");
}
$blogger = new Blogger();

$perpage = 10;
if (!isset($blog_page)){
    $blog_page = 1;
}

$posts = $blogger->getPosts($blog_page,$perpage,array("language" => $language));

if (!count($posts)){
    $posts = $blogger->getPosts($blog_page,$perpage);
    $total_posts = $blogger->getPostCount();
    $max_pages = ceil($total_posts/$perpage);
} else {
    $total_posts = $blogger->getPostCount(array("language" => $language));
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
    $smarty->assign("previous_url", "/blog/page/".$previous);
} else {
    $smarty->assign("previous_url", 0);
}

if ($blog_page<$max_pages){
    $next = $blog_page+1;
    $smarty->assign("next_url", "/blog/page/".$next);    
} else {
    $smarty->assign("next_url", 0);
}

$smarty->assign("blog_posts", $posts);

?>