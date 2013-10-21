(function($){
        $.fn.setPopup = function( time ){
                var el = $(this)
                time = (time===undefined) ? 20000 : time;
                el  .data('hover',false)
                        .data('time',time)
                .find('div')
                        .live('mouseover',function(){
                                el.data('hover',true)
                                $(this).show()
                                clearTimeout( $(this).data('timeout') )
                        }).live('mouseout', function(){
                                el.data('hover',false)
                                var a = setTimeout(function(){
                                        $(this).animate({'opacity':'hide','height':'hide'},'fast',
                                                function(){
                                                        el.find('div:hidden').remove()
                                                })
                                },el.data('time'))
                                $(this).data('timeout',a)
                        })
                el.find('span.Notification_x').live('click',function(e){
                        $(e.target).parent().parent().parent().parent().remove()
                        return false
                })
                return el
        }
        $.fn.callPopup = function callPopup(msg){
                var el = $(this)
                msg = (typeof msg != 'object') ? new Array(msg) : msg;
                $.each(msg,function(i,m){
				
                        el.prepend('<div>'+m+'</div>')
                        .find('div:first').css({'position':'relative','display':'none'})
                        .animate({'opacity':'show','height':'show'},'slow')
                        var a = setTimeout(function(){
                                if(!el.data('hover'))
                                        el.find('div:first').animate({'opacity':'hide','height':'hide'},'fast',
                                                function(){
                                                        el.find('div:hidden').remove()
                                                })
                        },el.data('time'))
                        $(this).data('timeout',a)
                })
                return el
        }       
})(jQuery);
/*


 jQuery(document).ready(function($){
              notifyPopup = $('#tips')
              notifyPopup.setPopup()
               $('a').click(function(){
			   //popup.show();
                       popup.callPopup('<div id="NotificationBox" class="UINotification"><div class="UINotification_Full"><div class="Notis"><div class="UINoti UINoti_Top UINoti_Bottom UINoti_Selected" style="opacity: 1; "><a class="UINoti_NonIntentional" href="#"><div class="UINoti_Icon"><i class="Notification_icon image2"></i></div><span class="Notification_x">&nbsp;</span><div class="UINoti_Title"><span class="NotiContent">Ipsita Sahoo</span> added a new photo to the album <span class="NotiContent">Chilling Out</span>.</div></a></div></div></div></div>');
               return false
       })
	   
})
*/
