<?php
/*echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

echo ini_get('display_errors');*/
##############################################
#                                            #
#             author: VanDaddy               #                        
#        website: http://bligblag.net        #
#         email: nonamatt@gmail.com          #
#                                            #
##############################################                       
$language = $_SESSION['language'];
if (!$language){
    $language = "en";
    $_SESSION['language'] = "en";
}

require_once("../vars.php");
require_once("../includes/settings.class.php");
require_once("../includes/show.class.php");
require_once("../templates/smarty/libs/Smarty.class.php");
require_once("../language/$language/general.php");
require_once("../language/$language/tv_guide.php");

$settings = new Settings();

$global_settings = $settings->getMultiSettings(array("seo_links"), true);

if (!isset($global_settings['seo_links']) || !in_array($global_settings['seo_links'],array(0,1))){
    $seo_links = 1;
} else {
    $seo_links = $global_settings['seo_links'];
}

if (!isset($_SESSION['theme']) || !$_SESSION['theme']){
    $theme = $settings->getSetting("theme");
    if (!count($theme)){
        $theme = 'svarog';
    } else {
        $theme = $theme->theme;
    }
} else {
    $theme = $_SESSION['theme'];
}

$smarty = new Smarty();
$smarty->caching = 0;
$smarty->template_dir = "$basepath/templates/$theme";
$smarty->compile_dir = "$basepath/cachefiles/";
$smarty->config_dir = "$basepath/templates/smarty/configs";
$smarty->cache_dir = "$basepath/cachefiles";
$smarty->assign("templatepath","$baseurl/templates/$theme");

if (!isset($_REQUEST['date'])){
    $date = date("Y-m-d");
} else {
    $date = $_REQUEST['date'];
}

$check = date("Y",strtotime($date));
if ($check < 2010){
    $date = date("Y-m-d");
}

require_once("../vars.php");
require_once("../includes/curl.php");

$curl = new Curl();
$show = new Show();

$cache_file = "tv_guide_".$date.".txt";
$from_cache = false;

if (file_exists($basepath."/cachefiles/".$cache_file)){
    $data = file_get_contents($basepath."/cachefiles/".$cache_file);
    $from_cache = true;
} else {
    $today = date("Ymd");
    $b64 = 'aHR0cDovL2FwaS50cmFrdC50di9jYWxlbmRhci9zaG93cy5qc29uLzUyMWRmZjU0NTQyM2RiNGE1NmUxNzUwMTNkYmFkNGFiLw==';
    $request = ''. base64_decode($b64) . urlencode($today) . '/1/';
    $data = $curl->get($request);    
}

if ($from_cache || ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400)){
    
    if (!$from_cache && is_writable($basepath."/cachefiles/")){
        @file_put_contents($basepath."/cachefiles/".$cache_file, $data);
    }
    
    $data = json_decode($data, true);
     
    if (isset($data['0'])){
   
        foreach($data['0']['episodes'] as $key => $event){
            
            $events[$key]['episode_number'] = str_replace(array("#season#","#episode#"), array(str_pad($event['episode'][season],2, 0, STR_PAD_LEFT),str_pad($event['episode'][number],2, 0, STR_PAD_LEFT)),  $lang['tv_guide_episode_number']);            
            $show_data = $show->getShowByImdb($events[$key]['imdb_id'], $language);
 
            if (!count($show_data)){
             // echo $show_data;
             
                $events[$key]['imdb_id'] = $event['show'][imdb_id];
                $events[$key]['country'] = $event['show'][country];  
                $events[$key]['series'] = $event['show'][title];
                $events[$key]['network'] = $event['show'][network];
                $events[$key]['air_time'] = $event['show'][air_time];
                $events[$key]['show_desc'] = $event['show'][overview];
                $events[$key]['episode_desc'] = $event['episode'][overview];
                $events[$key]['runtime'] = $event['show'][runtime];

            if($events[$key]['country'] == 'United States'){
                $us = str_replace("United States", "US", "United States");
                $events[$key]['country'] = $us;
            } elseif ($events[$key]['country'] == 'United Kingdom'){
                $uk = str_replace("United Kingdom", "UK", "United Kingdom");
                $events[$key]['country'] = $uk;
            } elseif ($events[$key]['country'] == 'Canada') {
                    $ca = str_replace("Canada", "CA", "Canada");
                    $events[$key]['country'] = $ca;
            } elseif ($events[$key]['country'] == 'Australia') {
                    $au = str_replace("Australia", "AU","Australia");
                    $events[$key]['country'] = $au;
            
            } else { 
                    $events[$key]['country'] = $event['show'][country];
                }
            } else {
                if ($seo_links){ 
                  $events[$key]['series'] = "<a href=\"$baseurl/".$routes['show']."/".$show_data['permalink']."\">".$show_data['title']."</a>";    
                } else {;
                   $events[$key]['series'] = "<a href=\"$baseurl/index.php?menu=show&perma=".$show_data['permalink']."\">".$show_data['title']."</a>";
                }
                
                $episode_data = $show->getEpisode($show_data['id'], $event['season'], $event['number']);
                if ($episode_data){
                    if ($seo_links){
                        $events[$key]['episode_number'] = "<a href=\"$baseurl/".$routes['show']."/".$show_data['permalink']."/season/".$event['season']."/episode/".$event['episode']."\">".$events[$key]['episode_number']."</a>";
                    } else {
                        $events[$key]['episode_number'] = "<a href=\"$baseurl/index.php?menu=episode&perma=".$show_data['permalink']."&season=".$event['season']."&episode=".$event['episode']."\">".$events[$key]['episode_number']."</a>";
                    }
                }
            }
        }
        
        
        $smarty->assign("events", $events);
    } else {
        $smarty->assign("events", array());
    }
    
} else {
    $smarty->assign("events", array());
}

if (isset($data['guide']['next_day'])){
    $smarty->assign("do_next_day", $data['guide']['next_day']);
} else {
    $smarty->assign("do_next_day", "no");
}

if (isset($data['guide']['prev_day'])){
    $smarty->assign("do_previous_day", $data['guide']['prev_day']);
} else {
    $smarty->assign("do_previous_day", "no");
}

if ($date == date("Y-m-d")){
    $title = $lang['tv_guide_todays_schedule'];
} elseif ($date == date("Y-m-d", strtotime("tomorrow"))){
    $title = $lang['tv_guide_tomorrows_schedule'];
} elseif ($date == date("Y-m-d", strtotime("yesterday"))){
    $title = $lang['tv_guide_yesterdays_schedule'];
} else {
    $title = str_replace("#date#", $date, $lang['tv_guide_dates_schedule']);
}

$result = mysql_query("SELECT imdb_id FROM shows WHERE imdb_id IS NOT NULL");

while ($row = mysql_fetch_array($result)) {

$imdbdb = $row['imdb_id'];

}



$smarty->assign("test", $imdbdb);
$smarty->assign("previous_day", date("Y-m-d",strtotime("-1 day", strtotime($date))));
$smarty->assign("next_day", date("Y-m-d",strtotime("+1 day", strtotime($date))));
$smarty->assign("server_timezone", date('T'));
$smarty->assign("title", $title);
$smarty->assign("date", $date);
$smarty->assign("lang", $lang);
$smarty->display("ajax_tv_guide.tpl");

?>