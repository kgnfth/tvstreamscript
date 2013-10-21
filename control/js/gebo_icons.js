/* [ ---- Gebo Admin Panel - icons ---- ] */

	$(document).ready(function() {
		//* show code for icons
		$('.icon_list_a li').css('cursor','pointer').on('click', function(){
            var ico_title = $(this).attr('title');
            $('.icon_copy_a span').html('[ <i class="'+ico_title+'"></i> <code>&lt;i class="'+ico_title+'"&gt;&lt;/i&gt;</code> ]');
        });
        $('.icon_list_b li').css('cursor','pointer').on('click', function(){
            var ico_name = $(this).attr('title');
            var ico_title = $(this).find('i').attr('class');
            $('.icon_copy_b span').html('[ <i class="'+ico_title+'"></i> '+ ico_name +' <code>&lt;i class="'+ico_title+'"&gt;&lt;/i&gt; '+ ico_name +'</code> ]');
        });
        $('.icon_list_c li').css('cursor','pointer').on('click', function(){
            var ico_src = $(this).find('img').attr('src');
            $('.icon_copy_c span').html('[ <img src="'+ico_src+'" alt="" /> <code>&lt;img src="'+ico_src+'" alt="" /&gt;</code>, <code>&lt;div style="background: url('+ico_src+');"&gt;&lt;/div&gt;</code> ]');
        });
        $('.icon_list_d li').css('cursor','pointer').on('click', function(){
            var ico_title = $(this).attr('title');
            $('.icon_copy_d span').html('[ <i class="'+ico_title+'"></i> <code>&lt;i class="'+ico_title+'"&gt;&lt;/i&gt;</code> ]');
        });
	});
