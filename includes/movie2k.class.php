<?php

class Movie2k{
	
	public $curl = null;
	public $misc = null;
	public $domain = "http://www.movie4k.to/";
	
	function __construct(){
		$this->curl = new Curl();
		$this->misc = new Misc();
	}
	
	public function getEmbeds($show,$season,$episode){
		$show_url = $this->getShowUrl($show);
		
		$res = array();
		
		$pages = array();
		$counter = 1;
		if ($show_url){
			$season_url = str_replace("-season-","-episode-$season-",$show_url);
			$page = $this->curl->get($season_url);
			$dom = new DOMDocument();
			@$dom->loadHTML($page);
			
			$tables = $dom->getElementsByTagName('table');
			for($i=0;$i<$tables->length;$i++){
				if ($tables->item($i)->getAttribute("id")=="tablemoviesindex"){
					$rows = $tables->item($i)->getElementsByTagName('tr');
					for($j=0;$j<$rows->length;$j++){
						$tds = $rows->item($j)->getElementsByTagName('td');
						if ($tds->length==5){
							$compare = preg_replace("/[^a-z0-9]/","",strtolower($tds->item(0)->textContent));
							$img = $tds->item(4)->getElementsByTagName('img');
							if (!$img->length){
								continue;
							}
							
							if (strpos($compare,"episode$episode") == strlen($compare)-strlen("episode$episode") && substr_count($img->item(0)->getAttribute("src"),"us_flag")){
								
								$type = strtolower(trim($tds->item(1)->textContent));
								$type = explode("watch on",$type);
								$type = trim($type[1]);
								$link = $tds->item(0)->getElementsByTagName('a');
								if (!$link->length){
									continue;
								}
								
								$link = $this->domain.$link->item(0)->getAttribute("href");
								$pages[] = array("link" => $link, "type" => $type);
							}
						}
					}				
				}
			}
		}
		
		
		
		if (count($pages)){
			foreach($pages as $key => $val){
				if ($val['type']!="stream2k"){
					$data = $this->getLink($val['link']);
					if ($data){
						$embed = array();
						$embed['language'] = "ENG";
						
						if (isset($data['link'])){
							$embed['embed'] = $this->misc->buildEmbed($data['link'],620,360);
							if (!$embed['embed']){
								continue;
							}
							$embed['link'] = $data['link'];
						} elseif (isset($data['embed'])){
							
							$link = $this->misc->buildLink($data['embed']);
							if (!$link){
								continue;
							}
							$embed['embed'] = $data['embed'];
							$embed['link'] = $link;
						}
						
						$res[$counter] = $embed;
						$counter++;
					} 
				}
			}
		}
			
		return $res;
	}
	
	public function getLink($url){
		$page = $this->curl->get($url);
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		
		$res = false;
		$links = $dom->getElementsByTagName('a');
		for($i=0;$i<$links->length;$i++){
			$img = $links->item($i)->getElementsByTagName('img');
			if ($img->length){
				$src = $img->item(0)->getAttribute("src");
				if (substr_count($src,"click_link.jpg")){
					return array("link" => $links->item($i)->getAttribute("href"));
					break;
				}
			}
		}
		
		if (!$res){
			// finding embed then
			$divs = $dom->getElementsByTagName('div');
			for($i=0;$i<$divs->length;$i++){
				if ($divs->item($i)->getAttribute("id")=="emptydiv"){
					$html = $this->getInnerHTML($divs->item($i));
					preg_match("/\<iframe.*\<\/iframe\>/i",$html,$matches);
					if (count($matches)){
						return array("embed" => $matches[0]);
					}
				}
			}
		}
		
		return $res;
	}
	
	public function getInnerHtml( $node ) { 
	    $innerHTML= ''; 
	    $children = $node->childNodes; 
	    foreach ($children as $child) { 
	        $innerHTML .= $child->ownerDocument->saveXML( $child ); 
	    } 
	
	    return $innerHTML; 
	}
	
	public function getShowUrl($show){
		$first_letter = strtoupper($show[0]);
		$stripped_show = preg_replace("/[^a-z0-9]/","",strtolower($show));
		
		$page = $this->curl->get("http://www.movie4k.to/tvshows-all-$first_letter.html");
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		
		$res = false;
		
		$tables = $dom->getElementsByTagName('table');
		for($i=0;$i<$tables->length;$i++){
			if ($tables->item($i)->getAttribute("id")=="tablemoviesindex"){
				$rows = $tables->item($i)->getElementsByTagName('tr');
				for($j=0;$j<$rows->length;$j++){
					$tds = $rows->item($j)->getElementsByTagName('td');
					if ($tds->length==2){
						$img = $tds->item(1)->getElementsByTagName('img');
						if ($img->length && substr_count($img->item(0)->getAttribute("src"),"us_flag")){
							
							$compare = preg_replace("/[^a-z0-9]/","",strtolower($tds->item(0)->textContent));
							if ($compare == $stripped_show){								
								$link = $tds->item(0)->getElementsByTagName('a');
								if ($link->length){
									$res = $this->domain.$link->item(0)->getAttribute("href");
									break;
								}
							}
						}
					}
				}
				
				break;
			}
		}
		return $res;
	}
	
}

?>