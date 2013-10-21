<?php

set_time_limit(0);
require_once("../vars.php");

// cleaning up
$e = mysql_query("DELETE FROM similar_movies WHERE movie1 NOT IN (SELECT id FROM movies) OR movie2 NOT IN (SELECT id FROM movies)") or die(mysql_error());
$e = mysql_query("DELETE FROM similar_shows WHERE show1 NOT IN (SELECT id FROM shows) OR show2 NOT IN (SELECT id FROM shows)") or die(mysql_error());
$e = mysql_query("DELETE FROM movie_tags_join WHERE movie_id NOT IN (SELECT id FROM movies) OR tag_id NOT IN (SELECT id FROM movie_tags)") or die(mysql_error());
$e = mysql_query("DELETE FROM tv_tags_join WHERE show_id NOT IN (SELECT id FROM shows) OR tag_id NOT IN (SELECT id FROM tv_tags)") or die(mysql_error());

// shows

$query = "SELECT `tvjoin1`.show_id as show1, `tvjoin2`.show_id as show2 FROM
			tv_tags_join as tvjoin1, tv_tags_join as tvjoin2
			WHERE tvjoin1.tag_id = tvjoin2.tag_id AND tvjoin1.show_id!=tvjoin2.show_id";
			
$compatible = array();

$e = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($e)){
	while($s = mysql_fetch_assoc($e)){
		extract($s);
		
		if (!isset($compatible[$show1][$show2])){
			$compatible[$show1][$show2] = 1;
		} else {
			$compatible[$show1][$show2]++;
		}
		
		if (!isset($compatible[$show2][$show1])){
			$compatible[$show2][$show1] = 1;
		} else {
			$compatible[$show2][$show1]++;
		}
	}
}

if (count($compatible)){
	foreach($compatible as $show => $val){
		arsort($val);
		$counter = 0;
		
		$del = mysql_query("DELETE FROM similar_shows WHERE show1='$show'") or die(mysql_error());
		
		foreach($val as $similar => $points){
			print("$show is similar to $similar\t$points\n");
			
			$ins = mysql_query("INSERT INTO similar_shows(show1,show2,score) VALUES('$show','$similar','$points')") or die(mysql_error());
			$counter++;
			if ($counter>=10){
				break;	
			}
		}
		
	}
}

// movies

$query = "SELECT `moviejoin1`.movie_id as movie1, `moviejoin2`.movie_id as movie2 FROM
			movie_tags_join as moviejoin1, movie_tags_join as moviejoin2
			WHERE moviejoin1.tag_id = moviejoin2.tag_id AND moviejoin1.movie_id!=moviejoin2.movie_id";
			
$compatible = array();

$e = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($e)){
	while($s = mysql_fetch_assoc($e)){
		extract($s);
		
		if (!isset($compatible[$movie1][$movie2])){
			$compatible[$movie1][$movie2] = 1;
		} else {
			$compatible[$movie1][$movie2]++;
		}
		
		if (!isset($compatible[$movie2][$movie1])){
			$compatible[$movie2][$movie1] = 1;
		} else {
			$compatible[$movie2][$movie1]++;
		}
	}
}

if (count($compatible)){
	foreach($compatible as $movie => $val){
		arsort($val);
		$counter = 0;
		
		$del = mysql_query("DELETE FROM similar_movies WHERE movie1='$movie'") or die(mysql_error());
		
		foreach($val as $similar => $points){
			print("$movie is similar to $similar\t$points\n");
			
			$ins = mysql_query("INSERT INTO similar_movies(movie1,movie2,score) VALUES('$movie','$similar','$points')") or die(mysql_error());
			$counter++;
			if ($counter>=10) break;
		}
		
	}
}