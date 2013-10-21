{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if !$loggeduser_id}
	<script>
		window.location = '{$baseurl}';
	</script>
{else}
	<div class="archive">
		<h3>
			{$lang.favorites_favorite_movies}
		</h3>
		<div class="rounded favorites">
			{if $favorite_movies}
				{foreach from=$favorite_movies key=id item=movie}
					{if $global_settings.seo_links}
						<a href="{$baseurl}/{$routes.movie}/{$movie.perma}"  class="">
							<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=100&amp;h=150&amp;zc=1" style="width:100px; height:150px">
						</a>
					{else}
						<a href="{$baseurl}/index.php?menu=watchmovie&perma={$movie.perma}"  class="">
							<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w=100&amp;h=150&amp;zc=1" style="width:100px; height:150px">
						</a>
					{/if}
				{/foreach}
				<div class="clear"></div><br />
			{else}
				<p>{$lang.favorites_no_movie|replace:'#baseurl#':$baseurl}</p>
				<form method="post" id="search-form" action="{$baseurl}/index.php">
					<input type="hidden" name="menu" value="search" />
					<input type="text" name="query" value="{$lang.search_tip}" onfocus="if(this.value=='{$lang.search_tip}') this.value=''" onblur="if(this.value=='') this.value='{$lang.search_tip}'" style="width:200px" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
				</form>
				<div class="clear"></div><br /><br />
			{/if}		
		</div>
	</div>
	
	<div class="archive">
		<h3>
			{$lang.favorites_favorite_shows}
		</h3>
		<div class="rounded favorites">
		
			{if $favorite_shows}
				{foreach from=$favorite_shows key=id item=show}
				
						{if $global_settings.seo_links}
							<a href="{$baseurl}/{$routes.show}/{$show.permalink}"  class="">
								<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=100&amp;h=150&amp;zc=1" style="width:100px; height:150px">
							</a>
						{else}
							<a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}"  class="">
								<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=100&amp;h=150&amp;zc=1" style="width:100px; height:150px">
							</a>
						{/if}
				{/foreach}
				<div class="clear"></div><br />
			{else}
				<p>{$lang.favorites_no_show|replace:'#baseurl#':$baseurl}</p>
				<form method="post" id="search-form" action="{$baseurl}/index.php">
					<input type="hidden" name="menu" value="search" />
					<input type="text" name="query" value="{$lang.search_tip}" onfocus="if(this.value=='{$lang.search_tip}') this.value=''" onblur="if(this.value=='') this.value='{$lang.search_tip}'" style="width:200px" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
				</form>
				<div class="clear"></div><br /><br />
			{/if}
		</div>
	</div>
{/if}

{include file="footer.tpl" title=footer}