<?php
error_reporting(E_ALL);
ini_set("display_errors","On");

set_time_limit(0);
require_once("../vars.php");
require_once("../includes/misc.class.php");

$misc = new Misc();
$languages = $misc->getLanguages();

$handle = fopen("../sitemap.xml","w+");

$buffer = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset
	xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
	xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";

fwrite($handle,$buffer);

$buffer = "	<url>
		<loc>$baseurl</loc>
  		<changefreq>always</changefreq>
	</url>\n";

fwrite($handle,$buffer);

foreach($languages as $lang_code => $lang_name){
	
	include($basepath."/language/".$lang_code."/general.php");
	
	$buffer = "	<url>
		<loc>$baseurl/{$routes['new_episodes']}</loc>
  		<changefreq>hourly</changefreq>
	</url>\n";
	
	fwrite($handle,$buffer);
	
	$buffer = "	<url>
		<loc>$baseurl/{$routes['new_movies']}</loc>
  		<changefreq>hourly</changefreq>
	</url>\n";
	
	fwrite($handle,$buffer);
	
	$buffer = "	<url>
		<loc>$baseurl/{$routes['tv_shows']}</loc>
  		<changefreq>hourly</changefreq>
	</url>\n";
	
	fwrite($handle,$buffer);
	
	$buffer = "	<url>
		<loc>$baseurl/{$routes['movies']}</loc>
  		<changefreq>hourly</changefreq>
	</url>\n";
	
	fwrite($handle,$buffer);
	
	
}

print("base urls done\n");

// doing shows
foreach($languages as $lang_code => $lang_name){
	
	include($basepath."/language/".$lang_code."/general.php");
	
	$e = mysql_query("SELECT * FROM shows ORDER BY featured DESC") or die(mysql_error());
	if (mysql_num_rows($e)){
		while($s = mysql_fetch_assoc($e)){
			$buffer = "	<url>
		<loc>$baseurl/{$routes['show']}/{$s['permalink']}</loc>
  		<changefreq>daily</changefreq>
	</url>\n";
			
			fwrite($handle,$buffer);
			
		}
	}
}

print("shows done\n");

// doing movies
foreach($languages as $lang_code => $lang_name){
	
	include($basepath."/language/".$lang_code."/general.php");
	
	$e = mysql_query("SELECT * FROM movies") or die(mysql_error());
	if (mysql_num_rows($e)){
		while($s = mysql_fetch_assoc($e)){
			$buffer = "	<url>
		<loc>$baseurl/{$routes['movie']}/{$s['perma']}</loc>
  		<changefreq>weekly</changefreq>
	</url>\n";
			
			fwrite($handle,$buffer);
			
		}
	}
}

print("movies done\n");

// doing episodes

$perma_cache = array();
$e = mysql_query("SELECT * FROM shows") or die(mysql_error());
if (mysql_num_rows($e)){
	while($s = mysql_fetch_assoc($e)){
		$perma_cache[$s['id']] = $s['permalink'];
	}
}

foreach($languages as $lang_code => $lang_name){
	
	include($basepath."/language/".$lang_code."/general.php");
	
	$e = mysql_query("SELECT * FROM episodes WHERE id IN (SELECT episode_id FROM embeds)") or die(mysql_error());
	if (mysql_num_rows($e)){
		while($s = mysql_fetch_assoc($e)){
			
			if (isset($perma_cache[$s['show_id']])){
				$buffer = "	<url>
		<loc>$baseurl/{$routes['show']}/{$perma_cache[$s['show_id']]}/season/{$s['season']}/episode/{$s['episode']}</loc>
  		<changefreq>weekly</changefreq>
	</url>\n";
			
				fwrite($handle,$buffer);
			}
		}
	}
}

print("episodes done\n");

// doing movie-tags
foreach($languages as $lang_code => $lang_name){
	
	include($basepath."/language/".$lang_code."/general.php");
	
	$e = mysql_query("SELECT * FROM movie_tags") or die(mysql_error());
	if (mysql_num_rows($e)){
		while($s = mysql_fetch_assoc($e)){
			$buffer = "	<url>
		<loc>$baseurl/{$routes['movie_tag']}/{$s['perma']}</loc>
  		<changefreq>hourly</changefreq>
	</url>\n";
			
			fwrite($handle,$buffer);
			
		}
	}
}


print("movie tags done\n");

// doing show-tags
foreach($languages as $lang_code => $lang_name){
	
	include($basepath."/language/".$lang_code."/general.php");
	
	$e = mysql_query("SELECT * FROM tv_tags") or die(mysql_error());
	if (mysql_num_rows($e)){
		while($s = mysql_fetch_assoc($e)){
			$buffer = "	<url>
		<loc>$baseurl/{$routes['tv_tag']}/{$s['perma']}</loc>
  		<changefreq>hourly</changefreq>
	</url>\n";
			
			fwrite($handle,$buffer);
			
		}
	}
}


print("tv tags done\n");

fwrite($handle,"</urlset>");

?>