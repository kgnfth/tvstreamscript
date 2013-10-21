{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if !$loggeduser_id}
    <script>
        window.location = '{$baseurl}';
    </script>
{else}
    <div class="archive">
        <h3>
            {$lang.recommend_shows_recommendations}
        </h3>
        <div class="rounded">
            {if $result_type eq 'random'}
                <p>{$lang.recommend_shows_no_show}</p>
            {else}
                <p>{$lang.recommend_shows_show}</p>
            {/if}
            
            {foreach from=$shows key=key item=show}
                {if $global_settings.seo_links}                
                    <div class="post">
                    
                        <div class="buttons">
                            <a href="{$baseurl}/{$routes.show}/{$show.permalink}" rel="bookmark" title="{$show.title}">
                                <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=100&amp;h=150&amp;zc=1" />
                            </a>
                            <div class="button-group">
                                <div class="button" hint="{$lang.watch_this}">
                                    <a href="{$baseurl}/{$routes.show}/{$show.permalink}"><i class="icon-play"></i></a>
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
                             <a href="{$baseurl}/{$routes.show}/{$show.permalink}" title="{$show.title}">{$show.title}</a>
                         </h2>
                         
                         <p>{$show.description}<br /></p>
                         
                         {if $show.meta.year_started or $show.meta.creators|@count or $show.meta.stars|@count or $show.imdb_rating}
                         <span class="meta">
                             {if $show.meta.year_started}
                                 {$lang.year_started}: <a href="{$baseurl}/index.php?menu=search&year={$show.meta.year_started}">{$show.meta.year_started}</a><br />
                             {/if}
                             
                             {if $show.meta.stars|@count}
                                 {$lang.stars}: 
                                 {foreach from=$show.meta.stars item=star key=star_id}
                                     <a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
                                 {/foreach}
                                 <br />
                             {/if}    
                             
                             {if $show.meta.creators|@count}
                                 {$lang.creators}: 
                                 {foreach from=$show.meta.creators item=creator key=creator_id}
                                     <a href="{$baseurl}/index.php?menu=search&director={$creator}">{$creator}</a>&nbsp; 
                                 {/foreach}
                                 <br />
                             {/if}    
                             
                             {if $show.imdb_rating and $show.imdb_id}
                                 {$lang.imdb_rating}: 
                                 <a href="http://www.imdb.com/title/{$show.imdb_id}" target="_blank">{$show.imdb_rating}</a><br /> 
                             {/if}                 
                         </span>
                         {/if}
    
                    </div>
                {else}
                    <div class="post">
                    
                        <div class="buttons">
                            <a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}" rel="bookmark" title="{$show.title}">
                                <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$show.thumbnail}&amp;w=100&amp;h=150&amp;zc=1" />
                            </a>
                            <div class="button-group">
                                <div class="button" hint="{$lang.watch_this}">
                                    <a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}"><i class="icon-play"></i></a>
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
                             <a href="{$baseurl}/index.php?menu=show&perma={$show.permalink}" title="{$show.title}">{$show.title}</a>
                         </h2>
                         
                         <p>{$show.description}<br /></p>
                         
                         {if $show.meta.year_started or $show.meta.creators|@count or $show.meta.stars|@count or $show.imdb_rating}
                         <span class="meta">
                             {if $show.meta.year_started}
                                 {$lang.year_started}: <a href="{$baseurl}/index.php?menu=search&year={$show.meta.year_started}">{$show.meta.year_started}</a><br />
                             {/if}
                             
                             {if $show.meta.stars|@count}
                                 {$lang.stars}: 
                                 {foreach from=$show.meta.stars item=star key=star_id}
                                     <a href="{$baseurl}/index.php?menu=search&star={$star}">{$star}</a>&nbsp; 
                                 {/foreach}
                                 <br />
                             {/if}    
                             
                             {if $show.meta.creators|@count}
                                 {$lang.creators}: 
                                 {foreach from=$show.meta.creators item=creator key=creator_id}
                                     <a href="{$baseurl}/index.php?menu=search&director={$creator}">{$creator}</a>&nbsp; 
                                 {/foreach}
                                 <br />
                             {/if}    
                             
                             {if $show.imdb_rating and $show.imdb_id}
                                 {$lang.imdb_rating}: 
                                 <a href="http://www.imdb.com/title/{$show.imdb_id}" target="_blank">{$show.imdb_rating}</a><br /> 
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