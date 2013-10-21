<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

require_once("../includes/pclzip.lib.php");

$updater = new Updater();
$curl = new Curl();
$curl->header(true);

if (isset($_POST['save_ftp']) && isset($_POST['ftp'])){
    $errors = $updater->validateFtp($_POST['ftp']);
    if (!count($errors)){
        $settings->addSetting("ftp",json_encode($ftp));
        $save_success = true;
    }
}

$ftp = $settings->getSetting("ftp",true);


?>
<script src="<?php print($baseurl); ?>/plugins/uploader/js/scripts.js"></script>
<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                <a href="index.php?menu=plugins">Plugins</a>
            </li>
            <li>
                Updater
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">

        <?php if (isset($save_success) && $save_success){ ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                Your FTP details are saved successfully
            </div>
        <?php } ?>

        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>
                <p class="f_legend">Your FTP details</p>

                <div class="control-group<?php if (isset($errors[1])){ print(" error"); } ?>">
                    <label class="control-label">FTP host</label>
                    <div class="controls">
                        <input type="text" name="ftp[host]" id="ftp[host]" value="<?php if (isset($ftp['host'])) print($ftp['host']); ?>" class="span8" />
                        <?php if (isset($errors[1])){ ?>
                            <span class="help-inline"><?php print($errors[1]); ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="control-group<?php if (isset($errors[2])){ print(" error"); } ?>">
                    <label class="control-label">FTP username</label>
                    <div class="controls">
                        <input type="text" name="ftp[user]" id="ftp[user]" value="<?php if (isset($ftp['user'])) print($ftp['user']); ?>" class="span8" />
                        <?php if (isset($errors[2])){ ?>
                            <span class="help-inline"><?php print($errors[2]); ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="control-group<?php if (isset($errors[3])){ print(" error"); } ?>">
                    <label class="control-label">FTP password</label>
                    <div class="controls">
                        <input type="password" name="ftp[pass]" id="ftp[pass]" value="<?php if (isset($ftp['pass'])) print($ftp['pass']); ?>" class="span8" />
                        <?php if (isset($errors[3])){ ?>
                            <span class="help-inline"><?php print($errors[3]); ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="control-group<?php if (isset($errors[4])){ print(" error"); } ?>">
                    <label class="control-label">FTP port</label>
                    <div class="controls">
                        <input type="text" name="ftp[port]" id="ftp[port]" value="<?php if (isset($ftp['port'])){ print($ftp['port']); } else { print("21"); }?>" class="span2" />
                        <?php if (isset($errors[4])){ ?>
                            <span class="help-inline"><?php print($errors[4]); ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                        <input type="hidden" name="menu" value="update" />

                        <input type="submit" value="Save FTP details" name="save_ftp" class="btn btn-primary" />
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</div>

<?php

$ftp = $settings->getSetting("ftp",true);

?>

<div class="row-fluid">
    <div class="span12">
        <form action="index.php" method="post" class="form-horizontal well">
            <fieldset>
                <p class="f_legend">Start your upgrade</p>
                <?php if (empty($ftp)){ ?>
                    <div class="alert alert-error">
                        <a class="close" data-dismiss="alert">×</a>
                        Please enter your FTP details above. The upgrade script will use FTP to compare each file on your server against the central database.
                    </div>
                <?php } else { ?>
                    <div class="alert alert-info">
                        <a class="close" data-dismiss="alert">×</a>
                        Please make sure you make a backup of both your files and database before running the upgrade script. We did our best to make this process as secure and bulletproof as possible but you can never know. 
                    </div>

                    <div class="control-group">
                        <label class="control-label">Relative path for the script's public_html</label>
                        <div class="controls">
                            <input type="text" name="root_path" id="root_path" value="<?php if (isset($_POST['root_path'])){ print($_POST['root_path']); } else { print(""); }?>" class="span8" />
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">&nbsp;</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox" name="no_template" id="no_template" value="<?php if (isset($_POST['no_template'])){ print("checked='checked'"); } else { print(""); }?>" /> 
                                &nbsp;&nbsp;Do NOT update language files
                            </label>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label">&nbsp;</label>
                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox" name="no_language" id="no_language" value="<?php if (isset($_POST['no_language'])){ print("checked='checked'"); } else { print(""); }?>" /> 
                                &nbsp;&nbsp;Do NOT update language files
                            </label>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">&nbsp;</label>
                        <div class="controls">
                            <input type="hidden" name="menu" value="update" />

                            <input type="submit" value="Check for updates" name="start_upgrade" class="btn btn-primary" />
                        </div>
                    </div>
                <?php } ?>
            </fieldset>
        </form>
    </div>
</div>

<?php
if (isset($_POST['start_upgrade'])){
?>

<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <?php if (!isset($_POST['do_start_upgrade'])){ ?>
                <h3 class="heading">Release notes</h3>
            <?php } else { ?>
                <h3 class="heading">Upgrade running...</h3>
            <?php } ?>
        </div>

        <div class="row-fluid" style="margin-top:0px;">
            <?php
                $allplugins = $plugins->getAllPlugins();
                $plugin_names = array();
                foreach($allplugins as $key => $val){
                    $plugin_names[] = $val['dirname'];   
                }
                                
                $current_version = $settings->getSetting("version", false);
                if (empty($current_version)){
                    $current_version = "2.0";   
                }
            
                $error = false;
                $should_upgrade = true;
                
                if (!$error){
                    $version = $curl->get($updater->api_path."?method=getVersion&key=".$license_key);
                    if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                        $version = json_decode($version,true);
                        if (!isset($version['version'])){
                            $latest_version = $current_version;
                        } else {
                            $latest_version = $version['version']; 
                        }                    
                    } else {
                        $updater->upgradeLog("Checking latest version...",false,"API down, try again later");
                        $error = true;   
                    }
                }
                
                if (!$error){
                    if ($latest_version <= $current_version){
                        $should_upgrade = false;
                        $updater->upgradeLog("Your installation is up to date",true,"Version: ".$current_version);
                    } elseif (!isset($_POST['do_start_upgrade'])) {
                        $release_notes = $curl->get($updater->api_path."?method=getReleaseNotes&key=".$license_key."&current_version=".$current_version);
                        if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                            $release_notes = json_decode($release_notes,true);
                            if (isset($release_notes['error'])){
                                $updater->upgradeLog("Getting release notes...",false,"API down, try again later #1");    
                            } else {
                                if (!count($release_notes)){
                                    $release_notes[$latest_version] = array("No release notes found for this version");   
                                }
                                
                                $updater->printReleaseNotes($release_notes);
                            }
                        } else {
                            $updater->upgradeLog("Getting release notes...",false,"API down, try again later #2");
                        }
                    }
                }
                
                if ($should_upgrade && isset($_POST['do_start_upgrade'])){
                    if (!$error){
                        if (!is_writeable($basepath."/cachefiles")){
                            $updater->upgradeLog("Checking if cache folder is writable...",false,"Fail. Please chmod /cachefiles");
                            $error = true;
                        } else {
                            $updater->upgradeLog("Checking if cache folder is writable...",true,"Success");
                        }
                    }
                    
                                    
                    if (!$error){
                        if ($ftp_conn = ftp_connect($ftp['host'], $ftp['port'])){
                            $updater->upgradeLog("Connecting to ftp...",true,"Success");
                        } else {
                            $updater->upgradeLog("Connecting to ftp...",false,"Error: Can't connect");
                            $error = true;
                        }
                    }
                    
                    if (!$error){
                        if (ftp_login($ftp_conn, $ftp['user'], $ftp['pass'])){
                            $updater->upgradeLog("Authenticating...",true,"Success");
                        } else {
                            $updater->upgradeLog("Authenticating...",false,"Error: Can't login");
                            $error = true;
                        }
                    }
                    
                    if (!$error){
                        if (!isset($_POST['root_path'])){
                            $_POST['root_path'] = "";
                        } elseif ($_POST['root_path']){
                            if ($_POST['root_path'][strlen($_POST['root_path'])-1] == "/"){
                                $_POST['root_path'] = substr($_POST['root_path'],0,strlen($_POST['root_path'])-1);   
                            }                        
                        }
                        
                        if (!$_POST['root_path'] || @ftp_chdir($ftp_conn, $_POST['root_path'])){
                            $updater->upgradeLog("Changing directory...",true,"Success");
                        } else {
                            $updater->upgradeLog("Changing directory...",false,"Directory doesn't exist");
                            $error = true;
                        }
                    }
                    
                    if (!$error){
                        $tmp_handle = fopen($basepath."/cachefiles/upgrade_check","w+");                        
                        $valid = false;
                        
                        /* finding public_html */
                        if (@ftp_fget($ftp_conn, $tmp_handle, "vars.php", FTP_BINARY)){
                            $valid = true;   
                        } else {
                            if (@ftp_chdir($ftp_conn, "public_html")){
                                if (@ftp_fget($ftp_conn, $tmp_handle, "vars.php", FTP_BINARY)){
                                    if ($_POST['root_path']){
                                        $_POST['root_path'].="/public_html";
                                    } else {
                                        $_POST['root_path'] = "public_html";
                                    }
                                    $valid = true;
                                }
                            }
                        }
                        
                        $root_path = $_POST['root_path'];
                        if ($root_path[0]!="/"){
                            $root_path = "/".$root_path;   
                        }
                        
                        fclose($tmp_handle);
                        unlink($basepath."/cachefiles/upgrade_check");
    
                        if ($valid){
                            $updater->upgradeLog("Validating installation...",true,"Success");
                        } else {
                            $updater->upgradeLog("Validating installation...",false,"Invalid root path");
                            $error = true;
                        }
                    }
                    
                    if (!$error){
                        $release_files = $curl->get($updater->api_path."?method=getFileList&key=".$license_key."&product=1");
                        if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                            $release_files = json_decode($release_files,true);
                            
                            if (!isset($release_files[$latest_version])){
                                $updater->upgradeLog("Getting release information...",false,"API down, please try again later");
                                $error = true;        
                            } else {
                                $updater->upgradeLog("Getting release information...",true,"Success");
                            }
                        } else {
                            $updater->upgradeLog("Getting release information...",false,"API down, please try again later");
                            $error = true;
                        }
                    }
                    
                    if (!$error){
                        $release_files = $release_files[$latest_version];
                        $release_file = array_pop($release_files);
                        
                        $file_content = $curl->get($updater->api_path."?method=getFile&file_id=".$release_file['id']."&key=".$license_key);
                        if ($file_content && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                            file_put_contents($basepath."/cachefiles/update.zip", $file_content);
                            $updater->upgradeLog("Downloading package...",true, "Success");
                        } else {
                            $updater->upgradeLog("Downloading package...",false,"API down, please try again later");
                            $error = true;
                        }
                    }
                    
                    if (!$error){
                        @mkdir($basepath."/cachefiles/update");
                        try{
                            $archive = new PclZip($basepath."/cachefiles/update.zip");
                            $archive->extract($basepath."/cachefiles/update");
                            $updater->upgradeLog("Extracting package content...",true, "Success");
                        } catch(Exception $e){
                            $error = true;
                            $updater->upgradeLog("Extracting package content...",false, "Unknown error");
                        }
                    }
                    
                    if (!$error){
                        unlink($basepath."/cachefiles/update.zip");
                        
                        $new_files = $updater->parseDir($basepath."/cachefiles/update/public_html");
                        if (count($new_files)){
                            foreach($new_files as $full_path => $file_size){
                                if (substr_count($full_path,"vars.php") == 0 && (!isset($_POST['no_language']) || !$_POST['no_language'] || !substr_count($full_path,"/language/")) && (!isset($_POST['no_template']) || !$_POST['no_template'] || !substr_count($full_path,"/templates/"))){
                                    
                                    $sub_path = str_replace($basepath."/cachefiles/update/public_html", "", $full_path);
                                    
                                    $tmp = explode("/", $sub_path);
                                    $file_name = $tmp[count($tmp)-1];
                                    unset($tmp[count($tmp)-1]);
                                    $file_path = implode("/",$tmp);
                                    
                                    if (!file_exists($basepath.$sub_path)){
                                        
                                        $file_content = file_get_contents($full_path);
                                        $res = $updater->updateFile($ftp_conn, $root_path, $file_content, $file_path, $file_name);
                                        if ($res == 1){
                                            $updater->upgradeLog("New file: $sub_path", true, "Added");    
                                        } elseif ($res == 2){
                                            $updater->upgradeLog("New file: $sub_path", false, "Can't create folder");
                                        } else {
                                            $updater->upgradeLog("New file: $sub_path", false, "Unexpected error");
                                        }
                                        
                                        
                                    } else {
                                        
                                        $new_md5 = md5(file_get_contents($full_path));
                                        $old_md5 = md5(file_get_contents($basepath.$sub_path));
                                        if ($new_md5 != $old_md5){
                                            $file_content = file_get_contents($full_path);
                                            $res = $updater->updateFile($ftp_conn, $root_path, $file_content, $file_path, $file_name);
                                            if ($res == 1){
                                                $updater->upgradeLog("File changed: $sub_path", true, "Added");    
                                            } elseif ($res == 2){
                                                $error = true;
                                                $updater->upgradeLog("File changed: $sub_path", false, "Can't create folder");
                                            } else {
                                                $error = true;
                                                $updater->upgradeLog("File changed: $sub_path", false, "Unexpected error");
                                            }
                                        }
                                        
                                    }
                                }
                            }
                            
                            // cleaning up
                            $updater->recursive_remove_directory($basepath."/cachefiles/update");
                            
                        } else {
                            $updater->upgradeLog("Analyzing files...",false, "Can't open downloaded content");
                        }
                    }
                    
                    if (!$error){
                        $updater->upgradeLog("Updating files finished",true,"Success");
                        
                        $queries = $curl->get($updater->api_path."?method=getQueries&key=".$license_key."&current_version=".$current_version);
                        if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                            $queries = json_decode($queries, true);
                            if (isset($queries['error'])){
                                $updater->upgradeLog("Getting SQL updates...",false,"API down, try again later #2");    
                            } else {
                                
                                if (count($queries)){
                                    foreach($queries as $version => $version_queries){
                                        foreach($version_queries as $qkey => $query){
                                            $res = mysql_query($query) or die($updater->upgradeLog("Error executing query ($query)",true,"SQL error"));
                                        }
                                    }
                                }
                                $updater->upgradeLog("Applying SQL updates...",true,"Success"); 
                            }
                        } else {
                            $updater->upgradeLog("Getting SQL updates...",false,"API down, try again later #1");    
                        }
                    } else {
                        $updater->upgradeLog("Updating files finished, but with errors",false,"Error updating some files");
                    }
                    
                    
                    
                    if (!$error){
                        $updater->upgradeLog("The main site has been updated",true,"Success");
                        
                        // plugins
                        $allplugins = $plugins->getAllPlugins();
                        $plugins_to_update = $updater->getSupportedPlugins($allplugins);
                        
                        if (count($plugins_to_update)){
                            foreach($plugins_to_update as $addon_id => $plugin_data){
                                $error = false;
                                
                                // get license
                                $plugin_license_key = $settings->getSetting($plugin_data['key_name'], false);
                                if (!$plugin_license_key){
                                    $updater->upgradeLog("Validating {$plugin_data['name']} plugin...",false,"No license key");
                                    $error = true;
                                }
                                
                                if (!$error){
                                    // check license
                                    if (!$updater->validateLicense($curl, 0, $addon_id, $plugin_license_key)){
                                        $updater->upgradeLog("Validating {$plugin_data['name']} plugin...",false,"Invalid license key");
                                        $error = true;
                                    } else {
                                        $updater->upgradeLog("Validating {$plugin_data['name']} plugin...",true,"Success");
                                    }
                                }
                                
                                if (!$error){
                                    $release_files = $curl->get($updater->api_path."?method=getFileList&key=".$license_key."&addon_id=".$addon_id);
                                    
                                    if ($curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                                        $release_files = json_decode($release_files,true);
                                        
                                        if (!isset($release_files[$latest_version]) || !count($release_files[$latest_version])){
                                            $updater->upgradeLog("Getting plugin file list ({$plugin_data['name']})...",false,"No new files");
                                            $error = true;        
                                        } else {
                                            $updater->upgradeLog("Getting plugin file list ({$plugin_data['name']})...",true,"Success");
                                        }
                                    } else {
                                        $updater->upgradeLog("Getting plugin file list ({$plugin_data['name']})...",false,"API down, please try again later");
                                        $error = true;
                                    }
                                }
                                
                                if (!$error){
                                    $release_files = $release_files[$latest_version];
                                    $release_file = array_pop($release_files);
                                    
                                    $file_content = $curl->get($updater->api_path."?method=getFile&file_id=".$release_file['id']."&key=".$license_key);
                                    if ($file_content && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
                                        file_put_contents($basepath."/cachefiles/update.zip", $file_content);
                                        $updater->upgradeLog("Downloading package ({$plugin_data['name']})...",true, "Success");
                                    } else {
                                        $updater->upgradeLog("Downloading package ({$plugin_data['name']})...",false,"API down, please try again later");
                                        $error = true;
                                    }
                                }
                                
                                if (!$error){
                                    @mkdir($basepath."/cachefiles/update");
                                    try{
                                        $archive = new PclZip($basepath."/cachefiles/update.zip");
                                        $archive->extract($basepath."/cachefiles/update");
                                        $updater->upgradeLog("Extracting package content ({$plugin_data['name']})...",true, "Success");
                                    } catch(Exception $e){
                                        $updater->upgradeLog("Extracting package content ({$plugin_data['name']})...",false, "Unknown error");
                                        $error = true;
                                    }
                                }
                                
                                if (!$error){
                                    unlink($basepath."/cachefiles/update.zip");
                                    
                                    $new_files = $updater->parseDir($basepath."/cachefiles/update/public_html");
                                    if (count($new_files)){
                                        foreach($new_files as $full_path => $file_size){
                                            if (substr_count($full_path,"vars.php") == 0 && (!isset($_POST['no_language']) || !$_POST['no_language'] || !substr_count($full_path,"/language/")) && (!isset($_POST['no_template']) || !$_POST['no_template'] || !substr_count($full_path,"/templates/"))){
                                                
                                                $sub_path = str_replace($basepath."/cachefiles/update/public_html", "", $full_path);
                                                
                                                $tmp = explode("/", $sub_path);
                                                $file_name = $tmp[count($tmp)-1];
                                                unset($tmp[count($tmp)-1]);
                                                $file_path = implode("/",$tmp);
                                                
                                                if (!file_exists($basepath.$sub_path)){
                                                    
                                                    $file_content = file_get_contents($full_path);
                                                    $res = $updater->updateFile($ftp_conn, $root_path, $file_content, $file_path, $file_name);
                                                    if ($res == 1){
                                                        $updater->upgradeLog("New file ({$plugin_data['name']}): $sub_path", true, "Added");    
                                                    } elseif ($res == 2){
                                                        $updater->upgradeLog("New file ({$plugin_data['name']}): $sub_path", false, "Can't create folder");
                                                    } else {
                                                        $updater->upgradeLog("New file ({$plugin_data['name']}): $sub_path", false, "Unexpected error");
                                                    }
                                                    
                                                    
                                                } else {
                                                    
                                                    $new_md5 = md5(file_get_contents($full_path));
                                                    $old_md5 = md5(file_get_contents($basepath.$sub_path));
                                                    if ($new_md5 != $old_md5){
                                                        $file_content = file_get_contents($full_path);
                                                        $res = $updater->updateFile($ftp_conn, $root_path, $file_content, $file_path, $file_name);
                                                        if ($res == 1){
                                                            $updater->upgradeLog("File changed ({$plugin_data['name']}): $sub_path", true, "Added");    
                                                        } elseif ($res == 2){
                                                            $error = true;
                                                            $updater->upgradeLog("File changed ({$plugin_data['name']}): $sub_path", false, "Can't create folder");
                                                        } else {
                                                            $error = true;
                                                            $updater->upgradeLog("File changed ({$plugin_data['name']}): $sub_path", false, "Unexpected error");
                                                        }
                                                    }
                                                    
                                                }
                                            }
                                        }
                                        
                                        // cleaning up
                                        $updater->recursive_remove_directory($basepath."/cachefiles/update");
                                        
                                    } else {
                                        $updater->upgradeLog("Analyzing files ({$plugin_data['name']})...",false, "Can't open downloaded content");
                                    }
                                }
                            }
                        }
                        $updater->upgradeLog("All done",true,"Success");
                        $settings->addSetting("version",$latest_version);
                    }
                }
            ?>
        </div>
    </div>
</div>
<?php
}
?>
