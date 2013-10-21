{if $loggeduser_username neq ''}
	<div id="avatar_form" class="modal" style="display:none">
		<h4 class="left">{$lang.change_avatar}</h4>
		<a href='javascript:void(0)' onclick="jQuery('#backgroundPopup').hide(); jQuery('#avatar_form').hide(); jQuery('object').show(); jQuery('iframe').show();" class="right modal-close">{$lang.close}</a>
	 	<div class="clear"></div>
	 	<form method="post" enctype="multipart/form-data" id="avatar_upload">
	 		<input type="file" name="avatar_file" id="file" />	 		
	 		<input type="hidden" name="action" value="change_avatar" />
	 		<a class="btn tab02b grey" style="margin:0px" onclick="jQuery('#avatar_upload').submit();" href="javascript:void(0);">{$lang.submit}</a>
	 	</form>
	</div>
	
	<div id="like_form" class="modal" style="display:none">
		<h4 class="left">{$lang.explain_why} <span id="nem"></span> {$lang.like_it}...</h4>
		<a href='javascript:void(0)' onclick="jQuery('#backgroundPopup').hide(); jQuery('#like_form').fadeOut('fast'); jQuery('object').show(); jQuery('iframe').show();" class="right modal-close">{$lang.close}</a>
	 	<div class="clear"></div>
	 	<form method="post">
	 		<textarea id="like_comment" name="like_comment"></textarea>
	 		<input type="hidden" name="action" value="like_comment" />
			<input type="hidden" name="like_id" id="like_id" value="" />
			<input type="hidden" name="like_type" id="like_type" value="" />
			<input type="hidden" name="like_vote" id="like_vote" value="" />
	 		<a class="btn tab02b grey" style="margin:0px" onclick="doLike();" href="javascript:void(0);" id="like_button">{$lang.like}</a> <a class="btn tab02b grey" style="margin:0px" onclick="jQuery('#backgroundPopup').hide(); jQuery('#like_form').fadeOut('fast'); jQuery('object').show(); jQuery('iframe').show();" href="javascript:void(0);">{$lang.close}</a> 
	 	</form>
	</div>
	
	<div id="settings_form" class="modal" style="display:none">
		<h4 class="left">{$lang.settings}</h4>
		<a href='javascript:void(0)' onclick="jQuery('#backgroundPopup').hide(); jQuery('#settings_form').fadeOut('fast'); jQuery('object').show(); jQuery('iframe').show();" class="right modal-close">{$lang.close}</a>
	 	<div class="clear"></div>
	 	<form method="post" id="settings_change">
	 		<div class="clear"></div><br />
	 		<input type="checkbox" name="notify_favorite" {if $loggeduser_details.notify_favorite}checked="checked"{/if} /> {$lang.email_on_new_favorite}
	 		<div class="clear"></div><br />
	 		<input type="checkbox" name="notify_new" {if $loggeduser_details.notify_new}checked="checked"{/if} /> {$lang.daily_email}
	 		<div class="clear"></div><br />
	 		<input type="hidden" name="action" value="settings" />
	 		<a class="btn tab02b grey" style="margin:0px" onclick="jQuery('#settings_change').submit();" href="javascript:void(0);" id="like_button">{$lang.save}</a> <a class="btn tab02b grey" style="margin:0px" onclick="jQuery('#backgroundPopup').hide(); jQuery('#settings_form').fadeOut('fast'); jQuery('object').show(); jQuery('iframe').show();" href="javascript:void(0);">{$lang.close}</a> 
	 	</form>
	</div>
	
{else}

	<div id="popup_login" class="small_modal" style="display:none">
		<h4 class="left">{$lang.login}</h4>
		<a href='javascript:void(0)' onclick="jQuery('#backgroundPopup').hide(); jQuery('#popup_login').hide(); jQuery('object').show(); jQuery('iframe').show();" class="right modal-close">{$lang.close}</a>
	 	<div class="clear"></div>
	 	<form method="post" id="popup_login_form">
			{$lang.username}:
			<div class="clear"></div>
			<input type="text" name="username" value="{$username}" style="margin: 5px 0px 5px 0px;width:100%;" />
			<div class="clear"></div>
			
			{$lang.password}: 
			<input type="password" name="password" style="margin: 5px 0px 0px 0px;width:100%;" />
			<div class="clear"></div>
			
			<input type="hidden" name="action" value="login" />
	 		<a class="btn tab02b grey" style="margin:0px" onclick="jQuery('#popup_login_form').submit();" href="javascript:void(0);">{$lang.login_button}</a> <a class="btn tab02b grey" style="margin:0px" href="{$baseurl}/register">{$lang.register}</a> 
	 	</form>
	 	{if $global_settings.facebook}
			<center>
				<div class="clear"></div><br />
				<span style="font-size: 18px; font-weight:bold">{$lang.or_caps}</span><br />
				<div class="clear"></div><br />
				<img src="{$templatepath}/images/fb_login.jpg" style="cursor:pointer" onclick="facebookDoLogin('#fb_popup_login_button');" id="fb_popup_login_button" />
	
				<div class="clear"></div><br />
			</center>
		{/if}
	</div>

{/if}

<div id="backgroundPopup"></div>