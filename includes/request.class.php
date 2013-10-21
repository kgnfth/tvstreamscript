<?php

class Request{
	
	function __construct(){
		
	}
	
	public function validate($params){
		global $lang;
		
		$errors = array();
		if (!isset($params['request_content']) || !$params['request_content'] || $params['request_content'] == $lang['requests_details']){
			$errors[] = $lang['requests_empty_details'];
		}
		
		if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id']){
			$errors[] = $lang[''];
		}
		
		return $errors;
	}
	
	public function save($params){
		$data = array();
		$data['message'] = mysql_real_escape_string(strip_tags($params['request_content']));
		$data['user_id'] = $_SESSION['loggeduser_id'];
		$data['request_date'] = date("Y-m-d H:i:s");
		$data['status'] = 0;
		
		$check = mysql_query("SELECT * FROM requests WHERE user_id='{$data['user_id']}' AND message='{$data['message']}'") or die(mysql_error());
		if (mysql_num_rows($check)==0){
			$ins = mysql_query("INSERT INTO requests(user_id,request_date,message,status,votes) VALUES('{$data['user_id']}','{$data['request_date']}','{$data['message']}','{$data['status']}',1)") or die(mysql_error());
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	public function getActive(){
		global $misc,$lang;
		
		$res = array();
		$two_weeks = date("Y-m-d H:i:s",strtotime("14 days ago"));
		$e = mysql_query("SELECT requests.*, users.username, users.avatar, users.email FROM requests, users WHERE (status=0 OR status=1 OR request_date>='$two_weeks') AND users.id = requests.user_id ORDER BY votes DESC,id DESC") or die(mysql_error());
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_assoc($e)){
				$res[$s['id']] = $s;
				if (!$res[$s['id']]['avatar']){
					$res[$s['id']]['avatar'] = "nopic.jpg";
				}
				
				$res[$s['id']]['date_print'] = $misc->ago(strtotime($s['request_date']),$lang);
			}
		}
		
		return $res;
		
	}
	
	public function getAll( $order_by = "votes DESC,id DESC"){
		global $misc,$lang;
		
		$order_by = mysql_real_escape_string($order_by);
		
		$res = array();
		$e = mysql_query("SELECT requests.*, users.username, users.avatar, users.email FROM requests, users WHERE users.id = requests.user_id ORDER BY $order_by") or die(mysql_error());
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_assoc($e)){
				$res[$s['id']] = $s;
				if (!$res[$s['id']]['avatar']){
					$res[$s['id']]['avatar'] = "nopic.jpg";
				}
				
				$res[$s['id']]['date_print'] = $misc->ago(strtotime($s['request_date']),$lang);
			}
		}
		
		return $res;
		
	}
	
	public function addVote($request_id,$user_id){
		$request_id = mysql_real_escape_string($request_id);
		$user_id = mysql_real_escape_string($user_id);
		
		// checking if request even exists
		$e = mysql_query("SELECT * FROM requests WHERE id='$request_id'") or die(mysql_error());
		if (mysql_num_rows($e)){
			$s = mysql_fetch_assoc($e);
			if ($s['user_id']!=$user_id){
				$check = mysql_query("SELECT * FROM request_votes WHERE request_id='$request_id' AND user_id='$user_id'") or die(mysql_error());
				if (mysql_num_rows($check)==0){
					$upd = mysql_query("UPDATE requests SET votes=votes+1 WHERE id='$request_id'") or die(mysql_error());
					$ins = mysql_query("INSERT INTO request_votes(request_id,user_id,vote_date) VALUES('$request_id','$user_id','".date("Y-m-d H:i:s")."')") or die(mysql_error()); 
					return $s['votes']+1;
				} else {
					return $s['votes'];	
				}
			} else {
				return $s['votes'];
			}
		} else {
			return false;
		}
	}
	
	public function getPendingCount(){
		$e = mysql_query("SELECT count(*) as pending_count FROM requests WHERE status='0'") or die(mysql_error());
		if (mysql_num_rows($e)){
			extract(mysql_fetch_assoc($e));
			if (!$pending_count){
				$pending_count = 0;
			}
			
			return $pending_count;
		} else {
			return 0;
		}
	}
	
	public function delete($request_id){
		$request_id = mysql_real_escape_string($request_id);
		$e = mysql_query("DELETE FROM requests WHERE id='$request_id'") or die(mysql_error());
		$e = mysql_query("DELETE FROM request_votes WHERE request_id='$request_id'") or die(mysql_error());
	}
	
	public function update($request_id,$data){
		$updates = array();
		foreach($data as $key => $val){
			$updates[] = "`".mysql_real_escape_string($key)."`='".mysql_real_escape_string($val)."'";
		}
		$request_id = mysql_real_escape_string($request_id);
		
		$up = mysql_query("UPDATE requests SET ".implode(",",$updates)." WHERE id='$request_id'") or die(mysql_error());
	}
	
}

?>