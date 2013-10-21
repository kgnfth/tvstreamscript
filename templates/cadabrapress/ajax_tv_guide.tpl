<div id="featured">
	<h3>
		<span class="heading-left">{$title}</span>
		<div class="navigation heading-nav">
			<a href="javascript:void(0);" {if $do_previous_day eq 'yes'}onclick="getTVguide('{$previous_day}');"{/if} class="page-numbers current">&laquo; {$lang.tv_guide_previous_day}</a>
			<a href="javascript:void(0);" {if $do_next_day eq 'yes'}onclick="getTVguide('{$next_day}');"{/if} class="page-numbers current">{$lang.tv_guide_next_day} &raquo;</a>
		</div>
		<div class="clear"></div>
	</h3>
	<div class="rounded">

		<div class="clear"></div>
		
		<table id="tv_guide_table" cellspacing="0" class="tablesorter" style="width: 100%">
		    <thead>
		        <tr class="header">
		            <th style="width: 40%">{$lang.tv_guide_show}</th>
		            <th>{$lang.tv_guide_episode}</th>
		            <th style="width: 20%">{$lang.tv_guide_starting} ({$server_timezone})</th>
		            <th style="width: 20%">{$lang.tv_guide_channel}</th>
		        </tr>
		    </thead>
		    <tbody>
		        {if $events|@count}
		            {foreach from=$events key=key item=event name=tv_guide_loop}
		                <tr>
		                    <td>{$event.show}</td>
		                    <td>{$event.episode_number}</td>
		                    <td>{$event.gmt_start_date}</td>
		                    <td>{$event.channel|truncate:15:"..."}</td>
		                </tr>
		            {/foreach}  
		        {else}
		            <tr>
		                <td colspan="4">{$lang.tv_guide_no_events}</td>
		            </tr>                
		        {/if}
		        
		        
		    </tbody>
		</table>
		<br /><br />
	</div>
</div>