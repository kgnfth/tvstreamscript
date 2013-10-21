<?php

class Plugins{

    var $basepath = null;
    
    function __construct(){
        global $basepath;
        $this->basepath = $basepath;
    }
    
    public function getFrontendMenu($activeplugins){
        $path = $this->basepath."/plugins";
        
        $dir_handle = @opendir($path);
        
        $menus = array();
        
        if (@$dir_handle){
            while ($file = readdir($dir_handle)) { 

				if($file == "." || $file == ".."){
					continue; 
				} else {
					if (file_exists($this->basepath."/plugins/".$file."/descriptor.xml") && isset($activeplugins[$file])){
						$content = file_get_contents($this->basepath."/plugins/".$file."/descriptor.xml");
						$dom = new DOMDocument();
						@$dom->loadXML($content);
						
						$plugin_name = $file;
						
						$paths = $dom->getElementsByTagName('paths');
						if ($paths->length){
							$backend = $paths->item(0)->getElementsByTagName('frontEnd');
							if ($backend->length){
								$paths = $backend->item(0)->getElementsByTagName('path');
								if ($paths->length){
									for($i=0; $i<$paths->length; $i++){
										$main_menu_url = $paths->item($i)->getElementsByTagName('mainMenuUrl');
										if ($main_menu_url->length){
											$main_menu_url = $main_menu_url->item(0)->nodeValue;
											
											$main_menu_title = $paths->item($i)->getElementsByTagName('mainMenuTitle');
											if ($main_menu_title->length){
												$main_menu_title = $main_menu_title->item(0)->nodeValue;
											} else {
												$main_menu_title = false;
											}
											
											$main_menu_icon = $paths->item($i)->getElementsByTagName('mainMenuIcon');
											if ($main_menu_icon->length){
												$main_menu_icon = $main_menu_icon->item(0)->nodeValue;
											} else {
												$main_menu_icon = "icon-magnet";
											}
											
											$sub_menu_title = $paths->item($i)->getElementsByTagName('subMenuTitle');
											if ($sub_menu_title->length){
												$sub_menu_title = $sub_menu_title->item(0)->nodeValue;
											} else {
												$sub_menu_title = false;
											}
											
											$sub_menu_url = $paths->item($i)->getElementsByTagName('subMenuUrl');
											if ($sub_menu_url->length){
												$sub_menu_url = $sub_menu_url->item(0)->nodeValue;
											} else {
												$sub_menu_url = false;
											}
											
											if (!isset($menus[$main_menu_url])){
												$menus[$main_menu_url] = array();
											}
											
											$full_url = false;
											if (isset($sub_menu_url[0]) && $sub_menu_url[0] == "/"){
												$full_url = $sub_menu_url;
											} else {
												$full_url = "/plugin/".$plugin_name."/".$sub_menu_url;
											}
											
											$menus[$main_menu_url][] = array("main_menu_title" => $main_menu_title, "sub_menu_title" => $sub_menu_title, "sub_menu_url" => $sub_menu_url, "plugin" => $plugin_name, "icon" => $main_menu_icon, "full_url" => $full_url);
										}
									}
								}
							}
						}			
						
					}
				}
			}
			closedir($dir_handle);
		}		
		return $menus;
	}	
	
	public function getBackendMenu(){
		$path = $this->basepath."/plugins";
		
		$dir_handle = @opendir($path);
		
		$menus = array();
		
		if (@$dir_handle){
			while ($file = readdir($dir_handle)) { 
                if($file == "." || $file == ".."){
                    continue; 
                } else {
                    if (file_exists($this->basepath."/plugins/".$file."/descriptor.xml")){
                        $content = file_get_contents($this->basepath."/plugins/".$file."/descriptor.xml");
                        $dom = new DOMDocument();
                        @$dom->loadXML($content);
                        
                        $plugin_name = $file;
                        
                        $paths = $dom->getElementsByTagName('paths');
                        if ($paths->length){
                            $backend = $paths->item(0)->getElementsByTagName('backEnd');
                            if ($backend->length){
                                $paths = $backend->item(0)->getElementsByTagName('path');
                                if ($paths->length){
                                    for($i=0; $i<$paths->length; $i++){
                                        $main_menu_url = $paths->item($i)->getElementsByTagName('mainMenuUrl');
                                        if ($main_menu_url->length){
                                            $main_menu_url = $main_menu_url->item(0)->nodeValue;
                                            
                                            $main_menu_title = $paths->item($i)->getElementsByTagName('mainMenuTitle');
                                            if ($main_menu_title->length){
                                                $main_menu_title = $main_menu_title->item(0)->nodeValue;
                                            } else {
                                                $main_menu_title = false;
                                            }
                                            
                                            $main_menu_icon = $paths->item($i)->getElementsByTagName('mainMenuIcon');
                                            if ($main_menu_icon->length){
                                                $main_menu_icon = $main_menu_icon->item(0)->nodeValue;
                                            } else {
                                                $main_menu_icon = "icon-magnet";
                                            }
                                            
                                            $sub_menu_title = $paths->item($i)->getElementsByTagName('subMenuTitle');
                                            if ($sub_menu_title->length){
                                                $sub_menu_title = $sub_menu_title->item(0)->nodeValue;
                                            } else {
                                                $sub_menu_title = false;
                                            }
                                            
                                            $sub_menu_url = $paths->item($i)->getElementsByTagName('subMenuUrl');
                                            if ($sub_menu_url->length){
                                                $sub_menu_url = $sub_menu_url->item(0)->nodeValue;
                                            } else {
                                                $sub_menu_url = false;
                                            }
                                            
                                            if (!isset($menus[$main_menu_url])){
                                                $menus[$main_menu_url] = array();
                                            }
                                            
                                            
                                            $menus[$main_menu_url][] = array("main_menu_title" => $main_menu_title, "sub_menu_title" => $sub_menu_title, "sub_menu_url" => $sub_menu_url, "plugin" => $plugin_name, "icon" => $main_menu_icon);
                                        }
                                    }
                                }
                            }
                        }            
                        
                    }
                }
            }
            closedir($dir_handle);
        }        
        return $menus;
    }
    
