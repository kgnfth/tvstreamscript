{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}


{if $show_data}
    {foreach from=$show_data key=key item=val}
        <div class="archive">
            <h3>
                {$val.title}{if $season} - {$lang.show_season_menu|replace:"#season#":$season}{/if}
            </h3>
            <div class="rounded">
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
                                 <a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$val.imdb_rating}</a>
                                 <br />
                             {/if} 
                                 
                             {if $val.categories and $val.categories|@count}
                                 {$lang.categories}: 
                                 {foreach from=$val.categories item=category_data key=category_id}
                                     <a href="{$baseurl}/{$routes.tv_tag}/{$category_data.perma}" >{$category_data.tag}</a>
                                 {/foreach}
                                 <br />
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
                                 <a href="http://www.imdb.com/title/{$val.imdb_id}" target="_blank">{$val.imdb_rating}</a>
                                 <br />
                             {/if} 
                                 
                             {if $val.categories and $val.categories|@count}
                                 {$lang.categories}: 
                                 {foreach from=$val.categories item=category_data key=category_id}
                                     <a href="{$baseurl}/index.php?menu=tv-tag&tag={$category_data.perma}" >{$category_data.tag}</a>
                                 {/foreach}
                                 <br />
                             {/if}             
                         </span>
                         {/if}
    
                    </div>
                {/if}
             </div>
         </div>
         
         <div class="archive  small-header">
             <h3>
                 <span class="heading-left">{$lang.episode_list}</span>
                 {if $show_seasons|@count gt 4}<div class="clear"></div>{/if}
                <div class="navigation heading-nav{if $show_seasons|@count gt 4} pull-left{/if}">
                    {if $global_settings.seo_links}
                        <a class="page-numbers current{if $season eq ''} highlight{/if}" href="{$baseurl}/{$routes.show}/{$val.permalink}">{$lang.show_all_seasons}</a>
                    {else}
                        <a class="page-numbers current{if $season eq ''} highlight{/if}" href="{$baseurl}/index.php?menu=show&perma={$val.permalink}">{$lang.show_all_seasons}</a>
                    {/if}
                    {if $show_seasons}
                        {foreach from=$show_seasons key=k item=v}
                            {if $global_settings.seo_links}
                                <a class="page-numbers current{if $season eq $v} highlight{/if}" href="{$baseurl}/{$routes.show}/{$val.permalink}/season/{$v}">{$lang.show_season_menu|replace:'#season#':$v}</a>
                            {else}
                                <a class="page-numbers current{if $season eq $v} highlight{/if}" href="{$baseurl}/index.php?menu=show&perma={$val.permalink}&season={$v}">{$lang.show_season_menu|replace:'#season#':$v}</a>
                            {/if}
                        {/foreach}
                    {/if}
                </div>
                <div class="clear"></div>
            </h3>
            <div class="rounded">
                {foreach from=$episodes key=key item=val name=episode_loop}
                    {if $global_settings.seo_links}                
                        <div class="post">
                        
                            <div class="buttons">
                                <a href="{$baseurl}/{$routes.show}/{$val.perma}/season/{$val.season}/episode/{$val.episode}" rel="bookmark" title="{$val.title}">
                                    <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=130&amp;zc=1" />
                                </a>
                                <div class="button-group">
                                    <div class="button" hint="{$lang.watch_this}">
                                        <a href="{$baseurl}/{$routes.show}/{$val.perma}/season/{$val.season}/episode/{$val.episode}"><i class="icon-play"></i></a>
                                    </div>
                                    <div class="button" hint="{$lang.episode_list}">
                                        <a href="{$baseurl}/{$routes.show}/{$val.perma}"><i class="icon-list"></i></a>
                                    </div>    
                                    {if $loggeduser_id}
                                        <div class="button" hint="{$lang.like}" onclick="addLike({$key},3,1);"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="addLike({$key},3,-1);"><i class="icon-thumbs-down"></i></div>
                                        <div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$key},3); jQuery(this).addClass('button-active'); "><i class="icon-eye-open"></i></div>
    
                                    {else}
                                        <div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
                                        <div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>                            
                                    {/if}    
                                </div>
                            </div>
                            <h2>
                                 <a href="{$baseurl}/{$routes.show}/{$val.perma}/season/{$val.season}/episode/{$val.episode}" title="{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}">{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}</a>
                             </h2>
                             
                             <p>{$val.description|truncate:180:"...":true}<br /></p>
                             
                             {foreach from=$val.languages item=flag key=key}                        
                                <img src="{$embed_languages[$flag].flag}" class="flag" hint="{$embed_languages[$flag].language}"  style="margin-right: 1px;"/>
                            {/foreach}
        
                        </div>
                    {else}
                        <div class="post">
                        
                            <div class="buttons">
                                <a href="{$baseurl}/index.php?menu=episode&perma={$val.perma}&season={$val.season}&episode={$val.episode}" rel="bookmark" title="{$val.title}">
                                    <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumbnail}&amp;w=190&amp;h=130&amp;zc=1" />
                                </a>
                                <div class="button-group">
                                    <div class="button" hint="{$baseurl}/index.php?menu=episode&perma={$val.perma}&season={$val.season}&episode={$val.episode}">
                                        <a href="{$baseurl}/index.php?menu=show&perma={$val.perma}"><i class="icon-play"></i></a>
                                    </div>
                                    <div class="button" hint="{$lang.episode_list}">
                                        <a href="{$baseurl}/index.php?menu=show&perma={$val.perma}"><i class="icon-list"></i></a>
                                    </div>    
                                    {if $loggeduser_id}
                                        <div class="button" hint="{$lang.like}" onclick="addLike({$key},3,1);"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="addLike({$key},3,-1);"><i class="icon-thumbs-down"></i></div>
                                        <div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="addWatch({$key},3); jQuery(this).addClass('button-active'); "><i class="icon-eye-open"></i></div>
    
                                    {else}
                                        <div class="button" hint="{$lang.like}" onclick="popUp('#popup_login');"><i class="icon-thumbs-up"></i></div>
                                        <div class="button" hint="{$lang.dislike}" onclick="popUp('#popup_login');"><i class="icon-thumbs-down"></i></div>
                                        <div class="button{if $val.seen} button-active{/if}" hint="{$lang.seen_it}" onclick="popUp('#popup_login');"><i class="icon-eye-open"></i></div>                            
                                    {/if}    
                                </div>
                            </div>
                            <h2>
                                 <a href="{$baseurl}/index.php?menu=episode&perma={$val.perma}&season={$val.season}&episode={$val.episode}" title="{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}">{$lang.show_episode_title|replace:'#season#':$val.season|replace:'#episode#':$val.episode}</a>
                             </h2>
                             
                             <p>{$val.description|truncate:180:"...":true}<br /></p>
                             
                             {foreach from=$val.languages item=flag key=key}                        
                                <img src="{$embed_languages[$flag].flag}" class="flag" hint="{$embed_languages[$flag].language}"  style="margin-right: 1px;"/>
                            {/foreach}
        
                        </div>
                    {/if}
                {/foreach}
            </div>
         </div>
    {/foreach}
{else}
    {include file="404.tpl" title=404}    
{/if}
{include file="footer.tpl" title=footer}