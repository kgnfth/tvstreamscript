

	<div class="clear"></div>

	<div class="pre_footer">
		
		<a href="{$baseurl}">
			<img src="{$templatepath}/images/logo_footer.png" alt="{$sitename}" />		
		</a>
		
		<span><a href="#top" title="top">&nbsp;</a></span>
		
	</div>
	
 
</div> <!-- /#page-wrap -->


<div id="footer_wrap">
	<div id="footer">
		
 		<div id="text-2" class="widget widget_tag_cloud">
 			<h3>TV show categories</h3>			
 			<div class="tagcloud">
				{if $tv_categories}
					{foreach from=$tv_categories key=id item=val}
						{if $global_settings.seo_links eq 1}
							<a href="{$baseurl}/{$routes.tv_tag}/{$val.perma}" style="font-size: 13.25pt;">{$val.name}</a>
						{else}
							<a href="{$baseurl}/index.php?menu=tv-tag&tag={$val.perma}"  style="font-size: 13.25pt;">{$val.name}</a>
						{/if}
					{/foreach}
				{/if}
			</div>
		</div>
		<div id="tag_cloud-2" class="widget widget_tag_cloud">
			<h3>Movie categories</h3>
			<div class="tagcloud">
				{if $movie_categories}
					{foreach from=$movie_categories key=id item=val}
						{if $global_settings.seo_links eq 1}
							<a href="{$baseurl}/{$routes.movie_tag}/{$val.perma}" style="font-size: 13.25pt;">{$val.name}</a>
						{else}
							<a href="{$baseurl}/index.php?menu=movie-tag&tag={$val.perma}" style="font-size: 13.25pt;">{$val.name}</a>
						{/if}
					{/foreach}
				{/if}
			</div>
		</div>		
		<div class="clear"></div>
 	</div> <!-- /#footer -->
 	
 	<div id="copyright">
		
		&copy; Copyright 2011 &mdash; <a href="{$baseurl}" class="on">{$sitename}</a>. All Rights Reserved		
		<span>Designed by <a href="http://www.wpzoom.com" target="_blank" title="WPZOOM WordPress Themes"><img src="http://www.wpzoom.com/demo/cadabrapress/wp-content/themes/cadabrapress2/images/wpzoom.png" alt="WPZOOM" /></a></span>
	
	</div>
	
</div> <!-- /#footer_wrap -->
 
{include file="popups.tpl" title=popups}

{if $analytics}
	{$analytics}
{/if}

{if $adversalad}
	{$adversalad}
{/if}


</body>
</html>