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
                SEO settings
            </li>
        </ul>
    </div>
</nav>

<?php 

require_once("../includes/settings.class.php");
$settings = new Settings();

if (isset($update_seo) && $update_seo){
    if (isset($default_title) && is_array($default_title)){
        $settings->addSetting("default_title",json_encode($default_title));
    }
    
    if (isset($default_keywords) && is_array($default_keywords)){
        $settings->addSetting("default_keywords",json_encode($default_keywords));
    }
    
    if (isset($default_description) && is_array($default_description)){
        $settings->addSetting("default_description",json_encode($default_description));
    }
    
    if (isset($tvshows_title) && is_array($tvshows_title)){
        $settings->addSetting("tvshows_title",json_encode($tvshows_title));
    }
    
    if (isset($tvshows_keywords) && is_array($tvshows_keywords)){
        $settings->addSetting("tvshows_keywords",json_encode($tvshows_keywords));
    }
    
    if (isset($tvshows_description) && is_array($tvshows_description)){
        $settings->addSetting("tvshows_description",json_encode($tvshows_description));
    }
    
    if (isset($tvcategory_title) && is_array($tvcategory_title)){
        $settings->addSetting("tvcategory_title",json_encode($tvcategory_title));
    }
    
    if (isset($tvcategory_keywords) && is_array($tvcategory_keywords)){
        $settings->addSetting("tvcategory_keywords",json_encode($tvcategory_keywords));
    }
    
    if (isset($tvcategory_description) && is_array($tvcategory_description)){
        $settings->addSetting("tvcategory_description",json_encode($tvcategory_description));
    }
    
    if (isset($show_title) && is_array($show_title)){
        $settings->addSetting("show_title",json_encode($show_title));
    }
    
    if (isset($show_keywords) && is_array($show_keywords)){
        $settings->addSetting("show_keywords",json_encode($show_keywords));
    }
    
    if (isset($show_description) && is_array($show_description)){
        $settings->addSetting("show_description",json_encode($show_description));
    }
    
    if (isset($episode_title) && is_array($episode_title)){
        $settings->addSetting("episode_title",json_encode($episode_title));
    }
    
    if (isset($episode_keywords) && is_array($episode_keywords)){
        $settings->addSetting("episode_keywords",json_encode($episode_keywords));
    }
    
    if (isset($episode_description) && is_array($episode_description)){
        $settings->addSetting("episode_description",json_encode($episode_description));
    }
    
    if (isset($movies_title) && is_array($movies_title)){
        $settings->addSetting("movies_title",json_encode($movies_title));
    }
    
    if (isset($movies_keywords) && is_array($movies_keywords)){
        $settings->addSetting("movies_keywords",json_encode($movies_keywords));
    }
    
    if (isset($movies_description) && is_array($movies_description)){
        $settings->addSetting("movies_description",json_encode($movies_description));
    }
    
    if (isset($moviecategory_title) && is_array($moviecategory_title)){
        $settings->addSetting("moviecategory_title",json_encode($moviecategory_title));
    }
    
    if (isset($moviecategory_keywords) && is_array($moviecategory_keywords)){
        $settings->addSetting("moviecategory_keywords",json_encode($moviecategory_keywords));
    }
    
    if (isset($moviecategory_description) && is_array($moviecategory_description)){
        $settings->addSetting("moviecategory_description",json_encode($moviecategory_description));
    }
    
    if (isset($watchmovie_title) && is_array($watchmovie_title)){
        $settings->addSetting("watchmovie_title",json_encode($watchmovie_title));
    }
    
    if (isset($watchmovie_keywords) && is_array($watchmovie_keywords)){
        $settings->addSetting("watchmovie_keywords",json_encode($watchmovie_keywords));
    }
    
    if (isset($watchmovie_description) && is_array($watchmovie_description)){
        $settings->addSetting("watchmovie_description",json_encode($watchmovie_description));
    }
    
    if (isset($search_title) && is_array($search_title)){
        $settings->addSetting("search_title",json_encode($search_title));
    }
    
    if (isset($search_keywords) && is_array($search_keywords)){
        $settings->addSetting("search_keywords",json_encode($search_keywords));
    }
    
    if (isset($search_description) && is_array($search_description)){
        $settings->addSetting("search_description",json_encode($search_description));
    }
    
    if (isset($livechannels_title) && is_array($livechannels_title)){
        $settings->addSetting("livechannels_title",json_encode($livechannels_title));
    }
    
    if (isset($livechannels_keywords) && is_array($livechannels_keywords)){
        $settings->addSetting("livechannels_keywords",json_encode($livechannels_keywords));
    }
    
    if (isset($livechannels_description) && is_array($livechannels_description)){
        $settings->addSetting("livechannels_description",json_encode($livechannels_description));
    }
    
    if (isset($channel_title) && is_array($channel_title)){
        $settings->addSetting("channel_title",json_encode($channel_title));
    }
    
    if (isset($channel_keywords) && is_array($channel_keywords)){
        $settings->addSetting("channel_keywords",json_encode($channel_keywords));
    }
    
    if (isset($channel_description) && is_array($channel_description)){
        $settings->addSetting("channel_description",json_encode($channel_description));
    }
}

