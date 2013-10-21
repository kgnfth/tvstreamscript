<?php
 class Page{
	function __construct(){
	
	}
	
	function validate($params,$page_id=null){
		global $global_languages;
		
		$errors = array();
		
		foreach($global_languages as $lang_code => $lang_name){
			if (!isset($params['title'][$lang_code]) || !$params['title'][$lang_code]){
				$errors[1][$lang_code] = "Please enter the $lang_name title for this page";
			}
			
			if (!isset($params['content'][$lang_code]) || !$params['content'][$lang_code]){
				$errors[2][$lang_code] = "Please enter the $lang_name content for this page";
			}
		}
		
		if (isset($params['parent_id']) && !is_numeric($params['parent_id'])){
			$errors[3] = "Invalid parent page";
		} elseif ($params['parent_id']>0) {
			$parent_id = mysql_real_escape_string($params['parent_id']);
			$check = mysql_query("SELECT * FROM pages WHERE id='$parent_id'") or die(mysql_error());
			if (mysql_num_rows($check) == 0){
				$errors[3] = "Invalid parent page";
			}
		}

		return $errors;
	}
	
	function validatePerma($perma,$page_id = null){
		global $default_language;
		
		$perma = mysql_real_escape_string($perma);
		
		if ($page_id){
			$page_id = mysql_real_escape_string($page_id);
			$check = mysql_query("SELECT * FROM pages WHERE permalink='$perma' AND id!='$page_id'") or die(mysql_error());
		} else {
			$check = mysql_query("SELECT * FROM pages WHERE permalink='$perma'") or die(mysql_error());
		}
		
		if (mysql_num_rows($check)){
			return array(1 => array($default_language => "There is already a page with the same title"));
		}
	}
	
	function save($params,$page_id=null){
		
		$title = mysql_real_escape_string(json_encode($params['title']));
		$permalink = mysql_real_escape_string($params['permalink']);
		$content = mysql_real_escape_string(json_encode($params['content']));
		
		if (isset($params['parent_id'])){
			$parent_id = mysql_real_escape_string($params['parent_id']);
		} else {
			$parent_id = 0;
		}
		
		if (isset($params['visible']) && $params['visible']){
			$visible = 1;
		} else {
			$visible = 0;
		}
		
		if ($page_id){
			$e = mysql_query("UPDATE pages SET title='$title', permalink='$permalink', content='$content', parent_id='$parent_id', visible='$visible' WHERE id='$page_id'") or die(mysql_error());
			return $page_id;
		} else {
			$e = mysql_query("INSERT INTO pages(title,permalink,content,parent_id,visible) VALUES('$title','$permalink','$content','$parent_id','$visible')") or die(mysql_error());
			return mysql_insert_id();
		}
	}
	
	function getPagesMenu($lang){
		$pages = array();
		$e = mysql_query("SELECT * FROM pages WHERE visible=1") or die(mysql_error());
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_assoc($e)){
				$title = json_decode($s['title'],true);
				$title = $title[$lang];
				
				$content = json_decode($s['content'],true);
				$content = $content[$lang];		
				
				if ($s['parent_id'] == 0){
					if (!isset($pages[$s['id']])){
						$pages[$s['id']] = array();
						$pages[$s['id']]['children'] = array();
					}
					
					$pages[$s['id']]['title'] = $title;
					$pages[$s['id']]['content'] = $content;
					$pages[$s['id']]['permalink'] = $s['permalink'];
				} else {
					if (!isset($pages[$s['parent_id']])){
						$pages[$s['parent_id']] = array();
						$pages[$s['parent_id']]['children'] = array();
					}
					
					$pages[$s['parent_id']]['children'][$s['id']] = array();
					$pages[$s['parent_id']]['children'][$s['id']]['title'] = $title;
					$pages[$s['parent_id']]['children'][$s['id']]['content'] = $content;
					$pages[$s['parent_id']]['children'][$s['id']]['permalink'] = $s['permalink'];
				}
			}
		}
		
		return $pages;
	}
	
	function getPages($lang = null){
		$pages = array();
		$e = mysql_query("SELECT * FROM pages") or die(mysql_error());
		if (mysql_num_rows($e)){
			while($s = mysql_fetch_array($e)){
				$pages[$s['id']] = $s;
				if ($lang){
					$pages[$s['id']]['title'] = json_decode($pages[$s['id']]['title'],true);
					$pages[$s['id']]['title'] = $pages[$s['id']]['title'][$lang];
					
					$pages[$s['id']]['content'] = json_decode($pages[$s['id']]['content'],true);
					$pages[$s['id']]['content'] = $pages[$s['id']]['content'][$lang];
				} else {
					$pages[$s['id']]['title'] = json_decode($pages[$s['id']]['title'],true);
					
					$pages[$s['id']]['content'] = json_decode($pages[$s['id']]['content'],true);
				}
				
			}
		}
		return $pages;
	}
	
	function delete($page_id){
		$page_id = mysql_real_escape_string($page_id);
		
		$del = mysql_query("DELETE FROM pages WHERE id='$page_id'") or die(mysql_error());
		$upd = mysql_query("UPDATE pages SET parent_id='0' WHERE parent_id='$page_id'") or die(mysql_error());
	}
	
	function getByPerma($permalink, $lang=null){
		$page = array();
		$permalink = mysql_real_escape_string($permalink);
		
		$e = mysql_query("SELECT * FROM pages WHERE permalink='$permalink'") or die(mysql_error());
		
		if (mysql_num_rows($e)){
			
			$page = mysql_fetch_assoc($e);

			if ($lang){
				$page['title'] = json_decode($page['title'],true);
				$page['title'] = $page['title'][$lang];
				
				$page['content'] = json_decode($page['content'],true);
				$page['content'] = $page['content'][$lang];
			} else {
				$page['title'] = json_decode($page['title'],true);
				$page['content'] = json_decode($page['content'],true);
			}
		}
		
		return $page;
	}
	
	function getPage($id, $lang=null){
		$id = mysql_real_escape_string($id);
		
		$page = array();
		$e = mysql_query("SELECT * FROM pages WHERE id='$id'") or die(mysql_error());
		
		if (mysql_num_rows($e)){
			
			$page = mysql_fetch_assoc($e);

			if ($lang){
				$page['title'] = json_decode($page['title'],true);
				$page['title'] = $page['title'][$lang];
				
				$page['content'] = json_decode($page['content'],true);
				$page['content'] = $page['content'][$lang];
			} else {
				$page['title'] = json_decode($page['title'],true);
				$page['content'] = json_decode($page['content'],true);
			}
		}
		return $page;
	}
 }
?>