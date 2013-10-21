<?php 
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <title><?php print($sitename); ?> Admin Panel</title>
        <script>
            var baseurl = '<?php print($baseurl); ?>';
            var basepath = '<?php print($basepath); ?>';
            var default_language = '<?php print($default_language); ?>';
        </script>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/dark.css" id="link_theme" />
        <link rel="stylesheet" href="lib/jquery-ui/css/Aristo/Aristo.css" />
        <link rel="stylesheet" href="lib/jBreadcrumbs/css/BreadCrumb.css" />
        <link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <link rel="stylesheet" href="lib/colorbox/colorbox.css" />    
        <link rel="stylesheet" href="lib/google-code-prettify/prettify.css" />    
        <link rel="stylesheet" href="lib/sticky/sticky.css" />    
        <link rel="stylesheet" href="img/splashy/splashy.css" />
        <link rel="stylesheet" href="img/flags/flags.css" />    
        <link rel="stylesheet" href="lib/fullcalendar/fullcalendar_gebo.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="lib/tag_handler/css/jquery.taghandler.css" />
        <link rel="stylesheet" href="lib/chosen/chosen.css" />
        <link rel="stylesheet" href="css/switch.css" />
        
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans" />
        <link rel="shortcut icon" href="favicon.ico" />
        
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/ie.css" />
            <script src="js/ie/html5.js"></script>
            <script src="js/ie/respond.min.js"></script>
            <script src="lib/flot/excanvas.min.js"></script>
        <![endif]-->
        
        
        <script src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/scripts.js?rand=<?php print(rand(0,11111)); ?>"></script>
    </head>
    <body>
        
        <div id="maincontainer" class="clearfix">
            <!-- header -->
            <header>
                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container-fluid">
                            <a class="brand" href="index.php"><i class="icon-home icon-white"></i> TVstreamScript admin</a>
                            <ul class="nav user_menu pull-right">
                                <li class="dropdown">
                                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"> Help <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="http://tvss.co" target="_blank">Support</a></li>

                                    </ul>
                                </li>
                                <li class="divider-vertical hidden-phone hidden-tablet"></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php if (isset($_SESSION['admin_username'])) print($_SESSION['admin_username']); ?> <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="index.php?menu=dashboard&logout=1">Log Out</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <a data-target=".nav-collapse" data-toggle="collapse" class="btn_menu">
                                <span class="icon-align-justify icon-white"></span>
                            </a>
                            <nav>
                                <div class="nav-collapse">
                                    <ul class="nav">

                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- main content -->
            <div id="contentwrapper">
                <div class="main_content">