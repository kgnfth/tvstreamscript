	<form method="post" id="search-form" action="{$baseurl}/index.php" class="right">
		<input type="hidden" name="menu" value="search" />
		<input type="text" name="query" value="{$lang.search_tip}" class="search-input" onfocus="if(this.value=='{$lang.search_tip}') this.value=''" onblur="if(this.value=='') this.value='{$lang.search_tip}'" /> <input type="submit" value="{$lang.search_button}" class="btn tab02d grey search-submit" style="width: 100px" />
	</form>