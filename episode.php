<?php

$thisshow = $show->getShowByPerma($perma,$language);

if (count($thisshow)){
    foreach($thisshow as $id => $val){
        extract($val);
        $showid = $id;
        $showtitle = $title;
        $smarty->assign("fb_image",$baseurl."/thumbs/".$thumbnail);
    }

    $thisepisode = $show->getEpisode($showid,$season,$episode);
    $similar_shows = $show->getSimilar($showid,8,$language);
    
    if (count($similar_shows)){
        $smarty->assign("similar_shows",$similar_shows);
    }
    
    if ($thisepisode){
        
        if ($logged){
            $user = new User();
            if (!isset($_SESSION['loggeduser_seen_episodes'])){
                $seen = $user->getSeenEpisodes(null,true);
            } else {
                $seen = $_SESSION['loggeduser_seen_episodes'];
            }
        } else {
            $seen = array();
        }
        
        extract($thisepisode);
        
        $thisepisode['rating'] = $show->getRating($episodeid);
        $thisepisode['showtitle'] = stripslashes($showtitle);
        $thisepisode['description'] = trim(stripslashes($description));
        $thisepisode['showthumbnail'] = $thisshow[$showid]['thumbnail'];
        $thisepisode['showdescription'] = trim($thisshow[$showid]['description']);
        $thisepisode['season'] = $season;
        $thisepisode['episode'] = $episode;
        
        
        $seodata['showtitle']=$thisepisode['showtitle'];        
        $seodata['description']=stripslashes($description);
        $seodata['episode']=$episode;
        $seodata['title']=$thisepisode['title'];
        $seodata['season']=$season;

        
        $episodeid = $thisepisode['episodeid'];    
        $thisepisode['embeds'] = $show->getEpisodeEmbeds($episodeid);
        
        if (in_array($episodeid,$seen)){
            $seen = 1;
        } else {
            $seen = 0;
        }
        
        $smarty->assign("seen",$seen);
        
        
        if (count($thisepisode['embeds'])){
            $sortable = array();
            foreach($thisepisode['embeds'] as $key => $val){
                
                if (substr_count($thisepisode['embeds'][$key]['lang'],$lang['code'])){
                    // if the embed's language is the same as the users we boost the weight    
                    if (substr_count($thisepisode['embeds'][$key]['lang'],"SUB")){
                        $thisepisode['embeds'][$key]['weight'] += 10;
                    } else {    
                        $thisepisode['embeds'][$key]['weight'] += 20;
                    }
                }
                
                $sortable[$key] = $thisepisode['embeds'][$key]['weight']; 
                
                if (substr_count($thisepisode['embeds'][$key]['embed'],"novamov")==0 && substr_count($thisepisode['embeds'][$key]['embed'],"filebox")==0 && substr_count($thisepisode['embeds'][$key]['embed'],"nosvideo")==0){
                    
                    $thisepisode['embeds'][$key]['embed'] = preg_replace("/ width\=['\"]?(\d+)['\"]?/"," width='620'",$thisepisode['embeds'][$key]['embed']);
                    $thisepisode['embeds'][$key]['embed'] = str_replace("/custom/500","/custom/620",$thisepisode['embeds'][$key]['embed']);
                    if (!trim($thisepisode['embeds'][$key]['embed']) || (substr_count($thisepisode['embeds'][$key]['embed'],"sharehoster.com"))){
                        unset($thisepisode['embeds'][$key]);
                    }
                }
            }
            
            
            arsort($sortable);
            $tmp = array();
            foreach($sortable as $key => $val){
                $tmp[$key] = $thisepisode['embeds'][$key];
            }
            
            $thisepisode['embeds'] = $tmp;
            unset($tmp);
            
        }
        
        $show->addView($episodeid);
        
        $linkerror = '';
        if ((@$_SESSION['loggeduser_id']) && (@$addlink)){
            if ((@$linktitle) && (@$link)){
                $show->addLink($episodeid,$linktitle,$link,$_SESSION['loggeduser_id']);
                $smarty->assign("linksuccess",1);
            } else {
                $linkerror = "All fields are mandatory";
            }
        }
        
        $smarty->assign("linkerror",$linkerror);
        
        $videoad = $settings->getSetting("videoad");
        if (@$videoad){
            $videoad = stripslashes($videoad->code);
        } else {
            $videoad = '';
        }
        
        $links = $show->getLinks($episodeid,1);
        if (!count($links)){ $links = ''; $linkcount = 0; } else { $linkcount = count($links); }
        $smarty->assign("links",$links);
        $smarty->assign("linkcount",$linkcount);
        if ($linkcount==1){
            $smarty->assign("link_title",$linkcount." link");
        } else {
            $smarty->assign("link_title",$linkcount." links");
        }
        
        $next = $show->getNextEpisode($showid,$season,$episode);
        if (count($next)){
            $smarty->assign("next_season",$next['season']);
            $smarty->assign("next_episode",$next['episode']);
        } else {
            $smarty->assign("next_season","");
            $smarty->assign("next_episode","");
        }
        
        $prev = $show->getPrevEpisode($showid,$season,$episode);
        if (count($prev)){
            $smarty->assign("prev_season",$prev['season']);
            $smarty->assign("prev_episode",$prev['episode']);
        } else {
            $smarty->assign("prev_season","");
            $smarty->assign("prev_episode","");
        }
        
        $moreepisodes = $show->getEpisodes($showid,$season,$language);
        if (count($moreepisodes)){
            foreach($moreepisodes as $key => $val){
                $moreepisodes[$key]['title']=stripslashes(stripslashes($moreepisodes[$key]['title']));
                $moreepisodes[$key]['episodetitle']=stripslashes(stripslashes($moreepisodes[$key]['episodetitle']));
                if (strlen($moreepisodes[$key]['episodetitle'])>40) $moreepisodes[$key]['episodetitle']=substr($moreepisodes[$key]['episodetitle'],0,40)."...";
            }
        } 
        
        if (substr_count($referer,"sidereel") || substr_count($referer,"one-tvshows")){
            $smarty->assign("is_sidereel",1);
        } else {
            $smarty->assign("is_sidereel",0);
        }
        
        $show_meta = $thisshow[$showid]['meta'];
        $show_categories = $show->getShowCategories($showid, true, $language);
        
    } else {
        $seodata = array();
        $thisshow = '';
        $moreepisodes = array();
    }
} else {
    $seodata = array();
    $thisshow = '';
    $moreepisodes = array();
}


if (isset($global_settings['seo_links']) && $global_settings['seo_links']){
    $smarty->assign("fullurl",$baseurl."/".$routes['show']."/".$perma."/season/".$season."/episode/".$episode);
} else {
    $smarty->assign("fullurl",$baseurl."/index.php?menu=episode&perma=".$perma."&season=".$season."&episode=".$episode);
}

$smarty->assign("perma",$perma);
$smarty->assign("season",$season);
$smarty->assign("thisepisode",$thisepisode);
$smarty->assign("videoad",@$videoad);
$smarty->assign("thisshow",$thisshow);

$smarty->assign("show_meta",$show_meta);
$smarty->assign("show_categories",$show_categories);
$smarty->assign("show_data", $thisshow[$showid]);

$smarty->assign("showid",$showid);
$smarty->assign("moreepisodes",$moreepisodes);
?>
