{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}
<div class="span-24" id="portfolio">
    <h1>{$lang.new_movies_new_movies}</h1>
    <div class="left">{$lang.pages}: </div>
    <div class="left filter-title myfilter">
        <ul id="filter">
            {if $global_settings.seo_links}
                <li><a href="{$baseurl}/{$routes.new_episodes}">{$lang.new_movies_new_episodes}</a></li>
                <li class="current"><a href="{$baseurl}/{$routes.new_movies}">{$lang.new_movies_new_movies}</a></li>
            {else}
                <li><a href="{$baseurl}/index.php?menu=new-shows">{$lang.new_movies_new_episodes}</a></li>
                <li class="current"><a href="{$baseurl}/index.php?menu=new-movies">{$lang.new_movies_new_movies}</a></li>
            {/if}
        </ul>
    </div>
    
    <div class="clear"></div>
    <ul class="span-24" id="portfolio">
        {php} $i=1; {/php}
        {if $movies neq ''}
            {foreach from=$movies key=key item=val name=movie_iterator}
                <li>
                    <div class="span-6 inner-6 tt {php}if ($i%4 == 0){ print('last'); } {/php} view">
                        <div class="item" style="text-align:center">
                            {if $global_settings.seo_links}
                                <a href="{$baseurl}/{$routes.movie}/{$val.perma}" class="spec-border-ie" title="">
                                    <img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=130&amp;h=190&amp;zc=1" alt=" " style="width:130px;height:190px;background-color: #717171;"/>
                                </a>
                            {else}
                                <a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" class="spec-border-ie" title="">
                                    <img class="img-preview spec-border"  src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$val.thumb}&amp;w=130&amp;h=190&amp;zc=1" alt=" " style="width:130px;height:190px;background-color: #717171;"/>
                                </a>
                            {/if}
                        </div>
                        <h5 class="left">
                            {if $global_settings.seo_links}
                                <a class="link" href="{$baseurl}/watch/{$val.perma}" title="{$val.title}">
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
                        </div>
                        
                        
                        <div class="right">
                            {if $val.imdb_rating}
                                <div style="float:right;display:block;color:#000;font-size:11px; font-weight: bold;">{$val.imdb_rating}</div>
                                <img src="{$templatepath}/images/imdb-icon.png" style="float:right;border:0px;margin-right: 5px;" />
                            {else}
                                &nbsp;
                            {/if}
                        </div>
                        
                        <span>
                            <ul>
                                {if $global_settings.seo_links}
                                    <li><img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="{$baseurl}/{$routes.movie}/{$val.perma}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
                                {else}
                                    <li><img src="{$templatepath}/images/icons/play.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="{$baseurl}/index.php?menu=watchmovie&perma={$val.perma}" class="left">{$lang.watch_this}</a></li><div class="clear"></div>
                                {/if}
                                {if $loggeduser_id neq ''}
                                    <li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addWatch({$key},2);" class="left" id="watch_button_{$key}">{$lang.seen_it}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="addLike({$key},2,1);" class="left">{$lang.like}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="addLike({$key},2,-1);" class="left">{$lang.dislike}</a></li><div class="clear"></div>
                                {else}
                                    <li><img src="{$templatepath}/images/icons/checkmark.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.seen_it}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/like.png" class="left" style="width:16px; height:16px; margin-right: 5px; margin-bottom:5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');"  class="left">{$lang.like}</a></li><div class="clear"></div>
                                    <li><img src="{$templatepath}/images/icons/delete.png" class="left" style="width:16px; height:16px; margin-right: 5px;" /><a href="javascript:void(0);" onclick="popUp('#popup_login');" class="left">{$lang.dislike}</a></li><div class="clear"></div>
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
    
    <div class="clear"></div>
    <div class="span-16 last clear">
        <ul class="pagination">
            {if $global_settings.seo_links}
                <li><a href="{$baseurl}/{$routes.movies}"><b>{$lang.new_movies_all_movies}</b></a></li>
            {else}
                <li><a href="{$baseurl}/index.php?menu=movies"><b>{$lang.new_movies_all_movies}</b></a></li>
            {/if}
        </ul>
    </div>
    
</div>
{include file="footer.tpl" title=footer}