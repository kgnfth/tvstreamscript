<div class="span-8 last">
	{if $widgets.side_bar_1 and (not $loggeduser_username or $widgets.side_bar_1.logged)}
		<div class="span-8 last notopmargin side-bar">
			{$widgets.side_bar_1.content}
       	</div>
       	<div class="span-8 last side-bar">
   	{else}
       	<div class="span-8 last notopmargin side-bar">
   	{/if}
	{if $loggeduser_id eq 0}
		<h5>{$lang.login} / {$lang.register}</h5>
		<form action="{$baseurl}/login" method="post" id="search-form" class="login-form">
			{$lang.username}:
			<div class="clear"></div>
			<input type="text" name="username" value="{$username}" style="margin: 5px 0px 5px 0px;width:100%;" />
			<div class="clear"></div>
					
			{$lang.password}: 
			<input type="password" name="password" style="margin: 5px 0px 10px 0px;width:100%;" />
			<div class="clear"></div>
							
			<input type="hidden" name="returnpath" id="returnpath" value="{$current_url}" />
			<a class="btn tab01c grey" href="javascript:void(0);" onclick="jQuery('.login-form').submit()" name="userlogin" style="width:40%;margin:0px;float:left;cursor:pointer;">{$lang.login}</a>
			{if $global_settings.seo_links eq 1}
				<a class="btn tab01d grey" href="{$baseurl}/register" style="width:40%;margin:0px;float:right;cursor:pointer;">{$lang.register}</a>
			{else}
				<a class="btn tab01d grey" href="{$baseurl}/index.php?menu=register" style="width:40%;margin:0px;float:right;cursor:pointer;">{$lang.register}</a>
			{/if}
			<div class="clear"></div>
		</form>

		{if $global_settings.facebook}
			<center>
				<div class="clear"></div><br />
				<span style="font-size: 18px; font-weight:bold">{$lang.or_caps}</span><br />
				<div class="clear"></div><br />
				<img src="{$templatepath}/images/fb_login.jpg" style="cursor:pointer" onclick="facebookDoLogin('#fb_login_button');" id="fb_login_button" />

				<div class="clear"></div><br />
			</center>
		{/if}
    {else}
			<h5>{$lang.welcome_back} {$loggeduser_username}</h5>
			<div class="span-2 left notopmargin" style="margin-right: 0px;">
				<div class="flickr item left last"><a href="javascript:void(0);" onclick="popUp('#avatar_form');" class=""><img alt="" class="tooltip" original-title="{$lang.change_avatar}" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$loggeduser_details.avatar}&amp;w=40&amp;h=40&amp;zc=1" style="width:40px; height:40px"></a></div>
			</div>
			<div class="span-6 left notopmargin last">
				<ul class="span-5" id="filter" style="margin-top:5px;">
					{if $global_settings.seo_links eq 1}
						{if $modules.submit_links.status eq 1}
							<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'submit'} class="current"{/if}><a href="{$baseurl}/submit">{$lang.submit_links}</a></li>
						{/if}
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'user'} class="current"{/if}><a href="{$baseurl}/user/{$loggeduser_username}">{$lang.my_account}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'friends'} class="current"{/if}><a href="{$baseurl}/friends">{$lang.my_friends}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'favorites'} class="current"{/if}><a href="{$baseurl}/{$routes.favorites}">{$lang.favorites}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'recommend-shows'} class="current"{/if}><a href="{$baseurl}/{$routes.recommend_shows}">{$lang.recommend_shows}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'recommend-movies'} class="current"{/if}><a href="{$baseurl}/{$routes.recommend_movies}">{$lang.recommend_movies}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"><a href="javascript:void(0);" onclick="popUp('#settings_form');">{$lang.settings}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"><a href="{$baseurl}/logout">{$lang.logout}</a></li>
					{else}
						{if $modules.submit_links.status eq 1}
							<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'submit'} class="current"{/if}><a href="{$baseurl}/index.php?menu=submit">{$lang.submit_links}</a></li>
						{/if}
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'user'} class="current"{/if}><a href="{$baseurl}/index.php?menu=user&profile_username={$loggeduser_username}">{$lang.my_account}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'friends'} class="current"{/if}><a href="{$baseurl}/index.php?menu=friends">{$lang.my_friends}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'favorites'} class="current"{/if}><a href="{$baseurl}/index.php?menu=favorites">{$lang.favorites}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'recommend-shows'} class="current"{/if}><a href="{$baseurl}/index.php?menu=recommend-shows">{$lang.recommend_shows}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"{if $menu eq 'recommend-movies'} class="current"{/if}><a href="{$baseurl}/index.php?menu=recommend-movies">{$lang.recommend_movies}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"><a href="javascript:void(0);" onclick="popUp('#settings_form');">{$lang.settings}</a></li>
						<li style="float:none; margin: 0px 0px 5px 0px;"><a href="{$baseurl}/index.php?menu=logout">{$lang.logout}</a></li>
					{/if}
				</ul>
			</div>
	{/if}
			
</div>
            	
		{if $widgets.side_bar_2 and (not $loggeduser_username or $widgets.side_bar_2.logged)}
			<div class="span-8 last side-bar">
				{$widgets.side_bar_2.content}
			</div>
		{/if}

		{if $widgets.like_box and (not $loggeduser_username or $widgets.like_box.logged)}
			<div class="span-8 last side-bar">
		       	<h5>{$lang.like_us}</h5>
				{$widgets.like_box.content}
			</div>
		{/if}
	            
		{if $similar_shows}
			<div class="span-8 last side-bar">
               	<h5>{$lang.similar_shows}</h5>
				{php} $similar_counter=1; {/php}
				{foreach from=$similar_shows key=key item=val name=similar_iterator}
					<div class="flickr item left {php}if ($similar_counter%4 == 0){ print('last'); } {/php}">
						{if $global_settings.seo_links eq 1}
						<a href="{$baseurl}/{$val.permalink}" class="">
							<img alt="" class="tooltip" original-title="{$val.title}" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=40&amp;h=40&amp;zc=1">
						</a>
						{else}
						<a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}" class="">
							<img alt="" class="tooltip" original-title="{$val.title}" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=40&amp;h=40&amp;zc=1">
						</a>									
						{/if}
					</div>
				
					{php}if ($similar_counter%4 == 0){ print('<div class="clear"></div>'); } {/php}
					{php}$similar_counter++{/php}
				{/foreach}
            </div>
		{/if}
</div>
<!-- END SUBPAGE CONTENT -->
<div class="clear"></div>