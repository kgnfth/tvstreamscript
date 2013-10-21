function getSidereelURL(){
    var showtitle = jQuery('#title\\[en\\]').val();
    if ((!showtitle) || (showtitle=='')){
        alert("Please type in the show's title");
    } else {
        jQuery.post("ajax/get_sidereel_url.php",{
            showtitle: showtitle
        }, function(data){
            if (data=='0'){
                alert("Can't find this show on Sidereel");
            } else {
                jQuery('#sidereel_url').val(data);
            }
        });
    }
}

var movie_star_counter = 0;

function removeMovieStars(){
    jQuery('#star_controls').html('');
    movie_star_counter = 0;
}

function addMovieStar(value){
    if (!value){
        value = '';
    }
    jQuery('#star_controls').append('<input type="text" name="stars[' + movie_star_counter + ']" id="stars[' + movie_star_counter + ']" value="' + value + '" class="span5" style="margin-bottom:5px;" /> <input type="button" value="Add more" onclick="addMovieStar();" class="btn"  style="margin-bottom:5px;" /><br />');
    movie_star_counter++;
}

function getMovie(){
    var title = jQuery('#title\\[en\\]').val();
    var imdb_id = jQuery('#imdb_id').val() || '';
    
    if ((title && title!='') || (imdb_id && imdb_id!='')){
        jQuery('#get_movie').val("Grabbing...");
        jQuery.post("ajax/get_movie_details.php",{
            title:title,
            imdb_id: imdb_id
        }, function(data){
            if (data=='0'){
                alert("Couldn't find movie details. Please enter it manually");
                jQuery('#get_movie').val("Grab details");
            } else {
                jQuery('#get_movie').val("Grab details");
                ret = eval("("+data+")");
                
                if (!title && ret.title){
                    jQuery('#title\\[en\\]').val(ret.title);
                }
                
                jQuery('#description\\[en\\]').val(ret.description);
                jQuery('#imdb_id').val(ret.imdb_id);
                if (ret.image!='0'){
                    jQuery('#movie_thumbnail').attr("src",baseurl+"/thumbs/"+ret.image);
                    jQuery('#thumbnail_hidden').val(ret.image);
                }
                
                if (ret.rating){
                    jQuery('#imdb_rating').val(ret.rating);
                }
                
                if (ret.year){
                    jQuery('#year').val(ret.year);
                }
                
                if (ret.director){
                    jQuery('#director').val(ret.director);
                }
                
                if (ret.stars && ret.stars.length){
                    removeMovieStars();
                    for(i=0;i<ret.stars.length;i++){
                        addMovieStar(ret.stars[i]);
                    }
                }
                
                if (typeof(ret.categories)!=undefined && jQuery(ret.categories).size()){
                    jQuery('.category_checkbox').attr("checked",false);
                    jQuery('.category_checkbox').each(function(){
                        for (key in ret.categories){
                            if (ret.categories[key] == jQuery(this).val()){
                                jQuery(this).attr("checked",true);
                            }
                        }
                    });
                }
                
            }
        }); 
    } else {
        alert("Please enter the title of the movie or the IMDB id");
    }
}

var show_star_counter = 0;
var show_creator_counter = 0;

function removeShowStars(){
    jQuery('#star_controls').html('');
    show_star_counter = 0;
}

function addShowStar(value){
    if (!value){
        value = '';
    }
    jQuery('#star_controls').append('<input type="text" name="stars[' + show_star_counter + ']" id="stars[' + show_star_counter + ']" value="' + value + '" class="span5" style="margin-bottom:5px;" /> <input type="button" value="Add more" onclick="addShowStar();" class="btn"  style="margin-bottom:5px;" /><br />');
    show_star_counter++;
}

function removeShowCreators(){
    jQuery('#creator_controls').html('');
    show_creator_counter = 0;
}

