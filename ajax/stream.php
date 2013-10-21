<?php
session_start();

$language = $_SESSION['language'];
if (!$language){
	$language = "en";
	$_SESSION['language'] = "en";
}

require_once("../vars.php");
require_once("../includes/stream.class.php");
require_once("../includes/misc.class.php");
require_once("../includes/user.class.php");
require_once("../includes/settings.class.php");
require_once("../templates/smarty/libs/Smarty.class.php");
require_once("../language/$language/general.php");
require_once("../language/$language/home.php");

$settings = new Settings();

$global_settings = $settings->getMultiSettings(array("seo_links"), true);

if (!isset($global_settings['seo_links']) || !in_array($global_settings['seo_links'],array(0,1))){
	$seo_links = 1;
} else {
	$seo_links = $global_settings['seo_links'];
}

if (!isset($_SESSION['theme']) || !$_SESSION['theme']){
	$theme = $settings->getSetting("theme");
	if (!count($theme)){
		$theme = 'svarog';
	} else {
		$theme = $theme->theme;
	}
} else {
	$theme = $_SESSION['theme'];
}

$smarty = new Smarty();
$smarty->caching = 0;
$smarty->template_dir = "$basepath/templates/$theme";
$smarty->config_dir = "$basepath/templates/smarty/configs";
if (file_exists("$basepath/cachefiles/$theme")){
	$smarty->compile_dir = "$basepath/cachefiles/$theme";
	$smarty->cache_dir = "$basepath/cachefiles/$theme";
} else {
	$smarty->compile_dir = "$basepath/cachefiles/";
	$smarty->cache_dir = "$basepath/cachefiles";
}
$smarty->assign("templatepath","$baseurl/templates/$theme");

$data = array();
$streamer = new Stream();
$misc = new Misc();


if (!isset($_POST['max_id'])){
	$_POST['max_id'] = 0;
} else {
	$_POST['max_id'] = (int) $_POST['max_id'];
}

if (!isset($_POST['type'])){
	$type = 1;
} else {
	$type = $_POST['type'];
}

$friends = array();;

if (isset($_POST['user_id'])){
	$user_id = $_POST['user_id'];
	$limit = 10;
	if (isset($_POST['friends'])){
		$user = new User();
		$friends = $user->getFollows($_POST['user_id']);
		if (count($friends)){
			$friends = array_keys($friends);
		}
	}
} else {
	$user_id = null;
	$limit = 20;
}




$stream = $streamer->get($_POST['max_id'],$limit,$language,$user_id,$friends);
$html = '';



function getNakNek($string){
	global $lang;
	
	$nak = array("a","á","o","ó","u","ú");
	$nek = array("e","é","i","í","ö","ő","ü","ű");
	$sting = strtolower($string);
	
	$rag = $lang['add1'];
	for($i=strlen($string)-1;$i>0;$i--){
		if (in_array($string[$i],$nak)){
			$rag = $lang['add1'];
			break;
		} elseif (in_array($string[$i],$nek)){
			$rag = $lang['add2'];
			break;				
		}
	}
	
	return $rag;
}

function getAAz($string){
	global $lang;

	$string = strtolower($string);
	$az = array("a","á","e","é","i","í","o","ó","ö","ő","u","ú","ü","ű");
	if (in_array($string[0],$az)){
		return $lang['the1'];
	} else {
		return $lang['the2'];
	}	
}

$counter = 1;
$last_id = 0;

