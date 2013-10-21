<?php
@session_start();
require_once("../vars.php");
require_once("../includes/user.class.php");

if (!isset($_SESSION['loggeduser_id']) && isset($_COOKIE['guid'])){
	$user = new User();
	$res = $user->cookieLogin($_COOKIE['guid']);
	if (!$res){
		setcookie("guid","",time()-60*60, "/");
	}
}

if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id'] || !isset($_POST['watch_id']) || !isset($_POST['watch_type'])){
	exit();
}

$user_id = (int) $_SESSION['loggeduser_id'];
$target_id = (int) $_POST['watch_id'];
$target_type = (int) $_POST['watch_type'];

require_once("../includes/stream.class.php");
require_once("../includes/show.class.php");
require_once("../includes/movie.class.php");

$stream = new Stream();

if ($user_id && $target_id && $target_type){

	$data = array();
	$data['user_id'] = $user_id;
	$data['target_id'] = $target_id;
	$data['target_type'] = $target_type;
	$data['date_added'] = date("Y-m-d H:i:s");
	
	$res = $stream->addWatch($data);
	
	if ($res){
		$data = array();
		$data['user_id'] = $user_id;
		$data['target_id'] = $target_id;
		$data['user_data'] = $_SESSION['loggeduser_details'];
		$data['target_type'] = $target_type;
		
		if ($target_type == 1){
			// show
			$show = new Show();
			$data['target_data'] = $show->getShow($target_id,0);
		} elseif ($target_type == 2){
			// movie
			$movie = new Movie();
			$data['target_data'] = $movie->getMovie($target_id);
			
		} elseif ($target_type == 3){
			// episode
			$show = new Show();
			$data['target_data'] = $show->getEpisodeById($target_id);	
		}
		
		$data['event_type'] = 5;		
		$data['event_date'] = date("Y-m-d H:i:s");
		$stream->addActivity($data);
	}

}

?>