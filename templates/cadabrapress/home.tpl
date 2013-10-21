{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if $smartbar and $global_settings.smart_bar eq 1 and ($smartbar.shows|@count gte 1114 or $smartbar.movies|@count gte 4)}
<div id="featured">
    <h3>Featured</h3>
    <div class="rounded">        
        {if $smartbar.shows|@count gte 4}            
            {foreach from=$smartbar.shows key=featured_show_id item=featured_show name=featured_show_loop}
                {if $smarty.foreach.featured_show_loop.iteration eq 1}
                    {if $global_settings.seo_links eq 1}
                        <div class="slide">
                            <span class="category"><a href="{$baseurl}/{$routes.tv_shows}" title="{$lang.tv_shows}" rel="category tag">{$lang.tv_shows}</a></span>
                            <a href="{$baseurl}/{$routes.show}/{$featured_show.permalink}" title="{$featured_show.title}">
                                <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$featured_show.thumbnail}&w=200&zc=1" alt="{$featured_show.title}" />
                            </a>                
                            <h2>
                                <a href="{$baseurl}/{$routes.show}/{$featured_show.permalink}" title="{$featured_show.title}">{$featured_show.title}</a>
                            </h2>    
                            <p>
                                {$featured_show.description|truncate:70:"...":true}
                            </p>
                        </div>
                        <div class="headings">
                            <ul>
                    {else}
                        <div class="slide">
                            <span class="category"><a href="{$baseurl}/index.php?menu=tv-shows" title="{$lang.tv_shows}" rel="category tag">{$lang.tv_shows}</a></span>
                        
                            <a href="{$baseurl}/index.php?menu=show&perma={$featured_show.permalink}" title="{$featured_show.title}">
                                <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$featured_show.thumbnail}&w=200&zc=1" alt="{$featured_show.title}" />
                            </a>                
                            <h2>
                                <a href="{$baseurl}/index.php?menu=show&perma={$featured_show.permalink}" title="{$featured_show.title}">{$featured_show.title}</a>
                            </h2>
                            <p>
                                {$featured_show.description|truncate:50:"...":true}
                            </p>
                        </div>
                        <div class="headings">
                            <ul>
                    {/if}
                {elseif $smarty.foreach.featured_show_loop.iteration lte 5}
                    {if $global_settings.seo_links eq 1}
                    <li>                        
                        <h2>
                            <a href="{$baseurl}/{$routes.show}/{$featured_show.permalink}" title="{$featured_show.title}">{$featured_show.title}</a>
                        </h2>                        
                        <a href="{$baseurl}/{$routes.show}/{$featured_show.permalink}" rel="bookmark" title="{$featured_show.title}"><img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$featured_show.thumbnail}&amp;w=75&amp;h=85&amp;zc=1" alt="{$featured_show.title}" /></a>                    
                        <p>
                            {$featured_show.description|truncate:160:"...":true}
                        </p>
                    </li>
                    {else}
                        <li>                        
                            <h2>
                                <a href="{$baseurl}/index.php?menu=show&perma={$featured_show.permalink}" title="{$featured_show.title}">{$featured_show.title}</a>
                            </h2>
                            <a href="{$baseurl}/index.php?menu=show&perma={$featured_show.permalink}" rel="bookmark" title="{$featured_show.title}"><img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$featured_show.thumbnail}&amp;w=75&amp;h=85&amp;zc=1" alt="{$featured_show.title}" /></a>                    
                            <p>{$featured_show.description|truncate:160:"...":true}</p>
                        </li>
                    {/if}
                {/if}
            {/foreach}
                            </ul>
                        </div>
        {else}
            {foreach from=$smartbar.movies key=featured_movie_id item=featured_movie name=featured_movie_loop}
                {if $smarty.foreach.featured_movie_loop.iteration eq 1}
                    {if $global_settings.seo_links eq 1}
                        <div class="slide">
                            <span class="category"><a href="{$baseurl}/{$routes.movies}" title="{$lang.movies}" rel="category tag">{$lang.movies}</a></span>
                            <a href="{$baseurl}/{$routes.movie}/{$featured_movie.perma}" title="{$featured_movie.title}">
                                <img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$featured_movie.thumb}&w=200&zc=1" alt="{$featured_movie.title}" />
                            </a>                
                            <h2>
                                <a href="{$baseurl}/{$routes.movie}/{$featured_movie.perma}" title="{$featured_movie.title}">{$featured_movie.title}</a>
                            </h2>    
                            <p>
                                {$featured_movie.description|truncate:70:"...":true}
                            </p>
                        </div>
                        <div class="headings">
                            <ul>
                    {else}
                        <div class="slide">
                            <span class="category"><a href="{$baseurl}/index.php?menu=movies" title="{$lang.movies}" rel="category tag">{$lang.movies}</a></span>
                        
                            <a href="{$baseurl}/index.php?menu=watchmovie&perma={$featured_movie.perma}" title="{$featured_movie.title}">
                                <img src="{$baseurl}/templates/cadabrapress/timthumb.php?src={$baseurl}/thumbs/{$featured_movie.thumb}&w=200&zc=1" alt="{$featured_movie.title}" />
                            </a>                
                            <h2>
                                <a href="{$baseurl}/index.php?menu=watchmovie&perma={$featured_movie.perma}" title="{$featured_movie.title}">{$featured_movie.title}</a>
                            </h2>
                            <p>
                                {$featured_movie.description|truncate:50:"...":true}
                            </p>
                        </div>
                        <div class="headings">
                            <ul>
                    {/if}
                {elseif $smarty.foreach.featured_movie_loop.iteration lte 5}
                    {if $global_settings.seo_links eq 1}
                        <li>
                            <h2>
                            <a href="{$baseurl}/{$routes.movie}/{$featured_movie.perma}" title="{$featured_movie.title}">{$featured_movie.title}</a>
                            </h2>
                            <a href="{$baseurl}/{$routes.movie}/{$featured_movie.perma}" rel="bookmark" title="{$featured_movie.title}"><img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$featured_movie.thumb}&amp;w=75&amp;h=85&amp;zc=1" alt="{$featured_movie.title}" /></a>                    
                            <p>{$featured_movie.description|truncate:160:"...":true}</p>
                        </li>
                    {else}
                        <li>
                            <h2>
                            <a href="{$baseurl}/index.php?menu=watchmovie&perma={$featured_movie.perma}" title="{$featured_movie.title}">{$featured_movie.title}</a>
                            </h2>
                            <a href="{$baseurl}/index.php?menu=watchmovie&perma={$featured_movie.perma}" rel="bookmark" title="{$featured_movie.title}"><img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$featured_movie.thumb}&amp;w=75&amp;h=85&amp;zc=1" alt="{$featured_movie.title}" /></a>                    
                            <p>{$featured_movie.description|truncate:160:"...":true}</p>
                        </li>
                    {/if}                
                {/if}
            {/foreach}
                            </ul>
                        </div>
        {/if}            
    </div>

</div> 
{/if}

<!-- TV guide -->

<div id="tv_guide"></div>
<script>
    {if $global_settings.tv_guide}
        getTVguide('{$today}');
    {/if}
</script>

{include file="footer.tpl" title=footer}