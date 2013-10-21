{if $loggeduser_username neq ''}
	<div id="avatar_form" class="modal">
		<h3 class="title">
			<div class="pull-left">{$lang.change_avatar}</div>
			<i class="icon-remove-circle pull-right modal-close"></i>
			<div class="clear"></div>
		</h3>
		<div class="padder">
		 	<form method="post" enctype="multipart/form-data" id="avatar_upload">
		 		<input type="file" name="avatar_file" onchange="jQuery('#fake-file').val(jQuery(this).val())" id="file" style="display:none" />
		 		<input type="text" id="fake-file" class="full-width-input" placeholder="click here to upload your avatar" onclick="jQuery('#file').click();" />	 		
		 		<input type="hidden" name="action" value="change_avatar" />
		 		<a class="btn-main" onclick="jQuery('#avatar_upload').submit();" href="javascript:void(0);">{$lang.submit}</a>
		 	</form>
	 	</div>
	</div>
	
	<div id="like_form" class="modal">
		<h3 class="title">
			<div class="pull-left">{$lang.explain_why} <span id="nem"></span> {$lang.like_it}...</span></div>
			<i class="icon-remove-circle pull-right modal-close"></i>
			<div class="clear"></div>
		</h3>
		<div class="padder">
		 	<form method="post">
		 		<textarea id="like_comment" name="like_comment"></textarea>
		 		<input type="hidden" name="action" value="like_comment" />
				<input type="hidden" name="like_id" id="like_id" value="" />
				<input type="hidden" name="like_type" id="like_type" value="" />
				<input type="hidden" name="like_vote" id="like_vote" value="" />
				<div class="clear"></div>
		 		<a class="btn-main" onclick="doLike();" href="javascript:void(0);" id="like_button">{$lang.like}</a>  
		 	</form>
		</div>
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

	<div id="popup_login" class="small_modal">
		<h3 class="title">
			<span class="pull-left">{$lang.login}</span>
			<i class="icon-remove-circle pull-right modal-close"></i>
			<div class="clear"></div>
		</h3>
		<div class="padder">
		 	<form method="post" id="popup_login_form">
				{$lang.username}:
				<div class="clear"></div>
				<input type="text" name="username" value="{$username}"  />
				<div class="clear"></div>
				
				{$lang.password}: 
				<input type="password" name="password" />
				<div class="clear"></div>
				
				<input type="hidden" name="action" value="login" />
		 		<input type="button" class="btn-main" onclick="jQuery('#popup_login_form').submit();" value="{$lang.login_button}" />
		 		<input type="button" onclick="window.location='{$baseurl}/register';" value="{$lang.register}" />
		 		
			 	{if $global_settings.facebook}
					<img src="{$templatepath}/images/fb_login.jpg" style="" onclick="facebookDoLogin('#fb_popup_login_button');" id="fb_popup_login_button" />
				{/if}
		 	</form>
		</div>
	</div>

{/if}

<div id="backgroundPopup"></div>