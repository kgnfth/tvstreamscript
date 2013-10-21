<?php 
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}
?>
<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                Configuration
            </li>
            <li>
                General settings
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <?php
        /* Edit tweaks */
        if (isset($update_tweaks) && $update_tweaks){
            if (isset($_POST['default_language']) && array_key_exists($_POST['default_language'],$global_languages)){ 
                $settings->addSetting("default_language",$_POST['default_language']);
            }
            
            if (isset($_POST['maxtvperpage']) && is_numeric($_POST['maxtvperpage'])){ 
                $settings->addSetting("maxtvperpage",$_POST['maxtvperpage']);
            }
            
            if (isset($_POST['maxmoviesperpage']) && is_numeric($_POST['maxmoviesperpage'])){
                $settings->addSetting("maxmoviesperpage",$_POST['maxmoviesperpage']);
            }
            
            if (isset($_POST['countdown_free']) && is_numeric($_POST['countdown_free'])){
                $settings->addSetting("countdown_free",$_POST['countdown_free']);
            }
            
            if (isset($_POST['countdown_user']) && is_numeric($_POST['countdown_user'])){
                $settings->addSetting("countdown_user",$_POST['countdown_user']);
            }
            
            if (isset($_POST['listing_style']) && $_POST['listing_style']){
                $settings->addSetting("listing_style",$_POST['listing_style']);
            }
            
            if (isset($_POST['smartbar_size']) && $_POST['smartbar_size'] && in_array($_POST['smartbar_size'], array("small","medium","large"))){
                $settings->addSetting("smartbar_size",$_POST['smartbar_size']);
            }
            
            if (isset($_POST['smartbar_rows']) && is_numeric($_POST['smartbar_rows'])){
                $settings->addSetting("smartbar_rows",$_POST['smartbar_rows']);
            }
            
            if (!isset($tv_guide) || !$tv_guide){
                $tv_guide = 0;    
            } else{
                $tv_guide = 1;
            }
            
            $settings->addSetting("tv_guide",$tv_guide);
            
            if (!isset($captchas) || !$captchas){
                $captchas = 0;    
            } else{
                $captchas = 1;
            }
            
            $settings->addSetting("captchas",$captchas);
            
            if (!isset($seo_links) || !$seo_links){
                $seo_links = 0;    
            } else{
                $seo_links = 1;
            }
            
            $settings->addSetting("seo_links",$seo_links);
            
            if (!isset($smart_bar) || !$smart_bar){
                $smart_bar = 0;    
            } else{
                $smart_bar = 1;
            }
            
            $settings->addSetting("smart_bar",$smart_bar);
        }

        $listing_style = $settings->getSetting("listing_style");
        
        if (!$listing_style || (is_array($listing_style) && empty($listing_style))){
            $listing_style = "embeds";
        }
        
    
        $default_language = $settings->getSetting("default_language");
        
        if (!$default_language || (is_array($default_language) && empty($default_language))){
            $default_language = "en";
        }
        
        $maxtvperpage = $settings->getSetting("maxtvperpage");
        
        if (is_array($maxtvperpage) && empty($maxtvperpage)){
            $maxtvperpage = 50;
        }
        
        $maxmoviesperpage = $settings->getSetting("maxmoviesperpage");
        
        if (is_array($maxmoviesperpage) && empty($maxmoviesperpage)){
            $maxmoviesperpage = 50;
        }
        
        $countdown_free = $settings->getSetting("countdown_free");
        
        if (is_array($countdown_free) && empty($countdown_free)){
            $countdown_free = 30;
        }
        
        $countdown_user = $settings->getSetting("countdown_user");
        
        if (is_array($countdown_user) && empty($countdown_user)){
            $countdown_user = 0;
        }
        
        $smartbar_rows = $settings->getSetting("smartbar_rows");
        
        if (!$smartbar_rows || (is_array($smartbar_rows) && empty($smartbar_rows))){
            $smartbar_rows = 2;
        }
        
        $smartbar_size = $settings->getSetting("smartbar_size");
        
        if (!$smartbar_size || (is_array($smartbar_size) && empty($smartbar_size))){
            $smartbar_size = "small";
        }
        
        $seo_links = $settings->getSetting("seo_links");
        
        if (is_array($seo_links) && empty($seo_links)){
            $seo_links = 1;
        }
        
        $captchas = $settings->getSetting("captchas");
        
        if (is_array($captchas) && empty($captchas)){
            $captchas = 0;
        }
        
        $tv_guide = $settings->getSetting("tv_guide");
        
        if (is_array($tv_guide) && empty($tv_guide)){
            $tv_guide = 1;
        }
        
        $smart_bar = $settings->getSetting("smart_bar");
        
        if (is_array($smart_bar) && empty($smart_bar)){
            $smart_bar = 1;
        }
    
    ?>
    
    <div class="span6">
        <h3 class="heading">Tweaks</h3>
        <form action="index.php" method="post">
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td width="*">Default language</td>
                        <td width="1">
                            <select name="default_language">
                                <?php 
                                    foreach($global_languages as $lang_code => $lang_name){
                                        print("<option value='$lang_code'");
                                        if ($lang_code == $default_language){
                                            print(" selected='selected'");
                                        }
                                        print(">$lang_name</option>");
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="*">Video listing style</td>
                        <td width="1">
                            <select name="listing_style">
                                <option value="embeds"<?php if ($listing_style=="embeds"){ print(" selected=\"selected\""); }?>>Embeds</option>
                                <option value="links"<?php if ($listing_style=="links"){ print(" selected=\"selected\""); }?>>Links</option>
                                <option value="both"<?php if ($listing_style=="both"){ print(" selected=\"selected\""); }?>>Embeds and links</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="*">Maximum number of TV shows per page</td>
                        <td width="1"><input type="text" value="<?php print($maxtvperpage); ?>" class="input-small" name="maxtvperpage" /></td>
                    </tr>
                    <tr>
                        <td width="*">Maximum number of Movies per page</td>
                        <td width="1"><input type="text" value="<?php print($maxmoviesperpage); ?>" class="input-small" name="maxmoviesperpage" /></td>
                    </tr>
                    <tr>
                        <td width="*">Coundown for not registered users (0 for none)</td>
                        <td width="1"><input type="text" value="<?php print($countdown_free); ?>" class="input-small" name="countdown_free" /></td>
                    </tr>
                    <tr>
                        <td width="*">Coundown for registered users (0 for none)</td>
                        <td width="1"><input type="text" value="<?php print($countdown_user); ?>" class="input-small" name="countdown_user" /></td>
                    </tr>
                    <tr>
                        <td width="*">Use captchas</td>
                        <td width="1"><input type="checkbox" <?php if ($captchas) print("checked='checked'"); ?> name="captchas" class="switch" /></td>
                    </tr>
                    <tr>
                        <td width="*">TV guide</td>
                        <td width="1"><input type="checkbox" <?php if ($tv_guide) print("checked='checked'"); ?> name="tv_guide" class="switch" /></td>
                    </tr>
                    <tr>
                        <td width="*">Seo friendly links</td>
                        <td width="1"><input type="checkbox" <?php if ($seo_links) print("checked='checked'"); ?> name="seo_links" class="switch" /></td>
                    </tr>
                    <tr>
                        <td width="*">SmartBar <a href="#" class="pop_over" title="Smartbar" data-content="SmartBar is the top part of the site where TVstreamScript shows the most relevant TV shows and Movies for users"><i class="icon-question-sign"></i></a></td>
                        <td width="1"><input type="checkbox" <?php if ($smart_bar) print("checked='checked'"); ?> name="smart_bar" class="switch" /></td>
                    </tr>
                    <tr>
                        <td width="*">SmartBar Size</td>
                        <td width="1">
                            <select name="smartbar_size">
                                <option value="small" <?php if ($smartbar_size == "small") print("selected=\"selected\""); ?>>Small</option>
                                <option value="medium" <?php if ($smartbar_size == "medium") print("selected=\"selected\""); ?>>Medium</option>
                                <option value="large" <?php if ($smartbar_size == "large") print("selected=\"selected\""); ?>>Large</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="*">SmartBar rows</td>
                        <td width="1"><input type="text" value="<?php print($smartbar_rows); ?>" class="input-small" name="smartbar_rows" /></td>
                    </tr>
                    
                    <tr>
                        <td colspan="2" align="right" style="text-align:right;" >
                            <input type="hidden" name="menu" value="settings_general" />
                            <input type="submit" name="update_tweaks" value="Update settings" class="btn btn-primary" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

    <?php 
        $modules = $settings->getModules();
        
        if (isset($update_modules) && $update_modules){
            if (!isset($module) || !$module || !is_array($module)){
                $module = array();
            }
            
            foreach($modules as $perma => $module_data){
                if (!array_key_exists($module_data['id'],$module)){
                    $settings->setModule($module_data['id'],0);
                    $modules[$perma]['status']=0;
                } else {
                    $settings->setModule($module_data['id'],1);
                    $modules[$perma]['status']=1;
                }        
            }
        }
    ?>

    <div class="span6">
        <h3 class="heading">Modules</h3>
        <form action="index.php" method="post">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="*">Name</th>
                        <th width="1">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if (count($modules)){
                            foreach($modules as $perma => $module_data){
                                if ($module_data['status']==1){
                                    $o = "checked='checked'";
                                } else {
                                    $o = "";
                                }
                                print("    <tr>
                                            <td>{$module_data['title']}</td>
                                            <td><input type=\"checkbox\" class=\"switch\" name=\"module[{$module_data['id']}]\" $o />
                                       </tr>");
                            }
                            
                            print("    <tr>
                                        <td colspan=\"2\">
                                            <input type=\"hidden\" name=\"menu\" value=\"settings_general\" />
                                            <input type=\"submit\" name=\"update_modules\" value=\"Update modules\" class=\"btn btn-primary\" />
                                        </td>
                                    </tr>");
                        }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
    
</div>