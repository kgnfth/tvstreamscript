{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-24">
	<h2>{$lang.requests}</h2>
	<p>{$lang.requests_description}</p>
	
	{if isset($errors) and $errors|@count neq 0}
		<span class="red">
			{foreach from=$errors item=error}
				{$error}<br />
			{/foreach}
			<br />
		</span>
	{/if}
	
	{if isset($success) and $success}
		<span class="green">
			{$lang.requests_success}
			<br /><br />
		</span>
	{/if}
	<form id="request-form" method="post">
		<textarea class="text span-24" name="request_content" rows="3"  placeholder="{$lang.requests_details}" {if $loggeduser_id eq 0}disabled{/if} style="height:100px;"></textarea><br />
		<div class="clear"></div>
		<input type="hidden" name="action" value="request" />
		<a class="btn tab02b grey" href="javascript:void(0);" onclick="{if $loggeduser_id eq 0}popUp('#popup_login');{else}jQuery('#request-form').submit();{/if}" name="userlogin" style="width:200px;margin:0px;float:left;cursor:pointer;">{$lang.requests_send}</a>
	</form>	
	
	<div class="clear"></div><br /><br />
	
	<h2>{$lang.requests_recent}</h2>
	<div class="clear"></div><br />
	{if $requests|@count eq 0}
		<p>{$lang.requests_no_request}</p>
	{else}
		{foreach from=$requests item=request key=key}
			<div class="span-24 notopmargin">
				<img onclick="{if $loggeduser_id neq 0}voteRequest({$key});{else}popUp('#popup_login');{/if}" src="{$baseurl}/templates/svarog/images/icons-big/like.png" class="tooltip left" original-title="{$lang.requests_i_vote}" style="margin-right:10px; cursor: pointer; margin-top: 5px;"/>
				<div class="votes">
					<div id="votes_{$key}">{$request.votes}</div>
					<div class="clear"></div>
					<span>
						{if $request.votes eq 1}
							{$lang.requests_vote}
						{else}
							{$lang.requests_votes}
						{/if}
					</span>
				</div>
				<p class="notopmargin left" style="font-weight:bold;width: 660px;">
					{$lang.requests_user_requested|replace:"#username#":$request.username} 	<span class="notopmargin small-italic">({$request.date_print})</span>
					<br />
					<span class="notopmargin small-italic" style="font-weight:normal">
						"{$request.message}"
						{if $request.response neq ''}
							<br />{$lang.requests_admin_comment}: {$request.response}
						{/if}
					</span><br />
					<span class="notopmargin {if $request.status eq 0} red{elseif $request.status eq 1} orange{else} green{/if}" style="font-weight:bold">
						{if $request.status eq 0}
							{$lang.requests_pending}
						{elseif $request.status eq 1}
							{$lang.requests_processing}
						{else}
							{$lang.requests_finished}
						{/if}
					</span>
				</p>
				<div class="flickr item right last">
					{if $global_settings.seo_links}
						<a href="{$baseurl}/user/{$request.username}" style="margin-right: 10px;"><img alt="" src="{$baseurl}/templates/svarog/timthumb.php?src={$baseurl}/thumbs/users/{$request.avatar}&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a>					
					{else}
						<a href="{$baseurl}/index.php?menu=user&profile_username={$request.username}" style="margin-right: 10px;"><img alt="" src="{$baseurl}/templates/svarog/timthumb.php?src={$baseurl}/thumbs/users/{$request.avatar}&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a>
					{/if}
				</div>
			</div>
			<div class="clear"></div>
		{/foreach}
	{/if}
	
</div>

{include file="footer.tpl" title=footer}