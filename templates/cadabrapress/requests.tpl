{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="archive">
	<h3>
		{$lang.requests}
	</h3>
	<div class="rounded">
		<p>{$lang.requests_description}</p>
		
		{if isset($success) and $success}
			<div class="success-box">
				{$lang.requests_success}
			</div>
		{/if}
		
		<form id="request-form" method="post">
			<textarea class="text span-24" name="request_content" rows="3"  placeholder="{$lang.requests_details}" {if $loggeduser_id eq 0}disabled{/if} style="height:100px;"></textarea><br />
			<div class="clear"></div>
			<input type="hidden" name="action" value="request" />
			<a class="btn btn-main" href="javascript:void(0);" onclick="{if $loggeduser_id eq 0}popUp('#popup_login');{else}jQuery('#request-form').submit();{/if}" name="userlogin" style="width:200px;margin:0px;float:left;cursor:pointer;">{$lang.requests_send}</a>
		</form>	
		<div class="clear"></div><br />
		
		{if $requests|@count eq 0}
			<p>{$lang.requests_no_request}</p>
		{else}
			{foreach from=$requests item=request key=key}
				<div class="request-line">
					<div class="request-icon">
						<img onclick="{if $loggeduser_id neq 0}voteRequest({$key});{else}popUp('#popup_login');{/if}" src="{$templatepath}/images/icons-big/like.png" class="tooltip left" original-title="{$lang.requests_i_vote}" />
					</div>
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
					<div class="request-description">
						{$lang.requests_user_requested|replace:"#username#":$request.username} 	<span class="notopmargin small-italic">({$request.date_print})</span>
						<br />
						<span class="small-italic" style="font-weight:normal">
							"{$request.message}"
							{if $request.response neq ''}
								<br />{$lang.requests_admin_comment}: {$request.response}
							{/if}
						</span><br />
						<span class="{if $request.status eq 0} red{elseif $request.status eq 1} orange{else} green{/if}" style="font-weight:bold">
							{if $request.status eq 0}
								{$lang.requests_pending}
							{elseif $request.status eq 1}
								{$lang.requests_processing}
							{else}
								{$lang.requests_finished}
							{/if}
						</span>
						<div class="clear"></div>
					</div>
					<div class="request-user">
						{if $global_settings.seo_links}
							<a href="{$baseurl}/user/{$request.username}"><img alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$request.avatar}&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a>					
						{else}
							<a href="{$baseurl}/index.php?menu=user&profile_username={$request.username}"><img alt="" src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/users/{$request.avatar}&amp;w=45&amp;h=45&amp;zc=1" style="width:45px; height:45px;"></a>
						{/if}
					</div>
				</div>
				<div class="clear"></div>
			{/foreach}
		{/if}
	</div>
</div>

{include file="footer.tpl" title=footer}