function addShowCreator(value){
    if (!value){
        value = '';
    }
    jQuery('#creator_controls').append('<input type="text" name="creators[' + show_creator_counter + ']" id="stars[' + show_creator_counter + ']" value="' + value + '" class="span5" style="margin-bottom:5px;" /> <input type="button" value="Add more" onclick="addShowCreator();" class="btn"  style="margin-bottom:5px;" /><br />');
    show_creator_counter++;
}

function getShow(){
    var showtitle = jQuery('#title\\[en\\]').val() || '';
    var imdb_id = jQuery('#imdb_id').val() || '';
    
    if ((!showtitle || showtitle=='') && (!imdb_id || imdb_id=='')){
        alert("Please enter either the title or the IMDB id of the show");
    } else {
        var save_button_title = jQuery('#getshow').val();
        
        jQuery('#getshow').val("Loading...");
        jQuery.post("ajax/get_show.php",{
            title: showtitle,
            imdb_id: imdb_id
        }, function(data){
            jQuery('#getshow').val(save_button_title);
            if (data=='0'){
                alert("Couldn't find show details. Please enter it manually");
            } else {
                ret = eval("("+data+")");
                
                if (!showtitle && ret.title){
                    jQuery('#title\\[en\\]').val(ret.title);
                }
                
                if (ret.rating && ret.rating){
                    jQuery('#imdb_rating').val(ret.rating);
                }
                
                if (ret.year_started && ret.year_started){
                    jQuery('#year_started').val(ret.year_started);
                }
                
                jQuery('#description\\[en\\]').val(ret.description);
                jQuery('#imdb_id').val(ret.imdb_id);
                if (ret.image!='0'){
                    jQuery('#show_thumbnail').attr("src",baseurl+"/thumbs/"+ret.image);
                    jQuery('#thumbnail_hidden').val(ret.image);
                }
                
                if (ret.stars && ret.stars.length){
                    removeShowStars();
                    for(i=0;i<ret.stars.length;i++){
                        addShowStar(ret.stars[i]);
                    }
                }
                
                if (ret.creators && ret.creators.length){
                    removeShowCreators();
                    for(i=0;i<ret.creators.length;i++){
                        addShowCreator(ret.creators[i]);
                    }
                }
                
                if (typeof(ret.categories)!=undefined && jQuery(ret.categories).size()){
                    jQuery('.category_checkbox').attr("checked",false);
                    jQuery('.category_checkbox').each(function(){
                        for (key in ret.categories){
                            if (ret.categories[key] == jQuery(this).val()){
                                jQuery(this).attr("checked",true);
                            }
                        }
                    });
                }
            }
        }); 
    }
}

function deleteMovietag(tagid){
    jQuery.post("ajax/delete_movie_tag.php",{   
            tagid:tagid
        }, function(resp) {   
            jQuery('#tag'+tagid).fadeOut('fast');
        }
    ); 
}

function deleteTVtag(tagid){
    jQuery.post("ajax/delete_tv_tag.php",{   
            tagid:tagid
        }, function(resp) {   
            jQuery('#tag'+tagid).fadeOut('fast');
        }
    ); 
}

function updateRequest(request_id){
    var response = jQuery('#response_'+request_id).val();
    var status = jQuery('#status_'+request_id).val();
    
    jQuery.post("ajax/update_request.php",{
            request_id: request_id,
            response: response,
            status: status
        }, function(data){
            alert("Updated");
        });        
}

function deleteRequest(request_id){
    if (confirm("Are you sure you want to delete this request?")){
        jQuery.post("ajax/delete_request.php",{
            request_id: request_id
        }, function(data){
            jQuery('#request_'+request_id).fadeOut("fast");
        });
    }
}

function getEpisodeThumbnail(showid, season, episode){    
    jQuery('#grab_button').val('Grabbing...');
    jQuery.post("ajax/get_episode_thumbnail.php",{   
            showid: showid,
            season: season,
            episode: episode
        }, function(response) {   
            jQuery('#grab_button').val('Grab');
            response = eval('(' + response + ')');
            if (response.status == 0){
                alert(response.message);
            } else {
                jQuery('#episode_thumbnail').attr("src",baseurl+"/thumbs/"+response.message);
                jQuery('#thumbnail_hidden').val(response.message);
            }
        }
    ); 
}

