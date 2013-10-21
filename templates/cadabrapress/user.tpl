{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if $no_user}
	{include file="404.tpl" title=404}
{else}

	<div class="archive">
		<h3>
			{$lang.user_profile_title|replace:'#username#':$profile_user.username}
		</h3>
		<div class="rounded">
			<div class="post_author">
				{if $loggeduser_id eq $profile_user.id}
					<a href="javascript:void(0);" onclick="popUp('#avatar_form');">
						<img alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$profile_user.avatar}&amp;w=70&amp;h=70&amp;zc=1" />
					</a>
				{else}
					<a href="{$baseurl}/thumbs/users/{$profile_user.avatar}" title="{$profile_user.username}">
						<img  alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$profile_user.avatar}&amp;w=70&amp;h=70&amp;zc=1"  />
					</a>
				{/if}
						
				<span>{$lang.user_profile_title|replace:'#username#':$profile_user.username}</span>
				{if $loggeduser_id eq 0}
					<a href="javascript:void(0);" onclick="popUp('#popup_login');">{$lang.user_follow|replace:'#username#':$profile_user.username}</a>
				{else}
					{if $loggeduser_id neq $profile_user.id}
						<a href="javascript:void(0);" onclick="follow({$profile_user.id});" {if $is_follower}style="display:none;"{/if}>{$lang.user_follow|replace:'#username#':$profile_user.username}</a>
						<a href="javascript:void(0);" onclick="unfollow({$profile_user.id});" {if not $is_follower}style="display:none;"{/if}>{$lang.user_stop_following|replace:'#username#':$profile_user.username}</a>
					{else}
						<a href="javascript:void(0);" onclick="popUp('#avatar_form');">{$lang.change_avatar}</a>
					{/if}
				{/if}				
			</div>
			
			<div class="clear"></div><br />	
			
			<div id="user_stream"></div>
				
			<script>
				userStream({$profile_user.id},0,'user_stream');
			</script>
						
			<div id="user_favorites">
				<h2>{$lang.user_favorite_movies|replace:'#username#':$profile_user.username}</h2>
				
				<div class="user_favorites">
					{if $profile_favorite_movies|@count eq 0}
						{$lang.user_no_favorite_movies|replace:'#username#':$profile_user.username}
					{else}
						{foreach from=$profile_favorite_movies key=id item=movie}
							{if $global_settings.seo_links}
								<a href="{$baseurl}/{$routes.movie}/{$movie.perma}"  class="">
									<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
								</a>
							{else}
								<a href="{$baseurl}/?menu=watchmovie&perma={$movie.perma}"  class="">
									<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
								</a>
							{/if}
						{/foreach}
					{/if}
				</div>
				<div class="clear"></div><br />
				
				<h2>{$lang.user_favorite_shows|replace:'#username#':$profile_user.username}</h2>
				
				<div class="user_favorites">
					{if $profile_favorite_shows|@count eq 0}
						{$lang.user_no_favorite_shows|replace:'#username#':$profile_user.username}
					{else}
						{foreach from=$profile_favorite_shows key=id item=show}
							{if $global_settings.seo_links}
								<a href="{$baseurl}/{$routes.show}/{$show.permalink}"  class="">
									<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
								</a>
							{else}
								<a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}"  class="">
									<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=43&amp;h=60&amp;zc=1" style="width:43px; height:60px">
								</a>
							{/if}						
						{/foreach}
					{/if}
				</div>
				<div class="clear"></div><br />
			</div>
			<div class="clear"></div><br /><br />
		</div>
	</div>

{/if}
{include file="footer.tpl" title=footer}