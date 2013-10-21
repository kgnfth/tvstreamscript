{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-24 notopmargin">
{if $show_data neq ''}
	{foreach from=$show_data key=id item=val}
	
		<div class="span-24 inner-24 view last">
		
			<div class="span-5 tt notopmargin nobottommargin">
				{if $global_settings.seo_links}
					<a href="{$baseurl}/{$routes.show}/{$val.permalink}">
						<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=172&amp;h=265&amp;zc=1" alt="" style="width: 172px; height: 265px; border:4px solid #D2D2D2"/>
					</a>
				{else}
					<a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}">
						<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=172&amp;h=265&amp;zc=1" alt="" style="width: 172px; height: 265px; border:4px solid #D2D2D2"/>
					</a>
				{/if}
				<span>
					<ul>
						{if $loggeduser_id neq ''}
							<li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="addLike({$id},1,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
							<li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link nobottommargin" /><a href="javascript:void(0);" onclick="addLike({$id},1,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
						{else}
							<li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
							<li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link nobottommargin" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
						{/if}
					</ul>
				</span>
			</div>
			
			<div class="span-18 last notopmargin nobottommargin">
				<h2>{$val.title}{if $season} - {$lang.show_season_menu|replace:"#season#":$season}{/if}</h2>
				<div class="show-details">
					{if $global_settings.facebook}
						<div class="fb-like" data-href="{$fullurl}" data-send="true" data-width="500" data-show-faces="false"></div>
						<div class="clear"></div><br />
					{/if}
					
					{if $val.description}
						<p>
							{$val.description}
						</p>
					{/if}
							
					{if $val.categories|@count neq 0}
						<div class="video-detail-line">
							<label>{$lang.categories}:</label>
							
							<ul class="filter">
								{foreach from=$val.categories key=category_id item=category name=categories}
								<li class="noleftmargin current">
									{if $global_settings.seo_links}
										<a href="{$baseurl}/{$routes.tv_tag}/{$category.perma}">{$category.tag}</a>
									{else}
										<a href="{$baseurl}/index.php?menu=tv-tag&perma={$category.perma}">{$category.tag}</a>
									{/if}
								</li>
								{/foreach}				  
							</ul>
							<div class="clear"></div>
						</div>
					{/if}
				
					{if $val.meta.year_started}
						<div class="show-detail-line">
							<label>{$lang.year_started}:</label>
		                                
		                    <ul class="filter">
								<li class="noleftmargin current">
									<a href="{$baseurl}/index.php?menu=search&year={$val.meta.year_started|escape:'url'}">{$val.meta.year_started}</a>
								</li>                 
							</ul>
							<div class="clear"></div>
						</div>
					{/if}
					
					{if $val.meta.stars}
						<div class="video-detail-line">
							<label>{$lang.stars}:</label>
							
							<ul class="filter">
								{foreach from=$val.meta.stars key=star_id item=star name=stars}
								<li class="noleftmargin current">
									<a href="{$baseurl}/index.php?menu=search&star={$star|escape:'url'}">{$star}</a>
								</li>
								{/foreach}				  
							</ul>
							<div class="clear"></div>
						</div>
					{/if}  
					
					{if $val.meta.creators}
						<div class="video-detail-line">
							<label>{$lang.creators}:</label>
							
							<ul class="filter">
								{foreach from=$val.meta.creators key=creator_id item=creator name=creators}
								<li class="noleftmargin current">
									<a href="{$baseurl}/index.php?menu=search&director={$creator|escape:'url'}">{$creator}</a>
								</li>
								{/foreach}				  
							</ul>
							<div class="clear"></div>
						</div>
					{/if} 
					
					{if $val.imdb_rating and $val.imdb_id}
						<div class="show-detail-line">
							<label>{$lang.imdb_rating}:</label>
		                                
							<ul class="filter">
								<li class="noleftmargin current">
									<a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$val.imdb_rating}</a>
								</li>				   
							</ul>
							<div class="clear"></div>
						</div>
					{/if}
					
				</div>
			</div>
		</div>
	
		<div class="clear"></div><br /><br />

	
	    <div class="left filter-title myfilter">
			<ul class="filter" id="season-list">
				{if $global_settings.seo_links}
					<li class="noleftmargin{if $season eq ''} current{/if}"><a href="{$baseurl}/{$routes.show}/{$val.permalink}">{$lang.show_all_seasons}</a></li>
				{else}
					<li class="noleftmargin{if $season eq ''} current{/if}"><a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}">{$lang.show_all_seasons}</a></li>
				{/if}
				{if $show_seasons}
					{foreach from=$show_seasons key=k item=v}
						{if $global_settings.seo_links}
							<li {if $season eq $v}class='current'{/if}><a href='{$baseurl}/{$routes.show}/{$val.permalink}/season/{$v}'>{$lang.show_season_menu|replace:'#season#':$v}</a></li>
						{else}
							<li {if $season eq $v}class='current'{/if}><a href='{$baseurl}/index.php?menu=show&perma={$val.permalink}&season={$v}'>{$lang.show_season_menu|replace:'#season#':$v}</a></li>
						{/if}
					{/foreach}
				{/if}
			</ul>
		</div>
		
		<div class="clear"></div>
		<ul class="span-24">
			{foreach from=$episodes key=id item=val name=episode_loop}
				{include file="block_episode.tpl" iteration=$smarty.foreach.episode_loop.iteration}
				{if $smarty.foreach.episode_loop.iteration % 4 eq 0}<div class="clear"></div>{/if}
			{/foreach}
		</ul>
	{/foreach}
{else}
	<div class="span-24">
		<center><br />{$lang.show_error}<br /><br /></center>
	</div>
{/if}
</div>
{include file="footer.tpl" title=footer}