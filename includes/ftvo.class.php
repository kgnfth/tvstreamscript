<?php

class FTVO{
    
    var $curl = null;
    
    function __construct(){
        global $basepath;
        
        $this->curl = new Curl();
        $this->curl->setCookieFile($basepath."/cachefiles/ftvo.cookie.txt");
    }
    
    public function getTitle($title){
        $title = trim($title);
        $title = stripslashes($title);
        $title = strtolower(urldecode($title));
        $title = str_replace(" ","_",$title);
        $title = str_replace("'","",$title);
        $title = str_replace(":","",$title);
        
        return $title;
    }
    
    public function getMovieEmbeds($title){
        $misc = new Misc();
        $searchlink = "http://www.free-tv-video-online.me/search/?q=".urlencode($title)."&md=movies";
        
        $page = $this->curl->get($searchlink);
        
        
        if (substr_count($page,"Your search query didn't match any video.")){
            return array();
        } else {
            $embeds = array();
            $codes = 0;
            $dom = new DOMDocument();
            @$dom->loadHTML($page);
            
            $movie_link = false;
            
            $links = $dom->getElementsByTagName('a');
            for($i=0;$i<$links->length;$i++){
                $href = $links->item($i)->getAttribute("href");
                $bs = $links->item($i)->getElementsByTagName('b');
                $link_title = $links->item($i)->textContent;
                
                if ($bs->length && substr_count($href,"/movies/") && substr_count($href,".html") && preg_replace("/[^a-z0-9]/","",strtolower($link_title)) == preg_replace("/[^a-z0-9]/","",strtolower($title))){
                    $movie_link = "http://www.free-tv-video-online.me".$href;
                    break;
                }
            }
            
            if ($movie_link){
                $page = $this->curl->get($movie_link);
                $dom = new DOMDocument();
                @$dom->loadHTML($page);

                $tds = $dom->getElementsByTagName('td');
                for ($i=0;$i<$tds->length;$i++){
                    if ((($tds->item($i)->getAttribute("class")=="mnllinklist") || (substr_count($tds->item($i)->getAttribute("class"),"mnllinklist "))) && (@$tds->item($i)->getAttribute("align")!='right')){
                        $td = $tds->item($i);
                        $videolink = $td->getElementsByTagName('a')->item(0)->getAttribute("href");
                        
                        if (substr_count($videolink,"/player/")){
                            $tmp = explode("/player/",$videolink);
                            $tmp = explode(".php?id=",$tmp[1]);
                            
                            if (count($tmp)!=2){
                                continue;
                            }
                            
                            $host = $tmp[0];
                            $id = $tmp[1];
                                                        
                                
                            $link = $misc->makeLink($host,$id);
                            if ($link){
                                $embed = $misc->buildEmbed($link);
                                if ($embed){
                                    $embeds[$codes] = array();
                                    $embeds[$codes]['embed'] = $embed;
                                    $embeds[$codes]['link'] = $link;
                                    $embeds[$codes]['language'] = "ENG";
                                    $codes++;
                                }
                            } else {
                                //print($host."<br />");
                            }
                        }
                    }
                    if (count($embeds)>=10){
                        break;
                    }
                }
            }
            
            return $embeds;
        }
    }
    
