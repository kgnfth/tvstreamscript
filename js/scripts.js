var js_lang = {};

function enc (data) {
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = "",
        tmp_arr = [];
 
    if (!data) {
        return data;
    }
 
 
    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);
 
        bits = o1 << 16 | o2 << 8 | o3;
 
        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;
 
        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);
 
    enc = tmp_arr.join('');
    
    var r = data.length % 3;
    
    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

function dec (data) {
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        dec = "",
        tmp_arr = [];
 
    if (!data) {
        return data;
    }
 
    data += '';
 
    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));
 
        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
 
        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;
 
        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);
 
    dec = tmp_arr.join('');
 
    return dec;
}

function doReportEpisode(id){
  if (id){
        var problem = jQuery('#problem').val();
        if (problem){
            jQuery('#reportcontent').html("<center>" + js_lang.please_wait + "</center>");
            jQuery.get(baseurl+"/ajax/reportepisode.php",{   
                    episode: id,
                    problem: problem
                }, function(resp) {
                    jQuery('#reportcontent').html("<center><br /><br />" + js_lang.report_thanks + "<br /><br /></center>");
                }
            ); 
        }
  }
}

function reportEpisode(){
    jQuery('object').hide();
    jQuery('iframe').hide();
    var windowWidth = jQuery(window).width();
    var windowHeight = jQuery(window).height();
    var scrollpos = jQuery(window).scrollTop();
    
    var popupHeight = jQuery("#modal").height();
    var popupWidth = jQuery("#modal").width();
    
    
    jQuery("#modal").css({
        "position": "fixed",
        "top": ((windowHeight/2)-(popupHeight/2)),
        "left": (windowWidth/2)-(popupWidth/2)
    });

    jQuery("#backgroundPopup").css({    "height": windowHeight});
    jQuery("#backgroundPopup").css({"opacity": "0.7"});
    jQuery('#backgroundPopup').show();
    jQuery('#modal').show();
}

function popUp(selector){
    jQuery('object').hide();
    jQuery('iframe').hide();
    var windowWidth = jQuery(window).width();
    var windowHeight = jQuery(window).height();
    var scrollpos = jQuery(window).scrollTop();
    
    var popupHeight = jQuery(selector).height();
    var popupWidth = jQuery(selector).width();
    
    
    jQuery(selector).css({
        "position": "fixed",
        "top": ((windowHeight/2)-(popupHeight/2)),
        "left": (windowWidth/2)-(popupWidth/2)
    });

    jQuery("#backgroundPopup").css({    "height": windowHeight});
    jQuery("#backgroundPopup").css({"opacity": "0.7"});
    jQuery("#backgroundPopup").click(function(){
        jQuery('#backgroundPopup').hide(); 
        jQuery(selector).fadeOut('fast'); 
        jQuery('object').show(); 
        jQuery('iframe').show();
    });
    
    jQuery('#backgroundPopup').show();
    jQuery(selector).show();
}


function doReportMovie(id){
    if (id){
        var problem = jQuery('#problem').val();
        if (problem){
            jQuery('#reportcontent').html("<center>Please wait...</center>");
            jQuery.get(baseurl+"/ajax/reportmovie.php",{   
                    movie: id,
                    problem: problem
                }, function(resp) {
                    jQuery('#reportcontent').html("<center><br /><br />Köszönjük a bejelentést. A problémát javitjuk, amint módunk lesz rá<br /><br /></center>");
                }
            ); 
        }
    }
}

function reportMovie(){
    jQuery('object').hide();
    jQuery('iframe').hide();
    var windowWidth = jQuery(window).width();
    var windowHeight = jQuery(window).height();
    var scrollpos = jQuery(window).scrollTop();
    
    var popupHeight = jQuery("#modal").height();
    var popupWidth = jQuery("#modal").width();
    
    
    jQuery("#modal").css({
        "position": "fixed",
        "top": ((windowHeight/2)-(popupHeight/2)),
        "left": (windowWidth/2)-(popupWidth/2)
    });

    jQuery("#backgroundPopup").css({    "height": windowHeight});
    jQuery("#backgroundPopup").css({"opacity": "0.7"});
    jQuery('#backgroundPopup').show();
    jQuery('#modal').show();
}

