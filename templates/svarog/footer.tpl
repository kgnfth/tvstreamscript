				{include file="popups.tpl" title=popups}
				<div class="clear"></div><br />
			</div>

			
			<div class="footer-line-wrapper">
				<div class="footer-container">
					<div class="span-24 notopmargin footer-line">
						<div class="span-10 notopmargin">
							<p>Copyright &copy 2011-2012 {$sitename} | Powered by <a href="http://tvstreamscript.com" target="_blank">TVstreamScript</a></p>
						</div>
						<div class="span-14 notopmargin last">
							<p class="right">
								{if $global_settings.seo_links eq 1}
									<a class="link" href="{$baseurl}">{$lang.home}</a> / 
									<a class="link" href="{$baseurl}/tv-shows">{$lang.tv_shows}</a> / 
									<a class="link" href="{$baseurl}/movies">{$lang.movies}</a>
								{else}
									<a class="link" href="{$baseurl}">{$lang.home}</a> / 
									<a class="link" href="{$baseurl}/?menu=tv-shows">{$lang.tv_shows}</a> / 
									<a class="link" href="{$baseurl}/?menu=movies">{$lang.movies}</a>
								{/if}
							</p>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<!-- END MAIN CONTENT -->
		<!-- END FOOTER -->
		<div class="clear"></div>
		
		<div class="bottom-bar" style="display:none">
			<div class="bottom-bar-close" onclick="hidePromoBar();"></div>
			<div class="bottom-container">
				<div class="bottom-left">
					{if $available_languages|@count gt 1}
						{$lang.select_language}
						
						{foreach from=$available_languages item=lang_data key=lang_key}
							<img src="{$lang_data.flag}" onclick="{literal}jQuery('#lang').val('{/literal}{$lang_data.language_code}{literal}');jQuery('#langselect').submit();{/literal}" />
						{/foreach} 
						<form method="post" id="langselect">
							<input type="hidden" name="lang" id="lang" value="hu" />
							<input type="hidden" name="action" value="change_language" />
						</form>
					{/if}
				</div>
				<div class="bottom-right">
					{if $global_settings.facebook}
						<div class="bottom-text">
							{$lang.bottom_promo}
						</div>
						<div class="bottom-like">
							<div class="fb-like" data-send="false" data-layout="button_count" data-href="{$baseurl}" data-width="450" data-show-faces="true"></div>
						</div>
					{/if}
				</div>
			</div>
			
		</div>
		
		{literal}
			<script>
				if (!getCookie("nobar")){
					jQuery('.bottom-bar').show();
				}
			</script>
		{/literal}
		
		{if $global_settings.analytics}
			{literal}
				<script type="text/javascript">
	
				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', '{/literal}{$global_settings.analytics.tracking}{literal}']);
				  _gaq.push(['_trackPageview']);
	
				  (function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();
	
				</script>
			{/literal}
		{/if}
		<script type="text/javascript" src="{$baseurl}/templates/svarog/js/bootstrap.js?r=1"></script>
	</body>
</html>