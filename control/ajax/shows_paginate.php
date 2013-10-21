<?php 

session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/show.class.php");
require_once("../../includes/settings.class.php");

$settings = new Settings();
$default_language = $settings->getSetting("default_language", true);
if (!$default_language || (is_array($default_language) && empty($default_language))){
	$default_language = "en";
}

if (isset($_GET['sEcho'])){
	$echo = $_GET['sEcho'];

	if (isset($_GET['iSortCol_0']) && isset($_GET['sSortDir_0'])){
		if ($_GET['iSortCol_0']==3){
			$sortby = "title";
		} elseif ($_GET['iSortCol_0']==4){
			$sortby = "description";
		} else {
			$sortby = "id";
		}
		
		$sortdir = strtoupper($_GET['sSortDir_0']);
	} else {
		$sortby = "id";
		$sortdir = "DESC";
	}
	
	if (isset($_GET['iDisplayLength'])){
		$per_page = $_GET['iDisplayLength'];
	} else {
		$per_page = 50;
	}
	
	if ($per_page>500){
		$per_page = 1;
	}
	
	if (isset($_GET['iDisplayStart'])){
		$start = $_GET['iDisplayStart'];
	} else {
		$start = 0;
	}
	
	$show = new Show();
	
	if (isset($_GET['sSearch']) && $_GET['sSearch']){
		$shows = $show->getList(null,$start,$per_page,$sortby,$sortdir,$_GET['sSearch']);
	} else {	
		$shows = $show->getList(null,$start,$per_page,$sortby,$sortdir);
	}
	
	$res = array();
	$res['sEcho'] = $echo;
	if (isset($_GET['sSearch']) && $_GET['sSearch']){
		$res['iTotalDisplayRecords'] = $show->getShowCount($_GET['sSearch']);
	} else {
		$res['iTotalDisplayRecords'] = $show->getShowCount();
	}
	$res['iTotalRecords'] = count($shows);
	$res['aaData'] = array();
	if (count($shows)){
		foreach($shows as $key => $val){
			extract($val);
			
			if (strlen($description['en'])>100){
				$description[$default_language] = substr($description[$default_language],0,97)."...";
			}
			
			$res['aaData'][] = array(	'<input type="checkbox" name="row_sel" class="row_sel" value="'.$key.'" id="show_checkbox_'.$key.'" />',
										'<a href="'.$baseurl.'/thumbs/'.$thumbnail.'" title="" class="cbox_single thumbnail"><img alt="" src="'.$baseurl.'/thumbs/'.$thumbnail.'" style="height:70px;width:50px"></a>',
										'<a href="http://www.imdb.com/title/'.$imdb_id.'" target="_blank">'.$imdb_id.'</a>',
										$title[$default_language],
										$description[$default_language],
										$episode_count,
										'<a href="javascript:void(0);" onClick="deleteShow('.$key.')">Delete</a>',
										'<a href="index.php?menu=shows_new&showid='.$key.'">Edit</a>',
										'<a href="index.php?menu=episodes&show_id='.$key.'">Add episode</a><br /><a href="index.php?menu=edit_episodes&showid='.$key.'">Episode list</a>');
		}
	}
	
	print(json_encode($res));
}