// getting the variables
$default_title = $settings->getSetting("default_title",true);
$default_keywords = $settings->getSetting("default_keywords",true);
$default_description = $settings->getSetting("default_description",true);


$tvshows_title = $settings->getSetting("tvshows_title",true);
$tvshows_keywords = $settings->getSetting("tvshows_keywords",true);
$tvshows_description = $settings->getSetting("tvshows_description",true);

$tvcategory_title = $settings->getSetting("tvcategory_title",true);
$tvcategory_keywords = $settings->getSetting("tvcategory_keywords",true);
$tvcategory_description = $settings->getSetting("tvcategory_description",true);

$show_title = $settings->getSetting("show_title",true);
$show_keywords = $settings->getSetting("show_keywords",true);
$show_description = $settings->getSetting("show_description",true);

$episode_title = $settings->getSetting("episode_title",true);
$episode_keywords = $settings->getSetting("episode_keywords",true);
$episode_description = $settings->getSetting("episode_description",true);

$movies_title = $settings->getSetting("movies_title",true);
$movies_keywords = $settings->getSetting("movies_keywords",true);
$movies_description = $settings->getSetting("movies_description",true);

$moviecategory_title = $settings->getSetting("moviecategory_title",true);
$moviecategory_keywords = $settings->getSetting("moviecategory_keywords",true);
$moviecategory_description = $settings->getSetting("moviecategory_description",true);

$watchmovie_title = $settings->getSetting("watchmovie_title",true);
$watchmovie_keywords = $settings->getSetting("watchmovie_keywords",true);
$watchmovie_description = $settings->getSetting("watchmovie_description",true);

$livechannels_title = $settings->getSetting("livechannels_title",true);
$livechannels_keywords = $settings->getSetting("livechannels_keywords",true);
$livechannels_description = $settings->getSetting("livechannels_description",true);

$channel_title = $settings->getSetting("channel_title",true);
$channel_keywords = $settings->getSetting("channel_keywords",true);
$channel_description = $settings->getSetting("channel_description",true);

?>

