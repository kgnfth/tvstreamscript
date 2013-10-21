<?php 

class IMDB{
    
    public $title;
    public $curl;
    public $year;
    
    function __construct($curl = null,$title = null,$year = 0){
        $this->title = $title;
        
        if ($curl){
            $this->curl = $curl;
        } else {
            if (!class_exists("Curl")){
                require_once("curl.php");
            }
            
            $this->curl = new Curl();
        }
        $this->curl->header(true);
        $this->year = $year;
    }
    
    public function getShowDetails($title = null){
        if (!$title){
            $title = $this->title;
        }
        
        $page = $this->curl->get("http://www.imdb.com/find?q=".urlencode($title)."&s=all");
        $dom = new DOMDocument();
        @$dom->loadHTML($page);
        $div = $dom->getElementById("main");
        
        $imdb_id = false;
        
        if ($div){
            $trs = $dom->getElementsByTagName('tr');
            for($i=0;$i<$trs->length;$i++){
                if (substr_count(strtolower($trs->item($i)->textContent),"tv series")){
                    $links = $trs->item($i)->getElementsByTagName('a');
                    for($j=0;$j<$links->length;$j++){
                        $href = $links->item($j)->getAttribute("href");
                        $text = $links->item($j)->textContent;
                        
                        if (substr_count($href,"/title/") && preg_replace("/[^a-z0-9]/","",strtolower($text))==preg_replace("/[^a-z0-9]/","",strtolower($title))){
                            $imdb_id = explode("/title/",$href);
                            $imdb_id = explode("/",$imdb_id[1]);
                            $imdb_id = $imdb_id[0];
                            break;
                        }
                    }
                    
                    if ($imdb_id){
                        break;
                    }
                }
            }
        }
        
        if ($imdb_id){
            $data = $this->getById($imdb_id);
            $data['imdb_id'] = $imdb_id;
            return $data;
        } else {
            return false;
        }
    }
    
    public function getById($id){
		global $basepath;
		
        $page = $this->curl->get("http://www.imdb.com/title/$id");
		
        return $this->processPage($page);
    }
    
    public function getAvailableEpisodes($title,$curl,$year=null){
        $title = trim(strtolower($title));
        $url = "http://imdbapi.poromenos.org/js/?name=".urlencode($title);
        
        if ($year){
            $url.="&year=".$year;
        }

        $page = $curl->get($url);
        
        if ($page=='null'){
            return 0;
        } else {
            $res = json_decode($page);
            if (isset($res->shows)){
                $thistitle = "";
                $aggregated = null;
                foreach($res->shows as $key => $val){            
                    if (isset($val->year)){
                        $res = $this->getAvailableEpisodes($title,$curl,$val->year);
                        if (count($res)){
                            if (!$thistitle){
                                foreach($res as $key => $val){
                                    $thistitle = $key;
                                    break;
                                }
                                $aggregated = $res;
                            } else {
                                foreach($res->$thistitle->episodes as $key => $val){
                                    $aggregated->$thistitle->episodes[]=$val;
                                }
                            }    
                            
                        }
                    } 
                }
                $res = $aggregated;
            }
            
            return $res;
        }
    }
    
