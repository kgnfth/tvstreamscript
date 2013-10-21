{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if $mov}
	<div class="archive small-header">
		<h3>
			<span class="heading-left no-padding-top">
				{$mov.title}
			</span>
			<div class="navigation heading-nav">			
				<div class="pull-right rating">
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 0.25}checked='checked'{/if} value="0.25"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 0.5}checked='checked'{/if} value="0.5"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 0.75}checked='checked'{/if} value="0.75"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1}checked='checked'{/if} value="1"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1.25}checked='checked'{/if} value="1.25"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1.5}checked='checked'{/if} value="1.5"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1.75}checked='checked'{/if} value="1.75"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2}checked='checked'{/if} value="2"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2.25}checked='checked'{/if} value="2.25"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2.5}checked='checked'{/if} value="2.5"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2.75}checked='checked'{/if} value="2.75"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3}checked='checked'{/if} value="3"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3.25}checked='checked'{/if} value="3.25"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3.5}checked='checked'{/if} value="3.5"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3.75}checked='checked'{/if} value="3.75"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4}checked='checked'{/if} value="4"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4.25}checked='checked'{/if} value="4.25"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4.5}checked='checked'{/if} value="4.5"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4.75}checked='checked'{/if} value="4.75"/>
					<input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 5}checked='checked'{/if} value="5"/>	
				</div>
			</div>
			<div class="clear"></div>
		</h3>
		
		<div class="rounded">
			{if $global_settings.seo_links}				
				<div class="post">
				
					<div class="buttons">
						<a href="{$baseurl}/{$routes.movie}/{$mov.perma}" rel="bookmark" title="{$mov.title}">
							<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$mov.thumb}&amp;w=100&amp;h=150&amp;zc=1" />
						</a>
						<div class="button-group">
							<div class="button" hint="{$lang.watch_this}">
								<a href="{$baseurl}/{$routes.movie}/{$mov.perma}"><i class="icon-play"></i></a>
							</div>
							{if $loggeduser_id}
								<div class="button" hint="{$lang.like}" onclick="addLike({$mov.id},2,1);"><i class="icon-thumbs-up"></i></div>
								<div class="button" hint="{$lang.dislike}" onclick="addLike({$mov.id},2,-1);"><i class="icon-thumbs-down"></i></div>
								<div class="button{if $seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$mov.id},2); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>
							{else}
								<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
								<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
								<div class="button{if $seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
							{/if}
						</div>
					</div>
							
				 	<p>{$mov.description|truncate:350:"...":true}<br /></p>
				 			
				 	{if $mov.meta.year or $mov.meta.director or $mov.meta.stars|@count or $mov.imdb_rating}
				 		<span class="meta">
				 			{if $mov.meta.year}
				 				{$lang.released}: <a href="{$baseurl}/index.php?menu=search&year={$mov.meta.year}">{$mov.meta.year}</a><br />
				 			{/if}
				 				
				 			{if $mov.meta.stars|@count}
				 				{$lang.stars}: 
				 				{foreach from=$mov.meta.stars item=star key=star_id}
				 					<a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
				 				{/foreach}
				 				<br />
				 			{/if}	
				 				
				 			{if $mov.meta.director}
				 				{$lang.director}: 
				 				<a href="{$baseurl}/index.php?menu=search&director={$mov.meta.director}">{$mov.meta.director}</a><br />
				 			{/if}	
				 				
				 			{if $mov.imdb_rating and $mov.imdb_id}
				 				{$lang.imdb_rating}: 
			 					<a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$mov.imdb_rating}</a><br />
				 			{/if} 	
	                        {if $tags neq ''}
								{$lang.categories}:					
								{foreach from=$tags key=id item=val name=tags}
									{if $global_settings.seo_links}
										<a href="{$baseurl}/{$routes.movie_tag}/{$val.perma}">{$val.tag}</a>&nbsp;
									{else}
										<a href="{$baseurl}/index.php?menu=movie-tag&tag={$val.perma}">{$val.tag}</a>&nbsp;
									{/if}
								{/foreach}
								<br />
	                        {/if}			
				 		</span>
				 	{/if}
					
					<div class="clear"></div>
					
					{if $widgets.movie_ad and (not $loggeduser_username or $widgets.movie_ad.logged)}
						<div class="center">
							<div class="clear"></div><br />
							{$widgets.movie_ad.content}
							<div class="clear"></div>
						</div>
						<div class="clear"></div><br /><br />
					{else}
						<br />
					{/if}  
					
		            {if $global_settings.facebook}
		            	<div class="pull-left">
		            		<div class="fb-like" data-href="{$fullurl}" data-send="true" data-width="400" data-show-faces="false"></div>
		            	</div>
		           	{/if}
					
					<div class="brokenlink pull-right">
						<a href="javascript:void(0);" onclick="reportMovie();">{$lang.movie_report_error}</a>
					</div>
					<div class="clear"></div>
					
				</div>
			{else}
				<div class="post">
				
					<div class="buttons">
						<a href="{$baseurl}/index.php?menu=movie&perma={$mov.perma}" rel="bookmark" title="{$mov.title}">
							<img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$mov.thumb}&amp;w=100&amp;h=150&amp;zc=1" />
						</a>
						<div class="button-group">
							<div class="button" hint="{$lang.watch_this}">
								<a href="{$baseurl}/index.php?menu=movie&perma={$mov.perma}"><i class="icon-play"></i></a>
							</div>
							{if $loggeduser_id}
								<div class="button" hint="{$lang.like}" onclick="addLike({$mov.id},2,1);"><i class="icon-thumbs-up"></i></div>
								<div class="button" hint="{$lang.dislike}" onclick="addLike({$mov.id},2,-1);"><i class="icon-thumbs-down"></i></div>
								<div class="button{if $seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$mov.id},2); jQuery(this).addClass('button-active');"><i class="icon-eye-open"></i></div>
							{else}
								<div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
								<div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
								<div class="button{if $seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>							
							{/if}
						</div>
					</div>
							
				 	<p>{$mov.description|truncate:350:"...":true}<br /></p>
				 			
				 	{if $mov.meta.year or $mov.meta.director or $mov.meta.stars|@count or $mov.imdb_rating}
				 		<span class="meta">
				 			{if $mov.meta.year}
				 				{$lang.released}: <a href="{$baseurl}/index.php?menu=search&year={$mov.meta.year}">{$mov.meta.year}</a><br />
				 			{/if}
				 				
				 			{if $mov.meta.stars|@count}
				 				{$lang.stars}: 
				 				{foreach from=$mov.meta.stars item=star key=star_id}
				 					<a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
				 				{/foreach}
				 				<br />
				 			{/if}	
				 				
				 			{if $mov.meta.director}
				 				{$lang.director}: 
				 				<a href="{$baseurl}/index.php?menu=search&director={$mov.meta.director}">{$mov.meta.director}</a><br />
				 			{/if}	
				 				
				 			{if $mov.imdb_rating and $mov.imdb_id}
				 				{$lang.imdb_rating}: 
			 					<a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$mov.imdb_rating}</a><br />
				 			{/if} 	
	                        {if $tags neq ''}
								{$lang.categories}:					
								{foreach from=$tags key=id item=val name=tags}
									{if $global_settings.seo_links}
										<a href="{$baseurl}/{$routes.movie_tag}/{$val.perma}">{$val.tag}</a>&nbsp;
									{else}
										<a href="{$baseurl}/index.php?menu=movie-tag&tag={$val.perma}">{$val.tag}</a>&nbsp;
									{/if}
								{/foreach}
								<br />
	                        {/if}			
				 		</span>
				 	{/if}
					
					<div class="clear"></div>
					
					{if $widgets.movie_ad and (not $loggeduser_username or $widgets.movie_ad.logged)}
						<div class="center">
							<div class="clear"></div><br />
							{$widgets.movie_ad.content}
							<div class="clear"></div>
						</div>
						<div class="clear"></div><br /><br />
					{else}
						<br />
					{/if}  
					
		            {if $global_settings.facebook}
		            	<div class="pull-left">
		            		<div class="fb-like" data-href="{$fullurl}" data-send="true" data-width="400" data-show-faces="false"></div>
		            	</div>
		           	{/if}
					
					<div class="brokenlink pull-right">
						<a href="javascript:void(0);" onclick="reportMovie();">{$lang.movie_report_error}</a>
					</div>
					<div class="clear"></div>
					
				</div>
			{/if}
		</div>
	</div>
	
	<div class="article">
        <script>
        {if $listing_styles.embeds}
        	var current_view = 'embed';
        {else}
        	var current_view = 'link';
        {/if}
        {literal}
        	function changeView(target){
				if (current_view != target){
					if (target == 'embed'){
						jQuery('#link_list').hide();
						jQuery('#embed_list').show();
						jQuery('#embed-style-selector').addClass('active');
						jQuery('#link-style-selector').removeClass('active');
					} else {
						jQuery('#embed_list').hide();
						jQuery('#link_list').show();
						jQuery('#link-style-selector').addClass('active');
						jQuery('#embed-style-selector').removeClass('active');
					}
					current_view = target;
				}
        	}
        {/literal}
        </script>
        
		{if $listing_styles.embeds and $listing_styles.links}
			<div class="video-column">
				<ul class="tabbernav">
		            <li class="tab-selector tabberactive" onclick="changeView('embed');" id="embed-style-selector">
		                <a href="javascript:void(0);">Embed codes</a>
		            </li>
		            <li class="tab-selector" onclick="changeView('link')" id="link-style-selector">
						<a href="javascript:void(0);">Links</a>
		            </li>
				</ul>
			</div>
		{/if}
		
        {if $listing_styles.embeds}
	        <div id="embed_list">
	        {foreach from=$mov.embeds key=id item=val name=titles}
	            <div class="embed-selector" onclick="changeEmbed({$val.id},{$global_settings.countdown});">
	            	<span class="embed-flag" style="background-image: url('{$embed_languages[$val.lang].flag}');"></span>
	               	<span class="embed-type">
	                    {$embed_languages[$val.lang].language}
	                    {if $val.type} - 
	                    	<strong>{$val.type}</strong>
	                    {/if}
	               	</span>
	               	<span class="embed-out-link">
	                    {if $val.link}
	                    	{if $global_settings.adfly.id}
	                        	<a href="http://adf.ly/{$global_settings.adfly.id}/{$val.link|replace:"http://":""}" target="_blank">{$lang.open_video}</a>
	                        {else}
	                        	<a href="{$val.link}" target="_blank">{$lang.open_video}</a>
	                        {/if}
	                    {/if}
	               	</span>
	               	<div class="clear"></div>
	            </div>
	            <div class="clear"></div>
	            <div class="embedcontainer" id="videoBox{$val.id}" style="display:none">
	                
	            </div>
	        {/foreach}
	        </div>
        {/if}
        
        {if $listing_styles.links}
	        <div id="link_list"{if $listing_styles.embeds} style="display:none;"{/if}>
	        {foreach from=$mov.embeds key=id item=val name=titles}
	            <div class="embed-selector">
	            	<span class="embed-flag" style="background-image: url('{$embed_languages[$val.lang].flag}');"></span>
	               	<span class="embed-type">
	                    {$embed_languages[$val.lang].language}
	                    {if $val.type} - 
	                    	<strong>{$val.type}</strong>
	                    {/if}
	               	</span>
	               	<span class="embed-out-link">
	                    {if $val.link}
	                    	{if $global_settings.adfly.id}
	                        	<a href="http://adf.ly/{$global_settings.adfly.id}/{$val.link|replace:"http://":""}" target="_blank">{$lang.open_video}</a>
	                        {else}
	                        	<a href="{$val.link}" target="_blank">{$lang.open_video}</a>
	                        {/if}
	                    {/if}
	               	</span>
	               	<div class="clear"></div>
	            </div>
	        {/foreach}
	        </div>
        
        {/if}
		
        {if $global_settings.facebook}
        	<div class="video-column">
        		<div class="clear"></div><br />
        		<div class="fb-comments" data-href="{$fullurl}" data-num-posts="10" data-width="650"></div>
        		<div class="clear"></div><br />
        	</div>
        {/if}
        
        <div class="clear"></div>
	</div>
	