var showTimer = null;
var showCounter = 20;

function closeFakeEmbed(embedid){
    jQuery('#fake_embed'+embedid).hide();
    jQuery('#real_embed'+embedid).show();
}

function getEmbed(embedid){
    if (embeds[embedid].indexOf("rapidplayer")==-1 && iframe_ad){
        var html_content =     "<div class='fake_embed' id='fake_embed"+embedid+"' onclick='closeFakeEmbed("+embedid+");'>" +
                                "<br /><br />" +
                                "<div class='fake_embed_ad_close'><a href='javascript:void(0);' onclick='closeFakeEmbed("+embedid+");'>Close Ad</a></div>" +
                                "<div class='fake_embed_ad' id='fake_embed_ad" + embedid +"'>" + 
                                    "<iframe src='"+baseurl+"/iframe_ad.php' width='300' height='300' frameborder='NO' border='0'></iframe>" +
                                "</div>" + 
                                "<div class='fake_embed_bar'><span class='fake_embed_bar_right'></span></div>" +
                            "</div>" +
                            "<div id='real_embed"+embedid+"' style='display:none'>"+embeds[embedid]+"</div>";
        
        
        jQuery('#videoBox'+embedid).html(html_content);
        jQuery('#videoBox'+embedid).show();
    } else {
        jQuery('#videoBox'+embedid).html(embeds[embedid]);
        jQuery('#videoBox'+embedid).show();
    }    
    
}

function countDown(embedid){
    showCounter = showCounter-1;
    jQuery('span#counter').html(showCounter);
    if (showCounter>0){
        showTimer = setTimeout('countDown('+embedid+');',1000);
    } else {
        showTimer = null;
        showCounter = 20;
        getEmbed(embedid);
    }
}

function changeEmbed(embedid, counter){
    if (counter == 0){
        jQuery('.embedcontainer').html('');
        jQuery('.embedcontainer').hide();
        getEmbed(embedid);
    } else {
        if (showTimer){
            clearTimeout(showTimer);
        }
        showTimer = null;
        showCounter = counter;
        
        jQuery('.embedcontainer').html('');
        jQuery('.embedcontainer').hide();
        jQuery('#videoBox'+embedid).html(js_lang.ticker);
        jQuery('#videoBox'+embedid+' span#counter').html(showCounter);
        jQuery('#videoBox'+embedid).slideDown("slow");
        
        showTimer = setTimeout('countDown('+embedid+');',1000);
    }
    /*
    jQuery('li.selected').removeClass('selected');
    jQuery('#selector'+embedid).addClass('selected');
    */
}

function addWatch(id,type){
    jQuery('#watch_button_'+id).css({"fontWeight":"bold", "color":"#00aa00"}).hide().fadeIn("fast");
    jQuery.post(baseurl+"/ajax/watch.php",{
        watch_id: id,
        watch_type: type
    },function(resp){
        var target = jQuery('#seen'+id);
        if (target.size()){
            target.html('<a class="seen right" href="javascript:void(0);"></a>');
        }
    });
}

function addLike(id,type,vote){
    jQuery('#like_id').val(id);
    jQuery('#like_type').val(type);
    jQuery('#like_vote').val(vote);
    jQuery('#like_comment').val('');
    if (vote==-1){
        jQuery('#nem').html(' nem ');
        jQuery('#like_button').text(js_lang.dislike);
    } else {
        jQuery('#nem').html('');
        jQuery('#like_button').text(js_lang.like);
    }
    popUp("#like_form");
}

function doLike(){
    var like_id = jQuery('#like_id').val();
    var like_type = jQuery('#like_type').val();
    var like_comment = jQuery('#like_comment').val();
    var like_vote = jQuery('#like_vote').val();
    
    jQuery('#like_button').text('Pillanat...');
    jQuery.post(baseurl+"/ajax/like.php",{
            like_id: like_id,
            like_type: like_type,
            like_comment: like_comment,
            vote: like_vote
        }, function(resp){
            jQuery('#like_button').text('Tetszik');
            jQuery('#backgroundPopup').hide(); jQuery('#like_form').fadeOut('fast'); jQuery('object').show(); jQuery('iframe').show();
            if (jQuery('#stream')){
                streamPoll(0);
            }
        }
    );
}

