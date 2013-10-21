{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-24" id="portfolio">
	<h1>{$lang.movies}</h1>
	
	{if $movielist neq ''}
		<div class="left">{$lang.sorting}: </div>
		<div class="left filter-title myfilter">
			<ul id="filter">
				{if $global_settings.seo_links}
					<li{if $sortby eq 'abc'} class="current"{/if}><a href="{$baseurl}/{$routes.movies}/abc">{$lang.sorting_abc}</a></li>
					<li{if $sortby eq 'date'} class="current"{/if}><a href="{$baseurl}/{$routes.movies}/date">{$lang.sorting_newest}</a></li>
					<li{if $sortby eq 'imdb_rating'} class="current"{/if}><a href="{$baseurl}/{$routes.movies}/imdb_rating">{$lang.sorting_imdb}</a></li>
				{else}
					<li{if $sortby eq 'abc'} class="current"{/if}><a href="{$baseurl}/index.php?menu=movies&sortby=abc">{$lang.sorting_abc}</a></li>
					<li{if $sortby eq 'date'} class="current"{/if}><a href="{$baseurl}/index.php?menu=movies&sortby=date">{$lang.sorting_newest}</a></li>
					<li{if $sortby eq 'imdb_rating'} class="current"{/if}><a href="{$baseurl}/index.php?menu=movies&sortby=imdb_rating">{$lang.sorting_imdb}</a></li>
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
		
		<form method="post" id="search-form" action="{$baseurl}/index.php" class="right">
			<input type="hidden" name="menu" value="search" />
			<input type="text" name="query" value="{$lang.search_tip}" onfocus="if(this.value=='{$lang.search_tip}') this.value=''" onblur="if(this.value=='') this.value='{$lang.search_tip}'" style="width:200px" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
		</form>
		<div class="clear"></div>
		
		<ul class="span-24" id="portfolio">
			{php} $i=1; {/php}
			{foreach from=$movielist key=key item=val}
		    	<li>
		        	<div class="span-6 inner-6 tt {php}if ($i%4 == 0){ print('last'); } {/php} view">
		            	<div class="item" style="text-align:center">
		            		{if $global_settings.seo_links}
			            		<a href="{$baseurl}/{$routes.movie}/{$val.perma}" class="spec-border-ie" title="">
			            			<img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=130&amp;h=190&amp;zc=1" alt=" " style="width:130px;height:190px;background-color: #717171;"/>
			            		</a>
		            		{else}
			            		<a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" class="spec-border-ie" title="">
			            			<img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=130&amp;h=190&amp;zc=1" alt=" " style="width:130px;height:190px;background-color: #717171;"/>
			            		</a>
		            		{/if}
		            	</div>
		                <h5 class="left">
		                	{if $global_settings.seo_links}
			                	<a class="link" href="{$baseurl}/{$routes.movie}/{$val.perma}" title="{$val.title}">
									{$val.title|truncate:25:"...":true}
				                </a>
			                {else}
			                	<a class="link" href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" title="{$val.title}">
									{$val.title|truncate:25:"...":true}
				                </a>
			                {/if}
						</h5>

		                {if $val.seen}
							<div id="seen{$key}"><a class="seen right" href="javascript:void(0);"></a></div>
						{else}
							<div id="seen{$key}"></div>						
						{/if}
						<div class="clear"></div><br />
						
						<div class="left">
						{foreach from=$val.languages item=flag key=k}
							<img src="{$embed_languages[$flag].flag}" style="margin-right: 1px;"/>
						{/foreach}
						</div>
						
						<div class="right">
							{if $val.imdb_rating}
							<div style="float:right;display:block;color:#000;font-size:11px; font-weight: bold;">{$val.imdb_rating}</div>
							<img src="{$templatepath}/images/imdb-icon.png" style="float:right;border:0px;margin-right: 5px;" />
							{else}
							&nbsp;
							{/if}
						</div>
						
						<span>
							<ul>
								{if $global_settings.seo_links}
									<li><img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="{$baseurl}/{$routes.movie}/{$val.perma}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
								{else}
									<li><img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
								{/if}
								{if $loggeduser_id neq ''}
									<li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addWatch({$key},2);" class="left" id="watch_button_{$key}">{$lang.seen_it}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addLike({$key},2,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="addLike({$key},2,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
								{else}
									<li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.seen_it}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
								{/if}
							</ul>
						</span>
					</div>
				</li>
			{php}if ($i%4 == 0){ print('<div class="clear"></div>'); } {/php}
			{php}$i++{/php}
			{/foreach}
		</ul>
		<div class="clear"></div><br /><br />
		<div class="left">{$lang.pages}: </div>
		<div class="left filter-title myfilter">
			<ul id="filter">
				{$pagination}
			</ul>
		</div>
		<div class="clear"></div>
	{else}
		<div class="span-24">
			<center>{$lang.movies_no_movie_in_the_database}<br /><br /></center>
		</div>
	{/if}
</div>


<br style="clear:both;" />
{include file="footer.tpl" title=footer}