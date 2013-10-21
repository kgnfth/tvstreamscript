<?php

$mov = $movie->getByPerma($perma,$language);
 
if (empty($mov)){
    $mov = '';
}    else {
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
    
    $movie->addView($mov['id']);
    
    $mov['description'] = nl2br(stripslashes($mov['description']));
    $mov['title']=stripslashes($mov['title']);
    $mov['embed']=stripslashes(stripslashes($mov['embed']));
    $mov['rating'] = $movie->getRating($mov['id']);
    
    $mov['embeds'] = $movie->getEmbeds($mov['id']);
    if (count($mov['embeds'])){
        
        $sortable = array();
        foreach($mov['embeds'] as $key => $val){
            
            if (substr_count($mov['embeds'][$key]['lang'],$lang['code'])){
                if (substr_count($mov['embeds'][$key]['lang'],"SUB")){
                    $mov['embeds'][$key]['weight'] += 10;
                } else {
                    $mov['embeds'][$key]['weight'] += 20;
                }
            }
            
            $sortable[$key] = $mov['embeds'][$key]['weight'];
            
            $mov['embeds'][$key]['embed'] = stripslashes(stripslashes($mov['embeds'][$key]['embed']));
            if (!substr_count($mov['embeds'][$key]['embed'],"novamov") && !substr_count($mov['embeds'][$key]['embed'],"filebox") && !substr_count($mov['embeds'][$key]['embed'],"nosvideo")){
                $mov['embeds'][$key]['embed'] = preg_replace("/ width\=['\"]?(\d+)['\"]?/"," width='620'",$mov['embeds'][$key]['embed']);
                if (!trim($mov['embeds'][$key]['embed'])){
                    unset($mov['embeds'][$key]);
                }
            }
        }
        
        arsort($sortable);
        $tmp = array();
        foreach($sortable as $key => $val){
            $tmp[$key] = $mov['embeds'][$key];
        }
        
        $mov['embeds'] = $tmp;
        unset($tmp);
    }
    
    $tags = $movie->getMovieCategoryDetails($mov['id'],$language);
    if (count($tags)){
        $smarty->assign("tags",$tags);
    } else {
        $smarty->assign("tags","");
    }
    
    $videoad = $settings->getSetting("videoad");
    if (@$videoad){
        $videoad = stripslashes($videoad->code);
    } else {
        $videoad = '';
    }
    
    if (in_array($mov['id'],$seen)){
        $seen = 1;
    } else {
        $seen = 0;
    }
    
    $smarty->assign("seen",$seen);
    $smarty->assign("videoad",$videoad);
    $smarty->assign("fb_image",$baseurl."/thumbs/".$mov['thumb']);
    
    $seodata['title']=stripslashes($mov['title']);
    $seodata['description']=stripslashes($mov['description']);
}
if ($global_settings['seo_links']){
    $smarty->assign("fullurl",$baseurl."/".$routes['movie']."/".$perma);
} else {
    $smarty->assign("fullurl",$baseurl."/index.php?menu=watchmovie&perma=".$perma);
}
$smarty->assign("mov",$mov);

?>