/* [ ---- Gebo Admin Panel - common ---- ] */

	//* detect touch devices 
    function is_touch_device() {
	  return !!('ontouchstart' in window);
	}
	$(document).ready(function() {
		//* search typeahead
		$('.search_query').typeahead({
			source: ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"],
			items: 4
		});
		//* accordion change actions
		$('#side_accordion').on('hidden shown', function () {
			gebo_sidebar.make_active();
            gebo_sidebar.update_scroll();
		});
		//* resize elements on window resize
		var lastWindowHeight = $(window).height();
		var lastWindowWidth = $(window).width();
		$(window).on("debouncedresize",function() {
			if($(window).height()!=lastWindowHeight || $(window).width()!=lastWindowWidth){
				lastWindowHeight = $(window).height();
				lastWindowWidth = $(window).width();
				gebo_sidebar.update_scroll();
				if(!is_touch_device()){
                    $('.sidebar_switch').qtip('hide');
                }
			}
		});
		//* tooltips
		gebo_tips.init();
        if(!is_touch_device()){
		    //* popovers
            gebo_popOver.init();
        }
		//* sidebar
        gebo_sidebar.init();
		gebo_sidebar.make_active();
		//* breadcrumbs
        gebo_crumbs.init();
		//* pre block prettify
		if(typeof prettyPrint == 'function') {
			prettyPrint();
		}
		//* external links
		gebo_external_links.init();
		//* style switcher
        gebo_style.init();
		//* accordion icons
		gebo_acc_icons.init();
		//* colorbox single
		gebo_colorbox_single.init();
		//* main menu mouseover (uncoment next line to enable mouseover)
		//gebo_nav_mouseover.init();
		gebo_sidebar.make_scroll();
		gebo_sidebar.update_scroll();
	});
    
    gebo_sidebar = {
        init: function() {
			// sidebar onload state
			if($(window).width() > 979){
                if(!$('body').hasClass('sidebar_hidden')) {
                    if( $.cookie('gebo_sidebar') == "hidden") {
                        $('body').addClass('sidebar_hidden');
                        $('.sidebar_switch').toggleClass('on_switch off_switch').attr('title','Show Sidebar');
                    }
                } else {
                    $('.sidebar_switch').toggleClass('on_switch off_switch').attr('title','Show Sidebar');
                }
            } else {
                $('body').addClass('sidebar_hidden');
                $('.sidebar_switch').removeClass('on_switch').addClass('off_switch');
            }

			gebo_sidebar.info_box();
			//* sidebar visibility switch
            $('.sidebar_switch').click(function(){
                $('.sidebar_switch').removeClass('on_switch off_switch');
                if( $('body').hasClass('sidebar_hidden') ) {
                    $.cookie('gebo_sidebar', null);
                    $('body').removeClass('sidebar_hidden');
                    $('.sidebar_switch').addClass('on_switch').show();
                    $('.sidebar_switch').attr( 'title', "Hide Sidebar" );
                } else {
                    $.cookie('gebo_sidebar', 'hidden');
                    $('body').addClass('sidebar_hidden');
                    $('.sidebar_switch').addClass('off_switch');
                    $('.sidebar_switch').attr( 'title', "Show Sidebar" );
                }
				gebo_sidebar.info_box();
				gebo_sidebar.update_scroll();
            });
			//* prevent accordion link click
            
            $('.sidebar .accordion-toggle').click(function(e){
            	if (!$(this).hasClass("clickable")){
            		e.preventDefault();
            	}            	
            });
        },
		info_box: function(){
			var s_box = $('.sidebar_info');
			var s_box_height = s_box.actual('height');
			s_box.css({
				'height'        : s_box_height
			});
			$('.push').height(s_box_height);
			$('.sidebar_inner').css({
				'margin-bottom' : '-'+s_box_height+'px',
				'min-height'    : '100%'
			});
        },
		make_active: function() {
			var thisAccordion = $('#side_accordion');
			thisAccordion.find('.accordion-heading').removeClass('sdb_h_active');
			var thisHeading = thisAccordion.find('.accordion-body.in').prev('.accordion-heading');
			if(thisHeading.length) {
				thisHeading.addClass('sdb_h_active');
			}
		},
        make_scroll: function() {
            antiScroll = $('.antiScroll').antiscroll().data('antiscroll');
        },
        update_scroll: function() {
			if($('.antiScroll').length) {
				if( $(window).width() > 979 ){
					$('.antiscroll-inner,.antiscroll-content').height($(window).height() - 40);
				} else {
					$('.antiscroll-inner,.antiscroll-content').height('400px');
				}
				antiScroll.refresh();
			}
        }
    };

	//* tooltips
	gebo_tips = {
		init: function() {
			if(!is_touch_device()){
				var shared = {
				style		: {
						classes: 'ui-tooltip-shadow ui-tooltip-tipsy'
					},
					show		: {
						delay: 100,
						event: 'mouseenter focus'
					},
					hide		: { delay: 0 }
				};
				if($('.ttip_b').length) {
					$('.ttip_b').qtip( $.extend({}, shared, {
						position	: {
							my		: 'top center',
							at		: 'bottom center',
							viewport: $(window)
						}
					}));
				}
				if($('.ttip_t').length) {
					$('.ttip_t').qtip( $.extend({}, shared, {
						position: {
							my		: 'bottom center',
							at		: 'top center',
							viewport: $(window)
						}
					}));
				}
				if($('.ttip_l').length) {
					$('.ttip_l').qtip( $.extend({}, shared, {
						position: {
							my		: 'right center',
							at		: 'left center',
							viewport: $(window)
						}
					}));
				}
				if($('.ttip_r').length) {
					$('.ttip_r').qtip( $.extend({}, shared, {
						position: {
							my		: 'left center',
							at		: 'right center',
							viewport: $(window)
						}
					}));
				};
			}
		}
	};
    
    //* popovers
    gebo_popOver = {
        init: function() {
            $(".pop_over").popover();
        }
    };
    
    //* breadcrumbs
    gebo_crumbs = {
        init: function() {
            if($('#jCrumbs').length) {
				$('#jCrumbs').jBreadCrumb({
					endElementsToLeaveOpen: 0,
					beginingElementsToLeaveOpen: 0,
					timeExpansionAnimation: 500,
					timeCompressionAnimation: 500,
					timeInitialCollapse: 500,
					previewWidth: 30
				});
			}
        }
    };
	
	//* external links
	gebo_external_links = {
		init: function() {
			$("a[href^='http']").not('.thumbnail>a').each(function() {
				$(this).attr('target','_blank').addClass('external_link');
			})
		}
	};
	
	//* style switcher
    gebo_style = {
        init: function() {
            $('.style_switcher a').click(function() {
                $('#link_theme').attr('href','css/'+$(this).attr('title')+'.css');
                $('.style_switcher a').removeClass('th_active');
                $(this).addClass('th_active');
            });
        }
    };
	
	//* accordion icons
	gebo_acc_icons = {
		init: function() {
			var accordions = $('.main_content .accordion');
			
			accordions.find('.accordion-group').each(function(){
				var acc_active = $(this).find('.accordion-body').filter('.in');
				acc_active.prev('.accordion-heading').find('.accordion-toggle').addClass('acc-in');
			});
			accordions.on('show', function(option) {
				$(this).find('.accordion-toggle').removeClass('acc-in');
				$(option.target).prev('.accordion-heading').find('.accordion-toggle').addClass('acc-in');
			});
			accordions.on('hide', function(option) {
				$(option.target).prev('.accordion-heading').find('.accordion-toggle').removeClass('acc-in');
			});	
		}
	};
	
	//* main menu mouseover
	gebo_nav_mouseover = {
		init: function() {
			$('header li.dropdown').on('mouseenter', function() {
				$(this).addClass('navHover').children('ul.dropdown-menu').show();
			}).on('mouseleave', function() {
				$(this).removeClass('navHover open').children('ul.dropdown-menu').hide();
			});
		}
	};
    
	//* single image colorbox
	gebo_colorbox_single = {
		init: function() {
			if($('.cbox_single').length) {
				$('.cbox_single').colorbox({
					maxWidth	: '80%',
					maxHeight	: '80%',
					opacity		: '0.2', 
					fixed		: true
				});
			}
		}
	};