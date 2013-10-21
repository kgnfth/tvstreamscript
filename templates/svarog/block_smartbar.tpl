<div class="span-24">
	<div class="clear"></div>
	{foreach from=$smartbar.shows key=show_id item=show name=smart_show}
		<div class="flickr item left" {if $smarty.foreach.smart_show.iteration % $smartbar_cols neq 0}style="margin-right:10px;"{/if}>
			{if $global_settings.seo_links eq 1}
				<a href="{$baseurl}/{$routes.show}/{$show.permalink}"  class="">
					<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w={$smartbar_width}&amp;h={$smartbar_height}&amp;zc=1" style="width:{$smartbar_width}px; height:{$smartbar_height}px" />
				</a>
			{else}
				<a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}"  class="">
					<img class="tooltip" original-title="{$show.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w={$smartbar_width}&amp;h={$smartbar_height}&amp;zc=1" style="width:{$smartbar_width}px; height:{$smartbar_height}px" />
				</a>
			{/if}
		</div>
		{if $smarty.foreach.smart_show.iteration % $smartbar_cols eq 0}<div class="clear"></div>{/if}
	{/foreach}
	
	{foreach from=$smartbar.movies key=movie_id item=movie name=smart_movie}
		<div class="flickr item left" {if ($smarty.foreach.smart_movie.iteration + $smarty.foreach.smart_show.iteration) % $smartbar_cols neq 0}style="margin-right:10px;"{/if}>
			{if $global_settings.seo_links eq 1}
				<a href="{$baseurl}/{$routes.movie}/{$movie.perma}"  class="">
					<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w={$smartbar_width}&amp;h={$smartbar_height}&amp;zc=1" style="width:{$smartbar_width}px; height:{$smartbar_height}px" />
				</a>
			{else}
				<a href="{$baseurl}/index.php?menu=watchmovie&perma={$movie.perma}"  class="">
					<img class="tooltip" original-title="{$movie.title}" alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$movie.thumb}&amp;w={$smartbar_width}&amp;h={$smartbar_height}&amp;zc=1" style="width:{$smartbar_width}px; height:{$smartbar_height}px" />
				</a>
			{/if}
		</div>
		{if $smarty.foreach.smart_movie.iteration % $smartbar_cols eq 0}<div class="clear"></div>{/if}
	{/foreach}
		
	<div class="clear"></div>
</div>