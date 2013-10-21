<?php
session_start();
if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id']){
    exit();
}

set_time_limit(0);

@extract($_POST);
@extract($_GET);

require_once("../../vars.php");
require_once("../../includes/show.class.php");
require_once("../../includes/sidereel.class.php");

$show = new Show();
$sidereel = new Sidereel();
$thisshow = $show->getShow($showid, true, "en");

$title = $sidereel->sidereelURL($thisshow[$showid]['title'],$thisshow[$showid]['sidereel_url']);
$link = "http://www.sidereel.com/$title/season-$season/episode-$episode"; 

$details = $sidereel->getEpisodeDetails($link);

if (@$details['title'] || @$details['description']){
    $t = $details['title'];
    if (!$t) $t = "Season $season, Episode $episode";
    
    $description = $details['description'];
    if ((substr_count(strtolower($t),"season")==0) && (substr_count(strtolower($t),"episode")==0)){
        $t = "Season $season, Episode $episode - $t";
    }
    
    $ret = array();
    $ret['title']=str_replace('"','',$t);
    $ret['description']=$description;
    print(json_encode($ret));
} else {
    print(json_encode(array("title" => "Season $season, Episode $episode","description" => "No description")));
}

?>