if (count($stream)){
	foreach($stream as $key => $event){
			
		if ($seo_links){
			if (isset($event['user_data']['username'])){
				$user_url = $baseurl.'/user/'.$event['user_data']['username'];
			} else {
				$user_url = '';
			}
			
			if (isset($event['target_data']['permalink'])){
				$show_url = $baseurl.'/'.$routes['show']."/".$event['target_data']['permalink'];
			} else {
				$show_url = '';
			}
			
			if (isset($event['target_data']['perma'])){
				$movie_url = $baseurl.'/'.$routes['movie'].'/'.$event['target_data']['perma'];
			} else {
				$movie_url = '';
			}
			
			if (isset($event['target_data']['show_perma'])){
				$show_url = $baseurl.'/'.$routes['show']."/".$event['target_data']['show_perma'];
			}
			
			if (isset($event['target_data']['season'])){
				$episode_url = $baseurl."/".$event['target_data']['show_perma']."/season/".$event['target_data']['season']."/episode/".$event['target_data']['episode'];
			} else {
				$episode_url = "";
			}
			
		} else {
			if (isset($event['user_data']['username'])){
				$user_url = $baseurl."/index.php?menu=user&profile_username=".$event['user_data']['username'];
			} else {
				$user_url = '';
			}
			
			if (isset($event['target_data']['permalink'])){
				$show_url = $baseurl."/index.php?menu=show&perma=".$event['target_data']['permalink'];
			} else {
				$show_url = '';
			}
			
			if (isset($event['target_data']['perma'])){
				$movie_url = $baseurl.'/index.php?menu=watchmovie&perma='.$event['target_data']['perma'];
			} else {
				$movie_url = '';
			}
			
			if (isset($event['target_data']['show_perma'])){
				$show_url = $baseurl."/index.php?menu=show&perma=".$event['target_data']['show_perma'];
			}
			
			if (isset($event['target_data']['season'])){
				$episode_url = $baseurl."/index.php?menu=episode&perma=".$event['target_data']['show_perma']."&season=".$event['target_data']['season']."&episode=".$event['target_data']['episode'];
			} else {
				$episode_url = "";
			}
		}
		
		/* Like */
		if ($event['event_type'] == 1){
			if ($event['target_type'] == 1){	
				/* Show */
				
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/like.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username'].getNakNek($event['user_data']['username'])." {$lang['stream_user_likes']} ".getAAz($event['target_data']['title']).' '.$lang['stream_show_before'].' <a href="'.$show_url.'" class="colored">'.$event['target_data']['title'].'</a> '.$lang['stream_show_after'].'
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$show_url.'" class=""><img alt="" class="tooltip" original-title="'.$event['target_data']['title'].'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumbnail'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';

			} elseif ($event['target_type'] == 2){
				/* Movie */
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/like.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username'].getNakNek($event['user_data']['username'])." {$lang['stream_user_likes']} ".getAAz($event['target_data']['title']).' '.$lang['stream_movie_before'].' <a href="'.$movie_url.'" class="colored">'.$event['target_data']['title'].'</a> '.$lang['stream_movie_after'].'
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$movie_url.'" class=""><img alt="" class="tooltip" original-title="'.$event['target_data']['title'].'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumb'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			} elseif ($event['target_type'] == 3){
				/* Episode*/
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/like.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username'].getNakNek($event['user_data']['username'])." {$lang['stream_user_likes']} ".getAAz($event['target_data']['title']).' <a href="'.$show_url.'" class="colored">'.$event['target_data']['showtitle'].'</a> <a href="'.$episode_url.'" class="colored">'.str_replace(array("#season#","#episode#"),array($event['target_data']['season'],$event['target_data']['episode']),$lang['stream_season_episode']).'</a>
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$episode_url.'" class=""><img alt="" class="tooltip" original-title="'.str_replace(array("#season#","#episode#"),array($event['target_data']['season'],$event['target_data']['episode']),$lang['stream_season_episode_long']).'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumbnail'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			}
		}
		
		/* Dislike */
		if ($event['event_type'] == 2){
			if ($event['target_type'] == 1){
				/* Show */
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/dislike.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username'].getNakNek($event['user_data']['username'])." {$lang['stream_user_dislikes']} ".getAAz($event['target_data']['title']).' '.$lang['stream_show_before'].' <a href="'.$show_url.'" class="colored">'.$event['target_data']['title'].'</a> '.$lang['stream_show_after'].'
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$show_url.'" class=""><img alt="" class="tooltip" original-title="'.$event['target_data']['title'].'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumbnail'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			} elseif ($event['target_type'] == 2){
				/* Movie */
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/dislike.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username'].getNakNek($event['user_data']['username'])." {$lang['stream_user_dislikes']} ".getAAz($event['target_data']['title']).' '.$lang['stream_movie_before'].' <a href="'.$movie_url.'" class="colored">'.$event['target_data']['title'].'</a> '.$lang['stream_movie_after'].'
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$movie_url.'" class=""><img alt="" class="tooltip" original-title="'.$event['target_data']['title'].'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumb'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			} elseif ($event['target_type'] == 3){
				/* Episode*/
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/dislike.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username'].getNakNek($event['user_data']['username'])." {$lang['stream_user_dislikes']} ".getAAz($event['target_data']['title']).' <a href="'.$show_url.'" class="colored">'.$event['target_data']['showtitle'].'</a> <a href="'.$episode_url.'" class="colored">'.str_replace(array("#season#","#episode#"),array($event['target_data']['season'],$event['target_data']['episode']),$lang['stream_season_episode']).'</a>
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$episode_url.'" class=""><img alt="" class="tooltip" original-title="'.str_replace(array("#season#","#episode#"),array($event['target_data']['season'],$event['target_data']['episode']),$lang['stream_season_episode_long']).'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumbnail'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			}
		}

		/* User reg */			
		if ($event['event_type'] == 3){
			$html.= '
				<div class="span-16 notopmargin">
					<img src="'.$baseurl.'/templates/svarog/images/icons-big/user.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
					<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
					<p class="notopmargin left" style="font-weight:bold;width: 435px;">
						'.$event['user_data']['username'].' '.$lang['stream_user_registered'].'
						<br />
					';
				$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$lang['stream_welcome_to_our_community'].'"</span><br />';

				
				$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
				
			$html.= '
				</p>
			';

			
			$html.='</div><div class="clear"></div>';
		}

		/* Avatar change */			
		if ($event['event_type'] == 4){
			$html.= '
				<div class="span-16 notopmargin">
					<img src="'.$baseurl.'/templates/svarog/images/icons-big/photo.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
					<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
					<p class="notopmargin left" style="font-weight:bold;width: 435px;">
						'.$event['user_data']['username'].' '.$lang['stream_user_changed_avatar'].'
						<br />
					';
				if ($event['event_comment']){
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
				}
				
				$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
				
			$html.= '
				</p>
			';

			
			$html.='</div><div class="clear"></div>';
		}
			
		/* Watch */
		if ($event['event_type'] == 5){
			if ($event['target_type'] == 1){	
				/* Show */
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/television.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username']." {$lang['stream_user_watched']} ".getAAz($event['target_data']['title']).' '.$lang['stream_show_before_watch'].' <a href="'.$show_url.'" class="colored">'.$event['target_data']['title'].'</a> '.$lang['stream_show_after_watch'].'
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$show_url.'" class=""><img alt="" class="tooltip" original-title="'.$event['target_data']['title'].'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumbnail'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			} elseif ($event['target_type'] == 2){
				/* Movie */
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/film.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username']." {$lang['stream_user_watched']} ".getAAz($event['target_data']['title']).' '.$lang['stream_movie_before_watch'].' <a href="'.$movie_url.'" class="colored">'.$event['target_data']['title'].'</a> '.$lang['stream_movie_after_watch'].'
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$movie_url.'" class=""><img alt="" class="tooltip" original-title="'.$event['target_data']['title'].'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumb'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			} elseif ($event['target_type'] == 3){
				/* Episode*/
				$html.= '
					<div class="span-16 notopmargin">
						<img src="'.$baseurl.'/templates/svarog/images/icons-big/television.png" class="left" style="margin-right:10px;margin-top: 5px;"/>
						<div class="flickr item left"><a href="'.$user_url.'" style="margin-right: 10px;"><img alt="" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/users/'.$event['user_data']['avatar'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
						<p class="notopmargin left" style="font-weight:bold;width: 435px;">
							'.$event['user_data']['username']." {$lang['stream_user_watched']} ".getAAz($event['target_data']['title']).' <a href="'.$show_url.'" class="colored">'.$event['target_data']['showtitle'].'</a> <a href="'.$episode_url.'" class="colored">'.str_replace(array("#season#","#episode#"),array($event['target_data']['season'],$event['target_data']['episode']),$lang['stream_season_episode_watch']).'</a>
							<br />
						';
					if ($event['event_comment']){
						$html.='<span class="notopmargin small-italic" style="font-weight:normal">"'.$event['event_comment'].'"</span><br />';
					}
					
					$html.='<span class="notopmargin small-italic" style="font-weight:normal">'.$misc->ago(strtotime($event['event_date']),$lang).'</span>';
					
				$html.= '
					</p>
					<div class="flickr item right last"><a href="'.$episode_url.'" class=""><img alt="" class="tooltip" original-title="'.str_replace(array("#season#","#episode#"),array($event['target_data']['season'],$event['target_data']['episode']),$lang['stream_season_episode_long']).'" src="'.$baseurl.'/templates/svarog/timthumb.php?src='.$baseurl.'/thumbs/'.$event['target_data']['thumbnail'].'&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a></div>
				';

				
				$html.='</div><div class="clear"></div>';
			}
		}
			
		if ($counter==count($stream)){
			$last_id = $key;
		}
		
		$counter++;
	}
}

