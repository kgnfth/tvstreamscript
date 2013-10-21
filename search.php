<?php

if ($logged){
    $user = new User();
    if (!isset($_SESSION['loggeduser_seen_episodes'])){
        $seen_episodes = $user->getSeenEpisodes(null,true);
    } else {
        $seen_episodes = $_SESSION['loggeduser_seen_episodes'];
    }
    
    if (!isset($_SESSION['loggeduser_seen_movies'])){
        $seen_movies = $user->getSeenMovies(null,true);
    } else {
        $seen_movies = $_SESSION['loggeduser_seen_movies'];
    }

} else {
    $seen_episodes = array();
    $seen_movies = array();
}

if (isset($query) && $query){
    $mode = "query";
    $seodata['searchterm'] = $query;
    $searchterm = $query;
} elseif (isset($director) && $director){
    $mode = "director";
    $seodata['searchterm'] = "Director: $director";
    $searchterm = $director;
} elseif (isset($star) && $star){
    $mode = "star";
    $seodata['searchterm'] = "Starring: $star";
    $searchterm = $star;
} elseif (isset($year) && $year){
    $mode = "year";
    $seodata['searchterm'] = "Released in $year";
    $searchterm = $year;    
} else {
    header("Location: $baseurl");
    die();
}

if ($mode == "query"){
    $searchepisodes = $show->search($query,$language);
} elseif ($mode == "director"){
	$searchshows = $show->searchByMeta(array("creator" => $director),$language);
} elseif ($mode == "star"){
	$searchshows = $show->searchByMeta(array("star" => $star),$language);
} elseif ($mode == "year"){
	$searchshows = $show->searchByMeta(array("year_started" => $year),$language);
}

if (isset($searchepisodes) && count($searchepisodes)){
    foreach($searchepisodes as $epid => $ep){                    
        if (in_array($epid,$seen_episodes)){
            $searchepisodes[$epid]['seen'] = 1;
        } else {
            $searchepisodes[$epid]['seen'] = 0;
        }
    }
} else {
    $searchepisodes = '';
}

if (!isset($searchshows) || !count($searchshows)){
	$searchshows = '';
}
    
$smarty->assign("searchepisodes",$searchepisodes);
$smarty->assign("searchshows",$searchshows);

if ($mode == "query"){
    $searchmovies = $movie->search($query,$language);
} elseif ($mode == "director"){
    $searchmovies = $movie->searchByMeta(array("director" => $director),$language);
} elseif ($mode == "star"){
    $searchmovies = $movie->searchByMeta(array("star" => $star),$language);
} elseif ($mode == "year"){
    $searchmovies = $movie->searchByMeta(array("year" => $year),$language);
} 

if (count($searchmovies)){
    foreach($searchmovies as $key => $val){
        @extract($val);
        $description = nl2br(stripslashes($description));

        
        $searchmovies[$key]['description']=$description;
        $searchmovies[$key]['title']=stripslashes(stripslashes($searchmovies[$key]['title']));
        
        if (in_array($key,$seen_movies)){
            $searchmovies[$key]['seen'] = 1;
        } else {
            $searchmovies[$key]['seen'] = 0;
        }
        
    }
} else {
    $searchmovies = '';
} 

$smarty->assign("searchmovies",$searchmovies);
$smarty->assign("searchmode",$mode);
$smarty->assign("searchterm",$searchterm);
?>