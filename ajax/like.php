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

if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id'] || !isset($_POST['like_id']) || !isset($_POST['like_type']) || !isset($_POST['vote'])){
	exit();
}

$user_id = (int) $_SESSION['loggeduser_id'];
$target_id = (int) $_POST['like_id'];
$target_type = (int) $_POST['like_type'];
$vote = (int) $_POST['vote'];


require_once("../includes/stream.class.php");
require_once("../includes/show.class.php");
require_once("../includes/movie.class.php");

$stream = new Stream();

if ($user_id && $target_id && $target_type && $vote){
	
	if (isset($_POST['like_comment'])){
		$comment = trim(strip_tags($_POST['like_comment']));
	} else {
		$comment = '';
	}
	
	$data = array();
	$data['user_id'] = $user_id;
	$data['target_id'] = $target_id;
	$data['target_type'] = $target_type;
	$data['vote'] = $vote;
	$data['comment'] = $comment;
	$data['date_added'] = date("Y-m-d H:i:s");
	
	$stream->addLike($data);
	
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
	
	if ($vote==1){
		$data['event_type'] = 1;
	} else {
		$data['event_type'] = 2;
	}
	
	$data['event_comment'] = $comment;
	$data['event_date'] = date("Y-m-d H:i:s");
	$stream->addActivity($data);
	
}
?>