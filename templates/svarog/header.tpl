<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
    <title>{$seo.title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="{$seo.description}" />
    <meta name="keywords" content="{$seo.keywords}">
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="{$seo.title}"/>
    {if $fb_image neq ''}
        <meta property="og:image" content="{$fb_image}" />
    {/if}
    
    <link href="{$baseurl}/favicon.ico" rel="shortcut icon" type="image/ico">
    <link href="{$baseurl}/templates/svarog/css/reset.css" rel="stylesheet" type="text/css" />
    <link href="{$baseurl}/templates/svarog/css/colorpicker.css" rel="stylesheet" type="text/css" />
    <!--[if IE]>
        <link href="{$baseurl}/templates/svarog/css/ie.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <link rel="stylesheet" href="{$baseurl}/templates/svarog/css/prettyPhoto.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="{$baseurl}/js/tooltip.css" type="text/css" media="screen" />    
    <link rel="stylesheet" href="{$templatepath}/js/jquery.rating.css?r=2" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="{$templatepath}/css/foxycomplete.css" type="text/css" media="screen" />
    
    <script> 
        var baseurl = '{$baseurl}';
        {if $widgets.iframe_ad and $widgets.iframe_ad.content and (not $loggeduser_id or $widgets.iframe_ad.logged)}
            var iframe_ad = true;
        {else}
            var iframe_ad = false;
        {/if}

    </script>
    
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{$baseurl}/templates/svarog/js/custom.js"></script>
    <script type="text/javascript" src="{$baseurl}/templates/svarog/js/prettyPhoto/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="{$baseurl}/templates/svarog/js/jquery.featureList-1.0.0.js"></script>
    <script type="text/javascript" src="{$baseurl}/templates/svarog/js/easing/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="{$baseurl}/templates/svarog/js/jquery.tipsy.js"></script>
    <script type="text/javascript" src="{$baseurl}/templates/svarog/js/jquery.quicksand.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/foxycomplete.js"></script>
    <script type='text/javascript' src='{$templatepath}/js/jquery.rating.js'></script>
    <script type='text/javascript' src='{$templatepath}/js/jquery.MetaData.js'></script>
    
    
    <!--GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css' />
    <!--/GOOGLE FONTS-->
    
    <script type="text/javascript" src="{$baseurl}/js/scripts.js?r=18"></script>
    
    <link rel="stylesheet" href="{$baseurl}/templates/svarog/css/bootstrap.css?r=5" type="text/css" media="screen" />
    <script>
        js_lang.like = '{$lang.like}';
        js_lang.dislike = '{$lang.dislike}';
        js_lang.facebook_error = '{$lang.unexpected_facebook_error}';
        js_lang.please_wait = '{$lang.please_wait}';
        js_lang.ticker = '{$lang.video_ticker}';
        js_lang.report_thanks = '{$lang.report_thanks}';
    </script>
    
    <link href="{$baseurl}/templates/svarog/css/style.css?r=10" rel="stylesheet" type="text/css" />
    <link href="{$templatepath}/style/color/cc3366.css" rel="stylesheet" type="text/css" />
</head>

<body>
    {if $global_settings.facebook}
        {literal}
            <div id="fb-root"></div>
            <script>
            window.fbAsyncInit = function() {
                    FB.init({
                    appId      : '{/literal}{$global_settings.facebook.app_id}{literal}',
                    status     : true, 
                    cookie     : true,
                    xfbml      : true,
                    oauth      : true
                });
        {/literal}
    
        {literal}
              
            };
            
            (function(d){
               var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
               js = d.createElement('script'); js.id = id; js.async = true;
               js.src = "//connect.facebook.net/en_US/all.js";
               d.getElementsByTagName('head')[0].appendChild(js);
             }(document));
            </script>    
        {/literal}
    {/if}
    
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand-text" href="/">{$sitename}</a>            
                <div class="nav-collapse">
                    <ul class="nav pull-left">
                        <li{if $menu eq '' or $menu eq 'home'} class="boot-active"{/if}><a href="{$baseurl}">{$lang.home}</a></li>
                        {if $loggeduser_id}
                        <li class="dropdown{if $menu eq 'favorites' or $menu eq 'friends'  or $menu eq 'recommend-movies' or $menu eq 'recommend-shows' or $menu eq 'user'} boot-active{/if}">
                            {if $global_settings.seo_links eq 1}
                                <a href="{$baseurl}/user/{$loggeduser_username}" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.my_account}</a>
                                <ul class="dropdown-menu">
                                    {if $modules.submit_links.status eq 1}
                                        <li><a href="{$baseurl}/submit">{$lang.submit_links}</a></li>
                                    {/if}
                                    <li><a href="{$baseurl}/friends">{$lang.my_friends}</a></li>
                                    <li><a href="{$baseurl}/{$routes.favorites}">{$lang.favorites}</a></li>
                                    <li><a href="{$baseurl}/{$routes.recommend_shows}">{$lang.recommend_shows}</a></li>
                                    <li><a href="{$baseurl}/{$routes.recommend_movies}">{$lang.recommend_movies}</a></li>
                                    {if $plugin_menus.user|@count neq 0}
                                        {foreach from=$plugin_menus.user item=plugin_menu}
                                            {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                                <li><a href="{$baseurl}/plugin/{$plugin_menu.plugin}/{$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                            {/if}
                                        {/foreach}
                                    {/if}                                    
                                    <li><a href="{$baseurl}/logout">{$lang.logout}</a></li>
                                </ul>
                            {else}
                                <a href="{$baseurl}/index.php?menu=user&profile_username={$loggeduser_username}" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.my_account}</a>
                                <ul class="dropdown-menu">
                                    {if $modules.submit_links.status eq 1}
                                        <li><a href="{$baseurl}/index.php?menu=submit">{$lang.submit_links}</a></li>
                                    {/if}
                                    <li><a href="{$baseurl}/index.php?menu=friends">{$lang.my_friends}</a></li>
                                    <li><a href="{$baseurl}/index.php?menu=favorites">{$lang.favorites}</a></li>
                                    <li><a href="{$baseurl}/index.php?menu=recommend_shows">{$lang.recommend_shows}</a></li>
                                    <li><a href="{$baseurl}/index.php?menu=recommend_movies">{$lang.recommend_movies}</a></li>
                                    {if $plugin_menus.user|@count neq 0}
                                        {foreach from=$plugin_menus.user item=plugin_menu}
                                            {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                                <li><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                            {/if}
                                        {/foreach}
                                    {/if}
                                    <li><a href="{$baseurl}/index.php?menu=logout">{$lang.logout}</a></li>
                                </ul>
                            {/if}
                        </li>
                        {/if}
                        
                        {if $modules.tv_shows.status eq 1 or $modules.movies.status}
                            <li class="dropdown{if $menu eq 'new-shows' or $menu eq 'new-movies'} boot-active{/if}">
                                {if $global_settings.seo_links eq 1}
                                    {if $modules.tv_shows.status eq 1}
                                        <a href="{$baseurl}/{$routes.new_episodes}" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.new_videos}</a>
                                    {else}
                                        <a href="{$baseurl}/{$routes.new_movies}" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.new_videos}</a>
                                    {/if}
                                    <ul class="dropdown-menu">
                                        {if $modules.tv_shows.status eq 1}
                                            <li><a href="{$baseurl}/{$routes.new_episodes}">{$lang.new_episodes}</a></li>
                                        {/if}
                                        {if $modules.movies.status eq 1}
                                            <li><a href="{$baseurl}/{$routes.new_movies}">{$lang.new_movies}</a></li>
                                        {/if}
                                    </ul>
                                {else}
                                    {if $modules.tv_shows.status eq 1}
                                        <a href="{$baseurl}/index.php?menu=new-shows" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.new_videos}</a>
                                    {else}
                                        <a href="{$baseurl}/index.php?menu=new-movies" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.new_videos}</a>
                                    {/if}
                                    <ul class="dropdown-menu">
                                        {if $modules.tv_shows.status eq 1}
                                            <li><a href="{$baseurl}/index.php?menu=new-shows">{$lang.new_episodes}</a></li>
                                        {/if}
                                        {if $modules.movies.status eq 1}
                                            <li><a href="{$baseurl}/index.php?menu=new-movies">{$lang.new_movies}</a></li>
                                        {/if}
                                    </ul>
                                {/if}
                            </li>
                        {/if}
                        
                        {if $modules.tv_shows.status eq 1}
                            {if $global_settings.seo_links eq 1}
                                <li class="dropdown{if $menu eq 'tv-shows' or $menu eq 'show' or $menu eq 'episode' or $menu eq 'tv-tag'} boot-active{/if}"><a href="{$baseurl}/{$routes.tv_shows}" class="dropdown-toggle" ><b class="caret"></b>&nbsp;&nbsp;{$lang.tv_shows}</a>
                            {else}
                                <li class="dropdown{if $menu eq 'tv-shows' or $menu eq 'show' or $menu eq 'episode' or $menu eq 'tv-tag'} boot-active{/if}"><a href="{$baseurl}/?menu=tv-shows" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.tv_shows}</a>
                            {/if}
                                {if $tv_categories or $plugin_menus.tv_shows|@count neq 0}
                                    <ul class="dropdown-menu">
                                    {foreach from=$tv_categories key=id item=val}
                                        {if $global_settings.seo_links eq 1}
                                            <li><a href="{$baseurl}/{$routes.tv_tag}/{$val.perma}">{$val.name}</a></li>
                                        {else}
                                            <li><a href="{$baseurl}/index.php?menu=tv-tag&tag={$val.perma}">{$val.name}</a></li>
                                        {/if}
                                    {/foreach}
                                    {if $plugin_menus.tv_shows|@count neq 0}
                                        {foreach from=$plugin_menus.tv_shows item=plugin_menu}
                                            {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                                {if $global_settings.seo_links eq 1} 
                                                    <li><a href="{$baseurl}/plugin/{$plugin_menu.plugin}/{$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                                {else}
                                                    <li><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                                {/if}
                                            {/if}
                                        {/foreach}
                                    {/if}
                                    </ul>
                                {/if}
                            </li>
                        {/if}
                        
                        {if $modules.movies.status eq 1}
                            {if $global_settings.seo_links eq 1}
                                <li class="dropdown{if $menu eq 'movies' or $menu eq 'watchmovie' or $menu eq 'movie-tag'} boot-active{/if}"><a href="{$baseurl}/{$routes.movies}" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.movies}</a>
                            {else}
                                <li class="dropdown{if $menu eq 'movies' or $menu eq 'watchmovie' or $menu eq 'movie-tag'} boot-active{/if}"><a href="{$baseurl}/?menu=movies" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$lang.movies}</a>
                            {/if}
                                {if $movie_categories or $plugin_menus.movies|@count neq 0}
                                    <ul class="dropdown-menu">
                                    {foreach from=$movie_categories key=id item=val}
                                        {if $global_settings.seo_links eq 1}
                                            <li><a href="{$baseurl}/{$routes.movie_tag}/{$val.perma}">{$val.name}</a></li>
                                        {else}
                                            <li><a href="{$baseurl}/index.php?menu=movie-tag&tag={$val.perma}">{$val.name}</a></li>
                                        {/if}
                                    {/foreach}
                                    {if $plugin_menus.movies|@count neq 0}
                                        {foreach from=$plugin_menus.movies item=plugin_menu}
                                            {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                                {if $global_settings.seo_links eq 1} 
                                                    <li><a href="{$baseurl}/plugin/{$plugin_menu.plugin}/{$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                                {else}
                                                    <li><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                                {/if}
                                            {/if}
                                        {/foreach}
                                    {/if}
                                    </ul>
                                {/if}    
                            </li>
                        {/if}
                        
                        {if $modules.requests.status eq 1}
                            {if $global_settings.seo_links eq 1}
                                <li{if $menu eq 'requests'} class="boot-active"{/if}><a href="{$baseurl}/{$routes.requests}">{$lang.requests}</a></li>
                            {else}
                                <li{if $menu eq 'requests'} class="boot-active"{/if}><a href="{$baseurl}/index.php?menu=requests">{$lang.requests}</a></li>
                            {/if}
                        {/if}
                        
                        {foreach from=$pages key=page_id item=page_data}
                            {if $global_settings.seo_links eq 1}
                                   <li class="{if $page_data.children|@count}dropdown{/if}{if $menu eq 'page'} boot-active{/if}">
                                       <a href='{$baseurl}/pages/{$page_data.permalink}' {if $page_data.children|@count}class="dropdown-toggle"{/if}>
                                           {if $page_data.children|@count}<b class="caret"></b>&nbsp;&nbsp;{/if}{$page_data.title}
                                       </a>
                                       {if $page_data.children|@count}
                                           <ul class="dropdown-menu">
                                               {foreach from=$page_data.children key=child_id item=child}
                                                   <li><a href="{$baseurl}/pages/{$child.permalink}">{$child.title}</a></li>
                                               {/foreach}
                                           </ul>
                                       {/if}
                                   </li>
                               {else}
                                   <li class="{if $page_data.children|@count}dropdown{/if}{if $menu eq 'page'} boot-active{/if}">
                                       <a href='{$baseurl}/index.php?menu=page&permalink={$page_data.permalink}' {if $page_data.children|@count}class="dropdown-toggle"{/if}>
                                           {if $page_data.children|@count}<b class="caret"></b>&nbsp;&nbsp;{/if}{$page_data.title}
                                       </a>
                                       {if $page_data.children|@count}
                                           <ul class="dropdown-menu">
                                               {foreach from=$page_data.children key=child_id item=child}
                                                   <li><a href="{$baseurl}/index.php?menu=page&permalink={$child.permalink}">{$child.title}</a></li>
                                               {/foreach}
                                           </ul>
                                       {/if}
                                   </li>
                               {/if}
                        {/foreach}
                        
                        {if $modules.submit_links.status eq 1}
                            {if $loggeduser_id}
                                {if $global_settings.seo_links eq 1}
                                    <li{if $menu eq 'submit'} class="boot-active"{/if}><a href="{$baseurl}/submit">{$lang.submit_module}</a></li>
                                {else}
                                    <li{if $menu eq 'submit'} class="boot-active"{/if}><a href="{$baseurl}/index.php?menu=submit">{$lang.submit_module}</a></li>
                                {/if}
                            {else}
                                <li{if $menu eq 'submit'} class="boot-active"{/if}><a href="javascript:void(0);" onclick="popUp('#popup_login');">{$lang.submit_module}</a></li>
                            {/if}
                        {/if}
                        
                    
                        {if $plugin_menus|@count neq 0}
                            
                            {foreach from=$plugin_menus key=main_key item=plugin_menu_top}
                                {if $main_key neq 'user' and $main_key neq 'tv_shows' and $main_key neq 'movies'}
                                    {if $plugin_menus[$main_key]|@count eq 1}
                                        {foreach from=$plugin_menus[$main_key] item=plugin_menu name=plugin_loop}    
                                            {if $global_settings.seo_links eq 1}
                                                <li class="dropdown{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} boot-active{/if}">
                                                    <a href="{$baseurl}{$plugin_menu.full_url}" class="dropdown-toggle">{$plugin_menu.main_menu_title}</a>
                                                </li>
                                            {else}
                                                <li class="dropdown{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} boot-active{/if}">
                                                    <a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}" class="dropdown-toggle">{$plugin_menu.main_menu_title}</a>
                                                </li>
                                            {/if}
                                        {/foreach}
                                    {else}
                                        {foreach from=$plugin_menus[$main_key] item=plugin_menu name=plugin_loop}    
                                            {if $smarty.foreach.plugin_loop.iteration eq 1}
                                                {if $global_settings.seo_links eq 1}
                                                    <li class="dropdown{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} boot-active{/if}"><a href="{$baseurl}/{$plugin_menu.full_url}" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$plugin_menu.main_menu_title}</a>
                                                    <ul class="dropdown-menu">
                                                {else}
                                                    <li class="dropdown{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} boot-active{/if}"><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.main_menu_url}" class="dropdown-toggle"><b class="caret"></b>&nbsp;&nbsp;{$plugin_menu.main_menu_title}</a>
                                                    <ul class="dropdown-menu">
                                                {/if}
                                            {/if}
                                            {if $global_settings.seo_links eq 1}
                                                <li><a href="{$baseurl}/{$plugin_menu.full_url}">{$plugin_menu.sub_menu_title}</a></li>
                                            {else}
                                                <li><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                            {/if}
                                        {/foreach}
                                        </ul>
                                    </li>
                                    {/if}
                                {/if}
                            {/foreach}
                        {/if}
                    </ul>
                    
                    <form class="navbar-search pull-right" style="float:right;margin-right: -45px;" action="{$baseurl}/index.php" >
                        <input type="hidden" name="menu" value="search" />
                        <input type="text" class="search-query" placeholder="{$lang.search_tip}" id="search-query" name="query" style="width:150px;">
                    </form>
                </div>
            </div>            
        </div>
    </div>
    
    <div id="tips"></div>
        
    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        <!-- MAIN CONTAINER -->
            <!-- HEADER -->
            <div class="container">
                {if $smartbar and $global_settings.smart_bar eq 1}
                    {include file="block_smartbar.tpl"}
                {/if}
                
                {if $widgets.top_ad and (not $loggeduser_username or $widgets.top_ad.logged)}
                    <div class="span-24 separator"></div>
                    <div class="span-24 slider-area-inner">
                        <h1 class="colored left">{$lang.sponsors}</h1>
                        <div class="descr" style="float:right;margin-top: 5px;">
                            {$widgets.top_ad.content}
                        </div>
                    </div>
                    <div class="span-24 separator-inner"></div>
                {else}
                    <p></p>
                {/if}