{else}
	{include file="404.tpl" title=404}
{/if}


<div class="modal" id="modal">
	<h3 class="title">
		<div class="pull-left">{$lang.movie_explain_problem}</div>
		<i class="icon-remove-circle pull-right modal-close"></i>
		<div class="clear"></div>
	</h3>
	<div class="padder" id="reportcontent">
 		<textarea id="problem"></textarea>
 		<a class="btn-main" onClick="doReportMovie({$mov.id});" href="javascript:void(0);">{$lang.submit}</a>
 	</div>
</div>

{if $listing_styles.embeds}
	<script>
		var embeds = [];
		{foreach from=$mov.embeds key=id item=val name=embeds}
			embeds[{$val.id}] = '{$val.embed|replace:"'":'"'}';
		{/foreach}
	</script>
	{foreach from=$mov.embeds key=id item=val name=titles}
		{if $smarty.foreach.titles.iteration eq 1}
			<script>
				changeEmbed({$val.id},{$global_settings.countdown});
			</script>
		{/if}
	{/foreach}
{/if}
<script>
	{literal}
		jQuery('.auto-submit-star').rating({
			callback: function(value, link){
			jQuery.get("{/literal}{$baseurl}{literal}/ajax/addmovierating.php",
				{
					movieid: {/literal}{$mov.id}{literal},
					rating: value
				}, function(resp) {	}); 
			}
		});
	{/literal}
</script>
	
{include file="footer.tpl" title=footer}