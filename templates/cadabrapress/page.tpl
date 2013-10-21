{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if page neq ''}
	<div class="archive">
		<h3>
			{$page.title}
		</h3>
		<div class="rounded">
			<p>
				<br />
				{$page.content}
				<br /><br /><br />
			</p>
		</div>
	</div>
{else}
	<div class="archive">
		<h3>
			Page not found
		</h3>
		<div class="rounded">
			<p>
				<br />
				<center>Invalid page</center>
				<br /><br /><br />
			</p>
		</div>
	</div>
{/if}

{include file="footer.tpl" title=footer}