<div class="row-fluid">
    <div class="span12">

    <div class="alert alert-info">
        <a class="close" data-dismiss="alert">×</a>
        On this page you can set title and meta tags for each sections of your site. You can use the following special <strong>replacement strings</strong>:<br />
        <strong>%TITLE%</strong>: Title of the actual episode, show, movie or live channel<br />
        <strong>%DESCRIPTION%</strong>: Description of the actual episode, show, movie or live channel <br />
        <strong>%SHOWTITLE%</strong>: Title of a show <br />
        <strong>%SEASON%</strong>: Season number for the episode <br />
        <strong>%EPISODE%</strong>: Episode number for the episode <br />
        <strong>%CATEGORY%</strong>: Title of the selected category <br />
        <strong>%SITENAME%</strong>: Name of your site<br />
        <strong>%SEARCHTERM%</strong>: Search term (only available on search results)  
    </div>
            
    <h3 class="heading">SEO options</h3>        
    <form action="index.php" method="post">
        <input type="hidden" name="menu" value="settings_seo" />
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">Home page / Default</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="default_title[<?php print($lang_code); ?>]" value="<?php if (isset($default_title[$lang_code]) && is_array($default_title)) print($default_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>

                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="default_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($default_keywords[$lang_code]) && is_array($default_keywords)) print($default_keywords[$lang_code]); ?>" class="span12" /></td></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="default_description[<?php print($lang_code); ?>]" value="<?php if (isset($default_description[$lang_code]) && is_array($default_description)) print($default_description[$lang_code]); ?>" class="span12" /></td></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">TV shows list</th>
                    <th width="75%">&nbsp;</th>
                </tr>            
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="tvshows_title[<?php print($lang_code); ?>]" value="<?php if (isset($tvshows_title[$lang_code]) && is_array($tvshows_title)) print($tvshows_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="tvshows_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($tvshows_keywords[$lang_code]) && is_array($tvshows_keywords)) print($tvshows_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="tvshows_description[<?php print($lang_code); ?>]" value="<?php if (isset($tvshows_description[$lang_code]) && is_array($tvshows_description)) print($tvshows_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">TV show category list</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="tvcategory_title[<?php print($lang_code); ?>]" value="<?php if (isset($tvcategory_title[$lang_code]) && is_array($tvcategory_title)) print($tvcategory_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="tvcategory_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($tvcategory_keywords[$lang_code]) && is_array($tvcategory_keywords)) print($tvcategory_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="tvcategory_description[<?php print($lang_code); ?>]" value="<?php if (isset($tvcategory_description[$lang_code]) && is_array($tvcategory_description)) print($tvcategory_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">Show episode list</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>                    
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="show_title[<?php print($lang_code); ?>]" value="<?php if (isset($show_title[$lang_code]) && is_array($show_title)) print($show_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="show_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($show_keywords[$lang_code]) && is_array($show_keywords)) print($show_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="show_description[<?php print($lang_code); ?>]" value="<?php if (isset($show_description[$lang_code]) && is_array($show_keywords)) print($show_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">Watch episode</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="episode_title[<?php print($lang_code); ?>]" value="<?php if (isset($episode_title[$lang_code]) && is_array($episode_title)) print($episode_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="episode_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($episode_keywords[$lang_code]) && is_array($episode_keywords)) print($episode_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="episode_description[<?php print($lang_code); ?>]" value="<?php if (isset($episode_description[$lang_code]) && is_array($episode_description)) print($episode_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">Movie list</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="movies_title[<?php print($lang_code); ?>]" value="<?php if (isset($movies_title[$lang_code]) && is_array($movies_title)) print($movies_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="movies_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($movies_keywords[$lang_code]) && is_array($movies_keywords)) print($movies_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="movies_description[<?php print($lang_code); ?>]" value="<?php if (isset($movies_description[$lang_code]) && is_array($movies_description)) print($movies_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">Movie category list</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="moviecategory_title[<?php print($lang_code); ?>]" value="<?php if (isset($moviecategory_title[$lang_code]) && is_array($moviecategory_title)) print($moviecategory_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="moviecategory_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($moviecategory_keywords[$lang_code]) && is_array($moviecategory_keywords)) print($moviecategory_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="moviecategory_description[<?php print($lang_code); ?>]" value="<?php if (isset($moviecategory_description[$lang_code]) && is_array($moviecategory_description)) print($moviecategory_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">Watch movie</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="watchmovie_title[<?php print($lang_code); ?>]" value="<?php if (isset($watchmovie_title[$lang_code]) && is_array($watchmovie_title)) print($watchmovie_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="watchmovie_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($watchmovie_keywords[$lang_code]) && is_array($watchmovie_keywords)) print($watchmovie_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="watchmovie_description[<?php print($lang_code); ?>]" value="<?php if (isset($watchmovie_description[$lang_code]) && is_array($watchmovie_description)) print($watchmovie_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="25%">Search results</th>
                    <th width="75%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($global_languages as $lang_code => $lang_name){
                ?>
                    <tr>
                        <td><?php print($lang_name); ?> title tag:</td>
                        <td><input type="text" name="search_title[<?php print($lang_code); ?>]" value="<?php if (isset($search_title[$lang_code]) && is_array($search_title)) print($search_title[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta keywords:</td>
                        <td><input type="text" name="search_keywords[<?php print($lang_code); ?>]" value="<?php if (isset($search_keywords[$lang_code]) && is_array($search_keywords)) print($search_keywords[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                    <tr>
                        <td><?php print($lang_name); ?> meta description:</td>
                        <td><input type="text" name="search_description[<?php print($lang_code); ?>]" value="<?php if (isset($search_description[$lang_code]) && is_array($search_description)) print($search_description[$lang_code]); ?>" class="span12" /></td>
                    </tr>
                <?php 
                    }
                ?>

                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type="submit" name="update_seo" value="Update SEO options" class="btn btn-primary" />
                    </td>
                </tr>
            </tbody>
        </table>
        
    </form>
    </div>
</div>