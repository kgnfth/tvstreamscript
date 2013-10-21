/* [ ---- Gebo Admin Panel - search page ---- ] */

	$(document).ready(function() {
		
		//* check if thumb_view is enabled
        if($('.thumb_view').length) {
            $('.box_trgr').addClass('active');
        } else {
            $('.list_trgr').addClass('active');
        };
        
        //* toggle between list/boxes view
        $(".result_view a").click(function(e){
            if(!$(this).hasClass('active')) {
                $(".result_view a").toggleClass("active");
                $(".search_panel").fadeOut("fast", function() {
                    $(this).fadeIn("fast").toggleClass("box_view");
                });
            }
            e.preventDefault();
        });

	});
