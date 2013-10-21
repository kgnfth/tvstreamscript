<?php

class TVcom{
	
	public $curl = null;
	
	function __construct($curl = null){
		if ($curl){
			$this->curl = $curl;
		} else {
			if (!class_exists("Curl")){
				require_once("curl.php");
			}
			
			$this->curl = new Curl();
		}
	}
	
	public function getShowUrl($title){
		$page = $this->curl->get("http://www.tv.com/autosuggest/?q=".urlencode($title)."&num_res=5");
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		$lis = $dom->getElementsByTagName("li");
		
		$url = false;
		
		for($i=0;$i<$lis->length;$i++){
			$h4s = $lis->item($i)->getElementsByTagName('h4');
			if ($h4s->length){
				$h4text = preg_replace("/[^a-z0-9]/","",strtolower($h4s->item(0)->textContent));
				if ($h4text == preg_replace("/[^a-z0-9]/","",strtolower($title))){
					$links = $lis->item($i)->getElementsByTagName('a');
					for($j=0;$j<$links->length;$j++){
						if ($links->item($j)->getAttribute("class")=="showing"){
							$url = "http://www.tv.com".$links->item($j)->getAttribute("href");
							break;
						}
					}
				}
			}
			
			if ($url){
				break;
			}
		}
		
		return $url;
	}
	
	public function getShowInfo($title){
		$show_url = $this->getShowUrl($title);
	}
	
}

?>