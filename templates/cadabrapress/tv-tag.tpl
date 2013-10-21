{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if $tag}

    <div class="archive small-header">
        <h3>
            <span class="heading-left">{$lang.tvtag_title|replace:'#tag#':$tag}</span>
            <div class="navigation heading-nav">
                {if $global_settings.seo_links}
                    <a class="page-numbers current{if $sortby eq 'abc'} highlight{/if}" href="{$baseurl}/{$routes.tv_tag}/{$tag_perma}/abc">{$lang.sorting_abc}</a>
                    <a class="page-numbers current{if $sortby eq 'date'} highlight{/if}" href="{$baseurl}/{$routes.tv_tag}/{$tag_perma}/date">{$lang.sorting_newest}</a>
                    <a class="page-numbers current{if $sortby eq 'imdb_rating'} highlight{/if}" href="{$baseurl}/{$routes.tv_tag}/{$tag_perma}/imdb_rating">{$lang.sorting_imdb}</a>
                {else}
                    <a class="page-numbers current{if $sortby eq 'abc'} highlight{/if}" href="{$baseurl}/index.php?menu=tv-tag&tag={$tag_perma}&sortby=abc">{$lang.sorting_abc}</a>
                    <a class="page-numbers current{if $sortby eq 'date'} highlight{/if}" href="{$baseurl}/index.php?menu=tv-tag&tag={$tag_perma}&sortby=date">{$lang.sorting_newest}</a>
                    <a class="page-numbers current{if $sortby eq 'imdb_rating'} highlight{/if}" href="{$baseurl}/index.php?menu=tv-tag&tag={$tag_perma}&sortby=imdb_rating">{$lang.sorting_imdb}</a>
                {/if}
            </div>
            <div class="clear"></div>
        </h3>
        <div class="rounded">
            {if $tagged_shows}
                {foreach from=$tagged_shows key=key item=val name=show_loop}
                    {if $global_settings.seo_links}                
                        <div class="post">
                        
                            <div class="buttons">
                                <a href="{$baseurl}/{$routes.show}/{$val.permalink}" rel="bookmark" title="{$val.title}">
                                    <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=100&amp;h=150&amp;zc=1" />
                                </a>
                                <div class="button-group">
                                    <div class="button" hint="{$lang.watch_this}">
                                        <a href="{$baseurl}/{$routes.show}/{$val.permalink}"><i class="icon-play"></i></a>
                                    </div>
                                    {if $loggeduser_id}
                                        <div class="button" hint="{$lang.like}" onclick="addLike({$key},1,1);"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="addLike({$key},1,-1);"><i class="icon-thumbs-down"></i></div>
                                    {else}
                                        <div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>                            
                                    {/if}
        
                                </div>
                            </div>
                            <h2>
                                 <a href="{$baseurl}/{$routes.show}/{$val.permalink}" title="{$val.title}">{$val.title}</a>
                             </h2>
                             
                             <p>{$val.description}<br /></p>
                             
                             {if $val.meta.year_started or $val.meta.creators|@count or $val.meta.stars|@count or $val.imdb_rating}
                             <span class="meta">
                                 {if $val.meta.year_started}
                                     {$lang.year_started}: <a href="{$baseurl}/index.php?menu=search&year={$val.meta.year_started}">{$val.meta.year_started}</a><br />
                                 {/if}
                                 
                                 {if $val.meta.stars|@count}
                                     {$lang.stars}: 
                                     {foreach from=$val.meta.stars item=star key=star_id}
                                         <a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
                                     {/foreach}
                                     <br />
                                 {/if}    
                                 
                                 {if $val.meta.creators|@count}
                                     {$lang.creators}: 
                                     {foreach from=$val.meta.creators item=creator key=creator_id}
                                         <a href="{$baseurl}/index.php?menu=search&director={$creator}">{$creator}</a>&nbsp; 
                                     {/foreach}
                                     <br />
                                 {/if}    
                                 
                                 {if $val.imdb_rating and $val.imdb_id}
                                     {$lang.imdb_rating}: 
                                     <a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$val.imdb_rating}</a><br /> 
                                 {/if}                 
                             </span>
                             {/if}
        
                        </div>
                    {else}
                        <div class="post">
                        
                            <div class="buttons">
                                <a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}" rel="bookmark" title="{$val.title}">
                                    <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=100&amp;h=150&amp;zc=1" />
                                </a>
                                <div class="button-group">
                                    <div class="button" hint="{$lang.watch_this}">
                                        <a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}"><i class="icon-play"></i></a>
                                    </div>
                                    {if $loggeduser_id}
                                        <div class="button" hint="{$lang.like}" onclick="addLike({$key},1,1);"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="addLike({$key},1,-1);"><i class="icon-thumbs-down"></i></div>
                                    {else}
                                        <div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>                            
                                    {/if}
        
                                </div>
                            </div>
                            <h2>
                                 <a href="{$baseurl}/index.php?menu=show&perma={$val.permalink}" title="{$val.title}">{$val.title}</a>
                             </h2>
                             
                             <p>{$val.description}<br /></p>
                             
                             {if $val.meta.year_started or $val.meta.creators|@count or $val.meta.stars|@count or $val.imdb_rating}
                             <span class="meta">
                                 {if $val.meta.year_started}
                                     {$lang.year_started}: <a href="{$baseurl}/index.php?menu=search&year={$val.meta.year_started}">{$val.meta.year_started}</a><br />
                                 {/if}
                                 
                                 {if $val.meta.stars|@count}
                                     {$lang.stars}: 
                                     {foreach from=$val.meta.stars item=star key=star_id}
                                         <a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
                                     {/foreach}
                                     <br />
                                 {/if}    
                                 
                                 {if $val.meta.creators|@count}
                                     {$lang.creators}: 
                                     {foreach from=$val.meta.creators item=creator key=creator_id}
                                         <a href="{$baseurl}/index.php?menu=search&director={$creator}">{$creator}</a>&nbsp; 
                                     {/foreach}
                                     <br />
                                 {/if}    
                                 
                                 {if $val.imdb_rating and $val.imdb_id}
                                     {$lang.imdb_rating}: 
                                     <a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$val.imdb_rating}</a><br /> 
                                 {/if}                 
                             </span>
                             {/if}
        
                        </div>
                                    
                    {/if}    
                {/foreach}
            {else}
                <center><br />{$lang.tvtag_no_show}<br /><br /><br /></center>
            {/if}
        </div>
    </div>
{else}
    {include file="404.tpl" title=404}
{/if}
{include file="footer.tpl" title=footer}