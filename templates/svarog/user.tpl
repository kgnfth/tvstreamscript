{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-16">
	{if $no_user eq 1}
		<h1>{$lang.user_error}</h1>
		
		<center>
			<br /><br />
			{$lang.user_no_user}
		</center>
	{else}
		<div class="span-4 left notopmargin">
			<div class="flickr item">
				{if $loggeduser_id eq $profile_user.id}
				<a href="javascript:void(0);" style="margin-right: 10px;" onclick="popUp('#avatar_form');">
					<img alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$profile_user.avatar}&amp;w=120&amp;h=120&amp;zc=1" style="width:120px; height:120px;" />
				</a>
				{else}
				<a href="{$baseurl}/thumbs/users/{$profile_user.avatar}" style="margin-right: 10px;" rel="prettyPhoto" title="{$profile_user.username}">
					<img  alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$profile_user.avatar}&amp;w=120&amp;h=120&amp;zc=1" style="width:120px; height:120px;" />
				</a>
				{/if}
			</div>
		</div>
		<div class="span-11 left notopmargin">
			<h1>{$lang.user_profile_title|replace:'#username#':$profile_user.username}</h1>
			{if $loggeduser_id eq 0}
				<ul id="filter" style="padding-left:0px; float:left; margin:8px auto;">	
					<li style="margin-left:0px;float:left">
						<a href="javascript:void(0);" onclick="popUp('#popup_login');">{$lang.user_follow|replace:'#username#':$profile_user.username}</a>
					</li>
				</ul>			
			{else}
				{if $loggeduser_id neq $profile_user.id}
					<ul id="filter" style="padding-left:0px; float:left; margin:8px auto;">
						
					
						<li id="follow_button" style="margin-left:0px;float:left;{if $is_follower}display:none;{/if}">
							<a href="javascript:void(0);" onclick="follow({$profile_user.id});">{$lang.user_follow|replace:'#username#':$profile_user.username}</a>
						</li>
						<li id="unfollow_button" class="current" style="margin-left:0px;float:left;{if not $is_follower}display:none;{/if}">
							<a href="javascript:void(0);" onclick="unfollow({$profile_user.id});">{$lang.user_stop_following|replace:'#username#':$profile_user.username}</a>
						</li>	
					</ul>
					
				{else}
					<ul id="filter" style="padding-left:0px; float:left; margin:8px auto;">	
						<li style="margin-left:0px;float:left">
							<a href="javascript:void(0);" onclick="popUp('#avatar_form');">{$lang.change_avatar}</a>
						</li>
						<li style="margin-left:0px;float:left">
							{if $global_settings.seo_links}
								<a href="{$baseurl}/friends">{$lang.my_friends}</a>
							{else}
								<a href="{$baseurl}/index.php?menu=friends">{$lang.my_friends}</a>
							{/if}
						</li>
					</ul>
				{/if}
			{/if}
		</div>
		
		<div class="clear"></div><br />
		
		<div id="user_stream"></div>
		<div class="clear"></div><br />
		<script>
			userStream({$profile_user.id},0,'user_stream');
		</script>
		
		<h4>{$lang.user_favorite_movies|replace:'#username#':$profile_user.username}</h4>
		{if $profile_favorite_movies|@count eq 0}
			{$lang.user_no_favorite_movies|replace:'#username#':$profile_user.username}
		{else}
			{php} $i=1; {/php}
			{foreach from=$profile_favorite_movies key=id item=movie}
			
				<div class="flickr item left" {php} if ($i%10!=0) print('style="margin-right:10px"'); {/php}>
					{if $global_settings.seo_links}
						<a href="{$baseurl}/{$routes.movie}/{$movie.perma}"  class="">
							<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
						</a>
					{else}
						<a href="{$baseurl}/?menu=watchmovie&perma={$movie.perma}"  class="">
							<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
						</a>
					{/if}
				</div>
				
				{php}if ($i%10 == 0){ print('<div class="clear"></div>'); } {/php}
				{php} $i++; {/php}
			{/foreach}
		{/if}
		<div class="clear"></div><br />
		
		<h4>{$lang.user_favorite_shows|replace:'#username#':$profile_user.username}</h4>
		{if $profile_favorite_shows|@count eq 0}
			{$lang.user_no_favorite_shows|replace:'#username#':$profile_user.username}
		{else}
			{php} $i=1; {/php}
			{foreach from=$profile_favorite_shows key=id item=show}
			
				<div class="flickr item left" {php} if ($i%10!=0) print('style="margin-right:10px"'); {/php}>
					{if $global_settings.seo_links}
						<a href="{$baseurl}/{$routes.show}/{$show.permalink}"  class="">
							<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
						</a>
					{else}
						<a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}"  class="">
							<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
						</a>
					{/if}
				</div>
				
				{php}if ($i%10 == 0){ print('<div class="clear"></div>'); } {/php}
				{php} $i++; {/php}
			{/foreach}
		{/if}
		<div class="clear"></div><br />
		
	{/if}
</div>

{include file="sidebar.tpl" title=sidebar}
{include file="footer.tpl" title=footer}