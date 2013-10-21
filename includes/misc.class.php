<?php
class Misc{
	function __construct(){
	
	}
	
	public function getDomain($url){
		$url = strtolower($url);
		$url = str_replace("https://","",$url);
		$url = str_replace("http://","",$url);
		$url = str_replace("www.","",$url);
		$tmp = explode("%3f",$url);
		$url = $tmp[0]; 
		$tmp = explode("%2f",$url);
		$url = $tmp[0];  
		$tmp = explode("/",$url);
		$url = $tmp[0];
		$tmp = explode("?",$url);
		$url = $tmp[0];
		return $url;
	}
	
	/* Makes a clean, url friendly representation of a string */	
	function makePerma($str, $replace=array(), $delimiter='-') {
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
	
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
	
	public function getSmartbar($user, $movie, $show, $language, $limit=20){
		$smartbar = array();
		
		$smartbar['movies'] = array();
		$smartbar['shows'] = array();
		
		$half = floor($limit/2);
		$show_limit = $half;
		$movie_limit = $limit-$half;
		
		$show_ids = array();
		$movie_ids = array();
		
		if ($user && isset($user->id)){
			$favorite_shows = $user->getFavoriteShows($user->id,false,$language);
			$favorite_movies = $user->getFavoriteMovies($user->id,false,$language);
			
			if (count($favorite_shows)){
				$counter = 0;
				foreach($favorite_shows as $show_id => $show_data){
					$counter++;
					$show_ids[] = $show_id;
					$smartbar['shows'][$show_id] = $show_data;
					if ($counter>=$show_limit){
						break;	
					}				
				}
			}
			
			if (count($favorite_movies)){
				$counter = 0;
				foreach($favorite_movies as $movie_id => $movie_data){
					$counter++;
					$movie_ids[] = $movie_id;
					$smartbar['movies'][$movie_id] = $movie_data;
					if ($counter>=$movie_limit){
						break;	
					}				
				}
			}
		}
		
		if (count($smartbar['movies'])<$movie_limit){
			$need = $movie_limit-count($smartbar['movies']);
			$random_movies = $movie->getRandomMovies($need, $language, $movie_ids);
			if (count($random_movies)){
				foreach($random_movies as $movie_id => $movie_data){
					$movie_ids[] = $movie_id;
					$smartbar['movies'][$movie_id] = $movie_data;
				}
			}
			
			$need = $movie_limit-count($smartbar['movies']);
			if ($need){
				// show more shows then
				$show_limit+=$need;
			}
		}
		
		if (count($smartbar['shows'])<$show_limit){
			$need = $show_limit-count($smartbar['shows']);
			$featured_shows = $show->getFeatured($need,$language);
			if (count($featured_shows)){
				foreach($featured_shows as $show_id => $show_data){
					$show_ids[] = $show_id;
					$smartbar['shows'][$show_id] = $show_data;
				}
			}
			
			$need = $show_limit-count($smartbar['shows']);
			if ($need){
				$random_shows = $show->getRandomShow($need,$language, $show_ids);
				if (count($random_shows)){
					foreach($random_shows as $show_id => $show_data){
						$smartbar['shows'][$show_id] = $show_data;
					}
				}
			}
		}
		
		return $smartbar;
	}
	
	public function getTranslation($url,$params = array(),$is_coockie_set = false){
	
		if(!$is_coockie_set){
			$ckfile = tempnam ("/tmp", "CURLCOOKIE");
			$ch = curl_init ($url);
			curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec ($ch);
		}
	
		$str = ''; $str_arr= array();
		foreach($params as $key => $value){
			$str_arr[] = urlencode($key)."=".urlencode($value);
		}
		
		if(!empty($str_arr)){
			$str = '?'.implode('&',$str_arr);
		}
	
		$Url = $url.$str;
	
		$ch = curl_init ($Url);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, $ckfile);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	
		$output = curl_exec ($ch);
		return $output;
	}
	
