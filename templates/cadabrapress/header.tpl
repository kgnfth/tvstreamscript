<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
    <title>{$seo.title}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="{$seo.description}" />
    <meta name="keywords" content="{$seo.keywords}">
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="{$seo.title}"/>
    {if $fb_image neq ''}
        <meta property="og:image" content="{$fb_image}" />
    {/if}
    
    <script>
        var baseurl = '{$baseurl}';
        {if $widgets.iframe_ad and $widgets.iframe_ad.content and (not $loggeduser_id or $widgets.iframe_ad.logged)}
            var iframe_ad = true;
        {else}
            var iframe_ad = false;
        {/if}
    </script>
    
    <meta name="generator" content="TVstreamScript 2.1" />
    
    <link rel="stylesheet" type="text/css" href="{$baseurl}/templates/cadabrapress/style.css" media="screen" />
    <link rel="stylesheet" href="{$templatepath}/css/dropdown.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="{$baseurl}/js/tooltip.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="{$templatepath}/css/bootstrap.css" type="text/css" media="screen" />   
    <!--[if IE 6]><link rel="stylesheet" type="text/css" href="{$baseurl}/templates/cadabrapress/css/ie6.css" /><![endif]-->
    <!--[if IE 7 ]><link rel="stylesheet" type="text/css" href="{$baseurl}/templates/cadabrapress/css/ie7.css" /><![endif]-->
     
    <link rel="stylesheet" href="{$templatepath}/css/pagenavi.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{$templatepath}/js/jquery.rating.css" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="{$templatepath}/css/foxycomplete.css" type="text/css" media="screen,projection" />
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/jquery.rating.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/jquery.MetaData.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/jcarousel.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/script.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/dropdown.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/tabber-minimized.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/jquery.tipsy.js"></script>
    <script type="text/javascript" src="{$baseurl}/js/scripts.js"></script>
    <script type="text/javascript" src="{$templatepath}/js/foxycomplete.js"></script>
    <script>
        js_lang.like = '{$lang.like}';
        js_lang.dislike = '{$lang.dislike}';
        js_lang.facebook_error = '{$lang.unexpected_facebook_error}';
        js_lang.please_wait = '{$lang.please_wait}';
        js_lang.ticker = '{$lang.video_ticker}';
        js_lang.report_thanks = '{$lang.report_thanks}';
    </script>
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

    <div id="page-wrap">
        <div id="header">
            <div id="topbar" class="dropdown">
                <div class="rounded">             
                    <ul id="menu-top" class="topmenu">
                        <li class="{if $menu eq 'home' or $menu eq ''}current-menu-item{/if} menu-item-home">
                            <a href="{$baseurl}">{$lang.home}</a>
                        </li>
                        {if $loggeduser_id}
                            {if $global_settings.seo_links eq 1}
                                <li class="{if $menu eq 'user'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/user/{$loggeduser_username}">{$lang.my_account}</a>
                                </li>
                                
                                {if $modules.submit_links.status eq 1}
                                    <li class="{if $menu eq 'submit'}current-menu-item{/if} menu-item-home">
                                        <a href="{$baseurl}/submit">{$lang.submit_links}</a>
                                    </li>
                                {/if}
                                <li class="{if $menu eq 'favorites'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/{$routes.favorites}">{$lang.favorites}</a>
                                </a>
                                <li class="{if $menu eq 'recommend-shows'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/{$routes.recommend_shows}">{$lang.recommend_shows}</a>
                                </a>
                                
                                <li class="{if $menu eq 'recommend-movies'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/{$routes.recommend_movies}">{$lang.recommend_movies}</a>
                                </li>
                                {if $plugin_menus.user|@count neq 0}
                                    {foreach from=$plugin_menus.user item=plugin_menu}
                                        {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                            <li><a href="{$baseurl}/plugin/{$plugin_menu.plugin}/{$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                        {/if}
                                    {/foreach}
                                {/if} 
                            {else}
                                <li class="{if $menu eq 'user'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/index.php?menu=user&profile_username={$loggeduser_username}">{$lang.my_account}</a>
                                </li>
                                
                                {if $modules.submit_links.status eq 1}
                                    <li class="{if $menu eq 'submit'}current-menu-item{/if} menu-item-home">
                                        <a href="{$baseurl}/index.php?menu=submit">{$lang.submit_links}</a>
                                    </li>
                                {/if}
                                <li class="{if $menu eq 'favorites'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/index.php?menu=favorites">{$lang.favorites}</a>
                                </a>
                                <li class="{if $menu eq 'recommend-shows'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/index.php?menu=recommend-shows">{$lang.recommend_shows}</a>
                                </a>
                                
                                <li class="{if $menu eq 'recommend-movies'}current-menu-item{/if} menu-item-home">
                                    <a href="{$baseurl}/index.php?menu=recommend-movies">{$lang.recommend_movies}</a>
                                </li>
                                {if $plugin_menus.user|@count neq 0}
                                    {foreach from=$plugin_menus.user item=plugin_menu}
                                        {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                            <li><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                        {/if}
                                    {/foreach}
                                {/if} 
                            
                            {/if}
                        {/if}
                    </ul>                     
                    <div class="user-bar">
                        {if $loggeduser_id}
                            {if $global_settings.seo_links}
                                Logged in as <strong>{$loggeduser_username}</strong> <a href="{$baseurl}/logout">Logout</a>
                            {else}
                                Logged in as <strong>{$loggeduser_username}</strong> <a href="{$baseurl}/index.php?menu=logout">Logout</a>
                            {/if}
                        {else}
                            &nbsp;
                        {/if}                     
                    </div>
                    {if not $loggeduser_id}
                    <div class="login-form">
                         <div class="login-tab" id="login-button">{$lang.login}</div>
                         <div class="register-tab">
                             {if $global_settings.seo_links}
                                <a href="{$baseurl}/register">{$lang.register}</a>
                            {else}
                                <a href="{$baseurl}/index.php?menu=register">{$lang.register}</a>
                            {/if}
                         </div>
                         <div class="clear"></div>
                        {if $global_settings.seo_links}
                            <form action="{$baseurl}/login" method="post" id="loginForm" style="display:none">
                        {else}
                            <form action="{$baseurl}/index.php" method="post" id="loginForm" style="display:none">
                            <input type="hidden" name="menu" value="login" />
                        {/if}
                            <fieldset>
                                <div class="inputs">
                                    <div class="input">
                                        <input type="text" name="username" id="log" value="{$username}" placeholder="{$lang.username}"    />
                                    </div>
                                    <div class="input">
                                         <input type="password" name="password" id="pwd"  placeholder="{$lang.password}" />
                                    </div>
                                </div>
                                <div class="button">
                                    <input type="hidden" name="returnpath" value="{$current_url}" />
                                    <input type="submit" name="userlogin" value="{$lang.login}" />
                                </div>
                            </fieldset>
                            
                            {if $global_settings.facebook}
                                <center>
                                    <img src="{$templatepath}/images/fb_login.jpg" style="cursor:pointer" onclick="facebookDoLogin('#fb_login_button');" id="fb_login_button" />                    
                                </center>
                            {/if}
                        </form>
                    </div>
                    {/if}
                    <div class="clear"></div>
                </div> <!-- /.rounded -->
            
            </div><!-- /top-bar -->
            
            
            <div class="logo">
                <a href="{$baseurl}">
                    <img src="{$templatepath}/images/logo.png" alt="CadabraPress Theme" />                
                </a>
                 <span>&nbsp;&nbsp;Edit header.tpl to modify this text</span>
            </div>
            
            <div class="adv">
                {if $widgets.top_ad and (not $loggeduser_username or $widgets.top_ad.logged)}
                    {$widgets.top_ad.content}
                {/if}
            </div>
            
        
            <!-- Main Menu -->
            <div id="menu" class="dropdown">
                <div class="rounded noborder">
                    <ul id="menu-main" class="mainmenu">
                        {if $modules.tv_shows.status or $modules.movies.status}
                            <li class="menu-item {if $menu eq 'new-shows' or $menu eq 'new-movies'} current-menu-item current_page_item{/if}">
                                {if $global_settings.seo_links eq 1}
                                    {if $modules.tv_shows.status eq 1}
                                        <a href="{$baseurl}/{$routes.new_episodes}" class="sf-with-ul">{$lang.new_videos}</a>
                                    {else}
                                        <a href="{$baseurl}/{$routes.new_movies}" class="sf-with-ul">{$lang.new_videos}</a>
                                    {/if}
                                    <ul class="sub-menu">
                                        {if $modules.tv_shows.status}
                                            <li class="menu-item"><a href="{$baseurl}/{$routes.new_episodes}">{$lang.new_episodes}</a></li>
                                        {/if}
                                        {if $modules.movies.status}
                                            <li class="menu-item"><a href="{$baseurl}/{$routes.new_movies}">{$lang.new_movies}</a></li>
                                        {/if}
                                    </ul>
                                {else}
                                    {if $modules.tv_shows.status}
                                        <a href="{$baseurl}/index.php?menu=new-shows" class="sf-with-ul">{$lang.new_videos}</a>
                                    {else}
                                        <a href="{$baseurl}/index.php?menu=new-movies" class="sf-with-ul">{$lang.new_videos}</a>
                                    {/if}
                                    <ul class="sub-menu">
                                        {if $modules.tv_shows.status}
                                            <li class="menu-item"><a href="{$baseurl}/index.php?menu=new-shows">{$lang.new_episodes}</a></li>
                                        {/if}
                                        {if $modules.movies.status}
                                            <li class="menu-item"><a href="{$baseurl}/index.php?menu=new-movies">{$lang.new_movies}</a></li>
                                        {/if}
                                    </ul>
                                {/if}
                            </li>
                        {/if}
                        
                        
                        {if $modules.tv_shows.status eq 1}
                            {if $global_settings.seo_links eq 1}
                                <li class="menu-item {if $menu eq 'tv-shows' or $menu eq 'show' or $menu eq 'episode' or $menu eq 'tv-tag'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/{$routes.tv_shows}" >{$lang.tv_shows}</a>
                            {else}
                                <li class="menu-item {if $menu eq 'tv-shows' or $menu eq 'show' or $menu eq 'episode' or $menu eq 'tv-tag'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/?menu=tv-shows">{$lang.tv_shows}</a>
                            {/if}
                                {if $tv_categories or $plugin_menus.tv_shows|@count neq 0}
                                    <ul class="sub-menu">
                                    {foreach from=$tv_categories key=id item=val}
                                        {if $global_settings.seo_links eq 1}
                                            <li class="menu-item"><a href="{$baseurl}/{$routes.tv_tag}/{$val.perma}">{$val.name}</a></li>
                                        {else}
                                            <li class="menu-item"><a href="{$baseurl}/index.php?menu=tv-tag&tag={$val.perma}">{$val.name}</a></li>
                                        {/if}
                                    {/foreach}
                                    {if $plugin_menus.tv_shows|@count neq 0}
                                        {foreach from=$plugin_menus.tv_shows item=plugin_menu}
                                            {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                                {if $global_settings.seo_links eq 1} 
                                                    <li class="menu-item"><a href="{$baseurl}/plugin/{$plugin_menu.plugin}/{$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                                {else}
                                                    <li class="menu-item"><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
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
                                <li class="menu-item {if $menu eq 'movies' or $menu eq 'watchmovie' or $menu eq 'movie-tag'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/{$routes.movies}">{$lang.movies}</a>
                            {else}
                                <li class="menu-item {if $menu eq 'movies' or $menu eq 'watchmovie' or $menu eq 'movie-tag'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/?menu=movies">{$lang.movies}</a>
                            {/if}
                                {if $movie_categories or $plugin_menus.movies|@count neq 0}
                                    <ul class="sub-menu">
                                    {foreach from=$movie_categories key=id item=val}
                                        {if $global_settings.seo_links eq 1}
                                            <li class="menu-item"><a href="{$baseurl}/{$routes.movie_tag}/{$val.perma}">{$val.name}</a></li>
                                        {else}
                                            <li class="menu-item"><a href="{$baseurl}/index.php?menu=movie-tag&tag={$val.perma}">{$val.name}</a></li>
                                        {/if}
                                    {/foreach}
                                    {if $plugin_menus.movies|@count neq 0}
                                        {foreach from=$plugin_menus.movies item=plugin_menu}
                                            {if $plugin_menu.sub_menu_url and $plugin_menu.sub_menu_title and $plugin_menu.plugin}
                                                {if $global_settings.seo_links eq 1} 
                                                    <li class="menu-item"><a href="{$baseurl}/plugin/{$plugin_menu.plugin}/{$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                                {else}
                                                    <li class="menu-item"><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
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
                                <li class="menu-item{if $menu eq 'requests'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/{$routes.requests}">{$lang.requests}</a></li>
                            {else}
                                <li class="menu-item{if $menu eq 'requests'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/index.php?menu=requests">{$lang.requests}</a></li>
                            {/if}
                        {/if}
                        
                        {if $modules.submit_links.status eq 1}
                            {if $loggeduser_id}
                                {if $global_settings.seo_links eq 1}
                                    <li class="menu-item{if $menu eq 'submit'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/submit">{$lang.submit_module}</a></li>
                                {else}
                                    <li class="menu-item{if $menu eq 'submit'} current-menu-item current_page_item{/if}"><a href="{$baseurl}/index.php?menu=submit">{$lang.submit_module}</a></li>
                                {/if}
                            {else}
                                <li class="menu-item{if $menu eq 'submit'} current-menu-item current_page_item{/if}"><a href="javascript:void(0);" onclick="popUp('#popup_login');">{$lang.submit_module}</a></li>
                            {/if}
                        {/if}
                        
                        {foreach from=$pages key=page_id item=page_data}
                            {if $global_settings.seo_links eq 1}
                                   <li class="menu-item{if $menu eq 'page'} current-menu-item current_page_item{/if}">
                                       <a href="{$baseurl}/pages/{$page_data.permalink}">{$page_data.title}</a>
                                       {if $page_data.children|@count}
                                           <ul class="sub-menu">
                                               {foreach from=$page_data.children key=child_id item=child}
                                                   <li class="menu-item"><a href="{$baseurl}/pages/{$child.permalink}">{$child.title}</a></li>
                                               {/foreach}
                                           </ul>
                                       {/if}
                                   </li>
                               {else}
                                   <li class="menu-item{if $menu eq 'page'} current-menu-item current_page_item{/if}">
                                       <a href="{$baseurl}/index.php?menu=page&permalink={$page_data.permalink}">
                                           {$page_data.title}
                                       </a>
                                       {if $page_data.children|@count}
                                           <ul class="sub-menu">
                                               {foreach from=$page_data.children key=child_id item=child}
                                                   <li class="menu-item"><a href="{$baseurl}/index.php?menu=page&permalink={$child.permalink}">{$child.title}</a></li>
                                               {/foreach}
                                           </ul>
                                       {/if}
                                   </li>
                               {/if}
                        {/foreach}
                        
                        {if $plugin_menus|@count neq 0}
                            
                            {foreach from=$plugin_menus key=main_key item=plugin_menu_top}
                                {if $main_key neq 'user' and $main_key neq 'tv_shows' and $main_key neq 'movies'}
                                    {if $plugin_menus[$main_key]|@count eq 1}
                                        {foreach from=$plugin_menus[$main_key] item=plugin_menu name=plugin_loop}    
                                            {if $global_settings.seo_links eq 1}
                                                <li class="menu-item{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} current-menu-item current_page_item{/if}">
                                                    <a href="{$baseurl}{$plugin_menu.full_url}">{$plugin_menu.main_menu_title}</a>
                                                </li>
                                            {else}
                                                <li class="menu-item{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} current-menu-item current_page_item{/if}">
                                                    <a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.main_menu_title}</a>
                                                </li>
                                            {/if}
                                        {/foreach}
                                    {else}
                                        {foreach from=$plugin_menus[$main_key] item=plugin_menu name=plugin_loop}    
                                            {if $smarty.foreach.plugin_loop.iteration eq 1}
                                                {if $global_settings.seo_links eq 1}
                                                    <li class="menu-item{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} current-menu-item current_page_item{/if}">
                                                        <a href="{$baseurl}/{$plugin_menu.full_url}">{$plugin_menu.main_menu_title}</a>
                                                        <ul class="sub-menu">
                                                {else}
                                                    <li class="menu-item{if $menu eq 'plugin' and $plugin eq $plugin_menu.plugin} current-menu-item current_page_item{/if}">
                                                        <a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.main_menu_url}">{$plugin_menu.main_menu_title}</a>
                                                        <ul class="sub-menu">
                                                {/if}
                                            {/if}
                                            {if $global_settings.seo_links eq 1}
                                                <li class="menu-item"><a href="{$baseurl}/{$plugin_menu.full_url}">{$plugin_menu.sub_menu_title}</a></li>
                                            {else}
                                                <li class="menu-item"><a href="{$baseurl}/index.php?menu=plugin&plugin={$plugin_menu.plugin}&plugin_menu={$plugin_menu.sub_menu_url}">{$plugin_menu.sub_menu_title}</a></li>
                                            {/if}
                                        {/foreach}
                                        </ul>
                                    </li>
                                    {/if}
                                {/if}
                            {/foreach}
                        {/if}
                    </ul>
                    <!-- 
                    <div class="rss">
                        {if $global_settings.seo_links}
                            {if $menu eq 'show'}
                                <a href="{$baseurl}/{$val.perma}/feed">Show RSS</a>
                            {else}
                                <a href="{$baseurl}/feed">TV shows RSS</a>
                            {/if}
                        {else}
                            {if $menu eq 'show'}
                                <a href="{$baseurl}/rss.php?perma={$val.perma}">Show RSS</a>
                            {else}
                                <a href="{$baseurl}/rss.php">TV shows RSS</a>
                            {/if}
                        {/if}
                    </div>
                         -->
                    <div class="search">
                        <form action="{$baseurl}/index.php" method="post" id="searchform">
                            <fieldset>
                            <input type="hidden" name="menu" value="search"/>
                            <span class="search-text"><i></i> 
                                <input type="text" id="search-query" name="query"  placeholder="Search" />
                            </span>
                            </fieldset> 
                        </form>
                    </div>
                
                </div>
            </div><!-- /menu -->

         </div><!-- /header -->
         
{include file="sidebar.tpl" title=sidebar}