function getEpisodeDescription(showid, season, episode, obj){
    var save_button_title = obj.val();
    obj.val('Grabbing...');
    
    jQuery.post("ajax/get_episode_description.php",{   
            showid: showid,
            season: season,
            episode: episode
        }, function(resp) {  
            obj.val(save_button_title);
            
            if (resp!='0'){
                data = eval('('+resp+')');
                jQuery('#title').val(data.title);
                jQuery('#description').val(data.description);
            }
        }
    ); 
}

function processEmbedCodes(response){
    for(i in response.embeds){
        var embed_id = addMoreEmbed();
        jQuery('#embeds_' + embed_id).val(response.embeds[i].embed);
        jQuery('#links_' + embed_id).val(response.embeds[i].link);
        jQuery('#languages_' + embed_id).find("option").each(function(){
            jQuery(this).attr("selected",false);
            if (jQuery(this).val()==response.embeds[i].language){
                jQuery(this).attr("selected",true);
            }
        });
    }
}

function grabEmbeds(grabber_url, obj){
    var season = jQuery('#season').val();
    var episode = jQuery('#episode').val()
    var title = document.getElementById('show_id').options[document.getElementById('show_id').selectedIndex].text;
    var showid = jQuery('#show_id').val();
    
    if (grabber_url.indexOf('grabber_')!=0){
        return true;
    }
        
    
    var save_button_title = obj.val();
    obj.val("Grabbing...");
    
    if (!season || !episode){
        alert("Please enter the season and the episode number");
    } else {

        jQuery.post("ajax/" + grabber_url,{   
                title: title,
                season: season,
                episode: episode,
                showid: showid
            }, function(response) {  
                obj.val(save_button_title);
                
                response = eval('(' + response + ')');
                
                if (response.status == 1){
                    var found = 0;
                    jQuery('#embed_list textarea[name*=embeds]').each(function(){
                        if (jQuery(this).val()){
                            found++;
                        }
                    });
                    
                    if (found){
                        jQuery('#confirm-modal').modal();
                        jQuery('#confirm-modal #replace-button').click(function(){
                            jQuery('#embed_list').html('');
                            jQuery('#confirm-modal').modal('hide');
                            processEmbedCodes(response);
                        });
                        
                        jQuery('#confirm-modal #add-button').click(function(){
                            jQuery('#confirm-modal').modal('hide');
                            processEmbedCodes(response);
                        });
                    } else {
                        jQuery('#embed_list').html('');
                        processEmbedCodes(response);
                    }
                                        
                    
                } else if (response.status == 2) {
                    alert(response.message);
                } else {
                    alert("Can't find embed codes with this grabber");
                }
                
            }
        ); 
    }
}

function grabMovieEmbeds(grabber_url, obj){
    
    var title = jQuery('#title\\[en\\]').val();
    var imdb_id = jQuery('#imdb_id').val();
        
    if (grabber_url.indexOf('movie_grabber_')!=0){
        return true;
    }        
    
    var save_button_title = obj.val();
    
    
    if (!title && (!imdb_id || grabber_url!='movie_grabber_tvapi.php')){
        if (grabber_url == 'movie_grabber_tvapi.php'){
            alert("Please enter the movie\'s imdb id");
        } else {
            alert("Please enter the movie\'s title");
        }
    } else {
        obj.val("Grabbing...");
        jQuery.post("ajax/" + grabber_url,{   
                title: title,
                imdb_id: imdb_id
            }, function(response) { 
                
                obj.val(save_button_title);
                
                response = eval('(' + response + ')');
                
                if (response.status == 1){
                    var found = 0;
                    jQuery('#embed_list textarea[name*=embeds]').each(function(){
                        if (jQuery(this).val()){
                            found++;
                        }
                    });
                    
                    if (found){
                        jQuery('#confirm-modal').modal();
                        jQuery('#confirm-modal #replace-button').click(function(){
                            jQuery('#embed_list').html('');
                            jQuery('#confirm-modal').modal('hide');
                            processEmbedCodes(response);
                        });
                        
                        jQuery('#confirm-modal #add-button').click(function(){
                            jQuery('#confirm-modal').modal('hide');
                            processEmbedCodes(response);
                        });
                    } else {
                        jQuery('#embed_list').html('');
                        processEmbedCodes(response);
                    }
                                        
                    
                } else if (response.status == 2) {
                    alert(response.message);
                } else {
                    alert("Can't find embed codes with this grabber");
                }                
            }
        ); 
    }
}

