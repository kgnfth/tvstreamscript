<?php

class Updater{
    
    public $api_path = "http://tvstreamscript.com/updater/";
    public $supported_plugins = array("autoembeds" => 1, "moviefeed" => 2);
    public $plugin_keys = array("autoembeds" => "auto_license_key", "moviefeed" => "moviefeed_license_key");
    
    function __construct(){

    }

    public function validateFtp($params){
        $errors = array();

        if (!isset($params['host']) || !$params['host']){
            $errors[1] = "Please enter the FTP host address";
        }

        if (!isset($params['user']) || !$params['user']){
            $errors[2] = "Please enter the FTP username";
        }

        if (!isset($params['pass']) || !$params['pass']){
            $errors[3] = "Please enter the FTP password";
        }

        if (!isset($params['port']) || !$params['port']){
            $errors[4] = "Please enter the FTP port";
        } elseif (!is_numeric($params['port'])){
            $errors[4] = "FTP port must be numeric";
        }

        if (!count($errors)){
            $ftp_conn = ftp_connect($params['host'],$params['port'],5);
            if (!$ftp_conn){
                $errors[1] = "Can't connect to this host";
            } else {
                if (!@ftp_login($ftp_conn, $params['user'], $params['pass'])){
                    $errors[2] = "Can't authentciate with these details";
                }
            }
        }

        return $errors;
    }
    
    public function printReleaseNotes($notes){
        print(" <div class=\"row-fluid\">
                    <div class=\"span12\">
                        <form action=\"index.php\" method=\"post\" class=\"form-horizontal well\">
                            <fieldset>");
        
        foreach($notes as $version => $version_notes){
            print("<strong>Version $version</strong><br /><br />");
            print("<ul>");
            
            foreach($version_notes as $key => $note){
                print("<li>$note</li>");                
            }
            
            print("</ul>");
        }
        
        print("<div class=\"clearfix\"></div><br />");
        print("<input type=\"hidden\" name=\"menu\" value=\"update\" />");
        print("<input type=\"hidden\" name=\"start_upgrade\" value=\"1\" />");
        print("<input type=\"submit\" name=\"do_start_upgrade\" class=\"btn btn-primary\" value=\"Apply upgrades\" />");
        
        print("             </fieldset>
                        </form>
                    </div>
                </div>");
    }

    public function upgradeLog($text, $result, $result_value){
        print(" <div class=\"row-fluid\" style=\"margin-top:5px;\">
                    <div class=\"span4\">$text</div>
                    <div class=\"span8\">");
        if ($result){
            print("<span class=\"label label-success\">$result_value</span>");
        } else {
            print("<span class=\"label label-important\">$result_value</span>");
        }
        print("     </div>
                </div>");

        @ob_flush();
        @flush();
    }

    public function ftp_is_dir($ftp_connect, $dir) {
        if (ftp_chdir($ftp_connect, basename($dir))) {
            ftp_chdir($ftp_connect, '..');
            return true;
        } else {
            return false;
        }
    }
    
    public function getLatestVersion($curl){
        global $license_key;
        
        $version = $curl->get($this->api_path."?method=getVersion&key=".$license_key);
        if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
            $version = json_decode($version,true);
            if (!isset($version['version'])){
                return false;
            } else {
                return $version['version'];                 
            }
        } else {
            return false;
        }
    }
    
    public function parseDir($path){
        $files = array();
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if(is_dir($path.'/'.$file)) {
                        $sub_res = $this->parseDir($path."/".$file);
                        foreach($sub_res as $key => $val){
                            $files[$key] = $val;
                        }
                    } else {
                        $files[$path."/".$file] = filesize($path."/".$file);
                    }
                }
            }
        }
    
        return $files;
    }
    
    public function createFtpPath($ftp_conn,$path){
        if (@ftp_chdir($ftp_conn,"/")){
            $path = explode("/",$path);
            foreach($path as $key => $current_folder){
                if ($current_folder){
                    if (!@ftp_chdir($ftp_conn,$current_folder)){
                        if (!@ftp_mkdir($ftp_conn,$current_folder)){
                            return false;
                        }
                    }
                }
            }
            
            return true;
        } else {
            return false;
        }
    }
    
    public function recursive_remove_directory($directory, $empty=false){
        if(substr($directory,-1) == '/'){
            $directory = substr($directory,0,-1);
        }
    
        if (!file_exists($directory) || !is_dir($directory)){
            return false;
        } elseif (!is_readable($directory)) {
            return false;
        } else {
            $handle = opendir($directory);

            while (false !== ($item = readdir($handle))){
                if($item != '.' && $item != '..'){
                    $path = $directory.'/'.$item;
                    
                    if (is_dir($path)){
                        $this->recursive_remove_directory($path);
                    } else {
                        unlink($path);
                    }
                }
            }

            closedir($handle);

            if($empty == false) {
                if(!rmdir($directory)) {
                    return false;
                }
            }
            return true;
        }
    }
    
    public function updateFile($ftp_conn, $root_path, $file_content, $file_path, $file_name){
        global $basepath, $license_key;

        // checking if ftp path exists
        
        $path_exists = false;
        if (@ftp_chdir($ftp_conn, $root_path."/".$file_path)){
            $path_exists = true;   
        } else {
            $path_exists = $this->createFtpPath($ftp_conn,$root_path."/".$file_path);
        }
        
        if ($path_exists){
            ftp_chdir($ftp_conn,$root_path."/".$file_path);
            
            file_put_contents($basepath."/cachefiles/tmp_upgrade_file",$file_content);
            if (file_exists($basepath."/cachefiles/tmp_upgrade_file")){
                if (ftp_put($ftp_conn,$root_path."/".$file_path."/".$file_name, $basepath."/cachefiles/tmp_upgrade_file",FTP_BINARY)){
                    unlink($basepath."/cachefiles/tmp_upgrade_file");
                    return 1;                          
                } else {
                    unlink($basepath."/cachefiles/tmp_upgrade_file");
                    return 3;
                }
            } else {
                return 3;
            }                
        } else {
            return 2;
        }
 
    }
    
    public function validateLicense($curl, $product_id = 1, $addon_id = 0, $license_key){
        $request = $this->api_path."?method=validateLicense&product_id=".$product_id."&addon_id=".$addon_id."&license_key=".$license_key;
        
        $result = $curl->get($request);
        if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
            $result = json_decode($result, true);
            if (isset($result['result']) && $result['result'] == "valid"){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    public function getSupportedPlugins($allplugins){
        $res = array();
        
        foreach($allplugins as $key => $val){
            if (isset($this->supported_plugins[$val['dirname']])){
                $res[$this->supported_plugins[$val['dirname']]] = array();
                $res[$this->supported_plugins[$val['dirname']]]['dirname'] = $val['dirname'];
                $res[$this->supported_plugins[$val['dirname']]]['name'] = $val['name'];
                $res[$this->supported_plugins[$val['dirname']]]['key_name'] = $this->plugin_keys[$val['dirname']]; 
            }    
        }
        
        return $res;
    }
}

?>