$smarty->assign("html",$html);


if ($type==1){
	$loadmore = '<ul id="filter" style="padding-left:25%; margin:0;">	
					<li style="float:none; text-align:center; width:50%;" onclick="streamPoll('.$last_id.',\'stream_'.$last_id.'\');"><a href="javascript:void(0);">'.$lang['load_more_activity'].'</a></li>
				</ul>';
}

if ($type==2){
	$loadmore = '<ul id="filter" style="padding-left:25%; margin:0;">	
					<li style="float:none; text-align:center; width:50%;" onclick="userStream('.$_POST['user_id'].','.$last_id.',\'stream_'.$last_id.'\');"><a href="javascript:void(0);">'.$lang['load_more_activity'].'</a></li>
				</ul>';
}

if ($type==3){
	$loadmore = '<ul id="filter" style="padding-left:25%; margin:0;">	
					<li style="float:none; text-align:center; width:50%;" onclick="friendStream('.$_POST['user_id'].','.$last_id.',\'stream_'.$last_id.'\');"><a href="javascript:void(0);">'.$lang['load_more_activity'].'</a></li>
				</ul>';
}

$smarty->assign("last_id",$last_id);
$smarty->assign("load_more_button",$loadmore);
$smarty->display("ajax_stream.tpl");

//print($html);