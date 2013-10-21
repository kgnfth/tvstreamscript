/* [ ---- Gebo Admin Panel - gallery grid ---- ] */

    $(document).ready(function() {
        //* init grid
        gebo_gal_grid.init();
        //* image grid
		gebo_gal_grid.large();
		//* mixed grid
        gebo_gal_grid.mixed();
    });

    gebo_gal_grid = {
		init: function() {
            $('.wmk_grid').each(function(){
                $(this).find('.yt_vid,.self_vid,.vimeo_vid').append('<span class="vid_ico"/>');
            }); 
        },
        large: function() {
            $('#large_grid ul').imagesLoaded(function() {
                // Prepare layout options.
                var options = {
                  autoResize: true, // This will auto-update the layout when the browser window is resized.
                  container: $('#large_grid'), // Optional, used for some extra CSS styling
                  offset: 6, // Optional, the distance between grid items
                  itemWidth: 220, // Optional, the width of a grid item (li)
                  flexibleItemWidth: true
                };
                
                // Get a reference to your grid items.
                var handler = $('#large_grid ul li');
                
                // Call the layout function.
                handler.wookmark(options);
                
                
                $('#large_grid ul li').on('mouseenter',function(){
                    $(this).addClass('act_tools');	
                }).on('mouseleave',function(){
                    $(this).removeClass('act_tools');
                });
                
                $('#large_grid ul li > a').attr('rel', 'gallery').colorbox({
                    maxWidth	: '80%',
                    maxHeight	: '80%',
                    opacity		: '0.1', 
                    loop		: true,
                    slideshow   : true,
                    fixed		: true
                });
            });
        },
		mixed: function() {
            $('#mixed_grid ul').imagesLoaded(function() {
                // Prepare layout options.
                var options = {
                  autoResize: true, // This will auto-update the layout when the browser window is resized.
                  container: $('#mixed_grid'), // Optional, used for some extra CSS styling
                  offset: 6, // Optional, the distance between grid items
                  itemWidth: 220, // Optional, the width of a grid item (li)
                  flexibleItemWidth: true
                };
                
                // Get a reference to your grid items.
                var handler = $('#mixed_grid ul li');
                
                // Call the layout function.
                handler.wookmark(options);
                
                $('#mixed_grid ul li').on('mouseenter',function(){
                    $(this).addClass('act_tools');	
                }).on('mouseleave',function(){
                    $(this).removeClass('act_tools');
                });
                
                $('#mixed_grid ul li > a').not('.ext_video,.int_video').attr('rel', 'mixed_gallery').colorbox({
                    maxWidth	    : '80%',
                    maxHeight	    : '80%',
                    opacity		    : '0.3', 
                    photo		    : true,
                    loop		    : false,
                    fixed		    : true
                });
                
                $('#mixed_grid .ext_video').attr('rel', 'mixed_gallery').colorbox({
                    width	        : '853px',
                    height	        : '480px',
                    opacity		    : '0.3', 
                    iframe		    : true,
                    loop		    : false,
                    fixed		    : true
                });
				
				$('#mixed_grid .int_video').attr('rel', 'mixed_gallery').colorbox({
                    minWidth      : '640px',
                    minHeight	    : '360px',
                    opacity		    : '0.3', 
                    inline		    : true,
                    loop		    : false,
					scrolling		: false,
                    fixed		    : true
                });
                
            });
        }
    };
