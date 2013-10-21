{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}
<div class="span-24" id="portfolio">
	<h1>{$lang.tv_shows}</h1>
	
	<div class="left">{$lang.sorting}: </div>
	<div class="left filter-title myfilter">
		<ul id="filter">
			{if $global_settings.seo_links}
				<li{if $sortby eq 'abc'} class="current"{/if}><a href="{$baseurl}/{$routes.tv_shows}/abc">{$lang.sorting_abc}</a></li>
				<li{if $sortby eq 'date'} class="current"{/if}><a href="{$baseurl}/{$routes.tv_shows}/date">{$lang.sorting_newest}</a></li>
				<li{if $sortby eq 'imdb_rating'} class="current"{/if}><a href="{$baseurl}/{$routes.tv_shows}/imdb_rating">{$lang.sorting_imdb}</a></li>
			{else}
				<li{if $sortby eq 'abc'} class="current"{/if}><a href="{$baseurl}/index.php?menu=tv-shows&sortby=abc">{$lang.sorting_abc}</a></li>
				<li{if $sortby eq 'date'} class="current"{/if}><a href="{$baseurl}/index.php?menu=tv-shows&sortby=date">{$lang.sorting_newest}</a></li>
				<li{if $sortby eq 'imdb_rating'} class="current"{/if}><a href="{$baseurl}/index.php?menu=tv-shows&sortby=imdb_rating">{$lang.sorting_imdb}</a></li>			
			{/if}
		</ul>
	</div>

	<div class="clear"></div><br />
	
	<div class="left">{$lang.pages}: </div>
    <div class="left filter-title myfilter">
		<ul id="filter">
			{$pagination}
		</ul>
	</div>
	
	{include file="block_search.tpl"}

	<div class="clear"></div>
	<ul class="span-24" id="portfolio">
		{if $tvshows neq ''}
			{foreach from=$tvshows key=key item=val name=show_iterator}
		    	<li>
		        	<div class="span-6 inner-6 tt {if $smarty.foreach.show_iterator.iteration % 4 eq 0} last{/if} view">
		            	<div class="item" style="text-align:center">
		            		{if $global_settings.seo_links}
			            		<a href="{$baseurl}/{$routes.show}/{$val.permalink}" class="spec-border-ie" title="">
			            			<img class="img-preview spec-border show-thumbnail"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=130&amp;h=190&amp;zc=1" alt=" " />
			            		</a>
		            		{else}
			            		<a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}" class="spec-border-ie" title="">
			            			<img class="img-preview spec-border show-thumbnail"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=130&amp;h=190&amp;zc=1" alt=" " />
			            		</a>
		            		{/if}
		            	</div>
		                <h5>
		                	{if $global_settings.seo_links}
		                		<a class="link" href="{$baseurl}/{$routes.show}/{$val.permalink}" title="{$val.title}">
		                	{else}
		                		<a class="link" href="{$baseurl}/index.php?menu=show&perma={$val.permalink}" title="{$val.title}">
		                	{/if}
			                {$val.title|truncate:25:"...":true}
		                	</a>
		                </h5>
						<span>
							<ul>
								{if $global_settings.seo_links}
									<li><img src="{$templatepath}/images/icons/play.png" class="left hover-link" /><a href="{$baseurl}/{$routes.show}/{$val.permalink}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
								{else}
									<li><img src="{$templatepath}/images/icons/play.png" class="left hover-link" /><a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
								{/if}
								{if $loggeduser_id neq ''}
									<li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="addLike({$key},1,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link nobottommargin" /><a href="javascript:void(0);" onclick="addLike({$key},1,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
								{else}
									<li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link nobottommargin" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
								{/if}
							</ul>
							
						</span>
					</div>
				</li>

				 {if $smarty.foreach.show_iterator.iteration % 4 eq 0}<div class="clear"></div>{/if}
			{/foreach}
		{/if}
	</ul>
	
	<div class="clear"></div><br /><br />
	<div class="left">{$lang.pages}: </div>
    <div class="left filter-title">
		<ul id="filter">
			{$pagination}
		</ul>
	</div>
</div>


{include file="footer.tpl" title=footer}