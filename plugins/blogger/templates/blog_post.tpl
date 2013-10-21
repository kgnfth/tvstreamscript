{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<link rel="stylesheet" href="{$baseurl}/plugins/blogger/css/blog.css" type="text/css" media="screen" />

{if $post_id neq 0}

<div class="span-16">
	<h3 class="span-16">
		{$post_data.title}
	</h3>
	<div class="blog_meta">
		{$lang.blog_posted_on} {$post_data.created}
		{if $post_data.tags|@count}
			 | {$lang.blog_tags}: 
			 {foreach from=$post_data.tags item=tag}
			 	<a href="{$baseurl}/blog/tag/{$tag.perma}" class="colored">{$tag.tag}</a>
			 {/foreach}
		{/if}
	</div> 
	<div class="blog_row">
		{$post_data.content}
		<br /><br />
	</div>
	
	{if $global_settings.facebook}
		<div class="fb-like" data-href="{$absolute_url}" data-send="true" data-width="620" data-show-faces="false"></div>
        <div class="clear"></div><br />
        <div class="fb-comments" data-href="{$absolute_url}" data-num-posts="10" data-width="620"></div>
        <div class="clear"></div><br />
	{/if}
</div>

{else}
	<script>
		window.location = '{$baseurl}/blog';
	</script>
{/if}
{include file="sidebar.tpl" title=sidebar}

{include file="footer.tpl" title=footer}