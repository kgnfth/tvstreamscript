	    	<li>
	        	<div class="span-6 inner-6 tt{if $iteration % 4 eq 0} last{/if} view">
	            	<div class="item">
	            		{if $global_settings.seo_links}
		            		<a href="{$baseurl}/{$routes.show}/{$val.perma}/season/{$val.season}/episode/{$val.episode}" class="spec-border-ie" title="">
		            			<img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=136&amp;zc=1" alt=" " style="width:190px;height:136px;background-color: #717171;"/>
		            		</a>
	            		{else}
		            		<a href="{$baseurl}/index.php?menu=episode&perma={$val.perma}&season={$val.season}&episode={$val.episode}" class="spec-border-ie" title="">
		            			<img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=136&amp;zc=1" alt=" " style="width:190px;height:136px;background-color: #717171;"/>
		            		</a>
	            		{/if}
	            	</div>
	                <h5 class="left">
	                	{if $global_settings.seo_links}
							<a class="link" href="{$baseurl}/{$routes.show}/{$val.perma}/season/{$val.season}/episode/{$val.episode}" title="{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}">{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}</a>
						{else}
							<a class="link" href="{$baseurl}/index.php?menu=episode&perma={$val.perma}&season={$val.season}&episode={$val.episode}" title="{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}">{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}</a>
						{/if}
					</h5>
	                {if $val.seen}
						<div id="seen{$id}"><a class="seen right" href="javascript:void(0);"></a></div>
					{else}
						<div id="seen{$id}"></div>						
					{/if}
					
					<div class="clear"></div><br />
	                
					<div class="left">
					{foreach from=$val.languages item=flag key=key}						
						<img src="{$embed_languages[$flag].flag}" style="margin-right: 1px;"/>
					{/foreach}
					</div>
	                

						<span>
							<ul>
								<li><img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" />
									{if $global_settings.seo_links}
										<a href="{$baseurl}/{$routes.show}/{$val.perma}/season/{$val.season}/episode/{$val.episode}" class="left">{$lang.watch_this}</a>
									{else}
										<a href="{$baseurl}/index.php?menu=episode&perma={$val.perma}&season={$val.season}&episode={$val.episode}" class="left">{$lang.watch_this}</a>
									{/if}									
								</li>
								<div class="clear"></div>
								{if $loggeduser_id neq ''}
									<li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addWatch({$id},3);" class="left" id="watch_button_{$id}">{$lang.seen_it}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addLike({$id},3,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="addLike({$id},3,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
								{else}
									<li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.seen_it}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
									<li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
								{/if}
							</ul>
						</span>
				</div>
			</li>