{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if !$loggeduser_id}
	<script>
		window.location = '{$baseurl}';
	</script>
{else}
	<div class="archive">
		<h3>
			{$lang.recommend_movies_recommendations}
		</h3>
		<div class="rounded">
			{if $result_type eq 'random'}
				<p>{$lang.recommend_movies_no_movie}</p>
			{else}
				<p>{$lang.recommend_movies_movie}</p>
			{/if}
			
			{foreach from=$movies key=key item=movie}
				{if $global_settings.seo_links}				
					<div class="post">
					
						<div class="buttons">
							<a href="{$baseurl}/{$routes.movie}/{$movie.perma}" rel="bookmark" title="{$movie.title}">
								<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=100&amp;h=150&amp;zc=1" />
							</a>
							<div class="button-group">
								<div class="button" hint="{$lang.watch_this}">
									<a href="{$baseurl}/{$routes.movie}/{$movie.perma}"><i class="icon-play"></i></a>
								</div>
								{if $loggeduser_id}
									<div class="button" hint="{$lang.like}" onclick="addLike({$key},2,1);"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="addLike({$key},2,-1);"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $movie.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$key},2); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>
								{else}
									<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $movie.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
								{/if}
	
							</div>
						</div>
						<h2>
			 				<a href="{$baseurl}/{$routes.movie}/{$movie.perma}" title="{$movie.title}">{$movie.title}</a>
			 			</h2>
			 			
			 			<p>{$movie.description|truncate:150:"...":true}<br /></p>
			 			
			 			{if $movie.meta.year or $movie.meta.director or $movie.meta.stars|@count or $movie.imdb_rating}
			 			<span class="meta">
			 				{if $movie.meta.year}
			 					{$lang.released}: <a href="{$baseurl}/index.php?menu=search&year={$movie.meta.year}">{$movie.meta.year}</a>&nbsp;
			 				{/if}
			 				
			 				{if $movie.meta.stars|@count}
			 					{$lang.stars}: 
			 					{foreach from=$movie.meta.stars item=star key=star_id}
			 						<a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
			 					{/foreach}
			 				{/if}	
			 				
			 				{if $movie.meta.director}
			 					{$lang.director}: 
			 					<a href="{$baseurl}/index.php?menu=search&director={$movie.meta.director}">{$movie.meta.director}</a>&nbsp; 
			 				{/if}	
			 				
			 				{if $movie.imdb_rating and $movie.imdb_id}
			 					{$lang.imdb_rating}: 
		 						<a href="http://www.imdb.com/title/{$movie.imdb_id}" target="_blank">{$movie.imdb_rating}</a>&nbsp; 
			 				{/if} 				
			 			</span>
			 			{/if}	
					</div>
				{else}
					<div class="post">
					
						<div class="buttons">
							<a href="{$baseurl}/index.php?menu=show&perma={$movie.perma}" rel="bookmark" title="{$movie.title}">
								<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=100&amp;h=150&amp;zc=1" />
							</a>
							<div class="button-group">
								<div class="button" hint="{$lang.watch_this}">
									<a href="{$baseurl}/index.php?menu=show&perma={$movie.perma}"><i class="icon-play"></i></a>
								</div>
								{if $loggeduser_id}
									<div class="button" hint="{$lang.like}" onclick="addLike({$key},2,1);"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="addLike({$key},2,-1);"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $movie.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$key},2); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>
								{else}
									<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $movie.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
								{/if}
	
							</div>
						</div>
						<h2>
			 				<a href="{$baseurl}/index.php?menu=show&perma={$movie.perma}" title="{$movie.title}">{$movie.title}</a>
			 			</h2>
			 			
			 			<p>{$movie.description|truncate:150:"...":true}<br /></p>
			 			
			 			{if $movie.meta.year or $movie.meta.director or $movie.meta.stars|@count or $movie.imdb_rating}
			 			<span class="meta">
			 				{if $movie.meta.year}
			 					{$lang.released}: <a href="{$baseurl}/index.php?menu=search&year={$movie.meta.year}">{$movie.meta.year}</a>&nbsp;
			 				{/if}
			 				
			 				{if $movie.meta.stars|@count}
			 					{$lang.stars}: 
			 					{foreach from=$movie.meta.stars item=star key=star_id}
			 						<a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
			 					{/foreach}
			 				{/if}	
			 				
			 				{if $movie.meta.director}
			 					{$lang.director}: 
			 					<a href="{$baseurl}/index.php?menu=search&director={$movie.meta.director}">{$movie.meta.director}</a>&nbsp; 
			 				{/if}	
			 				
			 				{if $movie.imdb_rating and $movie.imdb_id}
			 					{$lang.imdb_rating}: 
		 						<a href="http://www.imdb.com/title/{$movie.imdb_id}" target="_blank">{$movie.imdb_rating}</a>&nbsp; 
			 				{/if} 				
			 			</span>
			 			{/if}	
					</div>
								
				{/if}
			{/foreach}
		</div>
	</div>
{/if}

{include file="footer.tpl" title=footer}