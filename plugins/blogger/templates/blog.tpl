{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<link rel="stylesheet" href="{$baseurl}/plugins/blogger/css/blog.css" type="text/css" media="screen" />
<div class="span-16">
    
    {if $blog_posts|@count neq 0}
        {foreach from=$blog_posts item=post name=post_loop}
            <div class="blog_row_short">
                <h4>
                    <a href="{$baseurl}/blog/{$post.perma}">{$post.title}</a>
                </h4>
                <div class="clear"></div>
                {if $post.thumbnail neq ''}
                    <div class="flickr item left blog_thumbnail">
                        <a href="{$baseurl}/blog/{$post.perma}">
                            <img class="tooltip" original-title="{$post.title}" alt="" src="{$baseurl}/thumbs/{$post.thumbnail}" style="width:150px;" />
                        </a>
                    </div>
                {/if}
                {$post.excerpt}                
            </div>
            <div class="clear"></div>
            <a href="{$baseurl}/blog/{$post.perma}" class="blog_read_more">{$lang.blog_read_more}</a>
            <br /><br />
        {/foreach} 
        
        {if $next_url or $previous_url}
            <div class="clear"></div>
            <div class="blog_meta">
                {if $next_url}
                    <a href="{$baseurl}{$next_url}" class="left colored">{$lang.blog_next}</a>
                {/if}
                {if $previous_url}
                    <a href="{$baseurl}{$previous_url}" class="right colored">{$lang.blog_previous}</a>
                {/if}
                <div class="clear"></div>
            </div>
        {/if}
        
    {else}
        {$lang.blog_no_post}
    {/if}
</div>
{include file="sidebar.tpl" title=sidebar}

{include file="footer.tpl" title=footer}