<?php

class TVapi{
    
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
    
    public function getEpisodeEmbeds($imdb_id, $season, $episode, $embed_languages){
        $api_data = $this->curl->get("http://tv-api.com/api/?method=getShowEmbeds&data=".json_encode(array("imdb_id" => $imdb_id, "season" => $season, "episode" => $episode, "language" => $embed_languages)));
        $api_data = json_decode($api_data,true);
        if ($api_data['status']=="success"){
            return $api_data['embeds'];
        } else {
            return false; 
        }
    }
    
    public function getMovieEmbeds($imdb_id, $embed_languages){
        $request = "http://tv-api.com/api/?method=getMovieEmbeds&data=".json_encode(array("imdb_id" => $imdb_id, "language" => $embed_languages));
        $api_data = $this->curl->get($request);
        
        $api_data = json_decode($api_data,true);
        if ($api_data['status']=="success"){
            return $api_data['embeds'];
        } else {
            return false;  
        }
    }
    
}

?>