function addMoreEmbed(){
    var index = jQuery('.embedgroup').size(); 
    jQuery('#embed_list').append(jQuery('#embed_block').html().replace(/\[embed_counter\]/g,index));
    return index;
}

function previewEmbed(embed_id){
    var content = jQuery('#embeds_'+embed_id).val();
    if (content){
        jQuery('#preview_body').html(content)
        jQuery('#embed_preview').modal()
    } else {
        alert("Please enter the embed code first");
    }
}

function makeLink(embed_id){
    var embed = jQuery('#embeds_'+embed_id).val();
    if (embed){
        jQuery.post("ajax/make_link.php",{
            embed: embed
        }, function(response){
            if (response){
                jQuery('#links_'+embed_id).val(response);
            } else {
                alert("Can't build a link out of this embed code");
            }
            
        });
    } else {
        alert("Please enter the embed code first");
    }    
}

function makeEmbed(embed_id){
    var link = jQuery('#links_'+embed_id).val();
    if (link){
        jQuery.post("ajax/make_embed.php",{
            link: link
        }, function(response){
            if (response){
                jQuery('#embeds_'+embed_id).val(response);
            } else {
                alert("Can't make an embed code out of this link");
            }
            
        });
    } else {
        alert("Please enter the link first");
    }
}

function prevEpisode(){
    var show_id = jQuery('#show_id').val();
    var episode = parseInt(jQuery('#episode').val());
    var season = parseInt(jQuery('#season').val());
    
    episode = episode - 1;
    window.location = 'index.php?menu=episodes&show__id='+show_id+'&season='+season+"&episode="+episode;
}

function nextEpisode(){
    var show_id = jQuery('#show_id').val();
    var episode = parseInt(jQuery('#episode').val());
    var season = parseInt(jQuery('#season').val());
    
    episode = episode + 1;
    window.location = 'index.php?menu=episodes&show_id='+show_id+'&season='+season+"&episode="+episode;
}

function prevSeason(){
    var show_id = jQuery('#show_id').val();
    var episode = parseInt(jQuery('#episode').val());
    var season = parseInt(jQuery('#season').val());
    
    season = season - 1;
    episode = 1;
    window.location = 'index.php?menu=episodes&show_id='+show_id+'&season='+season+"&episode="+episode;
}

function nextSeason(){
    var show_id = jQuery('#show_id').val();
    var episode = parseInt(jQuery('#episode').val());
    var season = parseInt(jQuery('#season').val());
    
    season = season + 1;
    episode = 1;
    window.location = 'index.php?menu=episodes&show_id='+show_id+'&season='+season+"&episode="+episode;
}

function reloadEpisode(){
    var show_id = jQuery('#show_id').val();
    var episode = parseInt(jQuery('#episode').val());
    var season = parseInt(jQuery('#season').val());
    
    window.location = 'index.php?menu=episodes&show_id='+show_id+'&season='+season+"&episode="+episode;
}

function deleteEpisode(id){
    if (confirm("Are you sure you want to delete this episode?")){
        jQuery.post("ajax/delete_episode.php",{
            id:id
        }, function(data){
            jQuery('#row'+id).fadeOut();
        });
    }
}

function removeEmbed(id){
    if (confirm("Are you sure you wan't to remove this embed code?")){
        jQuery('#embedgroup_'+id).remove();
    }
}

function getTvGuide(date){
    jQuery("#output").html("<center><br /><br /><img src=\"img/ajax_loader.gif\" /></center>");
    jQuery.post("ajax/get_tv_guide.php",{
        date: date
    }, function(data){
        jQuery("#output").html(data);
    }); 
}

