{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if not $loggeduser_id}
    <script> window.location = '{$baseurl}'; </script>
{else}
    <div class="single">
   	
    	<div class="rounded">
    		<div id="comments">
		    	<div id="respond">
			    	<h3>
			    		{$lang.submit_links}
			    	</h3>
			    	
				    {if $submit_success}
				    	<div class="success-box">{$lang.submit_success}</div>
				    {/if}
		    	
				    <form method="post" class="submit-form" id="respond">
				        <br /><br />
				        <div class="form-label margin-top-10">
				            <label>{$lang.submit_link_type}</label>
				        </div>            
				        <div class="form-input margin-top-10">
				            <select name="type" onchange="hideSeason();" id="submit_type">
				                <option value="1"{if $submit_data.type eq 1} selected="selected"{/if}>TV show</option>
				                <option value="2"{if $submit_data.type eq 2} selected="selected"{/if}>Movie</option>
				            </select>
				        </div>
				        <div class="clear"></div>
				        
				        {if $submit_errors[1]}
				            <div class="form-label margin-top-10">&nbsp;</div>
				            <div class="form-input margin-top-10 error-message">
				                <span style="color: #aa0000;">{$submit_errors[1]}</span>
				            </div>                    
				            <div class="clear"></div>
				        {/if}
				        
				        <!-- Season -->
				        <div id="season_row"{if $submit_data.type eq 2} style="display:none"{/if}>
				            <div class="form-label margin-top-10">
				                <label>{$lang.season}</label>
				            </div>            
				            <div class="form-input margin-top-10">
				                <input type="text" name="season" class="span-2" {if $submit_data.season}value="{$submit_data.season}"{/if} />
				            </div>
				            <div class="clear"></div>
				            
				            {if $submit_errors[4]}
				                <div class="form-label margin-top-10">&nbsp;</div>
				                <div class="form-input margin-top-10 error-message">
				                    <span style="color: #aa0000;">{$submit_errors[4]}</span>
				                </div>                    
				                <div class="clear"></div>
				            {/if}
				        </div>
				        
				        <!-- Episode -->
				        <div id="episode_row"{if $submit_data.type eq 2} style="display:none"{/if}>
				            <div class="form-label margin-top-10">
				                <label>{$lang.episode}</label>
				            </div>            
				            <div class="form-input margin-top-10">
				                <input type="text" name="episode" class="span-2"  {if $submit_data.episode}value="{$submit_data.episode}"{/if} />
				            </div>
				            <div class="clear"></div>
				            
				            {if $submit_errors[5]}
				                <div class="form-label margin-top-10">&nbsp;</div>
				                <div class="form-input margin-top-10 error-message">
				                    <span style="color: #aa0000;">{$submit_errors[5]}</span>
				                </div>                    
				                <div class="clear"></div>
				            {/if}
				        </div>
				        
				        <!-- IMDB link -->
				        <div class="form-label margin-top-10">
				            <label>{$lang.submit_imdb_link}</label>
				        </div>            
				        <div class="form-input margin-top-10">
				            <input type="text" name="imdb_url" style="width:95%;" {if $submit_data.imdb_url}value="{$submit_data.imdb_url}"{/if} />
				        </div>
				        <div class="clear"></div>
				        
				        {if $submit_errors[2]}
				            <div class="form-label margin-top-10">&nbsp;</div>
				            <div class="form-input margin-top-10 error-message">
				                <span style="color: #aa0000;">{$submit_errors[2]}</span>
				            </div>                    
				            <div class="clear"></div>
				        {/if}
				        
				        <!-- Video link link -->
				        <div class="form-label margin-top-10">
				            <label>{$lang.submit_video_url}</label>
				        </div>            
				        <div class="form-input margin-top-10">
				            <input type="text" name="video_url" style="width:95%;" {if $submit_data.video_url}value="{$submit_data.video_url}"{/if} />
				        </div>
				        <div class="clear"></div>
				        
				        {if $submit_errors[3]}
				            <div class="form-label margin-top-10">&nbsp;</div>
				            <div class="form-input margin-top-10 error-message">
				                <span style="color: #aa0000;">{$submit_errors[3]}</span>
				            </div>                    
				            <div class="clear"></div>
				        {/if}
				        
				        <!-- Language -->
				        <div class="form-label margin-top-10">
				            <label>{$lang.submit_embed_language}</label>
				        </div>            
				        <div class="form-input margin-top-10">
				            <select name="embed_language">
				                {foreach from=$embed_languages item=embed_lang key=embed_key}
				                    <option value="{$embed_key}"{if $submit_data.embed_language eq $embed_key} selected="selected"{/if}>{$embed_lang.language}</option>
				                {/foreach}
				            </select>
				        </div>
				        <div class="clear"></div>
				        
				        {if $submit_errors[6]}
				            <div class="form-label margin-top-10">&nbsp;</div>
				            <div class="form-input margin-top-10 error-message">
				                <span style="color: #aa0000;">{$submit_errors[6]}</span>
				            </div>                    
				            <div class="clear"></div>
				        {/if}
				        
						{if $global_settings.captchas}
							<div class="form-label margin-top-10">
								<label>{$lang.submit_captcha}</label>
							</div>			
							<div class="form-input margin-top-10">
								<input type="text" name="captcha" style="margin: 0px 0px 5px 0px;width:95%;"/><br /> 
								<img src="{$baseurl}/includes/captcha/captcha.php?{$random_number}" />
							</div>
							<div class="clear"></div>
				
							{if $submit_errors[7]}
								<div class="form-label">&nbsp;</div>
								<div class="form-input error-message">
									<span style="color: #aa0000;">{$submit_errors[7]}</span>
								</div>					
								<div class="clear"></div>
							{/if}
						{else}
							<div class="clear"></div><br />
						{/if}
				        
				        <div class="clear"></div><br />
				        
				        <input type="hidden" name="action" value="submit_link" />
				        <div class="form-label">&nbsp;</div>
				        <input type="submit" name="submit-submit" class="comm-submit" value="{$lang.submit_video_button}" />
				        <div class="clear"></div><br /><br />
				        
				    </form>
			    </div>
		    </div>
    	</div>
    </div>
{/if}
{include file="footer.tpl" title=footer}