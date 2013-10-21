<?php
if (isset($tag) && $tag){
    
    if (isset($_REQUEST['p']) && $_REQUEST['p'] && is_numeric($_REQUEST['p'])){
        $p = (int) $_REQUEST['p'];
    } else {
        $p = 1;
    }
    
    if (!$global_settings['maxmoviesperpage']){
        $maxperpage = 40;
    } else {
        $maxperpage = $global_settings['maxmoviesperpage'];
    }
    
    $tag_perma = $tag;
    $smarty->assign("tag_perma",$tag_perma);    
    $tag = $movie->getCategoryByPerma($tag_perma,$language);
    
    if ($tag){
    
        if (!isset($sortby)){
            $sortby = "date";
        }
    
        $tag_id = $tag['id'];
        $tag = $tag['tag'];
        
        $category_count = $movie->getCategoryCount($tag_id);
        
        if ($global_settings['seo_links']){
            $pagination = $movie->getBasicPagination($category_count,$p,$maxperpage,$baseurl."/".$routes['movie_tag']."/".$tag_perma."/".$sortby."/");
        } else {
            $pagination = $movie->getBasicPagination($category_count,$p,$maxperpage,$baseurl."/index.php?menu=movie-tag&tag=$tag_perma&sortby=$sortby&p=");
        }
        
        
        $tagmovies = $movie->getMoviesByCategory($tag_id, $sortby, $language, $p, $maxperpage);
        
        if (!count($tagmovies)){
            $tagmovies = '';
        } else {
            if ($logged){
                $user = new User();
                if (!isset($_SESSION['loggeduser_seen_movies'])){
                    $seen = $user->getSeenMovies(null,true);
                } else {
                    $seen = $_SESSION['loggeduser_seen_movies'];
                }
            } else {
                $seen = array();
            }
            
            foreach($tagmovies as $key => $val){
                extract($val);
                $description = nl2br(stripslashes($description));
                $tagmovies[$key]['description']=$description;
                $tagmovies[$key]['title']=stripslashes($title);
                
                if (in_array($key,$seen)){
                    $tagmovies[$key]['seen'] = 1;
                } else {
                    $tagmovies[$key]['seen'] = 0;
                }
            }
        }
        $smarty->assign("tagmovies",$tagmovies);
        $smarty->assign("sortby",$sortby);
        
    } else {
    	$tag = '';
    	$pagination = '';
    }
} else {
    $tag = '';
    $pagination = '';
}
$seodata['category'] = $tag;
$smarty->assign("tag", $tag);
$smarty->assign("pagination", $pagination);

?>