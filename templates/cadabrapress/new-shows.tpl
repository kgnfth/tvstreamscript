{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}


<div class="archive">
	<h3>
		<span class="heading-left">{$lang.new_shows_new_episodes}</span>
		<div class="navigation heading-nav">
			{if $global_settings.seo_links}
				<a class="page-numbers current highlight" href="{$baseurl}/{$routes.new_episodes}">{$lang.new_shows_new_episodes}</a>
				<a class="page-numbers current" href="{$baseurl}/{$routes.new_movies}">{$lang.new_shows_new_movies}</a>
			{else}
				<a class="page-numbers current highlight" href="{$baseurl}/index.php?menu=new-shows">{$lang.new_shows_new_episodes}</a>
				<a class="page-numbers current" href="{$baseurl}/index.php?menu=new-movies">{$lang.new_shows_new_movies}</a>	
			{/if}
		</div>
		<div class="clear"></div>
	</h3>
	<div class="rounded">
		{if $episodes neq ''}
			{foreach from=$episodes key=key item=val name=show_iterator}
				{if $global_settings.seo_links}				
					<div class="post">
					
						<div class="buttons">
							<a href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" rel="bookmark" title="{$val.title}">
								<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=130&amp;zc=1" />
							</a>
							<div class="button-group">
								<div class="button" hint="{$lang.watch_this}">
									<a href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}"><i class="icon-play"></i></a>
								</div>
								<div class="button" hint="{$lang.episode_list}">
									<a href="{$baseurl}/{$routes.show}/{$val.permalink}"><i class="icon-list"></i></a>
								</div>	
								{if $loggeduser_id}
									<div class="button" hint="{$lang.like}" onclick="addLike({$val.epid},3,1);"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="addLike({$val.epid},3,-1);"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$val.epid},3); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>

								{else}
									<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
								{/if}	
							</div>
						</div>
						<h2>
			 				<a href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" title="{$val.showtitle} - {$val.title}">{$val.showtitle} - {$val.title}</a>
			 			</h2>
			 			
			 			<p>{$val.description|truncate:120:"...":true}<br /></p>
			 			
			 			{foreach from=$val.languages item=flag key=key}						
							<img src="{$embed_languages[$flag].flag}" class="flag" hint="{$embed_languages[$flag].language}"  style="margin-right: 1px;"/>
						{/foreach}
	
					</div>
				{else}
					<div class="post">
					
						<div class="buttons">
							<a href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" rel="bookmark" title="{$val.title}">
								<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=130&amp;zc=1" />
							</a>
							<div class="button-group">
								<div class="button" hint="{$lang.watch_this}">
									<a href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}"><i class="icon-play"></i></a>
								</div>
								<div class="button" hint="{$lang.episode_list}">
									<a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}"><i class="icon-list"></i></a>
								</div>	
								{if $loggeduser_id}
									<div class="button" hint="{$lang.like}" onclick="addLike({$val.epid},3,1);"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="addLike({$val.epid},3,-1);"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$val.epid},3); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>

								{else}
									<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
									<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
									<div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
								{/if}	
							</div>
						</div>
						<h2>
			 				<a href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" title="{$val.showtitle} - {$val.title}">{$val.showtitle} - {$val.title}</a>
			 			</h2>
			 			
			 			<p>{$val.description|truncate:120:"...":true}<br /></p>
			 			
			 			{foreach from=$val.languages item=flag key=key}						
							<img src="{$embed_languages[$flag].flag}" class="flag" hint="{$embed_languages[$flag].language}"  style="margin-right: 1px;"/>
						{/foreach}
	
					</div>	
				{/if}

			{/foreach}
		{/if}
		
		<div class="navigation">
			<ul>
				{if $global_settings.seo_links}
					<li class="current"><a href="{$baseurl}/{$routes.tv_shows}">{$lang.new_shows_all_shows}</a></li>
				{else}
					<li class="current"><a href="{$baseurl}/index.php?menu=tv-shows">{$lang.new_shows_all_shows}</a></li>
				{/if}
			</ul>
		</div>
		
	</div>
</div>

{include file="footer.tpl" title=footer}