    public function getAllPlugins(){
        // clearing the cache
        $path = $this->basepath."/plugins";
        $dir_handle = @opendir($path);
        
        $plugs = array();
        $cntr = 0;
        
        if (@$dir_handle){
            while ($file = readdir($dir_handle)) { 

                if($file == "." || $file == ".."){
                    continue; 
                } else {
                    if (file_exists($this->basepath."/plugins/".$file."/descriptor.xml")){
                        $content = file_get_contents($this->basepath."/plugins/".$file."/descriptor.xml");
                        $dom = new DOMDocument();
                        @$dom->loadXML($content);
                        
                        $names = $dom->getElementsByTagName('name');
                        if ($names->length){
                            $name = $names->item(0)->nodeValue;
                        } else {
                            $name = '';
                        }
                        
                        
                        $descriptions = $dom->getElementsByTagName('description');
                        if ($descriptions->length){
                            $description = $descriptions->item(0)->nodeValue;
                        } else {
                            $description = '';
                        }
                        
                        $installUrls = $dom->getElementsByTagName('installUrl');
                        if ($installUrls->length){
                            $installUrl = $installUrls->item(0)->nodeValue;
                        } else {
                            $installUrl = '';
                        }
                        
                        $uninstallUrls = $dom->getElementsByTagName('uninstallUrl');
                        if ($uninstallUrls->length){
                            $uninstallUrl = $uninstallUrls->item(0)->nodeValue;
                        } else {
                            $uninstallUrl = '';
                        }
                        
                        $startUrls = $dom->getElementsByTagName('startUrl');
                        if ($startUrls->length){
                            $startUrl = $startUrls->item(0)->nodeValue;
                        } else {
                            $startUrl = '';
                        }
                        
                        $authors = $dom->getElementsByTagName('author');
                        if ($authors->length){
                            $author = $authors->item(0)->nodeValue;
                        } else {
                            $author = '';
                        }    
                        
                        $authorUrls = $dom->getElementsByTagName('authorUrl');
                        if ($authorUrls->length){
                            $authorUrl = $authorUrls->item(0)->nodeValue;
                        } else {
                            $authorUrl = '';
                        }                        
                        
                        if ($name){
                            $plugs[$cntr]=array();
                            $plugs[$cntr]['dirname']=$file;
                            $plugs[$cntr]['name']=$name;
                            $plugs[$cntr]['description']=$description;
                            $plugs[$cntr]['install_url']=$installUrl;
                            $plugs[$cntr]['uninstall_url']=$uninstallUrl;
                            $plugs[$cntr]['start_url']=$startUrl;
                            $plugs[$cntr]['author']=$author;
                            $plugs[$cntr]['author_url']=$authorUrl;                                                        
                            $cntr++;
                        }
                    }
                }
            }
            closedir($dir_handle);
        }
        
        return $plugs;
    }
    
    public function getInstalledPlugins(){
        $e = mysql_query("SELECT * FROM plugins") or die(mysql_error());
        $plugs = array();
        if (mysql_num_rows($e)){
            while($s=mysql_fetch_assoc($e)){
                $plugs[$s['dirname']]=$s;
            }
        }
        return $plugs;
        
    }
    
    public function deletePlugin($plug){
        $plug['dirname'] = mysql_real_escape_string($plug['dirname']);
        $e = mysql_query("DELETE FROM plugins WHERE dirname='{$plug['dirname']}'") or die(mysql_error());
    }
    
    public function activatePlugin($plug){
        $plug['dirname']=mysql_real_escape_string($plug['dirname']);
        $plug['name']=mysql_real_escape_string($plug['name']);
        $plug['description']=mysql_real_escape_string($plug['description']);
        $plug['author']=mysql_real_escape_string($plug['author']);
        $plug['install_url']=mysql_real_escape_string($plug['install_url']);
        $plug['start_url']=mysql_real_escape_string($plug['start_url']);
        $plug['author_url']=mysql_real_escape_string($plug['author_url']);
        
        $e = mysql_query("INSERT INTO plugins(dirname,name,description,author,install_url,start_url,author_url)
                                 VALUES ('{$plug['dirname']}','{$plug['name']}','{$plug['description']}','{$plug['author']}','{$plug['install_url']}','{$plug['start_url']}','{$plug['author_url']}')") or die(mysql_error());
    }
    
}

?>