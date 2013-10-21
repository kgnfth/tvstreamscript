<?php

@error_reporting(-1);
@ini_set("display_errors", "Off");

session_start();
set_time_limit(0);

extract($_GET);
extract($_POST);

if (!file_exists("vars.php")){
    header("Location: install/index.php");
} else {
    include("vars.php");
    
    if (!isset($sitename)){
        header("Location: install/index.php");    
    }
}

include("includes/user.class.php");
include("includes/show.class.php");
include("includes/cache.class.php");
include("includes/seo.class.php");
include("includes/misc.class.php");
include("includes/movie.class.php");
include("includes/settings.class.php");
include("includes/page.class.php");
include("includes/stream.class.php");
include("includes/request.class.php");
include("includes/plugins.class.php");
include("language/language_mapping.php");

$settings = new Settings();

$modules = $settings->getModules();

$default_language = $settings->getSetting("default_language", true);
if (!$default_language || (is_array($default_language) && empty($default_language))){
    $default_language = "en";
}

if (isset($_SESSION['language']) && $_SESSION['language']){
    $language = $_SESSION['language'];
} else if (isset($_COOKIE['language']) && $_COOKIE['language']) {
    $language = $_COOKIE['language'];
    $_SESSION['language'] = $_COOKIE['language'];
} else {
    if (isset($_SERVER['GEOIP_COUNTRY_CODE'])){
        $country_code = $_SERVER['GEOIP_COUNTRY_CODE'];
        
        if ($country_code && isset($language_mapping) && isset($language_mapping[$country_code])){
            $language = $language_mapping[$country_code];
            $_SESSION['language'] = $language_mapping[$country_code];
        } else {
            $_SESSION['language'] = $default_language;
            $language = $default_language;
        }

    } else {
        $_SESSION['language'] = $default_language;
        $language = $default_language;
    }
}
 
// force language based on url
if (isset($lang) && $lang!=$language){
    $lang = preg_replace("/[^a-z]/i","",$lang);
    if (file_exists($basepath."/language/".$lang."/general.php")){
        $_SESSION['language'] = $lang;
        $language = $lang;
    }
}
 
// force country if its posted in
if (isset($_POST['action']) && isset($_POST['lang'])){
    $_POST['lang'] = preg_replace("/[^a-z]/i","",$_POST['lang']);
    if (file_exists($basepath."/language/".$_POST['lang']."/general.php")){
        $_SESSION['language'] = $_POST['lang'];
        $language = $_POST['lang'];
        setcookie("language",$_POST['lang'],time()+60*60*24*30, "/");
        
        if (isset($_SESSION['loggeduser_id']) && $_SESSION['loggeduser_id']){
            $user = new User();            
            $user->update($_SESSION['loggeduser_id'],array("language" => $_POST['lang']));
        }
    }
}
 
if (isset($_SERVER['HTTP_REFERER'])){
    $referer = $_SERVER['HTTP_REFERER'];
} else {
    $referer = '';
}

if (!file_exists("language/$language/general.php")){
    $language = $default_language;
}

include("language/$language/general.php");
 
if (isset($menu) && $menu=='logout'){
    session_destroy();
    setcookie("guid","",time()-60*60, "/");
    print("<script>window.location='$baseurl';</script>");
    exit();
}

if (!isset($_SESSION['loggeduser_id']) && isset($_COOKIE['guid'])){
    $user = new User();
    $res = $user->cookieLogin($_COOKIE['guid']);
    if (!$res){
        setcookie("guid","",time()-60*60, "/");
    }
}
 
if (((isset($menu) && $menu=='login') || (isset($action) && $action=='login')) && isset($username) && isset($password)){
    $user = new User();
    $errors = $user->login($username,$password);
    if ((isset($returnpath) && $returnpath) || (isset($menu) && $menu=='login')){
        print("<script>window.location='$baseurl$returnpath';</script>");
        exit();
    } 
}

