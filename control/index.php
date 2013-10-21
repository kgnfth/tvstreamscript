<?php
@header('Content-Type:text/html; charset=UTF-8');
@session_start();
@set_time_limit(0);
@ob_start();

if (get_magic_quotes_gpc()) {

    function stripslashes_deep($value) {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);

        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

@error_reporting(E_ALL);
@ini_set("display_errors","On");
@ini_set('output_buffering','on');  
@ini_set('zlib.output_compression', 0);
@ini_set('default_charset', 'UTF-8');

if (isset($_GET) && count($_GET)){
    extract($_GET);
}

if (isset($_POST) && count($_POST)){
    extract($_POST);
}

require_once("../vars.php");
require_once("../includes/curl.php");
require_once("../includes/plugins.class.php");
require_once("../includes/cache.class.php");
require_once("../includes/misc.class.php");
require_once("../includes/user.class.php");
require_once("../includes/show.class.php");
require_once("../includes/movie.class.php");
require_once("../includes/plugins.class.php");
require_once("../includes/request.class.php");
require_once("../includes/page.class.php");
require_once("../includes/settings.class.php");
require_once("../includes/phpmailer/class.phpmailer.php");
require_once("../includes/facebook/facebook.php");
require_once("../includes/updater.class.php");


$misc = new Misc();
$cache = new Cache($basepath);
$user = new User();
$show = new Show();
$movie = new Movie();
$request = new Request();
$plugins = new Plugins();
$settings = new Settings();
$page = new Page();
$updater = new Updater();
$curl = new Curl();

$global_languages = $misc->getLanguages();
$default_language = $settings->getSetting("default_language", true);
if (!$default_language || (is_array($default_language) && empty($default_language))){
    if (array_key_exists($global_languages['en'])){
        $default_language = "en";    
    } else {
        foreach($global_languages as $key => $val){
            $default_language = $key;
            break;
        }
    }    
}

if (isset($perma) && $perma=="control"){
    unset($perma);
    $menu = '';
}

if (isset($perma) && $perma){
    $menu = $perma;
}

if (isset($logout)){
    $_SESSION = array();
    session_destroy();
}

$current_version = $settings->getSetting("version", false);
if (empty($current_version)){
    $current_version = "2.1";   
}

if (!isset($_SESSION['update_version'])){
    $update_version = $updater->getLatestVersion($curl);
    if (!$update_version){
        $update_version = $current_version;
    }
    $_SESSION['update_version'] = $update_version;
} else {
    $update_version = $_SESSION['update_version'];
}

if (isset($dologin)){
    $adminuser = mysql_real_escape_string($adminuser);
    $adminpass = md5($adminpass);
    $e = mysql_query("SELECT id as admin_id FROM admin WHERE username='$adminuser' AND password='$adminpass'") or die(mysql_error());
    if (mysql_num_rows($e)){
        
        $captchas = $settings->getSetting("captchas");
                        
        if (is_array($captchas) && empty($captchas)){
            $captchas = 0;
        }
        
        if ($captchas && (empty($_SESSION['captcha']) || !isset($_REQUEST['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha'])){
            $login_error = "Invalid captcha provided";
        } else {
            extract(mysql_fetch_assoc($e));
            $_SESSION['admin_user_id'] = $admin_id;
            $_SESSION['admin_username'] = $adminuser;
        }
        
    } else {
        $login_error = 'Invalid login details';
    }
}

if (!isset($menu) || $menu==""){
    $menu = "dashboard";
}

$menu = preg_replace("/[^a-zA-Z0-9\-_]/","",$menu);

if (!isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    if (isset($login_error)){
        header("Location: login.php?menu=$menu&login_error=$login_error");    
    } else {
        header("Location: login.php?menu=$menu");
    }
    exit();
}

define("IN_SCRIPT",true);
//define("DEMO",true);

require_once("header.php");

if (defined('DEMO') && DEMO){
?> 

<div class="alert alert-warning">
    <a class="close" data-dismiss="alert">×</a>
    <strong>Demo mode: </strong>some features might be disabled in this demo. This demo resets every hour.
</div>

<?php 
}

if ($menu!='plugin'){
    if (file_exists($menu.".php")){
        require_once($menu.".php");
    } else {
        print("not implemented");
    }
} else {
    if (isset($plugin) && $plugin && isset($plugin_menu) && $plugin_menu){
        $plugin = preg_replace("/[^a-zA-Z0-9\-_]/","",$plugin);
        $plugin_menu = preg_replace("/[^a-zA-Z0-9\-_]/","",$plugin_menu);
        
        if (file_exists("../plugins/".$plugin."/".$plugin_menu.".php")){
            require_once("../plugins/".$plugin."/".$plugin_menu.".php");
        } else {
            print("not implemented");
        }
    } else {
        require_once("dashboard.php");
    }
}

$admin_menu = array();
$admin_menu['dashboard']    = array(     "menu" => "dashboard", "url" => "index.php?menu=dashboard", "title" => "Dashboard", "icon" => "icon-tasks",
                                        "submenus" => array());
$admin_menu['users']         = array(     "menu" => false, "url" => false, "title" => "Users", "icon" => "icon-user",
                                         "submenus" => array(    "users" => array("title" => "Manage users", "url" => "index.php?menu=users", "type" => "normal"),
                                                                "users_requests" => array("title" => "Requests", "url" => "index.php?menu=users_requests", "type" => "normal"),
                                                                "users_email" => array("title" => "Email users", "url" => "index.php?menu=users_email", "type" => "normal")
                                                            )
                                    );
$admin_menu['shows']         = array(     "menu" => false, "url" => false, "title" => "TV shows", "icon" => "icon-facetime-video",
                                         "submenus" => array(    "shows_new" => array("title" => "Add new show", "url" => "index.php?menu=shows_new", "type" => "normal"),
                                                                "shows_manage" => array("title" => "Manage shows", "url" => "index.php?menu=shows_manage", "type" => "normal"),
                                                                "episodes" => array("title" => "Add episodes", "url" => "index.php?menu=episodes", "type" => "normal"),
                                                                "tv_categories" => array("title" => "Manage categories", "url" => "index.php?menu=tv_categories", "type" => "normal"),
                                                                "tv_guide" => array("title" => "TV guide", "url" => "index.php?menu=tv_guide", "type" => "normal"),
                                                                "tv_popular" => array("title" => "Popular episodes", "url" => "index.php?menu=tv_popular", "type" => "normal"),
                                                                "tv_broken" => array("title" => "Broken episodes", "url" => "index.php?menu=tv_broken", "type" => "normal"),
                                                                "tv_links" => array("title" => "Submitted links", "url" => "index.php?menu=tv_links", "type" => "normal")
                                                            )
                                    );
                                    
$admin_menu['movies']         = array(     "menu" => false, "url" => false, "title" => "Movies", "icon" => "icon-film",
                                         "submenus" => array(    "movies_new" => array("title" => "Add new movie", "url" => "index.php?menu=movies_new", "type" => "normal"),
                                                                "movies_manage" => array("title" => "Manage movies", "url" => "index.php?menu=movies_manage", "type" => "normal"),
                                                                "movie_categories" => array("title" => "Manage categories", "url" => "index.php?menu=movie_categories", "type" => "normal"),
                                                                "movie_popular" => array("title" => "Popular movies", "url" => "index.php?menu=movie_popular", "type" => "normal"),
                                                                "movie_broken" => array("title" => "Broken movies", "url" => "index.php?menu=movie_broken", "type" => "normal"),
                                                                "movie_links" => array("title" => "Submitted links", "url" => "index.php?menu=movie_links", "type" => "normal")
                                                            )
                                    );
                                    
$admin_menu['themes']        = array(     "menu" => "themes", "url" => "index.php?menu=themes", "title" => "Templates", "icon" => "icon-eye-open",
                                        "submenus" => array());

$admin_menu['plugins']         = array(     "menu" => false, "url" => false, "title" => "Plugins", "icon" => "icon-magnet",
                                         "submenus" => array(    "plugins" => array("title" => "Manage plugins", "url" => "index.php?menu=plugins", "type" => "normal"))
                                    );
                                    
$admin_menu['submitters']     = array(     "menu" => false, "url" => false, "title" => "TV submitters", "icon" => "icon-bullhorn",
                                        "submenus" => array(    "submitter_sidereel" => array("title" => "Sidereel", "url" => "index.php?menu=submitter_sidereel", "type" => "normal"),
                                                                "submitter_tvlinks" => array("title" => "TV-links.eu", "url" => "index.php?menu=submitter_tvlinks", "type" => "normal")
                                                            )
                                    );
                                    
$admin_menu['configuration']= array(     "menu" => false, "url" => false, "title" => "Configuration", "icon" => "icon-cog",
                                         "submenus" => array(    "settings_general" => array("title" => "General settings", "url" => "index.php?menu=settings_general", "type" => "normal"),
                                                                "settings_seo" => array("title" => "SEO settings", "url" => "index.php?menu=settings_seo", "type" => "normal"),
                                                                "settings_accounts" => array("title" => "Manage accounts", "url" => "index.php?menu=settings_accounts", "type" => "normal"),
                                                                "settings_widgets" => array("title" => "Text widgets / Ads", "url" => "index.php?menu=settings_widgets", "type" => "normal")
                                                            )
                                    );
                                    
$admin_menu['pages']         = array(     "menu" => false, "url" => false, "title" => "Pages", "icon" => "icon-edit",
                                         "submenus" => array(    "pages_manage" => array("title" => "Manage pages", "url" => "index.php?menu=pages_manage", "type" => "normal"))
                                    );
$admin_menu['update']    = array(     "menu" => "update", "url" => "index.php?menu=update", "title" => "Update", "icon" => "icon-arrow-up",
                                        "submenus" => array());                                        

$plugin_menus = $plugins->getBackendMenu();
$activeplugins = $plugins->getInstalledPlugins();

if (count($activeplugins) && count($plugin_menus)){
    foreach($plugin_menus as $pm_key => $pm_submenus){
        
        foreach($pm_submenus as $pm_val){
            if (isset($activeplugins[$pm_val['plugin']])){            
                if (!isset($admin_menu[$pm_key])){
                    // completely new menu
                    
                    $admin_menu[$pm_key] = array( "menu" => false, "url" => false, "title" => $pm_val['main_menu_title'], "icon" => $pm_val['icon'], "submenus" => array());
                }
                            
                $admin_menu[$pm_key]['submenus'][$pm_val['sub_menu_url']] = array("title" => $pm_val['sub_menu_title'], "url" => "index.php?menu=plugin&plugin=".$pm_val['plugin']."&plugin_menu=".$pm_val['sub_menu_url'], "type" => "plugin", "plugin" => $pm_val['plugin']);
            }
        }
    }
}

$tips = array();
$tips[] = "Add your Facebook app details to enable Facebook connect, commenting and like boxes. This will improve your social presence<br /><a href='index.php?menu=settings_accounts'>Click here</a>";
$tips[] = "Submit your content to Sidereel daily. Sidereel is an awesome source of traffic which can deliver tens of thousands of users to your site<br /><a href='index.php?menu=submitter_sidereel'>Click here</a>";
$tips[] = "Check TV guide daily and keep adding missing TV shows and episodes. Your users will love it<br /><a href='index.php?menu=tv_guide'>Click here</a>";
$tips[] = "Add movies and TV shows requested by users to gain following. People love feeling heard and they will come back if you listen to their requests<br /><a href='index.php?menu=users_requests'>Click here</a>";
$tips[] = "Fix the broken links. There is nothing worse than finding a movie after a long search and realising it is broken<br /><a href='index.php?menu=movie_broken'>Click here</a>";
$tips[] = "Find out what are your best performing shows and add as much episodes as possible<br /><a href='index.php?menu=tv_popular'>Click here</a>";
$tips[] = "Connect your Google Analytics account and check out your visitor statistics on the dashboard.<br /><a href='index.php?menu=dashboard'>Click here</a>";
$tips[] = "Make your titles and meta tags unique and improve your Search Engine rankings. Try to come up with something unique, Google loves that<br /><a href='index.php?menu=seo_settings'>Click here</a>";


require_once("footer.php");

$cache->clear();
