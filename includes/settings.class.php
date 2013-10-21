<?php

class Settings{
 
   	public function __construct(){
	
   	}
   
	public function getModules(){
		$e = mysql_query("SELECT * FROM modules") or die(mysql_error());
		$modules = array();
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_assoc($e)){
				$modules[$s['perma']]=$s;
			}
		}
		
		return $modules;
	}
	
	public function setModule($module_id,$status){
		$e = mysql_query("UPDATE modules SET status='$status' WHERE id='$module_id'") or die(mysql_error());
	}
	
	public function addWidget($params){
		$widget = array();
		$reference = "widget_".mysql_real_escape_string($params['widget_reference']);
		$widget['content'] = $params['widget_content'];
		if (!isset($params['widget_logged']) || !$params['widget_logged']){
			$widget['logged'] = 0;
		} else {
			$widget['logged'] = 1;
		}
		
		$widget = mysql_real_escape_string(json_encode($widget));
		$ins = mysql_query("INSERT INTO settings(title,value) VALUES('$reference','$widget')") or die(mysql_error());
		return mysql_insert_id();
	}
	
	public function updateWidget($params,$widget_id){
		$widget = array();
		
		$widget_id = mysql_real_escape_string($widget_id);
		$reference = "widget_".mysql_real_escape_string($params['widget_reference']);
		$widget['content'] = $params['widget_content'];
		if (!isset($params['widget_logged']) || !$params['widget_logged']){
			$widget['logged'] = 0;
		} else {
			$widget['logged'] = 1;
		}
		
		$widget = mysql_real_escape_string(json_encode($widget));
		$update = mysql_query("UPDATE settings SET title='$reference',value='$widget' WHERE id='$widget_id'") or die(mysql_error());
	}
	
	public function deleteWidget($widget_id){
		$widget_id = mysql_real_escape_string($widget_id);
		$del = mysql_query("DELETE FROM settings WHERE id='$widget_id'") or die(mysql_error());		
	}
	
	public function getWidgets(){
		$widgets = array();
		$e = mysql_query("SELECT * FROM settings WHERE title LIKE 'widget_%'") or die(mysql_error());
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_assoc($e)){
				$reference = explode("widget_",$s['title']);
				$reference = $reference[1];
				$widgets[$reference] = json_decode($s['value'],true);
				$widgets[$reference]['id'] = $s['id'];
				
			}
		}
		return $widgets;
	}
	
	public function validateWidget($params, $widget_id = null){
		
		$errors = array();
		if (!isset($params['widget_reference']) || !$params['widget_reference']){
			$errors[1] = "Please enter a reference for this widget";
		} else {
			preg_match("/[^a-zA-Z0-9_]/",$params['widget_reference'],$matches);
			if (count($matches)){
				$errors[1] = "Reference can only contain alphanumeric characters and underscores";
			} else {
				$reference = mysql_real_escape_string("widget_".$params['widget_reference']);
				if (!$widget_id){
					$check = mysql_query("SELECT * FROM settings WHERE title='".$reference."'") or die(mysql_error());
				} else {
					$widget_id = mysql_real_escape_string($widget_id);
					$check = mysql_query("SELECT * FROM settings WHERE title='".$reference."' AND id!='$widget_id'") or die(mysql_error());
				}
				if (mysql_num_rows($check)){
					$errors[1] = "This reference is already in use";
				}
			}
		}
		
		if (!isset($params['widget_content']) || !$params['widget_content']){
			$errors[2] = "Please enter the content of the widget";
		}
		
		return $errors;
		
	}
	
	public function getMultiSettings($settings,$get_array=false){
		$res = array();
		foreach($settings as $key => $val){
			$res[$val] = '';
			$settings[$key] = "'".mysql_real_escape_string($val)."'";
		}
		
		$e = mysql_query("SELECT * FROM settings WHERE title IN (".implode(",",$settings).")") or die(mysql_error());
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_assoc($e)){
				extract($s);	
				if (isset($value[0]) && $value[0]=="{"){
		   			if (!$get_array){
						$res[$title] = json_decode($value);
		   			} else {
		   				$res[$title] = json_decode($value,true);
		   			}
				} else {
					$res[$title] = $value;
				}
			}
		}
		
		return $res;
	}
   
   	public function getSetting($title,$get_array=false){
     	$setting = array();
	 	$title = mysql_real_escape_string($title);
	 	$e = mysql_query("SELECT * FROM settings WHERE title='$title'") or die(mysql_error());
	 	if (mysql_num_rows($e)){
	   		extract(mysql_fetch_array($e));
	   		if (isset($value[0]) && $value[0]=="{"){
	   			if (!$get_array){
					$setting = json_decode($value);
	   			} else {
	   				$setting = json_decode($value,true);
	   			}
			} else {
				$setting = $value;
			}
	 	}
	 
	 	return $setting;
   	}
   
   	public function addSetting($title,$set){
    	$title = mysql_real_escape_string($title);
	 	$set = mysql_real_escape_string($set);
	 	$e = mysql_query("SELECT id FROM settings WHERE title = '$title'") or die(mysql_error());
	 	if (mysql_num_rows($e)==0){
			$e = mysql_query("INSERT INTO settings(title,value) VALUES('$title','$set')") or die(mysql_error());
	 	} else {
			$e = mysql_query("UPDATE settings SET value='$set' WHERE title='$title'") or die(mysql_error());
	 	}	
   	}
   
   	public function deleteSetting($title){
    	$title = mysql_real_escape_string($title);
	 	$e = mysql_query("DELETE FROM settings WHERE title='$title'") or die(mysql_error());
   	}

}
?>