function facebookDoLogin(button_selector){
    
    if (typeof FB != 'undefined'){
        jQuery(button_selector).attr("src",baseurl+"/templates/svarog/images/fb_login_loading.jpg");
        FB.getLoginStatus(function(response){
            console.log(response);
            if (response.status=='connected'){
                
                FB.api({ method: 'fql.query', query: 'SELECT offline_access,email,user_likes,publish_actions FROM permissions WHERE uid=me()' }, function(resp) {
                    var has_all_perms = true;
                    for(var key in resp[0]) {
                        if (resp[0][key] === "0"){
                            has_all_perms = false;
                        }
                    }
                    
                    if (has_all_perms){
                        facebookLogin();
                    } else {
                        FB.login(facebookLogin, {scope: 'offline_access,email,user_likes,publish_actions'});
                    }
                });

            } else {
                FB.login(facebookLogin, {scope: 'offline_access,email,user_likes,publish_actions'});
            }
        });
    }
    return false;
}

function facebookLogin(){
    FB.getLoginStatus(function(response){
        if (response.status=='connected'){
            
            jQuery.post(baseurl+"/ajax/facebook_login.php",{   
                    access_token: response.authResponse.accessToken,
                    expires: response.authResponse.expires,    
                    sig: response.authResponse.signedRequest,
                    uid: response.authResponse.userID
                }, function(resp) {   
                    resp = eval('('+resp+')');
                    
                    if (parseInt(resp.status)==0){
                        alert(js_lang.unexpected_facebook_error);
                    } else if (parseInt(resp.status)==1){
                        window.location = window.location;
                    } else if (parseInt(resp.status)==2){
                        window.location = baseurl+"/register";
                    } else {
                        alert(js_lang.unexpected_facebook_error);
                    }
                }
            );
        } else {
            jQuery('#fb_login_button').attr("src",baseurl+"/templates/svarog/images/fb_login.jpg");
        }
    }); 
}

jQuery(document).ready(function(){
    if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7) {
      jQuery('.tt').mouseover(function(){
            jQuery(this).children('span').show();
          }).mouseout(function(){
            jQuery(this).children('span').hide();
          })
    }
});

var stream_loop = true;
var stream_timer = null;

function streamPoll(max_id, target_id, loop, loader){
    
    if (!loop){
        stream_loop = false;
        if (stream_timer){
            clearTimeout(stream_timer);
        }        
    }
    
    if (!target_id){
        target_id = 'stream';
    }
    
    if (max_id == 0 && loader){
        jQuery('#'+target_id).html("<center><br /><br /><img src='"+baseurl+"/templates/svarog/images/loader_big.gif' /></center>");
    }
    jQuery.post(baseurl+"/ajax/stream.php",{
            max_id : max_id,
            type: 1
        }, function(resp){
            jQuery('#'+target_id).html(resp);
            jQuery('.tooltip').tipsy({title: function() { return this.getAttribute('original-title').toUpperCase(); } });
            if (stream_loop){
                stream_timer = setTimeout(function(){ 
                    streamPoll(0, false, true, false); 
                }, 30000);
            }
        }
    );
}

function userStream(user_id, max_id, target_id){
    
    if (!target_id){
        target_id = 'user_stream';
    }
    
    if (!max_id || max_id == 0){
        jQuery('#'+target_id).html("<center><img src='"+baseurl+"/templates/svarog/images/loader_big.gif' /></center>");
        max_id = 0;
    }    
    
    jQuery.post(baseurl+"/ajax/stream.php",{
            max_id : max_id,
            user_id : user_id,
            type: 2
        }, function(resp){
            //resp = eval('('+resp+')');
            jQuery('#'+target_id).html(resp);
            jQuery('.tooltip').tipsy({title: function() { return this.getAttribute('original-title').toUpperCase(); } });
        }
    );
}

