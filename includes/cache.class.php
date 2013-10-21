<?php

class Cache{
	
	var $basepath;
	
	function __construct($basepath){
		$this->basepath = $basepath;
	}
	
	function checkDir(){
		$dir = $this->basepath."/cachefiles";
		$perms = base_convert(fileperms($dir), 10, 8);
		if ((substr_count($perms,"777")==0) && ($perms!=666)){
	  		return 0;
	  	} else {
	  		return 1;
	  	}
		
	}
	
	function clear(){
		$path = $this->basepath."/cachefiles";
		$dir_handle = @opendir($path);
		
		if (@$dir_handle){
			while ($file = readdir($dir_handle)) { 

				if($file == "." || $file == ".." || substr_count($file,".html")==0){
					continue; 
				} else {
					@unlink($this->basepath."/cachefiles/".$file);
				}
			}
		}
	}
	
	function getCache($cachekey){
		if (file_exists($this->basepath."/cachefiles/".$cachekey.".html")){
			return file_get_contents($this->basepath."/cachefiles/".$cachekey.".html");
		} else {
			return 0;
		}
	}
	
	function saveCache($cachekey,$pagecontent){
		$handle = fopen($this->basepath."/cachefiles/".$cachekey.".html","w+");
		fwrite($handle,$pagecontent);
		//fclose($handle);
	}

}

?>