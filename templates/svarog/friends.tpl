{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-16">
	
	<h1>{$lang.friends_activity}</h1>
	<br />
	<div id="friend_stream"></div>
	<div class="clear"></div><br />
	<script>
		friendStream({$loggeduser_id});
	</script>
	
	<div class="clear"></div>
	
	<h4>{$lang.friends_people_you_follow}</h4>
	
	{if $followed|@count eq 0}
		{$lang.friends_no_follow}
	{else}
		{php} $i=1; {/php}
		{foreach from=$followed key=id item=user}
		
			<div class="flickr item left" {php} if ($i%10!=0) print('style="margin-right:10px"'); {/php}>
				{if $global_settings.seo_links}
					<a href="{$baseurl}/user/{$user.username}"  class="">
						<img class="tooltip" original-title="{$user.username}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$user.avatar}&amp;w=43&amp;h=43&amp;zc=1" style="width:43px; height:43px">
					</a>
				{else}
					<a href="{$baseurl}/index.php?menu=user&profile_username={$user.username}"  class="">
						<img class="tooltip" original-title="{$user.username}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$user.avatar}&amp;w=43&amp;h=43&amp;zc=1" style="width:43px; height:43px">
					</a>
				{/if}
			</div>
			
			{php}if ($i%10 == 0){ print('<div class="clear"></div>'); } {/php}
			{php} $i++; {/php}
		{/foreach}
	{/if}
	<div class="clear"></div><br />
	
	<h4>{$lang.friends_people_following_you}</h4>
	
	{if $followers|@count eq 0}
		{$lang.friends_no_followers}
	{else}
		{php} $i=1; {/php}
		{foreach from=$followers key=id item=user}
		
			<div class="flickr item left" {php} if ($i%10!=0) print('style="margin-right:10px"'); {/php}>
				{if $global_settings.seo_links}
					<a href="{$baseurl}/user/{$user.username}"  class="">
						<img class="tooltip" original-title="{$user.username}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$user.avatar}&amp;w=43&amp;h=43&amp;zc=1" style="width:43px; height:43px">
					</a>
				{else}
					<a href="{$baseurl}/index.php?menu=user&profile_username={$user.username}"  class="">
						<img class="tooltip" original-title="{$user.username}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$user.avatar}&amp;w=43&amp;h=43&amp;zc=1" style="width:43px; height:43px">
					</a>
				{/if}
			</div>
			
			{php}if ($i%10 == 0){ print('<div class="clear"></div>'); } {/php}
			{php} $i++; {/php}
		{/foreach}
	{/if}
	<div class="clear"></div><br />
	
</div>

{include file="sidebar.tpl" title=sidebar}

{include file="footer.tpl" title=footer}