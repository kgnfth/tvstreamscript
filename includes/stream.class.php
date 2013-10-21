<?php

class Stream{
	
	function __construct(){
	
	}
	
	public function get($max_id,$limit=20,$lang=null,$user_id = null, $friends = array()){
		$where = array();
		
		if ($user_id && is_numeric($user_id)){
			if (count($friends)){
				$friends[] = $user_id;
				foreach($friends as $k => $v){
					$friends[$k] = mysql_real_escape_string($v);
				}
				$where[] = "user_id IN (".implode(",",$friends).")";
			} else {
				$where[] = "user_id='".mysql_real_escape_string($user_id)."'";
			}
		}
		
		if ($max_id){
			$where[] = "id<$max_id";
		}
		
		if (count($where)){
			$where = "WHERE ".implode(" AND ",$where);
		} else {
			$where = "";
		}
		
				
		$e = mysql_query("SELECT * FROM activity $where ORDER BY id DESC LIMIT $limit") or die(mysql_error());
		$res = array();
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_assoc($e)){
				$s['user_data'] = json_decode($s['user_data'],true);
				if (!$lang){
					$s['target_data'] = json_decode($s['target_data'],true);
				} else {
					$s['target_data'] = json_decode($s['target_data'],true);
					if (isset($s['target_data']['title'])){
						if (isset($s['target_data']['title'][$lang])){
							$s['target_data']['title'] = $s['target_data']['title'][$lang];
						} else {
							$s['target_data']['title'] = $s['target_data']['title']['en'];
						}
					}
					
					
					
					if (isset($s['target_data']['description']) && $s['target_data']['description']){
						
						if (isset($s['target_data']['description'][$lang])){
							
							$s['target_data']['description'] = $s['target_data']['description'][$lang];
						} else {
							$s['target_data']['description'] = $s['target_data']['description']['en'];
						}
					} else {
						$s['target_data']['description'] = "";
					}
					
					if (isset($s['target_data']['showtitle']) && $s['target_data']['showtitle']){
						if (isset($s['target_data']['showtitle'][$lang])){
							$s['target_data']['showtitle'] = $s['target_data']['showtitle'][$lang];
						} else {
							$s['target_data']['showtitle'] = $s['target_data']['showtitle']['en'];
						}
					} 
				}
				$res[$s['id']] = $s;
			}
		}
		return $res;
	}
	
	public function addLike($data){
		$keys = array();
		$values = array();
		foreach($data as $key => $val){
			$values[] = "'".mysql_real_escape_string($val)."'";
			$keys[] = "`$key`";
		}
		
		$e = mysql_query("SELECT id as like_id FROM likes WHERE user_id='{$data['user_id']}' AND target_id='{$data['target_id']}' AND target_type='{$data['target_type']}'") or die(mysql_error());
		if (mysql_num_rows($e)){
			extract(mysql_fetch_assoc($e));
			$del = mysql_query("DELETE FROM likes WHERE id='$like_id'") or die(mysql_error());
		}
		
		$ins = mysql_query("INSERT INTO likes(".implode(",",$keys).") VALUES(".implode(",",$values).")") or die(mysql_error());
		return mysql_insert_id();
	}
	
	public function addWatch($data){
		$keys = array();
		$values = array();
		foreach($data as $key => $val){
			$values[] = "'".mysql_real_escape_string($val)."'";
			$keys[] = "`$key`";
		}
		
		if ($data['target_type']==3){
			if (!isset($_SESSION['loggeduser_seen_episodes'])){
				$_SESSION['loggeduser_seen_episodes'] = array();
			}
			
			if (!in_array($data['target_id'],$_SESSION['loggeduser_seen_episodes'])){
				$_SESSION['loggeduser_seen_episodes'][] = $data['target_id'];
			}
		} elseif ($data['target_type']==2){
			if (!isset($_SESSION['loggeduser_seen_movies'])){
				$_SESSION['loggeduser_seen_movies'] = array();
			}
			
			if (!in_array($data['target_id'],$_SESSION['loggeduser_seen_movies'])){
				$_SESSION['loggeduser_seen_movies'][] = $data['target_id'];
			}
		}
		
		$e = mysql_query("SELECT id as watch_id FROM watches WHERE user_id='{$data['user_id']}' AND target_id='{$data['target_id']}' AND target_type='{$data['target_type']}'") or die(mysql_error());
		if (mysql_num_rows($e)==0){
			$ins = mysql_query("INSERT INTO watches(".implode(",",$keys).") VALUES(".implode(",",$values).")") or die(mysql_error());
			return mysql_insert_id();
		} else {
			return 0;
		}
	}
	
	public function addActivity($data){
		$keys = array();
		$values = array();
		foreach($data as $key => $val){
			if (is_array($val)){
				$values[] = "'".mysql_real_escape_string(json_encode($val))."'";
			} else {
				$values[] = "'".mysql_real_escape_string($val)."'";
			}
			$keys[] = "`$key`";
		}
		
		$ins = mysql_query("INSERT INTO activity(".implode(",",$keys).") VALUES(".implode(",",$values).")") or die(mysql_error());
		return mysql_insert_id();
	}

}

?>