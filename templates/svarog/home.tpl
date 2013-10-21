{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-16">
    <div id="tv_guide"></div>
    
    <div class="clear"></div><br />
    <h3 class="span-8 left">
        {$lang.home_whats_going_on}
    </h3>
    
    <ul id="filter" style="width: auto; float:right; margin:0px; padding:5px 0px 0px 0px;">
        {if $loggeduser_id eq 0}
            <li style="margin-right:0px; float:right; margin-left: 10px;" class="stream_select" onclick="popUp('#popup_login');">
                <a href="javascript:void(0);">{$lang.stream_select_me}</a>
            </li>
            <li style="margin-right:0px; float:right; margin-left: 10px;" class="stream_select" onclick="popUp('#popup_login');">
                <a href="javascript:void(0);">{$lang.stream_select_friends}</a>
            </li>
            <li style="margin-right:0px; float:right; margin-left: 10px;" class="stream_select current"  onclick="jQuery('#filter .current').removeClass('current'); jQuery(this).addClass('current');streamPoll(0,'stream');">
                <a href="javascript:void(0);">{$lang.stream_select_all}</a>
            </li>
        {else}
            <li style="margin-right:0px; float:right; margin-left: 10px;" class="stream_select" onclick="jQuery('#filter .current').removeClass('current'); jQuery(this).addClass('current'); userStream({$loggeduser_id},0,'stream');">
                <a href="javascript:void(0);">{$lang.stream_select_me}</a>
            </li>
            <li style="margin-right:0px; float:right; margin-left: 10px;" class="stream_select" onclick="jQuery('#filter .current').removeClass('current'); jQuery(this).addClass('current'); friendStream({$loggeduser_id},0,'stream');">
                <a href="javascript:void(0);">{$lang.stream_select_friends}</a>
            </li>
            <li style="margin-right:0px; float:right; margin-left: 10px;" class="stream_select current"  onclick="jQuery('#filter .current').removeClass('current'); jQuery(this).addClass('current');streamPoll(0,'stream');">
                <a href="javascript:void(0);">{$lang.stream_select_all}</a>
            </li>
        {/if}
    </ul>    
    <div class="clear"></div><br />
    
    
    <div id="stream"></div>
    <script>
        {if $global_settings.tv_guide}
            getTVguide('{$today}');
        {/if}
        streamPoll(0, false, true, true);
    </script>
</div>
{include file="sidebar.tpl" title=sidebar}

{include file="footer.tpl" title=footer}