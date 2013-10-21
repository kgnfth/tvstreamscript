{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if $tag}
	
	<div class="archive small-header">
		<h3>
			<span class="heading-left">{$lang.movietag_title|replace:'#tag#':$tag}</span>
			<div class="navigation heading-nav">
				{if $global_settings.seo_links}
					<a class="page-numbers current{if $sortby eq 'abc'} highlight{/if}" href="{$baseurl}/{$routes.movie_tag}/{$tag_perma}/abc">{$lang.sorting_abc}</a>
					<a class="page-numbers current{if $sortby eq 'date'} highlight{/if}" href="{$baseurl}/{$routes.movie_tag}/{$tag_perma}/date">{$lang.sorting_newest}</a>
					<a class="page-numbers current{if $sortby eq 'imdb_rating'} highlight{/if}" href="{$baseurl}/{$routes.movie_tag}/{$tag_perma}/imdb_rating">{$lang.sorting_imdb}</a>
				{else}
					<a class="page-numbers current{if $sortby eq 'abc'} highlight{/if}" href="{$baseurl}/index.php?menu=movie-tag&tag={$tag_perma}&sortby=abc">{$lang.sorting_abc}</a>
					<a class="page-numbers current{if $sortby eq 'date'} highlight{/if}" href="{$baseurl}/index.php?menu=movie-tag&tag={$tag_perma}&sortby=date">{$lang.sorting_newest}</a>
					<a class="page-numbers current{if $sortby eq 'imdb_rating'} highlight{/if}" href="{$baseurl}/index.php?menu=movie-tag&tag={$tag_perma}&sortby=imdb_rating">{$lang.sorting_imdb}</a>
				{/if}
			</div>
			<div class="clear"></div>
		</h3>
		<div class="rounded">
			{if $tagmovies neq ''}
				{foreach from=$tagmovies key=key item=val}
					{if $global_settings.seo_links}				
						<div class="post">
						
							<div class="buttons">
								<a href="{$baseurl}/{$routes.movie}/{$val.perma}" rel="bookmark" title="{$val.title}">
									<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=100&amp;h=150&amp;zc=1" />
								</a>
								<div class="button-group">
									<div class="button" hint="{$lang.watch_this}">
										<a href="{$baseurl}/{$routes.movie}/{$val.perma}"><i class="icon-play"></i></a>
									</div>
									{if $loggeduser_id}
										<div class="button" hint="{$lang.like}" onclick="addLike({$key},2,1);"><i class="icon-thumbs-up"></i></div>
										<div class="button" hint="{$lang.dislike}" onclick="addLike({$key},2,-1);"><i class="icon-thumbs-down"></i></div>
										<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$key},2); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>
									{else}
										<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
										<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
										<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
									{/if}
		
								</div>
							</div>
							<h2>
				 				<a href="{$baseurl}/{$routes.movie}/{$val.perma}" title="{$val.title}">{$val.title}</a>
				 			</h2>
				 			
				 			<p>{$val.description|truncate:150:"...":true}<br /></p>
				 			
				 			{if $val.meta.year or $val.meta.director or $val.meta.stars|@count or $val.imdb_rating}
				 			<span class="meta">
				 				{if $val.meta.year}
				 					{$lang.released}: <a href="{$baseurl}/index.php?menu=search&year={$val.meta.year}">{$val.meta.year}</a>&nbsp;
				 				{/if}
				 				
				 				{if $val.meta.stars|@count}
				 					{$lang.stars}: 
				 					{foreach from=$val.meta.stars item=star key=star_id}
				 						<a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
				 					{/foreach}
				 				{/if}	
				 				
				 				{if $val.meta.director}
				 					{$lang.director}: 
				 					<a href="{$baseurl}/index.php?menu=search&director={$val.meta.director}">{$val.meta.director}</a>&nbsp; 
				 				{/if}	
				 				
				 				{if $val.imdb_rating and $val.imdb_id}
				 					{$lang.imdb_rating}: 
			 						<a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$val.imdb_rating}</a>&nbsp; 
				 				{/if} 				
				 			</span>
				 			{/if}
				 			
				 			<br />
				 			{foreach from=$val.languages item=flag key=k}
	                            <img src="{$embed_languages[$flag].flag}" class="flag" hint="{$embed_languages[$flag].language}" style="margin-right: 1px;"/>
	                        {/foreach}
		
						</div>
					{else}
						<div class="post">
						
							<div class="buttons">
								<a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" rel="bookmark" title="{$val.title}">
									<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=100&amp;h=150&amp;zc=1" />
								</a>
								<div class="button-group">
									<div class="button" hint="{$lang.watch_this}">
										<a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}"><i class="icon-play"></i></a>
									</div>
									{if $loggeduser_id}
										<div class="button" hint="{$lang.like}" onclick="addLike({$key},2,1);"><i class="icon-thumbs-up"></i></div>
										<div class="button" hint="{$lang.dislike}" onclick="addLike({$key},2,-1);"><i class="icon-thumbs-down"></i></div>
										<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$key},2); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>
									{else}
										<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
										<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
										<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
									{/if}
		
								</div>
							</div>
							<h2>
				 				<a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" title="{$val.title}">{$val.title}</a>
				 			</h2>
				 			
				 			<p>{$val.description|truncate:150:"...":true}<br /></p>
				 			
				 			{if $val.meta.year or $val.meta.director or $val.meta.stars|@count or $val.imdb_rating}
				 			<span class="meta">
				 				{if $val.meta.year}
				 					{$lang.released}: <a href="{$baseurl}/index.php?menu=search&year={$val.meta.year}">{$val.meta.year}</a>&nbsp;
				 				{/if}
				 				
				 				{if $val.meta.stars|@count}
				 					{$lang.stars}: 
				 					{foreach from=$val.meta.stars item=star key=star_id}
				 						<a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
				 					{/foreach}
				 				{/if}	
				 				
				 				{if $val.meta.director}
				 					{$lang.director}: 
				 					<a href="{$baseurl}/index.php?menu=search&director={$val.meta.director}">{$val.meta.director}</a>&nbsp; 
				 				{/if}	
				 				
				 				{if $val.imdb_rating and $val.imdb_id}
				 					{$lang.imdb_rating}: 
			 						<a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$val.imdb_rating}</a>&nbsp; 
				 				{/if} 				
				 			</span>
				 			{/if}
				 			
				 			<br />
				 			{foreach from=$val.languages item=flag key=k}
	                            <img src="{$embed_languages[$flag].flag}" class="flag" hint="{$embed_languages[$flag].language}" style="margin-right: 1px;"/>
	                        {/foreach}
		
						</div>
									
					{/if}
				{/foreach}
				<div class="navigation">
					<ul>
						<li class="label">{$lang.pages}: </li>
						{$pagination}
					</ul>
				</div>
			{/if}	
		</div>
	</div>
{else}
	{include file="404.tpl" title=404}
{/if}

{include file="footer.tpl" title=footer}