    public function processPage($page){
        $res = array();
        $res['title'] = '';
        $res['rating'] = 0;
        $res['image'] = 'http://tvss.pw/api/img/nopicture.jpg';
        $res['stars'] = array();
        $res['genres'] = array();
        $res['creators'] = array();
        $res['trailer'] = '';
        $res['director'] = '';
        $res['summary'] = 'No description available';
        $res['year'] = 0;
        $res['year_started'] = 0;
        $res['year_ended'] = 0;
                
        preg_match("/\<a href=\"\/year\/(\d{4})\/\"\>/i",$page,$matches);
        if (count($matches)==2){
            $res['year2'] = $matches[1];
        }
                
        preg_match("/IMDb users have given an average vote of (\d+\.\d+)\/10/i",$page,$matches);
        if (count($matches)){
            $res['rating'] = $matches[1];
        }
        
        $dom = new DOMDocument();
        @$dom->loadHTML($page);
        
        $spans = $dom->getElementsByTagName('span');
        for($i=0; $i<$spans->length; $i++){
            $itemprop = $spans->item($i)->getAttribute("itemprop");
            if ($itemprop == "ratingValue" && !$res['rating']){
                if ($spans->item($i)->textContent!='-'){
                    $res['rating'] = $spans->item($i)->textContent;
                }
            }
        }
                
        $divs = $dom->getElementsByTagName('div');
        for($i=0; $i<$divs->length; $i++){
            $itemprop = $divs->item($i)->getAttribute("itemprop");
            if ($itemprop == "director"){
                $links = $divs->item($i)->getElementsByTagName('span');
                //if ($links->length && substr_count($links->item(0)->getAttribute("href"),"/name/")){
                    $res['director'] = $links->item(0)->textContent;
                //}
            }
            
        
            if ($itemprop == "creator"){
                $links = $divs->item($i)->getElementsByTagName('a');
                //if ($links->length && substr_count($links->item(0)->getAttribute("href"),"/name/")){
                    $res['creators'][] = $links->item(0)->textContent;
                }
            //}
        }
        
        $links = $dom->getElementsByTagName('td');
        for($i=0;$i<$links->length;$i++){
            $href = $links->item($i)->getAttribute("itemprop");
            if (substr_count($href,"actor")){
                if (!in_array(trim($links->item($i)->textContent),$res['stars'])){
                    $res['stars'][] = trim($links->item($i)->textContent);
                }
            }
        }
        $h1s = $dom->getElementsByTagName('span');
        for($i=0;$i<$h1s->length;$i++){
            if ($h1s->item($i)->getAttribute("itemprop")=="name"){
                $text = $h1s->item($i)->textContent;
    /*            
                preg_match("/\((\d{4})([^\)]*)(\d{4})?\)/i",$text,$matches);
                if (count($matches)){
                    $res['year2'] = $matches[1];
                    $res['year_started2'] = $matches[1];
                    if (isset($matches[3])){
                        $res['year_ended2'] = $matches[3];
                    }
                }
                */
                $title = $h1s->item($i)->textContent;
                $spans = $h1s->item($i)->getElementsByTagName('span');
                for($j=0;$j<$spans->length;$j++){
                    $title = str_replace($spans->item($j)->textContent,"",$title);
                }
                $res['title'] = trim($title);
                break;
            }
        }
        
        $h2s =$dom->getElementsByTagName('h1');
        for($i=0;$i<$h2s->length;$i++){
            if ($h2s->item($i)->getAttribute("class")=="header"){
                $release = $h2s->item(0)->nodeValue;
                $placeholders = array($title, 'Video ', 'TV', 'I', 'V', 'X', ' ', '(', ')', '-');
                $year_raw = str_replace($placeholders, "", $release);
                $year = substr($year_raw, 1,4);
                $show_year = substr_replace($year, "", 0,7);
                $res['year'] = $year;
                $res['year_started'] = $year;
    
            }
        }
        
        $ps = $dom->getElementsByTagName('p');
        for($i=0;$i<$ps->length;$i++){
            $itemprop = $ps->item($i)->getAttribute("itemprop");
            if ($itemprop=="description"){
                $tmp = explode("See full summary",$ps->item($i)->textContent);
                $tmp = explode("See full synopsis",$tmp[0]);
                $res['summary'] = trim($tmp[0]);
                break;
            }
        }
        
        $tds = $dom->getElementsByTagName('td');
        for($i=0;$i<$tds->length;$i++){
            if ($tds->item($i)->getAttribute("id")=="img_primary"){
                $imgs = $tds->item($i)->getElementsByTagName('img');
                if ($imgs->length){
                    $res['image'] = $imgs->item(0)->getAttribute("src");
                }
            }
        }
        
        $links = $dom->getElementsByTagName('a');
        for($i=0;$i<$links->length;$i++){
            $href = $links->item($i)->getAttribute("href");
            if (substr_count($href,"/genre/")){
                if (!in_array(trim($links->item($i)->textContent),$res['genres'])){
                    $res['genres'][] = trim($links->item($i)->textContent);
                }
            }
        }

//        if ($res['year_started'] > 2100){
//            $res['year_started'] = 0;
//        } 
        
        
        return $res;
    }
    
    public function getDetails($title = null){
        
        $res = array();
        
        if ($title){
            $this->title = $title;
        }
        
        $tmp = strtolower($this->title);
        $url = "http://www.imdb.com/find?s=all&q=".str_replace(" ","+",$this->title);
        
        $page = $this->curl->get($url);
        
        $info = $this->curl->getInfo();
        
        
        
        if (substr_count($info['url'],"/title/")){
            
            preg_match("/\/title\/([a-zA-Z0-9]+)\//i",$info['url'],$matches);
            
            if (count($matches)){
                $res['imdb'] = "http://www.imdb.com/title/".$matches[1]."/";
                $data = $this->processPage($page);
                $tmp = $res['imdb'];
                $res = $data;
                $res['imdb'] = $tmp;
            }
            
        } else {
            
            $dom = new DOMDocument();
            @$dom->loadHTML($page);
            
            $tds = $dom->getElementsByTagName('td');
            for($i=0;$i<$tds->length;$i++){
                $tdtext = $tds->item($i)->textContent;
                
                if ($tds->item($i)->getAttribute("class") == "result_text" && (!$this->year || substr_count($tdtext,$this->year))){
                    
                    $links = $tds->item($i)->getElementsByTagName('a');
                    for($j=0;$j<$links->length;$j++){
                        
                        $href = $links->item($j)->getAttribute("href");
                        $text1 = preg_replace("/[^a-zA-Z0-9]/i","",strtolower($links->item($j)->textContent));
                        $text2 = preg_replace("/[^a-zA-Z0-9]/i","",strtolower($this->title));
                        
                        if ($text1==$text2){
                            if ($href[0]=="/"){
                                $href = "http://www.imdb.com".$href;
                            }
                            $res['imdb'] = $href;
                            break;                        
                        }
                    }
                    if (isset($res['imdb'])){
                        break;
                    }
                }
            }
            
            
            if (isset($res['imdb'])){
                $page = $this->curl->get($res['imdb']);
                $data = $this->processPage($page);
                $data['imdb'] = $res['imdb'];
                $res = $data;
            }
        }
        
        if (isset($res['imdb']) && !isset($res['imdb_id'])){
            $imdb_id = explode("/title/",$res['imdb']);
            if (count($imdb_id)==2){
                $imdb_id = explode("/",$imdb_id[1]);
                $res['imdb_id'] = $imdb_id[0]; 
            }
        }
        
        return $res;
        
    }
    
}

?>