function deleteBrokenEpisode(report_id){
    if (confirm("Are you sure you want to delete this broken episode report?")){
        jQuery.post("ajax/delete_broken_episode.php",{
            report_id:report_id
        }, function(data){
            jQuery('#row'+report_id).fadeOut();
        }); 
    }
}

function deleteBrokenMovie(report_id){
    if (confirm("Are you sure you want to delete this broken movie report?")){
        jQuery.post("ajax/delete_broken_movie.php",{
            report_id:report_id
        }, function(data){
            jQuery('#row'+report_id).fadeOut();
        }); 
    }
}

var ensureTimer = null;
var ensureTexts = ["In progress...", "Still working...", "Getting there...", "Getting close...", "Any second now..."];

function ensureUser(target_div){
    var ensureText = ensureTexts[Math.floor(Math.random()*ensureTexts.length)];
    target_div.html(ensureText);
    ensureTimer = setTimeout(function(){
                        ensureUser(target_div);
                    },5000);
}

function submitSidereel(to_submit){
    if (to_submit.length){
        
        var episode_id = to_submit.shift();
        
        jQuery('#status_'+episode_id).html("In progress...");
        ensureTimer = setTimeout(function(){ ensureUser(jQuery('#status_'+episode_id)); },5000);
        
        jQuery.post("ajax/auto_sidereel.php",{
            id: episode_id            
        }, function(data){
            if (ensureTimer){
                clearTimeout(ensureTimer);
                ensureTimer = null;
            }
            
            data = eval("(" + data + ")");
            
            var status = parseInt(data.status) || 99;
            if (status == 99){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Unexpected error</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");
            } else if (status == 98){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Sidereel error</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");
            } else if (status == 0){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Can't login to SideReel</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");                
            } else if (status == 2){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Can't find TV show</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");                
            } else if (status == 3){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">DeCaptcher error</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");                
            } else if (status == 4){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Invalid Captcha</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");                
            } else if (status == 5){
                jQuery('#status_'+episode_id).html("<span style=\"color:#00aa00 !important\">Already have</span>");
                jQuery('#link_'+episode_id).html("<a href=\"" + data.link + "\">" + data.link + "</a>");                
            } else if (status == 1){
                jQuery('#status_'+episode_id).html("<span style=\"color:#00aa00 !important\">Submitted</span>");
                jQuery('#link_'+episode_id).html("<a href=\"" + data.link + "\">" + data.link + "</a>");                
            }
            
            submitSidereel(to_submit);
            
        }).error(function(){
            if (ensureTimer){
                clearTimeout(ensureTimer);
                ensureTimer = null;
            }
            
            jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Timeout error</span>");
            jQuery('#link_'+episode_id).html("&nbsp;");
            
            submitSidereel(to_submit);
        });
    } else {
        jQuery('#submit_button').val("Submit selected to SideReel");
    }
}

function submitSidereelInit(){
    if (jQuery('.selected_episode:checked').size()){
        
        var to_submit = [];
        jQuery('#submit_button').val("Submitting...");
        
        jQuery('.selected_episode:checked').each(function(){
            to_submit.push(jQuery(this).val());
            jQuery(this).attr("checked",false);
            jQuery('#status_'+jQuery(this).val()).html("<img src=\"img/ajax_loader.gif\" />");
            jQuery('#link_'+jQuery(this).val()).html("<img src=\"img/ajax_loader.gif\" />");
        });
        
        
        submitSidereel(to_submit);
        
    } else {
        alert("Please select at least one episode to submit");
    }
}

