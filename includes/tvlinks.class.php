<?php

class TVlinks{
	
	public $curl = null;
	
	function __construct($curl){
		$this->curl = $curl;
	}
	
	public function login($user,$pass){
		$data = array();
		$data['action'] = 7;
		$data['sri'] = str_replace(" ","",microtime());
		$data['username'] = $user;
		$data['passw'] = $pass;
		$page = $this->curl->post("http://www.tv-links.eu/ajax.php",$data);
		
		if (substr_count($page,"Invalid username or password")){
			return false;
		} else {
			return true;
		}
	}
	
	public function submit($show,$season,$episode,$url){
		$show_url = $this->getShowUrl($show);
		if ($show_url){
			$page = $this->curl->get($show_url."season_".$season."/episode_".$episode."/");
			
			$dom = new DOMDocument();
			@$dom->loadHTML($page);
			$links = $dom->getElementsByTagName('a');
			$onclick = false;
			for($i=0;$i<$links->length;$i++){
				$text = $links->item($i)->textContent;
				if (substr_count($text,"add a link for this episode")){
					$onclick = $links->item($i)->getAttribute("onclick");
					break;
				}
			}
			
			if ($onclick){
				preg_match_all("/'([^']+)'/i",$onclick,$matches);
				if (isset($matches[1])){
					$perma = str_replace("http://www.tv-links.eu/","",$show_url);
					$perma = explode("/",$perma);
					$perma = $perma[1];
					
					$show_id = str_replace("/","",$matches[1][1]);
					$show_id = str_replace("_","",$show_id);
					$show_name = explode($show_id,$perma);
					$show_name = $show_name[0];
					$show_name = str_replace("-"," ",$show_name);
					
					$episode_name = $matches[1][2];
					$show_date = $matches[1][5];
					$show_year = date("Y",strtotime($show_date));
					
					$data = array();
					$data['action'] = "s3";
					$data['sri'] = str_replace(" ","",microtime());
					$data['show_id'] = $show_id;
					$data['show_name'] = $show_name;
					$data['show_year'] = $show_year;
					$data['show_cat'] = "tv-shows";
					$data['ep_name'] = $episode_name;
					$data['s_nr'] = $season;
					$data['e_nr'] = $episode;
					$data['link_1'] = $url;
					$data['link_2'] = '';
					$data['link_3'] = '';
					$data['link_4'] = '';
					$data['link_5'] = '';
					$data['link_6'] = '';
					$data['link_7'] = '';
					$data['link_8'] = '';
					$data['link_9'] = '';
					$data['link_10'] = '';
					
					$page = $this->curl->post("http://www.tv-links.eu/ajax.php",$data);
					
					return $show_url."season_".$season."/episode_".$episode."/";
					
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getShowUrl($title){
		$data = array();
		$data['action'] = 5;
		$data['o_item0'] = $title;
		$data['o_item1'] = "search_atc";
		$data['o_item2'] = "search_atc_ul";
		$data['sri'] = "0.5801835701696413";
		
		$page = $this->curl->post("http://www.tv-links.eu/ajax.php",$data);
		$dom = new DOMDocument();
		@$dom->loadXML($page);
		
		$content = $dom->getElementsByTagName('content');
		if ($content->length){
			$content = html_entity_decode($content->item(0)->nodeValue);
			$dom = new DOMDocument();
			@$dom->loadHTML($content);
			
			$links = $dom->getElementsByTagName('a');
			if ($links->length){
				return "http://www.tv-links.eu".$links->item(0)->getAttribute("href");
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function getEmbeds($show,$season,$episode,$limit=10){
		$embeds = array();
		
		$link = $this->getShowUrl($show);
		if ($link){
			$page = $this->curl->get($link."season_".$season."/episode_".$episode."/video-results/");
			$dom = new DOMDocument();
			@$dom->loadHTML($page);
			
			$links = $dom->getElementsByTagName('a');
			for($i=0;$i<$links->length;$i++){
				if ($links->item($i)->getAttribute("class")=="list outer cfix"){
					$onclick = $links->item($i)->getAttribute("onclick");
					if (substr_count($onclick,"frameLink")){
						$link_id = explode("frameLink('",$onclick);
						$link_id = explode("'",$link_id[1]);
						$link_id = $link_id[0];
						
						$spans = $links->item($i)->getElementsByTagName('span');
						
						$host = false;
						for($j=0;$j<$spans->length;$j++){
							if ($spans->item($j)->getAttribute("class")=="bold"){
								$host = $spans->item($j)->textContent;
								break;
							}
						}
						
						if ($host){
							$this->curl->manual_follow = true;
							
							$page = $this->curl->get("http://www.tv-links.eu/gateway.php?data=".$link_id);
							$info = $this->curl->getInfo();
							
							print_r($info); exit();
							$embeds[] = $info['url'];
							
							$this->curl->manual_follow = false;
							
							if (count($embeds)>=$limit){
								return $embeds;
							}
						}
					}
				}
			}
		}
		
		return $embeds;
	}
	
}

?>