	public function googleTranslate($word,$conversion){
		$word = urlencode($word);
		$conversion = explode("_to_",$conversion);
		if (count($conversion)==2){
			$from = $conversion[0];
			$to = $conversion[1];
			$url = 'http://translate.google.com/translate_a/t?client=t&text='.$word.'&hl=en&sl='.$from.'&tl='.$to.'&multires=1&otf=2&pc=1&ssel=0&tsel=0&sc=1';
						 
			$name_en = $this->getTranslation($url);
			$name_en = explode('"',$name_en);
			return  $name_en[1];
		}
	}
	
	public function getLanguageOptions(){
		global $basepath,$sitename;

		$languages = array();
		if ($dh = opendir($basepath."/language")) {
			while (($file = readdir($dh)) !== false) {
				if ($file!='.' && $file!='..'){
					if (is_dir($basepath."/language/".$file) && file_exists($basepath."/language/".$file."/general.php")){
						include($basepath."/language/".$file."/general.php");
						
						if (isset($lang['code']) && isset($lang['shortname'])){
							$languages[$lang['shortname']] = $lang['code'];
							unset($lang);
							unset($routes);
						}
					}
				}
			}
		}
	
		$options = array();
		foreach($languages as $short_name => $code){
			$options[] = $code;
			$options[] = $code."SUB";
		}
		
		return $options;
	}
	
	public function getEmbedLanguages(){
		global $basepath, $theme, $baseurl, $lang;
		
		if ($dh = opendir($basepath."/language")) {
			while (($file = readdir($dh)) !== false) {
				if ($file!='.' && $file!='..'){
					if (is_dir($basepath."/language/".$file) && file_exists($basepath."/language/".$file."/embed_languages.php")){				
						include($basepath."/language/".$file."/embed_languages.php");
					}
				}
			}
		}
		
		if (isset($embed_languages)){
			return $embed_languages;
		} else {
			return array();
		}
	}
	
	public function getLanguages(){
		global $basepath,$sitename;
		

		$languages = array();
		if (!isset($_SESSION['global_languages'])){
			if ($dh = opendir($basepath."/language")) {
				while (($file = readdir($dh)) !== false) {
					if ($file!='.' && $file!='..'){
						if (is_dir($basepath."/language/".$file) && file_exists($basepath."/language/".$file."/general.php")){
							include($basepath."/language/".$file."/general.php");
							
							if (isset($lang['descriptor']) && isset($lang['shortname'])){
								$languages[$lang['shortname']] = $lang['descriptor'];
								unset($lang);
								unset($routes);
							}
						}
					}
				}
			}
			$_SESSION['global_languages'] = $languages;
		} else {
			$languages = $_SESSION['global_languages'];
		}
		return $languages;
	}
	
	public function aasort($array, $key) {
	    
		$sortable = array();
		foreach($array as $k => $v){
			$sortable[$k] = $v[$key];
		}
		
		asort($sortable);
		$res = array();
		foreach($sortable as $k => $v){
			$res[$k] = $array[$k]; 
		}
		return $res;
	}
	
	public function ago($time,$language){
		$periods = array($language['seconds'], $language['minutes'], $language['hours'], $language['days'], $language['weeks'], $language['months'], $language['years'], $language['decade']);
		$lengths = array("60","60","24","7","4.35","12","10");

		$now = time();

		$difference     = $now - $time;

		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}

		$difference = round($difference);


