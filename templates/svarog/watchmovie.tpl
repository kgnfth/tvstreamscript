{config_load file=test.conf section="setup"}
{include file="header.tpl" title=header}

<div class="span-16">
    {if $mov eq ''}
        <center><br />{$lang.movie_invalid_link}<br /><br /></center>
    {else}

        <div class="span-16 {if $global_settings.smart_bar eq 1}notopmargin{/if} inner-16 view last">
            <h3>{$mov.title}</h3>
            <div class="clear"></div><br />
            
            <div class="span-4 notopmargin">
                <div class="span-4 notopmargin">
                    <div class="item">
                        {if $global_settings.seo_links}
                            <a href="{$baseurl}/{$routes.movie}/{$mov.perma}"><img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$mov.thumb}&amp;w=145&amp;h=190&amp;zc=1" class="video-page-thumbnail" /></a>
                        {else}
                            <a href="{$baseurl}/index.php?menu=watchmovie&perma={$mov.perma}"><img src="{$templatepath}/timthumb.php?src={$baseurl}/thumbs/{$mov.thumb}&amp;w=145&amp;h=190&amp;zc=1" class="video-page-thumbnail" /></a>
                        {/if}
                    </div>
                </div>                        
            </div>
            <div class="span-11 notopmargin last">

                <div class="video-details">
                    
                    <div class="video-detail-line">
                        <label>{$lang.rating}: </label>
                        
                        <div style="float:left;">
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 0.25}checked='checked'{/if} value="0.25"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 0.5}checked='checked'{/if} value="0.5"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 0.75}checked='checked'{/if} value="0.75"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1}checked='checked'{/if} value="1"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1.25}checked='checked'{/if} value="1.25"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1.5}checked='checked'{/if} value="1.5"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 1.75}checked='checked'{/if} value="1.75"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2}checked='checked'{/if} value="2"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2.25}checked='checked'{/if} value="2.25"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2.5}checked='checked'{/if} value="2.5"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 2.75}checked='checked'{/if} value="2.75"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3}checked='checked'{/if} value="3"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3.25}checked='checked'{/if} value="3.25"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3.5}checked='checked'{/if} value="3.5"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 3.75}checked='checked'{/if} value="3.75"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4}checked='checked'{/if} value="4"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4.25}checked='checked'{/if} value="4.25"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4.5}checked='checked'{/if} value="4.5"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 4.75}checked='checked'{/if} value="4.75"/>
                            <input class="auto-submit-star {literal}{split:4}{/literal}" type="radio" name="star" {if $mov.rating eq 5}checked='checked'{/if} value="5"/>    
                        </div>
                        <div class="brokenlink">                
                            <a href='javascript:void(0);' class="right" onclick="reportMovie();">{$lang.movie_report_error}</a>
                            <div class="icon right icon26 reporticon"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    {if $tags neq '' or $mov.meta}
                        {if $mov.meta.year}
                            <div class="video-detail-line">
                                <label>{$lang.released}:</label>
                                
                                <ul class="filter">
                                    <li class="noleftmargin current">
                                        <a href="{$baseurl}/index.php?menu=search&year={$mov.meta.year}">{$mov.meta.year}</a>
                                    </li>                   
                                </ul>
                                <div class="clear"></div>
                            </div>
                        {/if}
                        
                        {if $tags neq ''}
                            <div class="video-detail-line">
                                <label>{$lang.categories}:</label>
                                
                                <ul class="filter">                    
                                {foreach from=$tags key=id item=val name=tags}
                                    {if $global_settings.seo_links}
                                        <li class="noleftmargin current">
                                            <a href="{$baseurl}/{$routes.movie_tag}/{$val.perma}">{$val.tag}</a>
                                        </li>
                                    {else}
                                        <li class="noleftmargin current">
                                            <a href="{$baseurl}/index.php?menu=movie-tag&tag={$val.perma}">{$val.tag}</a>
                                        </li>
                                    {/if}
                                {/foreach}
                                </ul>
                                <div class="clear"></div>
                            </div>
                            
                        {/if}
                        
                        {if $mov.meta.director}
                            <div class="video-detail-line">
                                <label>{$lang.director}:</label>
                                
                                <ul class="filter">
                                    <li class="noleftmargin current">
                                        <a href="{$baseurl}/index.php?menu=search&director={$mov.meta.director|escape:'url'}">{$mov.meta.director}</a>
                                    </li>                   
                                </ul>
                                <div class="clear"></div>
                            </div>
                        {/if}
                        
                        {if $mov.meta.stars}
                            <div class="video-detail-line">
                                <label>{$lang.stars}:</label>
                                
                                <ul class="filter">
                                    {foreach from=$mov.meta.stars key=star_id item=star name=stars}
                                    <li class="noleftmargin current">
                                        <a href="{$baseurl}/index.php?menu=search&star={$star|escape:'url'}">{$star}</a>
                                    </li>
                                    {/foreach}                  
                                </ul>
                                <div class="clear"></div>
                            </div>
                        {/if}  
                        
                        {if $mov.imdb_rating and $mov.imdb_id}
                            <div class="video-detail-line">
                                <label>{$lang.imdb_rating}:</label>
                                
                                <ul class="filter">
                                    <li class="noleftmargin current">
                                        <a href="http://www.imdb.com/title/{$mov.imdb_id}" target="_blank">{$mov.imdb_rating}</a>
                                    </li>                   
                                </ul>
                                <div class="clear"></div>
                            </div>
                        {/if}
                    {/if}
                </div>
            </div>
            {if $global_settings.facebook}
            	<div class="clear"></div>
            	<div class="fb-like" data-href="{$fullurl}" data-send="true" data-width="500" data-show-faces="false"></div>
           	{/if}
            <div class="clear"></div><br />  
            
            {if $mov.description}
                <p class="small-italic">
                    <strong>{$lang.movie_about}:</strong> {$mov.description}
                </p>
            {/if}
            
            <ul class="video-control-list filter">
                {if $loggeduser_id neq 0}                
                    <li id="watch_button"  class="{if $seen}current{/if}" onclick="addWatch({$mov.id},2);jQuery('#watch_button').addClass('current');">
                        <a href="javascript:void(0);">
                            <img src="{$templatepath}/images/icons/checkmark.png" />
                            <span>{$lang.seen_it}</span>
                        </a>
                    </li>
                    <li  onclick="addLike({$mov.id},2,1);">
                        <a href="javascript:void(0);">
                            <img src="{$templatepath}/images/icons/like.png" />
                            <span>{$lang.like}</span>
                        </a>
                    </li>
                    <li class="norightmargin" onclick="addLike({$mov.id},2,-1);">
                        <a href="javascript:void(0);">
                            <img src="{$templatepath}/images/icons/dislike.png" />
                            <span>{$lang.dislike}</span>
                        </a>
                    </li>
                {else}
                    <li id="watch_button" onclick="popUp('#popup_login');">
                        <a href="javascript:void(0);">
                            <img src="{$templatepath}/images/icons/checkmark.png" />
                            <span>{$lang.seen_it}</span>
                        </a>
                    </li>
                    <li onclick="popUp('#popup_login');">
                        <a href="javascript:void(0);">
                            <img src="{$templatepath}/images/icons/like.png" />
                            <span>{$lang.like}</span>
                        </a>
                    </li>
                    
                    <li class="norightmargin" onclick="popUp('#popup_login');">
                        <a href="javascript:void(0);">
                            <img src="{$templatepath}/images/icons/dislike.png" />
                            <span>{$lang.dislike}</span>
                        </a>
                    </li>
                {/if}
            </ul>
            {if $widgets.movie_ad and (not $loggeduser_username or $widgets.movie_ad.logged)}
                <div class="clear"></div><br />
                <div class="center">
                    {$widgets.movie_ad.content}
                </div>
                <div class="clear"></div>
            {/if}            
        </div>
         
        
        <div class="clear"></div><br /><br />
        
        <script>
        {if $listing_styles.embeds}
        	var current_view = 'embed';
        {else}
        	var current_view = 'link';
        {/if}
        {literal}
        	function changeView(target){
				if (current_view != target){
					if (target == 'embed'){
						jQuery('#link_list').hide();
						jQuery('#embed_list').show();
						jQuery('#embed-style-selector').addClass('active');
						jQuery('#link-style-selector').removeClass('active');
					} else {
						jQuery('#embed_list').hide();
						jQuery('#link_list').show();
						jQuery('#link-style-selector').addClass('active');
						jQuery('#embed-style-selector').removeClass('active');
					}
					current_view = target;
				}
        	}
        {/literal}
        </script>
        
        <ul class="nav nav-tabs" id="list-style">
        	{if $listing_styles.embeds}
            <li class="active" onclick="changeView('embed');" id="embed-style-selector">
                <a href="javascript:void(0);">Embed codes</a>
            </li>
            {/if}
            {if $listing_styles.links}
            <li {if not $listing_styles.embeds}class="active" {/if}onclick="changeView('link')" id="link-style-selector">
                <a href="javascript:void(0);">Links</a>
            </li>
            {/if}
        </ul>
        
        {if $listing_styles.embeds}
	        <div id="embed_list">
	        {foreach from=$mov.embeds key=id item=val name=titles}
	            <div class="span-16 inner-16 notopmargin embed-selector" style="background: #F1F2F1 url('{$embed_languages[$val.lang].flag}') 15px 17px no-repeat;" onclick="changeEmbed({$val.id},{$global_settings.countdown});">
	                
	                <h5 class="left">
	                    {$embed_languages[$val.lang].language}
	                    {if $val.type} - 
	                    <a class="link" href="javascript:void(0);" {if $smarty.foreach.titles.first}class="current"{/if} id="selector{$val.id}"><span>{$val.type}</span></a>
	                    {/if}
	                </h5>
	                
	                <ul class="filter" style="width:200px;float:right;margin-top: 0px;">
	                    {if $val.link}
	                    	{if $global_settings.adfly.id}
	                        	<li class="current right" style="float:right"><a href="http://adf.ly/{$global_settings.adfly.id}/{$val.link|replace:"http://":""}" target="_blank">{$lang.open_video}</a></li>
	                        {else}
	                        	<li class="current right" style="float:right"><a href="{$val.link}" target="_blank">{$lang.open_video}</a></li>
	                        {/if}
	                    {/if}
	                </ul>
	            </div>
	            <div class="span-16 notopmargin embedcontainer" id="videoBox{$val.id}" style="display:none">
	                
	            </div>
	        {/foreach}
	        </div>
        {/if}
        
        {if $listing_styles.links}
	        <div id="link_list" {if $listing_styles.embeds}style="display:none"{/if}>
	        {foreach from=$mov.embeds key=id item=val name=titles}
	            <div class="span-16 inner-16 notopmargin embed-selector" style="background: #F1F2F1 url('{$embed_languages[$val.lang].flag}') 15px 17px no-repeat;">
	                
	                <h5 class="left">
	                    {$embed_languages[$val.lang].language}
	                    {if $val.type} - 
	                    <a class="link" href="javascript:void(0);" {if $smarty.foreach.titles.first}class="current"{/if} id="selector{$val.id}"><span>{$val.type}</span></a>
	                    {/if}
	                </h5>
	                
	                <ul class="filter" style="width:200px;float:right;margin-top: 0px;">
	                    {if $val.link}
	                    	{if $global_settings.adfly.id}
	                        	<li class="current right" style="float:right"><a href="http://adf.ly/{$global_settings.adfly.id}/{$val.link|replace:"http://":""}" target="_blank">{$lang.open_video}</a></li>
	                        {else}
	                        	<li class="current right" style="float:right"><a href="{$val.link}" target="_blank">{$lang.open_video}</a></li>
	                        {/if}
	                    {/if}
	                </ul>
	            </div>
	        {/foreach}
	        </div>
        {/if}
        
        <div class="clear"></div><br />
        {if $global_settings.facebook}
        	<div class="fb-comments" data-href="{$fullurl}" data-num-posts="10" data-width="620"></div>
        	<div class="clear"></div><br />
        {/if}
        
        <div id="modal" style="display:none">
			<h4 class="left">{$lang.movie_explain_problem}</h4>
			<a href='javascript:void(0)' onclick="jQuery('#backgroundPopup').hide(); jQuery('#modal').hide(); jQuery('object').show(); jQuery('iframe').show();" class="right modal-close">{$lang.close}</a>
            <div class="clear"></div>
            <div id="reportcontent">
            	<div class="clear"></div>
                <textarea id="problem"></textarea>
                <a class="btn tab02b grey" style="margin:0px" onClick="doReportMovie({$mov.id});" href="javascript:void(0);">{$lang.submit}</a>
            </div>
        </div>
    
        <script>
            var embeds = [];
            {foreach from=$mov.embeds key=id item=val name=embeds}
                embeds[{$val.id}] = '{$val.embed|replace:"'":'"'}';
            {/foreach}
        </script>
        {foreach from=$mov.embeds key=id item=val name=titles}
            {if $smarty.foreach.titles.iteration eq 1}
                <script>
                    changeEmbed({$val.id},{$global_settings.countdown});
                </script>
            {/if}
        {/foreach}
    
        <script>
            {literal}
                jQuery('.auto-submit-star').rating({
                    callback: function(value, link){
                    jQuery.get("{/literal}{$baseurl}{literal}/ajax/addmovierating.php",
                        {
                            movieid: {/literal}{$mov.id}{literal},
                            rating: value
                        }, function(resp) {    }); 
                    }
                });
            {/literal}
        </script>
    
    {/if}
</div>

{include file="sidebar.tpl" title=sidebar}
{include file="footer.tpl" title=footer}