if (isset($action) && $action=='change_avatar' && isset($_SESSION['loggeduser_id'])){
    
    if (isset($_FILES['avatar_file']['name']) && $_FILES['avatar_file']['name']){
        if (($_FILES["avatar_file"]["type"] == "image/gif") || ($_FILES["avatar_file"]["type"] == "image/jpeg") || ($_FILES["avatar_file"]["type"] == "image/pjpeg") || ($_FILES["avatar_file"]["type"] == "image/png")){
            $filename = $_SESSION['loggeduser_id']."_".date("YmdHis").".jpg";
            if (move_uploaded_file($_FILES["avatar_file"]["tmp_name"],"$basepath/thumbs/users/" . $filename)){
                $user = new User();
                $user->update($_SESSION['loggeduser_id'],array("avatar" => $filename));
                $_SESSION['loggeduser_details']['avatar'] = $filename;
                
                $stream = new Stream();
                $data = array();
                $data['user_id'] = $_SESSION['loggeduser_id'];
                $data['user_data'] = $_SESSION['loggeduser_details'];
                $data['target_id'] = 0;
                $data['target_data'] = array();
                $data['target_type'] = 0;
                $data['event_date'] = date("Y-m-d H:i:s");
                $data['event_type'] = 4;
                $data['event_comment'] = '';
                $stream->addActivity($data);
                unset($stream);
            }    
        }
    }
    
}

if (isset($action) && $action=='settings' && isset($_SESSION['loggeduser_id'])){
    $user = new User();
    if (isset($_POST['notify_favorite']) && isset($_POST['notify_favorite'])){
        $notify_favorite = 1;
    } else {
        $notify_favorite = 0;
    }
    
    if (isset($_POST['notify_new']) && isset($_POST['notify_new'])){
        $notify_new = 1;
    } else {
        $notify_new = 0;
    }
    
    $user->update($_SESSION['loggeduser_id'],array("notify_favorite" => $notify_favorite,"notify_new" => $notify_new));
    
}

if (!isset($user)){
    $user = new User();
}
 
include("$basepath/templates/smarty/libs/Smarty.class.php");

if (isset($theme) && $theme){
    $_SESSION['theme']=$theme;
}

$page = new Page();
$movie = new Movie();
$show = new Show();
$cache = new Cache($basepath);
$misc = new Misc();
$request = new Request();
$plugins = new Plugins();

$hascache = 0;
$cache_writeable = $cache->checkDir();

if ($cache_writeable){
    $cachekey_plain = date("YmdH").@$_SERVER['HTTP_HOST'].@$_SERVER['REQUEST_URI'].@json_encode($_GET).@json_encode($_POST).@json_encode($_SESSION).@json_encode($_COOKIE)."_".$language;
    
    $cachekey = md5($cachekey_plain);
    $hascache = $cache->getCache($cachekey);
}

$hascache = 0;

