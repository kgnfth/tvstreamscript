{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

{if not $loggeduser_id}
    <script> window.location = '{$baseurl}'; </script>
{/if}

<div class="span-16">
    <h1>
        {$lang.submit_links}
    </h1>    
    
    {if $submit_success}
        <span style="color: #00aa00;">{$lang.submit_success}</span>
        <br /><br />
    {/if}
    
    <form method="post" class="submit-form">
        <div class="span-4 margin-top-10">
            <label>{$lang.submit_link_type}</label>
        </div>            
        <div class="span-11 margin-top-10">
            <select name="type" class="span-4" onchange="hideSeason();" id="submit_type">
                <option value="1"{if $submit_data.type eq 1} selected="selected"{/if}>TV show</option>
                <option value="2"{if $submit_data.type eq 2} selected="selected"{/if}>Movie</option>
            </select>
        </div>
        <div class="clear"></div>
        
        {if $submit_errors[1]}
            <div class="span-4 margin-top-10">&nbsp;</div>
            <div class="span-11 margin-top-10 error-message">
                <span style="color: #aa0000;">{$submit_errors[1]}</span>
            </div>                    
            <div class="clear"></div>
        {/if}
        
        <!-- Season -->
        <div id="season_row"{if $submit_data.type eq 2} style="display:none"{/if}>
            <div class="span-4 margin-top-10">
                <label>{$lang.season}</label>
            </div>            
            <div class="span-11 margin-top-10">
                <input type="text" name="season" class="span-2" {if $submit_data.season}value="{$submit_data.season}"{/if} />
            </div>
            <div class="clear"></div>
            
            {if $submit_errors[4]}
                <div class="span-4 margin-top-10">&nbsp;</div>
                <div class="span-11 margin-top-10 error-message">
                    <span style="color: #aa0000;">{$submit_errors[4]}</span>
                </div>                    
                <div class="clear"></div>
            {/if}
        </div>
        
        <!-- Episode -->
        <div id="episode_row"{if $submit_data.type eq 2} style="display:none"{/if}>
            <div class="span-4 margin-top-10">
                <label>{$lang.episode}</label>
            </div>            
            <div class="span-11 margin-top-10">
                <input type="text" name="episode" class="span-2"  {if $submit_data.episode}value="{$submit_data.episode}"{/if} />
            </div>
            <div class="clear"></div>
            
            {if $submit_errors[5]}
                <div class="span-4 margin-top-10">&nbsp;</div>
                <div class="span-11 margin-top-10 error-message">
                    <span style="color: #aa0000;">{$submit_errors[5]}</span>
                </div>                    
                <div class="clear"></div>
            {/if}
        </div>
        
        <!-- IMDB link -->
        <div class="span-4 margin-top-10">
            <label>{$lang.submit_imdb_link}</label>
        </div>            
        <div class="span-11 margin-top-10">
            <input type="text" name="imdb_url" style="width:95%;" {if $submit_data.imdb_url}value="{$submit_data.imdb_url}"{/if} />
        </div>
        <div class="clear"></div>
        
        {if $submit_errors[2]}
            <div class="span-4 margin-top-10">&nbsp;</div>
            <div class="span-11 margin-top-10 error-message">
                <span style="color: #aa0000;">{$submit_errors[2]}</span>
            </div>                    
            <div class="clear"></div>
        {/if}
        
        <!-- Video link link -->
        <div class="span-4 margin-top-10">
            <label>{$lang.submit_video_url}</label>
        </div>            
        <div class="span-11 margin-top-10">
            <input type="text" name="video_url" style="width:95%;" {if $submit_data.video_url}value="{$submit_data.video_url}"{/if} />
        </div>
        <div class="clear"></div>
        
        {if $submit_errors[3]}
            <div class="span-4 margin-top-10">&nbsp;</div>
            <div class="span-11 margin-top-10 error-message">
                <span style="color: #aa0000;">{$submit_errors[3]}</span>
            </div>                    
            <div class="clear"></div>
        {/if}
        
        <!-- Language -->
        <div class="span-4 margin-top-10">
            <label>{$lang.submit_embed_language}</label>
        </div>            
        <div class="span-11 margin-top-10">
            <select name="embed_language" class="span-4">
                {foreach from=$embed_languages item=embed_lang key=embed_key}
                    <option value="{$embed_key}"{if $submit_data.embed_language eq $embed_key} selected="selected"{/if}>{$embed_lang.language}</option>
                {/foreach}
            </select>
        </div>
        <div class="clear"></div>
        
        {if $submit_errors[6]}
            <div class="span-4 margin-top-10">&nbsp;</div>
            <div class="span-11 margin-top-10 error-message">
                <span style="color: #aa0000;">{$submit_errors[6]}</span>
            </div>                    
            <div class="clear"></div>
        {/if}
        
		{if $global_settings.captchas}
			<div class="span-4 left">
				<label>{$lang.submit_captcha}:</label>
			</div>			
			<div class="span-11 left">
				<input type="text" name="captcha" style="margin: 0px 0px 5px 0px;width:95%;"/><br /> 
				<img src="{$baseurl}/includes/captcha/captcha.php?{$random_number}" />
			</div>
			<div class="clear"></div>

			{if $submit_errors[7]}
				<div class="span-4 left">&nbsp;</div>
				<div class="span-11 left error-message">
					<span style="color: #aa0000;">{$submit_errors[7]}</span>
				</div>					
				<div class="clear"></div>
			{/if}
		{else}
			<div class="clear"></div><br />
		{/if}
        
        <div class="clear"></div><br />
        <input type="hidden" name="action" value="submit_link" />
        <a href="javascript:void(0);" onclick="jQuery('.submit-form').submit();" class="btn grey" style="margin-left:0px">{$lang.submit_video_button}</a>
        
    </form>
    
    
</div>
{include file="sidebar.tpl" title=sidebar}
{include file="footer.tpl" title=footer}