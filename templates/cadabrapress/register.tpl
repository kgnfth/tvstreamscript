{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}
<div class="single">
	<div class="rounded">
		<div id="comments">
			<div id="respond">
				<h3>{$lang.register}{if $fb_reg eq 1} {$lang.register_with_facebook}{/if}</h3>
				
				{if $fb_reg eq 1}
					<p>{$lang.register_facebook_description}</p>
				{/if}
				
				{if $global_settings.seo_links}
					<form action="{$baseurl}/register" method="post" class="submit-form" id="respond">
				{else}
					<form action="{$baseurl}/index.php" method="post" class="submit-form" id="respond">
					<input type="hidden" name="menu" value="register" />
				{/if}			
					<br />		
					{if $general_error}
						<div class="form-label margin-top-10">&nbsp;</div>
						<div class="form-input margin-top-10">
							<span style="color: #aa0000">{$general_error}</span>
						</div>
						<div class="clear"></div>
					{/if}
					
					<div class="clear"></div>
					
					<div class="form-label margin-top-10">
						<label>{$lang.username}:</label>
					</div>			
					<div class="form-input margin-top-10">
						<input type="text" name="username" value="{$reg_username}"/>
					</div>
					
					<div class="clear"></div>
	
					{if $username_error}
						<div class="form-label margin-top-10">&nbsp;</div>
						<div class="form-input margin-top-10">
							<span style="color: #aa0000">{$username_error}</span>
						</div>
						<div class="clear"></div>
					{/if}
					
					<div class="form-label margin-top-10">
						<label>{$lang.password}:</label>
					</div>			
					<div class="form-input margin-top-10">
						<input type="password" name="pass1"/>
					</div>
					<div class="clear"></div>
	
					{if $password_error}
						<div class="form-label margin-top-10">&nbsp;</div>
						<div class="form-input margin-top-10">
							<span style="color: #aa0000">{$password_error}</span>
						</div>
						<div class="clear"></div>
					{/if}
	
					<div class="form-label margin-top-10">
						<label>{$lang.register_password_confirm}:</label>
					</div>			
					<div class="form-input margin-top-10">
						<input type="password" name="pass2"/>
					</div>
					<div class="clear"></div>
					
					<div class="form-label margin-top-10">
						<label>{$lang.register_email}:</label>
					</div>			
					<div class="form-input margin-top-10">
						<input type="text" name="email" value="{$reg_email}"/>
					</div>
					<div class="clear"></div>
	
					{if $email_error}
						<div class="form-label margin-top-10">&nbsp;</div>
						<div class="form-input margin-top-10">
							<span style="color: #aa0000">{$email_error}</span>
						</div>
						<div class="clear"></div>
					{/if}
	
					{if $global_settings.captchas}
						<div class="form-label margin-top-10">
							<label>{$lang.register_captcha}:</label>
						</div>			
						<div class="form-input margin-top-10">
							<input type="text" name="captcha" /><br /> 
							<img src="{$baseurl}/includes/captcha/captcha.php?{$random_number}" />
						</div>
						<div class="clear"></div>
		
						{if $captcha_error}
							<div class="form-label margin-top-10">&nbsp;</div>
							<div class="form-input margin-top-10">
								<span style="color: #aa0000">{$captcha_error}</span>
							</div>
							<div class="clear"></div>
						{/if}
					{else}
						<div class="clear"></div><br />
					{/if}
					
					<input type="hidden" name="doregister" value="register" />
					<input type="hidden" name="fb_id" value="{$fb_id}" />
					<input type="hidden" name="fb_session" value="{$fb_session}" />

					<div class="form-label">&nbsp;</div>
					<input type="submit" name="doregister" class="comm-submit pull-left" value="{$lang.register_button}" />
					<div class="clear"></div>
					<br /><br />
				</form>
			</div>
		</div>
	</div>
</div>

{include file="footer.tpl" title=footer}