if ($hascache){
    print($hascache);
} else {
    @ob_start();

    if (!isset($_SESSION['theme']) || !$_SESSION['theme']){
        $theme = $settings->getSetting("theme");
        if (!count($theme)){
            $theme = 'svarog';
        } else {
            $theme = $theme->theme;
        }
    } else {
        $theme = $_SESSION['theme'];
    }
    
    

    $smarty = new Smarty();
    $smarty->caching = 0;
    //$smarty->clear_all_cache();
    $smarty->template_dir = "$basepath/templates/$theme";
    
    if (file_exists("$basepath/cachefiles/$theme")){
    	$smarty->compile_dir = "$basepath/cachefiles/$theme";
    	$smarty->cache_dir = "$basepath/cachefiles/$theme";
    } else {
    	$smarty->compile_dir = "$basepath/cachefiles/";
    	$smarty->cache_dir = "$basepath/cachefiles";
    }
    $smarty->config_dir = "$basepath/templates/smarty/configs";
    
    $smarty->assign("templatepath","$baseurl/templates/$theme");
    $smarty->assign("cachekey_plain",$cachekey_plain);
    
    if (isset($_SESSION['fb_justregistered']) && $_SESSION['fb_justregistered']){
        $smarty->assign("facebook_promo",1);
        unset($_SESSION['fb_justregistered']);
    }

    
    $pages = $page->getPagesMenu($language);
    
    $global_settings = $settings->getMultiSettings(array("tv_guide","captchas","listing_style","adfly","analytics","seo_links","facebook","smart_bar","smartbar_size","smartbar_rows","maxtvperpage","maxmoviesperpage","countdown_free","countdown_user"), true);
    
    /* SEO links */
    
    if (!isset($global_settings['seo_links']) || !in_array($global_settings['seo_links'],array(0,1))){
        $global_settings['seo_links'] = 1;
    }
    
    if (!isset($global_settings['captchas']) || !$global_settings['captchas']){
        $global_settings['captchas'] = false;
    } else {
        $global_settings['captchas'] = true;
    }
    
    if (!isset($global_settings['tv_guide'])){
        $global_settings['tv_guide'] = true;
    } elseif (!$global_settings['tv_guide']){
        $global_settings['tv_guide'] = false;
    } else {
        $global_settings['tv_guide'] = true;
    }
    
    /* Video listing style */
    if (!isset($global_settings['listing_style']) || !$global_settings['listing_style'] || !in_array($global_settings['listing_style'],array("embeds", "links", "both"))){
        $global_settings['listing_style'] = "embeds";
    }    
    
    $listing_styles = array();
    if ($global_settings['listing_style'] == "embeds"){
        $listing_styles['embeds'] = true;
        $listing_styles['links'] = false;
    } elseif ($global_settings['listing_style'] == "links"){
        $listing_styles['embeds'] = false;
        $listing_styles['links'] = true;        
    } elseif ($global_settings['listing_style'] == "both"){
        $listing_styles['embeds'] = true;
        $listing_styles['links'] = true;        
    }
    
    $smarty->assign("listing_styles", $listing_styles);
    
    /* SmartBar */
    
    if (!isset($global_settings['smart_bar']) || !in_array($global_settings['smart_bar'],array(0,1))){
        $global_settings['smart_bar'] = 1;
    }
    
    
    /* Countdown before video */
    
    if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id']){
        if ($global_settings['countdown_free']==''){
            $global_settings['countdown_free'] = 20;
        }
        
        $global_settings['countdown'] = $global_settings['countdown_free'];
    } else {
    
        if ($global_settings['countdown_user']==''){
            $global_settings['countdown_user'] = 0;
        }
        
        $global_settings['countdown'] = $global_settings['countdown_user'];
    }
    
    /* Widgets */
    
    $widgets = $settings->getWidgets();
    
    $shows = $show->getRandomShow(5,$language);
    if (!count($shows)){
        $shows = '';
    } else {
        foreach($shows as $key=>$val){
            $shows[$key]['title']=stripslashes(stripslashes($shows[$key]['title']));
        }
    }
    
    $shows = '';
    
    // smartbar
    if ($user && isset($user->id)){
        $smartbar_cachekey = "smartbar_".$user->id."_".date("YmdH")."_".$language; 
    } else {
        $smartbar_cachekey = "smartbar_global_".date("YmdH")."_".$language;
    }
    
    if (!isset($global_settings['smartbar_size']) || !$global_settings['smartbar_size']){
        $smartbar_size = "small";
    } else {
        $smartbar_size = $global_settings['smartbar_size'];
    }
    
    if (!isset($global_settings['smartbar_rows']) || !$global_settings['smartbar_rows']){
        $smartbar_rows = 2;
    } else {
        $smartbar_rows = $global_settings['smartbar_rows'];
    }
    
    switch($smartbar_size){
        
        case "small":
            $smartbar_width = 43;
            $smartbar_height = 65;
            $smartbar_cols = 15;
            break;
            
        case "medium":
            $smartbar_width = 74;
            $smartbar_height = 120;
            $smartbar_cols = 10;
            break;
            
        case "large":
            $smartbar_width = 114;
            $smartbar_height = 170;
            $smartbar_cols = 7;
            break;
            
        default:
            $smartbar_width = 43;
            $smartbar_height = 60;
            $smartbar_cols = 15;
            break;
    }
    
    $smartbar = $cache->getCache($smartbar_cachekey);
    if (!$smartbar){
        $smartbar = $misc->getSmartbar($user,$movie,$show,$language,$smartbar_cols * $smartbar_rows);
        if ($cache_writeable){
            $cache->saveCache($smartbar_cachekey,json_encode($smartbar));
        }
    } else {
        $smartbar = json_decode($smartbar,true);
    }
    
    $smarty->assign("smartbar_width", $smartbar_width);
    $smarty->assign("smartbar_height", $smartbar_height);
    $smarty->assign("smartbar_cols", $smartbar_cols);
    
    /* Featured shows */
    
    $featured_shows = $show->getFeatured(4,$language);
    if (!count($featured_shows)){
        $featured_shows = '';
    } else {
        foreach($featured_shows as $key => $val){
            extract($val);
            $description = nl2br(stripslashes($description));
            $featured_shows[$key]['title'] = stripslashes($title);
            $featured_shows[$key]['description'] = $description;
        }
    }
    /* Categories */
    
    $tv_categories = $show->getCategories($language);
    
    if (!count($tv_categories)){
        $tv_categories = '';
    }

    $movie_categories =  $movie->getCategories($language);
    
    if (!count($movie_categories)){
        $movie_categories = '';
    }
    
    /* Rendering */
    
    if (!isset($menu) || !$menu || $menu=='login'){
        $menu = 'home';
    }
    $menu = preg_replace("/[^a-zA-Z0-9\-_]/","",$menu);
    
    $smarty->assign("smartbar",$smartbar);
    $smarty->assign("baseurl",$baseurl);
    $smarty->assign("sitename",$sitename);
    $smarty->assign("siteslogan",$siteslogan);
    $smarty->assign("menu",$menu);
    $smarty->assign("pages",$pages);
    $smarty->assign("global_settings",$global_settings);
    $smarty->assign("widgets",$widgets);
    $smarty->assign("shows",$shows);
    $smarty->assign("tv_categories",$tv_categories);
    $smarty->assign("movie_categories",$movie_categories);
    $smarty->assign("current_url",@$_SERVER['REQUEST_URI']);
    $smarty->assign("modules",$modules);
    

    if (!isset($_SESSION['loggeduser_id']) || !$_SESSION['loggeduser_id']){
        $smarty->assign("loggeduser_id",0);
        $logged = 0;
    } else {
        $logged = 1;
        $smarty->assign("loggeduser_id",$_SESSION['loggeduser_id']);
        $smarty->assign("loggeduser_username",$_SESSION['loggeduser_username']);
        $smarty->assign("loggeduser_details",$_SESSION['loggeduser_details']);
    }

    $seo = new SEO();
    $seodata = array();
    
    
    $seodata['menu']=$menu;
    
    if (file_exists("language/$language/$menu.php")){
        require_once("language/$language/$menu.php");
    }
    
    $embed_languages = $misc->getEmbedLanguages();
    $available_languages = array();
    foreach($embed_languages as $lang_code => $lang_data){
        if (substr_count($lang_code,"SUB")==0){
            $available_languages[] = $lang_data;
        }
    }
    
    $activeplugins = $plugins->getInstalledPlugins();
    $plugin_menus = $plugins->getFrontendMenu($activeplugins);
    if ($menu == "plugin"){
        if (isset($plugin) && $plugin && isset($plugin_menu) && $plugin_menu){
            $plugin = preg_replace("/[^a-zA-Z0-9\-_]/","",$plugin);
            $plugin_menu = preg_replace("/[^a-zA-Z0-9\-_]/","",$plugin_menu);
            
            if (file_exists($basepath."/plugins/".$plugin."/".$plugin_menu.".php")){
                $smarty->assign("plugin",$plugin);
                $smarty->assign("plugin_menu",$plugin_menu);
                include($basepath."/plugins/".$plugin."/".$plugin_menu.".php");
            } else {
                unset($plugin);
                unset($plugin_menu);
                $smarty->assign("menu","home");
                $menu = "home";
                include("home.php");                
            }
        } else {
            $smarty->assign("menu","home");
            $menu = "home";
            include("home.php");
        }
    } else {
        include("$menu.php");    
    }
    
    $seo_tags = $seo->getSeo($seodata);
    $smarty->assign("seo",$seo_tags);
    $smarty->assign("lang",$lang);
    $smarty->assign("routes",$routes);
    $smarty->assign("available_languages",$available_languages);
    $smarty->assign("embed_languages",$embed_languages);
    $smarty->assign("plugin_menus",$plugin_menus);
    $smarty->assign("absolute_url","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    
    if ($menu == "plugin"){
        if (isset($plugin) && $plugin && isset($plugin_menu) && $plugin_menu){
            $smarty->display($basepath."/plugins/".$plugin."/templates/".$plugin_menu.".tpl");
        } else {
            $smarty->display("home.tpl");
        }
    } else {
        $smarty->display($menu.".tpl");
    }
    
    $pagecontent = ob_get_contents();
    ob_end_clean();
    
    print($pagecontent);
    
    if ($cache_writeable){
        $cache->saveCache($cachekey,$pagecontent);
    }
    
}
?>