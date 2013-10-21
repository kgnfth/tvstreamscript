<!--
##############################################
#                                            #
#             author: VanDaddy               #                        
#        website: http://bligblag.net        #
#         email: nonamatt@gmail.com          #
#                                            #
##############################################
-->
<h3 class="span-8 left">{$title}</h3>

<div class="clear"></div><br />

<table id="tv_guide_table" cellspacing="0" class="tablesorter">
    <thead>
        <tr class="header">
            <th style="width: 40%">{$lang.tv_guide_show}</th>
            <th style="width: 20%">{$lang.tv_guide_episode}</th>
            <th style="width: 10%">{$lang.tv_guide_starting}<!-- ({$server_timezone})--></th>
            <th style="width: 10%">{$lang.tv_guide_channel}</th>
            <th style="width: 10%">{$lang.tv_guide_country}</th>
           
        </tr>
    </thead>
    <tbody> 

        {if $events|@count}

            {foreach from=$events key=key item=event name=tv_guide_loop}
            {if $event.country eq 'US' || $event.country eq 'UK' || $event.country eq 'CA' || $event.country eq 'AU'}
            {if $event.imdb_id neq ''}
            {if $event.series neq ''}
                <tr>
                    <td title="{$event.show_desc}">{$event.series}</td>
                    <td title="{$event.episode_desc}">{$event.episode_number}</td>
                    <td title="Runtime: {$event.runtime}min">{$event.air_time}</td>
                    <td>{$event.network}</td>
                    <td>{$event.country}</td>
                  
                    
                </tr>
             {/if}   
             {/if}
             {/if}
            {/foreach}

        {else}
            <tr>
                <td colspan="4">{$lang.tv_guide_no_events}</td>
            </tr>                
        {/if}
        
        
    </tbody>
</table>