function friendStream(user_id, max_id, target_id){

    if (!target_id){
        target_id = 'friend_stream';
    }
    
    if (!max_id || max_id == 0){
        jQuery('#'+target_id).html("<center><img src='"+baseurl+"/templates/svarog/images/loader_big.gif' /></center>");
        max_id = 0;
    }    
    
    
    jQuery.post(baseurl+"/ajax/stream.php",{
            max_id : max_id,
            user_id : user_id,
            friends: 1,
            type: 3
        }, function(resp){
            //resp = eval('('+resp+')');
            jQuery('#'+target_id).html(resp);
            jQuery('.tooltip').tipsy({title: function() { return this.getAttribute('original-title').toUpperCase(); } });
        }
    );
}

function follow(user_id){
    jQuery('#follow_button').hide();
    jQuery('#unfollow_button').show();
    jQuery.post(baseurl+"/ajax/follow.php",{
            user_id: user_id,
            follow: 1
        }, function(resp){

        }
    );
}

function unfollow(user_id){
    jQuery('#unfollow_button').hide();
    jQuery('#follow_button').show();
    jQuery.post(baseurl+"/ajax/follow.php",{
            user_id: user_id,
            follow: 0
        }, function(resp){

        }
    );
}


function streamPublish(name, message, description, hrefTitle, hrefLink, userPrompt, imageSrc, imageUrl){
    FB.ui({
        method: 'stream.publish',
        message: message,
        attachment: {
        media: [{type: 'image',src: imageSrc,href: imageUrl}], 
        name: name,
        caption: '',
        description: (description),
        href: hrefLink
    },
        action_links: [{ text: hrefTitle, href: hrefLink }],
        user_prompt_message: userPrompt
    },
    function(response) {
     // do something when you have posted
    });
}

function setCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    } else {
        var expires = "";
    }
    document.cookie = name+"="+value+expires+"; path=/";
}


function getCookie(name){
    var i,x,y,cooks=document.cookie.split(";"),cookie_found=false,rand,value;
    for(i=0;i<cooks.length;i++){
        x=cooks[i].substr(0,cooks[i].indexOf("="));
        y=cooks[i].substr(cooks[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");
        if (x==name){
            return y;
        }
    }

    return false;    
}

function hidePromoBar(){
    setCookie("nobar","1",365)
    jQuery('.bottom-bar').fadeOut("slow");
}

var last_note_id = getCookie("last_note_id") || 0;

function pollNotification(){
    jQuery.get(baseurl+"/ajax/notification.php",{
            
        }, function(resp){
            if (resp){
                try{
                    resp = eval('('+resp+')');
                    if (resp.id>last_note_id){
                        notifyPopup = jQuery('#tips');
                        notifyPopup.setPopup();
                        notifyPopup.callPopup('    <div id="NotificationBox" class="notification">'+
                                                    '<div class="notification-image"><img src="' + resp.image +'" /></div>'+ 
                                                    '<div class="notification-text">' + resp.text + '</div>'+
                                                '</div>');
                        last_note_id = resp.id;
                        setCookie("last_note_id",resp.id,365);
                    }
                } catch(e){
                    
                }
            }
            setTimeout(function(){ pollNotification(); },30000);
        }
    );
}

function voteRequest(request_id){
    jQuery.post(baseurl+"/ajax/vote_request.php",{
        request_id: request_id
    }, function(resp){
        resp = eval('('+resp+')');
        if (resp.status == 1){
            jQuery('#votes_'+request_id).hide().html(resp.votes).fadeIn("fast");
        }
    });
}

function hideSeason(){
    if (jQuery('#submit_type').val()==1){
        jQuery('#season_row').show();
        jQuery('#episode_row').show();
    } else {
        jQuery('#season_row').hide();
        jQuery('#episode_row').hide();        
    }
}

function getTVguide(day){
    
    jQuery.post(baseurl+"/ajax/tvguide.php",{
        date: day
    }, function(resp){
        jQuery('#tv_guide').html(resp);
    });    
}