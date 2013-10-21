<?php 

session_start();

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username'])){
	exit();
}

require_once("../../vars.php");
require_once("../../includes/user.class.php");

if (isset($_GET['sEcho'])){
	$echo = $_GET['sEcho'];

	if (isset($_GET['iSortCol_0']) && isset($_GET['sSortDir_0'])){
		
		if ($_GET['iSortCol_0']==2){
			$sortby = "username";
		} elseif ($_GET['iSortCol_0']==3){
			$sortby = "fb_id";
		} elseif ($_GET['iSortCol_0']==4){
			$sortby = "language";
		} elseif ($_GET['iSortCol_0']==5){
			$sortby = "email";
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
	
	$user = new User();
	
	if (isset($_GET['sSearch']) && $_GET['sSearch']){
		$users = $user->search($_GET['sSearch'],$start,$per_page,$sortby,$sortdir);
	} else {	
		$users = $user->getAllUsers(null,$start,$per_page,$sortby,$sortdir);
	}
	
	$res = array();
	$res['sEcho'] = $echo;
	if (isset($_GET['sSearch']) && $_GET['sSearch']){
		$res['iTotalDisplayRecords'] = $user->getUserCount($_GET['sSearch']);
	} else {
		$res['iTotalDisplayRecords'] = $user->getUserCount();
	}
	$res['iTotalRecords'] = count($users);
	$res['aaData'] = array();
	if (count($users)){
		foreach($users as $key => $val){
			extract($val);
			if ($fb_id){
				$facebook_link = "<a href='http://www.facebook.com/profile.php?id=$fb_id' target='_blank'>$fb_id</a>";
			} else {
				$facebook_link = "&nbsp;";
			}
			
			$res['aaData'][] = array(	'<input type="checkbox" name="row_sel" class="row_sel" value="'.$key.'" id="user_checkbox_'.$key.'" />',
										'<a href="'.$baseurl.'/thumbs/users/'.$avatar.'" title="" class="cbox_single thumbnail"><img alt="" src="'.$baseurl.'/thumbs/users/'.$avatar.'" style="height:50px;width:50px"></a>',
										$username,
										$facebook_link,
										$language,
										'<a href="index.php?menu=users_email&user_list='.$username.'">'.$email.'</a>',
										'<a href="javascript:void(0);" onclick="deleteUser('.$key.');">Delete</a>');
		}
	}
	
	print(json_encode($res));
}