{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-16 {if $global_settings.smart_bar eq 1}notopmargin{/if}">
	<h1>{$lang.register}{if $fb_reg eq 1} {$lang.register_with_facebook}{/if}</h1>
		
		{if $fb_reg eq 1}
			<p>{$lang.register_facebook_description}</p>
		{/if}
		<form action="{$baseurl}/register" method="post" id="search-form" class="register-form">
			
			<div id="formLabels">
				{if $general_error}
				<br />
				<span style="color: #aa0000">{$general_error}</span>
				<div class="clear"></div>
				{/if}
				
				<div class="span-4 left">
					<label>{$lang.username}:</label>
				</div>			
				<div class="span-11 left">
					<input type="text" name="username" value="{$reg_username}" style="margin: 0px 0px 0px 0px;width:90%;"/>
				</div>
				<div class="clear"></div>

				{if $username_error}
					<div class="span-4 left">&nbsp;</div>
					<div class="span-11 left error-message">
						<span style="color: #aa0000;">{$username_error}</span>
					</div>					
					<div class="clear"></div>
				{/if}
				
				<div class="span-4 left">
					<label>{$lang.password}:</label>
				</div>			
				<div class="span-11 left">
					<input type="password" name="pass1"  style="margin: 0px 0px 0px 0px;width:90%;"/>
				</div>
				<div class="clear"></div>

				{if $password_error}
					<div class="span-4 left">&nbsp;</div>
					<div class="span-11 left error-message">
						<span style="color: #aa0000;">{$password_error}</span>
					</div>					
					<div class="clear"></div>
				{/if}

				<div class="span-4 left">
					<label>{$lang.register_password_confirm}:</label>
				</div>			
				<div class="span-11 left">
					<input type="password" name="pass2"  style="margin: 0px 0px 0px 0px;width:90%;"/>
				</div>
				<div class="clear"></div>
				
				<div class="span-4 left">
					<label>{$lang.register_email}:</label>
				</div>			
				<div class="span-11 left">
					<input type="text" name="email" value="{$reg_email}" style="margin: 0px 0px 0px 0px;width:90%;"/>
				</div>
				<div class="clear"></div>

				{if $email_error}
					<div class="span-4 left">&nbsp;</div>
					<div class="span-11 left error-message">
						<span style="color: #aa0000;">{$email_error}</span>
					</div>					
					<div class="clear"></div>
				{/if}

				{if $global_settings.captchas}
					<div class="span-4 left">
						<label>{$lang.register_captcha}:</label>
					</div>			
					<div class="span-11 left">
						<input type="text" name="captcha" style="margin: 0px 0px 5px 0px;width:90%;"/><br /> 
						<img src="{$baseurl}/includes/captcha/captcha.php?{$random_number}" />
					</div>
					<div class="clear"></div>
	
					{if $captcha_error}
						<div class="span-4 left">&nbsp;</div>
						<div class="span-11 left error-message">
							<span style="color: #aa0000;">{$captcha_error}</span>
						</div>					
						<div class="clear"></div>
					{/if}
				{else}
					<div class="clear"></div><br />
				{/if}
			</div>
			<input type="hidden" name="doregister" value="register" />
			<input type="hidden" name="fb_id" value="{$fb_id}" />
			<input type="hidden" name="fb_session" value="{$fb_session}" />
			<a href="javascript:void(0);" onclick="jQuery('.register-form').submit();" class="btn grey" style="margin-left:0px">{$lang.register_button}</a>
			
			
		</form>
		<br /><br />
</div>

{include file="sidebar.tpl" title=sidebar}
{include file="footer.tpl" title=footer}