    public function getEmbeds($title,$showid,$season,$episode){
        $misc = new Misc();
        
        $title = $this->getTitle($title);
        $link = "http://www.free-tv-video-online.info/internet/$title/season_$season.html";
        
        $this->curl->header(true);
        $page = $this->curl->get($link);        
        $code = $this->curl->getHttpCode();

        $page = explode("\r\n\r\n",$page);
        if (count($page)>1){
            $page = $page[1];
        } else {
            $page = $page[0];
        }
        
        if ($code==200){
            
            $searchstring = "season$season"."episode$episode";
            $codes = 1;
            
            $ret = array();
    
            $dom = new DOMDocument();
            @$dom->loadHTML($page);
            $tds = $dom->getElementsByTagName('td');
            for ($i=0;$i<$tds->length;$i++){
                if ((($tds->item($i)->getAttribute("class")=="mnllinklist") || (substr_count($tds->item($i)->getAttribute("class"),"mnllinklist "))) && (@$tds->item($i)->getAttribute("align")!='right')){
                    
                    $td = $tds->item($i);
                    $linktitle = $td->getElementsByTagName('a')->item(0)->getElementsByTagName('div');
                    if ($linktitle->length){
                        $linktitle = $linktitle->item(0)->textContent;
                        $linktitle = strtolower(preg_replace("/[^a-zA-Z0-9]/i","",$linktitle));
                    } else {
                        $linktitle = '';
                    }
                    
                    $tmp = explode($searchstring,$linktitle);
                    
                                        
                    if (count($tmp)==2 && $tmp[1]==""){
                        // we have the link I guess
                
                        $videolink = $td->getElementsByTagName('a')->item(0)->getAttribute("href");
                        
                        if (substr_count($videolink,"/player/")){
                            $tmp = explode("/player/",$videolink);
                            $tmp = explode(".php?id=",$tmp[1]);
                            
                            if (count($tmp)!=2){
                                continue;
                            }
                            
                            $host = $tmp[0];
                            $id = $tmp[1];
                                                        
                                
                            $link = $misc->makeLink($host,$id);
                            if ($link){
                                $embed = $misc->buildEmbed($link);
                                if ($embed){
                                    $ret[$codes] = array();
                                    $ret[$codes]['embed'] = $embed;
                                    $ret[$codes]['link'] = $link;
                                    $ret[$codes]['language'] = "ENG";
                                    $codes++;
                                }
                            }
                        }
                    }
                }
            }
        
            return $ret;
        
        } else {
            return array();
        }
    }
    
    public function getRandomMovies($limit){
        $alphabet = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
        
        $letter = array_rand($alphabet);
        $letter = $alphabet[$letter];
        
        $all_movies = array();
        
        $page = $this->curl->get("http://www.free-tv-video-online.me/movies/browse/".$letter.".html");
        $dom = new DOMDocument();
        @$dom->loadHTML($page);
        
        $tds = $dom->getElementsByTagName('td');
        for($i=0; $i<$tds->length; $i++){
            if ($tds->item($i)->getAttribute("class") == "mnlcategorylist"){
                $links = $tds->item($i)->getElementsByTagName('a');
                if ($links->length){
                    $bs = $links->item(0)->getElementsByTagName('b');
                    if ($bs->length){
                        $movie_title = trim($bs->item(0)->textContent);
                        if (!in_array($movie_title, $all_movies)){
                            $all_movies[] = $movie_title;
                        }
                    }
                }
            }
        }
        
        if (count($all_movies) <= $limit){
            return $all_movies;
        } else {
            $movies = array();
            while($limit > 0){
                $key = array_rand($all_movies);
                if (!in_array($all_movies[$key], $movies)){
                    $movies[] = $all_movies[$key];
                    $limit--;
                }
            }
            return $movies;
        }
    }
    
    public function getRecentMovies(){
        $this->curl->header(true);
        
        $page = $this->curl->get("http://www.free-tv-video-online.me/movies/");
        $dom = new DOMDocument();
        @$dom->loadHTML($page);
                
        $movies = array();
        
        $links = $dom->getElementsByTagName('a');
        for($i=0; $i<$links->length; $i++){
            
            if (substr_count($links->item($i)->getAttribute("href"),"/player/")){
                $divs = $links->item($i)->getElementsByTagName('div');
                if ($divs->length){
                    
                    $movie_title = $divs->item(0)->textContent;

                    if (!in_array($movie_title,$movies)){
                        $movies[] = $movie_title;
                    }
                }
            }
        }
        
        return $movies;
    }
}

?>