<?php
set_time_limit(0);

require_once("../vars.php");
require_once("../includes/curl.php");
require_once("../includes/imdb.class.php");
require_once("../includes/movie.class.php");

$curl = new Curl();
$movie = new Movie();
$imdb = new IMDB($curl);

$movies = $movie->getMovies("en");
if (count($movies)){
	foreach($movies as $movie_id => $movie_data){
		if (!$movie_data['meta']){
			$imdb_id = $movie_data['imdb_id'];
			
			$imdb_data = $imdb->getById($imdb_id);
			$meta = array();
			
			if (isset($imdb_data['rating']) && $imdb_data['rating']){
				$meta['imdb_rating'] = $imdb_data['rating'];
			}
	
			if (isset($imdb_data['stars']) && !empty($imdb_data['stars'])){
				$meta['stars'] = $imdb_data['stars'];
			}
			
			if (isset($imdb_data['director']) && !empty($imdb_data['director'])){
				$meta['director'] = $imdb_data['director'];
			}
			
			if (isset($imdb_data['year']) && !empty($imdb_data['year'])){
				$meta['year'] = $imdb_data['year'];
			}
			
			
			$movie->updateMeta($movie_id,$meta);
			print("updated\t".$movie_data['title']."\n");
		}
	}
}

?>