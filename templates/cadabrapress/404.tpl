<div class="archive">
	<h3>
		Not found
	</h3>
	<div class="rounded">
		<p></p>
		<form method="post" id="search-form" action="{$baseurl}/index.php">
			<input type="hidden" name="menu" value="search" />
			<input type="text" name="query" value="{$lang.search_tip}" onfocus="if(this.value=='{$lang.search_tip}') this.value=''" onblur="if(this.value=='') this.value='{$lang.search_tip}'" style="width:200px" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey" style="width:100px; cursor:pointer;" />
		</form>
		<div class="clear"></div><br /><br />
	</div>
</div>