		return $difference." ".$periods[$j]." ".$language['ago'];
	}
	
	public function makeLink($host,$id){
		
		if ($host == "filebox"){
			return "http://www.filebox.com/".$id;
		}
		
		if ($host == "novamov"){
			return "http://novamov.com/video/".$id;
		}
		
		if ($host == "indavideo"){
			return "http://indavideo.hu/video/".$id;
		}
		
		if ($host == "putlocker"){
			return "http://www.putlocker.com/file/".$id;
		}
		
		if ($host == "sockshare"){
			return "http://www.sockshare.com/file/".$id;
		}
		
		if ($host == "jumbofiles"){
			return "http://jumbofiles.com/".$id;
		}
		
		if ($host == "nosvideo"){
			return "http://nosvideo.com/?v=".$id;
		}
		
		if ($host == "uploadc"){
			return "http://www.uploadc.com/".$id;
		}
		
		if ($host == "divxden"){
			return "http://www.divxden.com/".$id;
		}
		
		if ($host == "vidxden"){
			return "http://www.vidxden.com/".$id;
		}
		
		if ($host == "vreer"){
			return "http://vreer.com/".$id;
		}
		
		if ($host == "youtube"){
			return "http://youtube.com/".$id;
		}
		
		if ($host == "videoweed"){
			return "http://www.videoweed.es/file/".$id;
		}		
			
		if ($host == "nowvideo"){
			return "http://www.nowvideo.eu/video/".$id;
		}		

		if ($host == "movshare"){
			return "http://www.movshare.net/video/".$id;
		}
		
		if ($host == "gorillavid"){
			return "http://gorillavid.in/".$id;
		}
		
		return false;
	}
	
	
	public function buildLink($embed){
		//$embed = strtolower($embed);
		if (substr_count($embed,"rapidplayer.org")){
			preg_match("/link_id=(\d+).*owner_id=(\d+)/i",$embed,$matches);
			if (count($matches)==3){
				return "http://rapidplayer.org/player/".$matches[1]."/".$matches[2];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"filebox.com")) {
			preg_match("/embed\-([A-Za-z0-9]+)\-/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.filebox.com/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"novamov.com")) {
			preg_match("/&v=([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://novamov.com/video/".$matches[1];
			} else {
				preg_match("/\?v=([A-Za-z0-9]+)/i",$embed,$matches);
				if (count($matches)==2){
					return "http://novamov.com/video/".$matches[1];
				} else {
					return false;
				}
			}
		} elseif (substr_count($embed,"indavideo.hu")) {
			preg_match("/\/player\/video\/([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://indavideo.hu/video/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"putlocker.com")) {
			preg_match("/\/embed\/([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.putlocker.com/file/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"sockshare.com")) {
			preg_match("/\/embed\/([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.sockshare.com/file/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"jumbofiles.com")) {
			preg_match("/jumbofiles\.com\/([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://jumbofiles.com/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"nosvideo.com")) {
			preg_match("/\/embed\/([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://nosvideo.com/?v=".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"uploadc.com")) {
			preg_match("/embed\-([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.uploadc.com/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"divxden.com")) {
			preg_match("/embed\-([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.divxden.com/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"vidxden.com")) {
			preg_match("/embed\-([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.vidxden.com/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"vreer.com")) {
			preg_match("/embed\-([A-Za-z0-9]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://vreer.com/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"youtube.com")) {
			preg_match("/\/embed\/([A-Za-z0-9\-_]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://youtube.com/".$matches[1];
			} else {
				return false;
			}
		} elseif (substr_count($embed,"videoweed.es")) {
			preg_match("/\?v=([A-Za-z0-9\-_]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.videoweed.es/file/".$matches[1];
			} else {
				preg_match("/&v=([A-Za-z0-9\-_]+)/i",$embed,$matches);
				if (count($matches)==2){
					return "http://www.videoweed.es/file/".$matches[1];
				} else {
					return false;
				}
			}
		} elseif (substr_count($embed,"nowvideo")) {
			preg_match("/\?v=([A-Za-z0-9\-_]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.nowvideo.eu/video/".$matches[1];
			} else {
				preg_match("/&v=([A-Za-z0-9\-_]+)/i",$embed,$matches);
				if (count($matches)==2){
					return "http://www.nowvideo.eu/video/".$matches[1];
				} else {
					return false;
				}
			}
		} elseif (substr_count($embed,"movshare")) {
			preg_match("/\?v=([A-Za-z0-9\-_]+)/i",$embed,$matches);
			if (count($matches)==2){
				return "http://www.movshare.net/video/".$matches[1];
			} else {
				preg_match("/&v=([A-Za-z0-9\-_]+)/i",$embed,$matches);
				if (count($matches)==2){
					return "http://www.movshare.net/video/".$matches[1];
				} else {
					return false;
				}
			}
		} elseif (substr_count($embed,"Click here to play this video")){
			preg_match("/href\='([^']+)' target\='_blank'/i",$embed,$matches);
			if (count($matches)){
				return $matches[1];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function buildEmbed($link, $width=620, $height=360){
		$embed = false;
		if (substr_count($link,"putlocker")){
			$embed = '<iframe src="'.str_replace("/file/","/embed/",$link).'" width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no"></iframe>';
		} elseif (substr_count($link,"novamov")){
			$video_id = explode("/video/",$link);
			$video_id = $video_id[1];
			$embed = '<iframe style="overflow: hidden; border: 0; width: '.$width.'px; height: '.$height.'px" src="http://embed.novamov.com/embed.php?width='.$width.'&height='.$height.'&v='.$video_id.'&px=1" scrolling="no"></iframe>';
		} elseif (substr_count($link,"sockshare")){
			$embed = '<iframe src="'.str_replace("/file/","/embed/",$link).'" width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no"></iframe>';
		} elseif (substr_count($link,"jumbofiles")){
			$embed = '<iframe src="/player_jumbo.php?url='.$link.'" width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no"></iframe>';
		} elseif (substr_count($link,"filebox")){
			$video_id = explode("/",$link);
			$video_id = $video_id[count($video_id)-1];
			$embed = '<IFRAME SRC="http://www.filebox.com/embed-'.$video_id.'-'.$width.'x'.$height.'.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$width.' HEIGHT='.$height.'></IFRAME>';
		} elseif (substr_count($link,"nosvideo")){
			$video_id = explode("/",$link);
			$video_id = $video_id[count($video_id)-1];
			$embed = '<IFRAME SRC="http://nosvideo.com/embed/'.$video_id.'/'.$width.'x'.$height.'" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$width.' HEIGHT='.$height.'></IFRAME>';							
		} elseif (substr_count($link,"nowvideo")){
			$video_id = explode("/video/",$link);
			if (count($video_id)>1){
				$video_id = explode("/",$video_id[1]);
				$video_id = $video_id[0];
				$embed = '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" src="http://embed.nowvideo.eu/embed.php?v='.$video_id.'&amp;width='.$width.'&amp;height='.$height.'" scrolling="no"></iframe>';
			}
		} elseif (substr_count($link,"videoweed")){
			$video_id = explode("/file/",$link);
			if (count($video_id)>1){
				$video_id = explode("/",$video_id[1]);
				$video_id = $video_id[0];
				$embed = '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" src="http://embed.videoweed.es/embed.php?v='.$video_id.'&width='.$width.'&height='.$height.'" scrolling="no"></iframe>';
			}
		} elseif (substr_count($link,"movshare")){
			$video_id = explode("/video/",$link);
			if (count($video_id)>1){
				$video_id = explode("/",$video_id[1]);
				$video_id = $video_id[0];
				
				$window_height = $height-30;
				$embed = '<iframe style="overflow: hidden; border: 0; width: '.$width.'px; height: '.$height.'px" src="http://embed.movshare.net/embed.php?v='.$video_id.'&width='.$width.'&height='.$window_height.'&color=black" scrolling="no"></iframe>';
			}
		} elseif (substr_count($link,"divxstage")){
			$video_id = explode("/video/",$link);
			if (count($video_id)>1){
				$video_id = explode("/",$video_id[1]);
				$video_id = $video_id[0];
				$embed = '<iframe style="overflow: hidden; border: 0; width: '.$width.'px; height: '.$height.'px" src="http://embed.divxstage.eu/embed.php?v='.$video_id.'&width='.$width.'&height='.$height.'" scrolling="no"></iframe>';
			}
		} elseif (substr_count($link,"xvidstage")){
			$video_id = explode("/",$link);
			$video_id = $video_id[count($video_id)-1];
			$embed = '<IFRAME SRC="http://xvidstage.com/embed-'.$video_id.'.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$width.' HEIGHT='.$height.'></IFRAME>';
		} elseif (substr_count($link,"miloyski")){
			$video_id = explode("/",$link);
			if (isset($video_id[count($video_id)-2])){
				$video_id = $video_id[count($video_id)-2];
				$embed = '<iframe src="http://www.miloyski.com/embed/'.$video_id.'/'.$width.'/'.$height.'" width="'.$width.'" height="'.$height.'" frameborder="0" marginwidth="0" marginheight="0" scrolling="no">your browser does not support frames</iframe>';
			}		
		} elseif (substr_count($link,"veevr")){
			$video_id = explode("/videos/",$link);
			if (count($video_id)>1){
				$video_id = explode("/",$video_id[1]);
				$video_id = $video_id[0];
				$embed = '<iframe src="http://veevr.com/embed/'.$video_id.'" width="'.$width.'" height="'.$height.'" scrolling="no" frameborder="0"></iframe>';
			}
		} elseif (substr_count($link,"uploadc")){
			$video_id = explode("/",$link);
			if (isset($video_id[count($video_id)-2])){
				$video_id = $video_id[count($video_id)-2];
				$embed = '<IFRAME SRC="http://www.uploadc.com/embed-'.$video_id.'.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$width.' HEIGHT='.$height.'></IFRAME>';
			}
		} elseif (substr_count($link,"zalaa")){
			$video_id = explode("/",$link);
			if (isset($video_id[count($video_id)-1])){
				$video_id = $video_id[count($video_id)-1];
				$embed = '<IFRAME SRC="http://www.zalaa.com/embed-'.$video_id.'.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$width.' HEIGHT='.$height.'></IFRAME>';
			}
		} elseif (substr_count($link,"gorillavid")){
			$video_id = explode("/",$link);
			if (isset($video_id[count($video_id)-1])){
				$video_id = $video_id[count($video_id)-1];
				$embed = '<IFRAME SRC="http://gorillavid.in/embed-cnx-'.$video_id.'-'.$width.'x'.$height.'.html" FRAMEBORDER="0" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="NO" WIDTH="'.$width.'" HEIGHT="'.$height.'"></IFRAME>';
			}
		} elseif (substr_count($link,"rapidplayer")){
			$video_id = explode("/player/",$link);
			if (isset($video_id[1])){
				$video_id = preg_replace("/[^0-9]/","",$video_id[1]);
				if ($video_id){
					$hash = md5($video_id."|1|embed_token");
					$embed = '<iframe src="http://rapidplayer.org/embed.php?link_id='.$video_id.'&width=620&height=360&owner_id=1&token='.$hash.'" style="border: 0px;" width="620" height="360" frameborder="0" scrolling="no"></iframe>';
				}
			}
		}	
		
		
		// general image link
		if (!$embed){
			$embed = "<div style='background-color:#000000; width: ".$width."px; height: ".$height."px;text-align:center;cursor:pointer;' onclick='window.open(\"$link\");'>";
			$embed.= "<p style='text-align:center; padding-top: 70px;'>";
			$embed.= "<a href='$link' target='_blank' style='font-size:24px; color: #ffffff !important'>Click here to play this video</a>";
			$embed.= "</p>";
			$embed.= "</div>";
		}
		
		return $embed;
	}
	
	public function getWeight($link){
		$weight = 0;
		
		if (substr_count($link,"novamov")){
			$weight = 2;
		} elseif (substr_count($link,"sockshare")){
			$weight = 1;
		} elseif (substr_count($link,"uploadc")){
			$weight = 1;
		} elseif (substr_count($link,"ovfile")){
			$weight = 1;
		} elseif (substr_count($link,"rapidshare")){
			$weight = 4;
		} elseif (substr_count($link,"rapidplayer")){
			$weight = 4;
		} elseif (substr_count($link,"streamcloud")){
			$weight = 3;
		} elseif (substr_count($link,"jumbofiles")){
			$weight = 2;
		} elseif (substr_count($link,"filebox")){
			$weight = 2;
		} elseif (substr_count($link,"nosvideo")){
			$weight = 1;					
		} elseif (substr_count($link,"180upload")){
			$weight = 1;
		} elseif (substr_count($link,"xvidstream")){
			$weight = 1;
		} elseif (substr_count($link,"vreer")){
			$weight = 1;
		} elseif (substr_count($link,"uploadvideos")){
			$weight = 1;
		}
		
		return $weight;
	}
	
	public function getBasicPagination($total_pages, $page, $limit, $targetpage, $current_class = 'current') {
		$adjacents = 3;
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//previous page is page - 1
		$next = $page + 1;							//next page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1

		$pagination = "";
		if($lastpage > 1){
				
			//previous button
			if ($page > 1) 
				$pagination.= " <li><a href=\"{$targetpage}{$prev}\">&laquo;</a></li> ";
			else
				$pagination.= " ";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2)){	
				for ($counter = 1; $counter <= $lastpage; $counter++)	{
					if ($counter == $page)
						$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
					else
						$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";					
					if ($counter < $lastpage)
						$pagination.= " ";
				}
			} elseif($lastpage > 5 + ($adjacents * 2)){
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2)){
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						else
							$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						if ($counter <  3 + ($adjacents * 2))
							$pagination.= " ";
					}
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lpm1}\">$lpm1</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lastpage}\">$lastpage</a></li> ";
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
					$pagination.= " <li><a href=\"{$targetpage}1\">1</a ></li> ";
					$pagination.= " <li><a href=\"{$targetpage}2\">2</a></li> ";
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)	{
						if ($counter == $page)
							$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						else
							$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						if ($counter < $page + $adjacents)
							$pagination.= " ";
					}
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lpm1}\">$lpm1</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lastpage}\">$lastpage</a></li> ";
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= " <li><a href=\"{$targetpage}1\">1</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}2\">2</a></li> ";
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
						if ($counter == $page)
							$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						else
							$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						if ($counter < $lastpage)
							$pagination.= " ";
					}
				}
			}
			
			//next button
			if ($page < $counter - 1) 
				$pagination.= " <li><a href=\"{$targetpage}{$next}\">&raquo;</a></li> ";
			else
				$pagination.= " ";
		}
		if (empty($pagination))
			$pagination = " <li><a href=\"{$targetpage}1\">1</a></li> ";
		return $pagination;
	}
	

	public function getAdminPagination($total_pages, $page, $limit, $targetpage, $current_class = 'current') {
		$adjacents = 2;
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//previous page is page - 1
		$next = $page + 1;							//next page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1

		$pagination = "";
		if($lastpage > 1){
				
			//previous button
			if ($page > 1) 
				$pagination.= " <li><a href=\"{$targetpage}{$prev}\">&laquo;</a></li> ";
			else
				$pagination.= " ";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2)){	
				for ($counter = 1; $counter <= $lastpage; $counter++)	{
					if ($counter == $page)
						$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
					else
						$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";					
					if ($counter < $lastpage)
						$pagination.= " ";
				}
			} elseif($lastpage > 5 + ($adjacents * 2)){
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2)){
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						else
							$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						if ($counter <  3 + ($adjacents * 2))
							$pagination.= " ";
					}
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lpm1}\">$lpm1</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lastpage}\">$lastpage</a></li> ";
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
					$pagination.= " <li><a href=\"{$targetpage}1\">1</a ></li> ";
					$pagination.= " <li><a href=\"{$targetpage}2\">2</a></li> ";
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)	{
						if ($counter == $page)
							$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						else
							$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						if ($counter < $page + $adjacents)
							$pagination.= " ";
					}
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lpm1}\">$lpm1</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}{$lastpage}\">$lastpage</a></li> ";
				}
				//close to end; only hide early pages
				else
				{
					$pagination.= " <li><a href=\"{$targetpage}1\">1</a></li> ";
					$pagination.= " <li><a href=\"{$targetpage}2\">2</a></li> ";
					$pagination.= " <li><a href=\"javascript:void(0);\">...</a></li> ";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
						if ($counter == $page)
							$pagination.= " <li class='$current_class'><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						else
							$pagination.= " <li><a href=\"{$targetpage}{$counter}\">$counter</a></li> ";
						if ($counter < $lastpage)
							$pagination.= " ";
					}
				}
			}
			
			//next button
			if ($page < $counter - 1) 
				$pagination.= " <li><a href=\"{$targetpage}{$next}\">&raquo;</a></li> ";
			else
				$pagination.= " ";
		}
		if (empty($pagination))
			$pagination = " <li><a href=\"{$targetpage}1\">1</a></li> ";
		return $pagination;
	}
	
	public function deleteLink($link_id){
		$link_id = mysql_real_escape_string($link_id);
		
		$del = mysql_query("DELETE FROM submitted_links WHERE id='$link_id'") or die(mysql_error());
	}
	
	public function approveLink($link_id){
		$link_id = mysql_real_escape_string($link_id);
		
		$upd = mysql_query("UPDATE submitted_links SET status='1' WHERE id='$link_id'") or die(mysql_error());
	}
	
	public function saveLink($params){
		$embed_language = mysql_real_escape_string($params['embed_language']);
		$user_id = $_SESSION['loggeduser_id'];
		$date_submitted = date("Y-m-d H:i:s");
		
		$imdb_id = explode("/title/",$params['imdb_url']);
		if (count($imdb_id)==2){
			$imdb_id = explode("/",$imdb_id[1]);
			$imdb_id = mysql_real_escape_string($imdb_id[0]);
			
			$link = mysql_real_escape_string(strip_tags($params['video_url']));
			if (isset($params['season'])){
				$season = mysql_real_escape_string($params['season']);
			} else {
				$season = 0;
			}
			
			if (isset($params['episode'])){
				$episode = mysql_real_escape_string($params['episode']);
			} else {
				$episode = 0;
			}
			
			$type = mysql_real_escape_string($params['type']);
			
			$check = mysql_query("SELECT * FROM submitted_links WHERE link='$link'") or die(mysql_error());
			if (mysql_num_rows($check)==0){
				$ins = mysql_query("INSERT INTO submitted_links(`user_id`,`type`,`imdb_id`,`season`,`episode`,`link`,`language`,`date_submitted`,`status`)
														VALUES('$user_id','$type','$imdb_id','$season','$episode','$link','$embed_language','$date_submitted','0')") or die(mysql_error());
			}
		}
	}
	
	public function validateLink($params){
		global $lang,$embed_languages;
		
		$errors = array();
		
		if (!isset($params['type']) || !in_array($params['type'],array(1,2))){
			$errors[1] = $lang['submit_error_select_type'];
		}
		
		if (!isset($params['imdb_url']) || !$params['imdb_url']){
			$errors[2] = $lang['submit_error_no_imdb'];
		} elseif (substr_count($params['imdb_url'],"imdb.com/title/")==0){
			$errors[2] = $lang['submit_error_invalid_imdb'];
		}
		
		if (!isset($params['video_url']) || !$params['video_url']){
			$errors[3] = $lang['submit_error_no_video_url'];
		}
		
		/* TV show */
		if (isset($params['type']) && $params['type'] == 1){
			if (!isset($params['season']) || !$params['season'] || !is_numeric($params['season'])){
				$errors[4] = $lang['submit_error_invalid_season'];
			}
			
			if (!isset($params['episode']) || !$params['episode'] || !is_numeric($params['episode'])){
				$errors[5] = $lang['submit_error_invalid_episode'];
			}
		}
		
		
		if (!isset($params['embed_language']) || !isset($embed_languages[$params['embed_language']])){
			$errors[6] = $lang['submit_error_no_embed_lanugage'];
		}
		
		return $errors;
	}

}
?>
