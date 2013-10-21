<?php
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (isset($activate_theme) && $activate_theme && $theme){
    $set = array();
    $set['theme']=$theme;
    $set = json_encode($set);
    $settings->addSetting("theme",$set);
    
    // clearing the cache
    $path = $basepath."/cachefiles";
    $dir_handle = @opendir($path);
    if ($dir_handle){
        while ($file = readdir($dir_handle)) { 

            if($file == "." || $file == ".." || $file == "smarty" || $file == '.svn' ){
                continue; 
            } else {
                if (file_exists($path."/".$file)){
                    unlink($path."/".$file);
                }
            }
        }
        closedir($dir_handle);
    }
    
    $_SESSION['theme']=$theme;
}

$current_theme = $settings->getSetting("theme");

if (count($current_theme)){
    $current_theme = $current_theme->theme;
} else {
    $current_theme = '';
}

?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                Manage templates
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
    
        <div class="alert alert-info">
            <a class="close" data-dismiss="alert">×</a>
            Below you can find all the available Templates for TVstreamScript. You can install them simply by hitting the "Activate" button next to each theme
        </div>
    
        <table class="table table-striped table-bordered">
        <?php
            $path = $basepath."/templates/";
            $dir_handle = @opendir($path) or die("Unable to open $path");
            while ($file = readdir($dir_handle)) { 
    
                if($file == "." || $file == ".." || $file == "smarty" || $file == '.svn' ){
                    continue; 
                } else {
                    $descriptor = @file_get_contents($path."/".$file."/descriptor.txt","r");
                    if ($descriptor){
                        $descriptor = str_replace("\r\n","\n",$descriptor);
                        $descriptor = str_replace("\n\r","\n",$descriptor);
                        $lines = explode("\n",$descriptor);
                        if (count($lines)){
                            $thumbnail = '';
                            $description = '';
                            $credits = '';
                            $title = '';
                            foreach($lines as $key=>$val){
                                if (substr_count($val,"thumbnail:")){
                                    $tmp = explode("thumbnail:",$val);
                                    $thumbnail = trim($tmp[1]);
                                }
                                
                                if (substr_count($val,"title:")){
                                    $tmp = explode("title:",$val);
                                    $title = trim($tmp[1]);
                                }
                                
                                if (substr_count($val,"description:")){
                                    $tmp = explode("description:",$val);
                                    $description = trim($tmp[1]);
                                }
                                
                                if (substr_count($val,"credits:")){
                                    $tmp = explode("credits:",$val);
                                    $credits = trim($tmp[1]);
                                }
                            }
                            
                            if ($thumbnail && $title && $description){
                                print("<tr>
                                         <td width=\"20%\"><img src=\"$baseurl/templates/$file/$thumbnail\" style=\"width:250px;\" /></td>
                                         <td width=\"80%\" style=\"vertical-align:top;padding-top: 15px;\">
                                            <h3 class=\"heading\">$title</h3>
                                            $description<br /><br />");
                                if ($credits) print("<strong>Credits: </strong> $credits<br /><br />");
                                
                                if ($current_theme==$file){
                                    print("<strong style=\"color:#00aa00\">This theme is currently active</strong>");
                                } else {                            
                                    print("<form action=\"index.php\" method=\"post\">
                                                <input type=\"hidden\" name=\"menu\" value='themes' />
                                                <input type=\"hidden\" name=\"theme\" value='$file' />
                                                <input type=\"submit\" name=\"activate_theme\" value=\"Activate theme\" class=\"btn btn-primary\" />
                                            </form>");
                                }
                                
                                print("    </td>
                                       </tr>");
                            }
                            
                        }
                    }
                }
    
            } 
            closedir($dir_handle);
        ?>
        </table>
    </div>
</div>