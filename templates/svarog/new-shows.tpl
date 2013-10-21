{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}
<div class="span-24" id="portfolio">
	<h1>{$lang.new_shows_new_episodes}</h1>
	<div class="left">{$lang.pages}: </div>
    <div class="left filter-title myfilter">
		<ul id="filter">
			{if $global_settings.seo_links}
				<li class="current"><a href="{$baseurl}/{$routes.new_episodes}">{$lang.new_shows_new_episodes}</a></li>
				<li><a href="{$baseurl}/{$routes.new_movies}">{$lang.new_shows_new_movies}</a></li>
			{else}
				<li class="current"><a href="{$baseurl}/index.php?menu=new-shows">{$lang.new_shows_new_episodes}</a></li>
				<li><a href="{$baseurl}/index.php?menu=new-movies">{$lang.new_shows_new_movies}</a></li>			
			{/if}
		</ul>
	</div>
	
	<div class="clear"></div>
	<ul class="span-24" id="portfolio">
		{php} $i=1; {/php}
		{if $episodes neq ''}
			{foreach from=$episodes key=key item=val name=show_iterator}
		    	<li>
		        	<div class="span-6 inner-6 tt {php}if ($i%4 == 0){ print('last'); } {/php} view">
		            	<div class="item" style="text-align:center">
		            		{if $global_settings.seo_links}
			            		<a href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" class="spec-border-ie" title="">
			            			<img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=130&amp;zc=1" alt=" " style="width:190px;height:130px;background-color: #717171;"/>
			            		</a>
		            		{else}
			            		<a href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" class="spec-border-ie" title="">
			            			<img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=130&amp;zc=1" alt=" " style="width:190px;height:130px;background-color: #717171;"/>
			            		</a>
		            		{/if}
		            	</div>
		                <h5>
		                	{if $global_settings.seo_links}
		                		<a class="link" href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" title="{$val.showtitle}">
		                	{else}
		                		<a class="link" href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" title="{$val.showtitle}">
		                	{/if}
				            {$val.showtitle|truncate:25:"...":true}
		                	</a>
		                </h5>
						<p class="left">{$lang.new_shows_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}</p>
		                {if $val.seen}
							<div id="seen{$val.epid}"><a class="seen right" href="javascript:void(0);"></a></div>
						{else}
							<div id="seen{$val.epid}"></div>						
						{/if}
						<div class="clear"></div>
						
						<div class="left">
						{foreach from=$val.languages item=flag key=key}
							<img src="{$embed_languages[$flag].flag}" style="margin-right: 1px;"/>
						{/foreach}
						</div>
						

						<span>
							<ul>
								<li>
									<img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" />
									{if $global_settings.seo_links}
										<a href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" class="left">{$lang.watch_this}</a>
									{else}
										<a href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" class="left">{$lang.watch_this}</a>
									{/if}
								</li>
								<div class="clear"></div>
								{if $loggeduser_id neq ''}
									<li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addWatch({$val.epid},3);" class="left" id="watch_button_{$val.epid}">{$lang.seen_it}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addLike({$val.epid},3,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addLike({$val.epid},3,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/film.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="{$baseurl}/{$routes.show}/{$val.permalink}" class="left">{$lang.episode_list}</a></li><div class="clear"></div>
								{else}
									<li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.seen_it}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/film.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="{$baseurl}/{$routes.show}/{$val.permalink}" class="left">{$lang.episode_list}</a></li><div class="clear"></div>
								{/if}
							</ul>
						</span>
					</div>
				</li>

				 {php}if ($i%4 == 0){ print('<div class="clear"></div>'); } {/php}
				 {php}$i++{/php}
			{/foreach}
		{/if}
	</ul>
	
	<div class="clear"></div>
	<div class="span-16 last clear">
		<ul class="pagination">
			{if $global_settings.seo_links}
				<li><a href="{$baseurl}/{$routes.tv_shows}"><b>{$lang.new_shows_all_shows}</b></a></li>
			{else}
				<li><a href="{$baseurl}/index.php?menu=tv-shows"><b>{$lang.new_shows_all_shows}</b></a></li>
			{/if}
		</ul>
	</div>
	
</div>
{include file="footer.tpl" title=footer}