function submitTVlinks(to_submit){
    if (to_submit.length){
        
        var episode_id = to_submit.shift();
        
        jQuery('#status_'+episode_id).html("In progress...");
        ensureTimer = setTimeout(function(){ ensureUser(jQuery('#status_'+episode_id)); },5000);
        
        jQuery.post("ajax/auto_tvlinks.php",{
            id: episode_id            
        }, function(data){
            if (ensureTimer){
                clearTimeout(ensureTimer);
                ensureTimer = null;
            }
            
            data = eval("(" + data + ")");
            
            var status = parseInt(data.status) || 99;
            jQuery('#link_'+episode_id).html("&nbsp;");
            
            if (status == 99){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Unexpected error</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");
            } else if (status == 2){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Error while submitting</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");
            } else if (status == 0){
                jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Can't login</span>");
                jQuery('#link_'+episode_id).html("&nbsp;");        
            } else if (status == 1){
                jQuery('#status_'+episode_id).html("<span style=\"color:#00aa00 !important\">Submitted</span>");
                jQuery('#link_'+episode_id).html("<a href=\"" + data.link + "\">" + data.link + "</a>");    
            }
            
            submitTVlinks(to_submit);
            
        }).error(function(){
            if (ensureTimer){
                clearTimeout(ensureTimer);
                ensureTimer = null;
            }
            
            jQuery('#status_'+episode_id).html("<span style=\"color:#aa0000 !important\">Timeout error</span>");
            jQuery('#link_'+episode_id).html("&nbsp;");
            submitTVlinks(to_submit);
        });
    } else {
        jQuery('#submit_button').val("Submit selected to TV-links.eu");
    }
}

function submitTVlinksInit(){
    if (jQuery('.selected_episode:checked').size()){
        
        var to_submit = [];
        jQuery('#submit_button').val("Submitting...");
        
        jQuery('.selected_episode:checked').each(function(){
            to_submit.push(jQuery(this).val());
            jQuery(this).attr("checked",false);
            jQuery('#status_'+jQuery(this).val()).html("<img src=\"img/ajax_loader.gif\" />");
            jQuery('#link_'+jQuery(this).val()).html("<img src=\"img/ajax_loader.gif\" />");
        });
        
        
        submitTVlinks(to_submit);
        
    } else {
        alert("Please select at least one episode to submit");
    }
}

function doManualSidereel(id){
    var answer = $('#answer').val();
    var recaptcha_challenge_field = $('#recaptcha_challenge_field').val();
    var authenticity_token = $('#authenticity_token').val();
    var sidereel_url = $('#sidereel_url').val();
    var image_id = $('#image_id').val();
    
    if (answer && recaptcha_challenge_field){
        $('#captcha').html('<br /><img src="img/ajax_loader.gif" />');
        $.post("ajax/manual_sidereel.php",{
            id:id,
            recaptcha_challenge_field: recaptcha_challenge_field,
            authenticity_token: authenticity_token,
            sidereel_url: sidereel_url,
            image_id: image_id,
            answer: answer
        }, function(data){
            data = eval("("+data+")");
            data.status = parseInt(data.status);
            if (data.status == 1 || data.status==2){
                $('#submit'+id).html("<strong>SUBMITTED</strong>");
                $('#link'+id).html("<a href='"+data.link+"' target='_blank'>"+data.link+"</a>");
            }
            
            $('#captcha').html("<center>"+data.message+"<br /><br /></center>");
            
        });
    }
}

function manualSidereel(id){
    jQuery('#submit_button_'+id).val('Sending...');
    
    $('#captcha').html('<br /><img src="img/ajax_loader.gif" />');
    
    $.colorbox({
        initialHeight: '0',
        initialWidth: '0',
        href: "#sidereel_overlay",
        inline: true,
        opacity: '0.3',
    });
    
    jQuery.post("ajax/manual_sidereel.php",{
        id:id
    }, function(data){
        if (data=='0'){
            alert("Can't login to sidereel. Please check your username and password");
            $('#submit_button_'+id).val('Submit');
        } else if (data=='1'){
            alert("Unexpected error occured.");
            $('#submit_button_'+id).val('Submit');
        } else {
            $('#submit_button_'+id).val('Submit');
            $('#captcha').html(data);
        }
    }); 
}



jQuery(document).ready(function(){
    $('.check-all').click(function () {
        $(this).parents('table').find(':checkbox').attr('checked', this.checked);
    });
});