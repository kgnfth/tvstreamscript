{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if $searchshows neq ''}
<div class="span-24">
    <h1>{$lang.search_show_results} "{$searchterm}"</h1>
    <div class="clear"></div>
    <form method="post" id="search-form" action="{$baseurl}/index.php" >
        <input type="hidden" name="menu" value="search" />
        <input type="text" name="query" placeholder="{$lang.search_tip}" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
    </form>
    <div class="clear"></div>
    <ul class="span-24">
        {php} $i=1; {/php}
        {if $searchshows neq ''}
            {foreach from=$searchshows key=key item=val name=show_iterator}
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
</div>
{/if}

{if $searchepisodes neq ''}
<div class="span-24">
    <h1>{$lang.search_episode_results} "{$searchterm}"</h1>
    <div class="clear"></div>
    <form method="post" id="search-form" action="{$baseurl}/index.php" >
        <input type="hidden" name="menu" value="search" />
        <input type="text" name="query"  placeholder="{$lang.search_tip}" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
    </form>
    <div class="clear"></div>
    <ul class="span-24">
        {php} $i=1; {/php}
        {if $searchepisodes neq ''}
            {foreach from=$searchepisodes key=key item=val name=show_iterator}
                <li>
                    <div class="span-6 inner-6 tt {php}if ($i%4 == 0){ print('last'); } {/php} view">
                        <div class="item" style="text-align:center">
                            {if $global_settings.seo_links}
                                <a href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" class="spec-border-ie" title="">
                                    <img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=136&amp;zc=1" alt=" " style="width:190px;height:136px;background-color: #717171;"/>
                                </a>
                            {else}
                                <a href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" class="spec-border-ie" title="">
                                    <img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=136&amp;zc=1" alt=" " style="width:190px;height:136px;background-color: #717171;"/>
                                </a>
                            {/if}
                        </div>
                        <h5 class="left">
                            {if $global_settings.seo_links}
                                <a class="link" href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" title="{$val.title}">
                                    {$val.title|truncate:25:"...":true}
                                </a>
                            {else}
                                <a class="link" href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" title="{$val.title}">
                                    {$val.title|truncate:25:"...":true}
                                </a>
                            {/if}
                        </h5>
                        
                        {if $val.seen}
                            <div id="seen{$key}"><a class="seen right" href="javascript:void(0);"></a></div>
                        {else}
                            <div id="seen{$key}"></div>                        
                        {/if}
                        
                        <div class="clear"></div>                        
                        
                        <p style="margin-bottom:0px;">{$lang.search_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}</p>
                        <div class="clear"></div><br />
                        
                        <div class="left">
                        {foreach from=$val.languages item=flag key=k}
                            <img src="{$embed_languages[$flag].flag}" style="margin-right: 1px;"/>
                        {/foreach}
                        </div>
                        <span>
                            <ul>
                                {if $global_settings.seo_links}
                                    <li><img src="{$templatepath}/images/icons/play.png" class="left hover-link" /><a href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$val.season}/episode/{$val.episode}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
                                {else}
                                    <li><img src="{$templatepath}/images/icons/play.png" class="left hover-link" /><a href="{$baseurl}/index.php?menu=episode&perma={$val.permalink}&season={$val.season}&episode={$val.episode}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
                                {/if}
                                {if $loggeduser_id neq ''}
                                    <li><img src="{$templatepath}/images/icons/checkmark.png" class="left hover-link" /><a href="javascript:void(0);" onclick="addWatch({$key},3);" class="left" id="watch_button_{$key}">{$lang.seen_it}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="addLike({$key},3,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link" /><a href="javascript:void(0);" onclick="addLike({$key},3,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/film.png" class="left hover-link nobottommargin" /><a href="{$baseurl}/{$routes.show}/{$val.permalink}" class="left">{$lang.episode_list}</a></li><div class="clear"></div>
                                {else}
                                    <li><img src="{$templatepath}/images/icons/checkmark.png" class="left hover-link" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.seen_it}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/film.png" class="left hover-link nobottommargin" /><a href="{$baseurl}/{$routes.show}/{$val.permalink}" class="left">{$lang.episode_list}</a></li><div class="clear"></div>
                                {/if}
                            </ul>
                        </span>
                    </div>
                </li>

                 {php}if ($i%4 == 0){ print('<div class="clear"></div>'); } {/php}
                 {php}$i++{/php}
            {/foreach}
        {/if}        
    
    </ul>
</div>
{/if}

{if $searchmovies neq ''}
<div class="span-24">
    <h1>{$lang.search_movie_results} "{$searchterm}"</h1>
    <div class="clear"></div>
    <form method="post" id="search-form" action="{$baseurl}/index.php" >
        <input type="hidden" name="menu" value="search" />
        <input type="text" name="query"  placeholder="{$lang.search_tip}" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
    </form>
    <div class="clear"></div>
    <ul class="span-24">
        {php} $i=1; {/php}
        {if $searchmovies neq ''}
            {foreach from=$searchmovies key=key item=val name=show_iterator}
                <li>
                    <div class="span-6 inner-6 tt {php}if ($i%4 == 0){ print('last'); } {/php} view">
                        <div class="item" style="text-align:center">
                            {if $global_settings.seo_links}
                                <a href="{$baseurl}/{$routes.movie}/{$val.perma}" class="spec-border-ie" title="">
                                    <img class="img-preview spec-border show-thumbnail"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=130&amp;h=190&amp;zc=1" alt=" " />
                                </a>
                            {else}
                                <a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" class="spec-border-ie" title="">
                                    <img class="img-preview spec-border show-thumbnail"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=130&amp;h=190&amp;zc=1" alt=" " />
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

                        <span>
                            <ul>
                                {if $global_settings.seo_links}
                                    <li><img src="{$templatepath}/images/icons/play.png" class="left hover-link" /><a href="{$baseurl}/{$routes.movie}/{$val.perma}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
                                {else}
                                    <li><img src="{$templatepath}/images/icons/play.png" class="left hover-link" /><a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
                                {/if}
                                {if $loggeduser_id neq ''}
                                    <li><img src="{$templatepath}/images/icons/checkmark.png" class="left hover-link" /><a href="javascript:void(0);" onclick="addWatch({$key},2);" class="left" id="watch_button_{$key}">{$lang.seen_it}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="addLike({$key},2,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link nobottommargin" /><a href="javascript:void(0);" onclick="addLike({$key},2,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
                                {else}
                                    <li><img src="{$templatepath}/images/icons/checkmark.png" class="left hover-link" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.seen_it}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/like.png" class="left hover-link" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/delete.png" class="left hover-link nobottommargin" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
                                {/if}
                            </ul>
                        </span>
                    </div>
                </li>

                 {php}if ($i%4 == 0){ print('<div class="clear"></div>'); } {/php}
                 {php}$i++{/php}
            {/foreach}
        {/if}        
    
    </ul>
</div>
{/if}

{if $searchmovies eq '' and $searchepisodes eq '' and $searchshows eq ''}
    <div class="span-24">
        <h1>{$lang.search_sorry}</h1>
        
        <br />{$lang.search_sorry_description}<br /><br />
        
        <form method="post" id="search-form" action="{$baseurl}/index.php" >
            <input type="hidden" name="menu" value="search" />
            <input type="text" name="query"  placeholder="{$lang.search_tip}" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
        </form>
    </div>
    <br /><br />
{/if}

{include file="footer.tpl" title=footer}