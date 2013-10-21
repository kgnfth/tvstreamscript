{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if !$loggeduser_id}
<div class="span-16">
	<h1>{$lang.recommend_shows_session_expired}</h1>
	
	<p>{$lang.recommend_shows_we_are_sorry}</p>
</div>
{else}
<div class="span-16">
	<h2>{$lang.recommend_shows_recommendations}</h2>
	{if $result_type eq 'random'}
		<p>{$lang.recommend_shows_no_show}</p>
	{else}
		<p>{$lang.recommend_shows_show}</p>
	{/if}
	
	{foreach from=$shows key=id item=show}
		<div class="span-16 inner-16 notopmargin last view">
			<h4 class="news">
				{if $global_settings.seo_links}
					<a href="{$baseurl}/{$routes.show}/{$show.permalink}" class="link">{$show.title}</a>
				{else}
					<a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}" class="link">{$show.title}</a>
				{/if}
			</h4>
			<div class="clear"></div>
			<div class="span-4 left tt" style="margin-top:10px; margin-bottom: 15px;">
				<div class="flickr item left">
					{if $global_settings.seo_links}
						<a href="{$baseurl}/{$routes.show}/{$show.permalink}"  class="" style="margin-bottom:0px;">
							<img alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=140&amp;h=180&amp;zc=1" style="width:140px; height:180px">
						</a>
					{else}
						<a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}"  class="" style="margin-bottom:0px;">
							<img alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=140&amp;h=180&amp;zc=1" style="width:140px; height:180px">
						</a>					
					{/if}
				</div>
				<span>
					<ul>
						{if $global_settings.seo_links}
							<li><img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="{$baseurl}/{$routes.show}/{$show.permalink}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
						{else}
							<li><img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
						{/if}
						{if $loggeduser_id neq ''}
							<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addLike({$show.id},1,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
							<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="addLike({$show.id},1,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
						{else}
							<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
							<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
						{/if}
					</ul>
				</span>
			</div>
			<div class="span-10 left" style="margin-top:10px">
				<p>{$show.description}</p>
			</div>
			<div class="clear"></div>			
		</div>
		<div class="clear"></div><br />
	{/foreach}
</div>
{/if}

{include file="sidebar.tpl" title=sidebar}

{include file="footer.tpl" title=footer}