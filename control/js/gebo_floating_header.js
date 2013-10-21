/* [ ---- Gebo Admin Panel - floating list header ---- ] */

	$(document).ready(function() {
		gebo_floating_header.basic();
		gebo_floating_header.scrollto();
	});

	//* floating list header
	gebo_floating_header = {
		basic: function() {
			var $list_a = $('#cities');
            
            $list_a.list({
				headerSelector : 'li.list_heading'
			});
            //* add arrows and add click event
            $list_a.wrap('<div class="list-outer" />');
            var $list_a_outer = $('.list-outer');
            var height = $list_a.height();
            $list_a_outer.append('<div class="slide-up slide-nav"><i class="icon-chevron-up"></i></div><div class="slide-down slide-nav"><i class="icon-chevron-down"></i></div>');
            $('.slide-down').click(function(){
                $('.ui-list',$list_a).animate({scrollTop : "+="+height/1.35+"px"}, 500);
            });
            $('.slide-up').click(function(){
                $('.ui-list',$list_a).animate({scrollTop : "-="+height/1.35+"px"}, 500);
            });
		},
		scrollto: function() {
            var $list_b = $('#countries');
            
			// Generate the list of buttons for the scrollto links.
			$('<div id="list-buttons"/>').insertBefore($list_b);
			$list_b.find('dt').each(function(i){
				var this_dd = $(this),
                    dd_length = $('+ dd',this_dd).nextUntil('dt').length + 1;
                // Create the new button element for this header.
				$('#list-buttons').append('<button data-header="'+i+'" class="btn btn-mini bstr_ttip" title="'+dd_length+'">'+$(this).text()+'</button>');
			});
			if(!is_touch_device()){
				//* add tooltip 
			    $('.bstr_ttip').tooltip();
			}
			//* add the list plugin.
			$list_b.list();
			
			var dd_height = $list_b.find('.ui-list dd').outerHeight(),
                dt_height = $list_b.find('.ui-list dt').outerHeight(),
                fake_header = $list_b.find('.-list-fakeheader');
			
			//* add an event handler 
			$('#list-buttons').on('click', 'button', function(){
				$('#list-buttons button').removeClass('btn-success');
				$(this).addClass('btn-success');
                
				var this_index = $(this).data('header'),
					this_dt = $list_b.find('.ui-list dt').eq(this_index);
                //* fix fakeheader text
				function updateHeader() {
					fake_header.html(this_dt.html());
				};
                //* update container height
                var dd_length = $('+ dd',this_dt).nextUntil('dt').length + 1;
                    $list_b.animate({ height: (dt_height + (dd_length * dd_height) - 2) }, 100);
                // Scroll to the selected element.
				$list_b.list( 'scrollTo', this_index, 400, function(){
                    setTimeout(updateHeader,0);
                } );

			});
            //* hide scrollbar
			$list_b.find('.ui-list').css('overflow-y','hidden');
			//* adjust fakeHeader width
            fake_header.width(fake_header.width() + 15);
            //* set first element
			$('#list-buttons button:first').trigger('click');
		}
	};