{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-16">
{if page}
	<h1>{$page.title}</h1>
	
	{$page.content}
	
{/if}
</div>


{include file="sidebar.tpl" title=sidebar}

{include file="footer.tpl" title=footer}