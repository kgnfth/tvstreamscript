// Sticky v1.0 by Daniel Raftery
// http://thrivingkings.com/sticky
// http://twitter.com/ThrivingKings

;(function($) {
	
	// Using it without an object
	$.sticky = function(note, options, callback) { return $.fn.sticky(note, options, callback); };
	
	$.fn.sticky = function(note, options, callback) {
		
		var settings =
			{
			'speed'			: 'fast',	    // animations: fast, slow, or integer
			'duplicates'	: false,         // true or false
			'autoclose'		: 5000,         // integer or false
			'position'		: 'top-right',  // top-center, top-left, top-right, bottom-left, or bottom-right
			'type'		    : ''            // st-success, st-info, st-error
            };
			
		if(options)	{
            $.extend(settings, options);
        }
		
		// Passing in the object instead of specifying a note
		if(!note) {
            note = this.html();
        }
		
		// Variables
		var display = true, duplicate = 'no';
		
		// Somewhat of a unique ID
		var uniqID = Math.floor(Math.random()*99999);
		
		// Handling duplicate notes and IDs
		$('.sticky-note').each(function() {
			if($(this).html() == note && $(this).is(':visible')) { 
				duplicate = 'yes';
				if(!settings['duplicates']) {
                    display = false;
                }
			}
			if($(this).attr('id')==uniqID) {
                uniqID = Math.floor(Math.random()*9999999);
            }
		});
		
		// Make sure the sticky queue exists
		if(!$('body').find('.sticky-queue.'+settings.position).html()) {
            $('body').append('<div class="sticky-queue ' + settings.position + '"></div>');
        }
		
		// Can it be displayed?
		if(display) {
			// Building and inserting sticky note
			$('.sticky-queue.'+settings.position).prepend('<div class="sticky border-' + settings.position + ' ' + settings.type +'" id="' + uniqID + '"></div>');
			$('#' + uniqID).append('<span class="close st-close" rel="' + uniqID + '" title="Close">&times;</span>');
			$('#' + uniqID).append('<div class="sticky-note" rel="' + uniqID + '">' + note + '</div>');
			
			// Smoother animation
			var height = $('#' + uniqID).height();
			$('#' + uniqID).css('height', height);
			
			$('#' + uniqID).slideDown(settings['speed']);
			
            display = true;
		}
		
		// Listeners
		$('.sticky').ready(function() {
            // If 'autoclose' is enabled, set a timer to close the sticky
            if(settings['autoclose']) {
                $('#' + uniqID).delay(settings['autoclose']).slideUp(settings['speed'], function(){
					var closest = $(this).closest('.sticky-queue');
					var elem = closest.find('.sticky');
					$(this).remove();
					if(elem.length == '1'){
						closest.remove()
					}
                });
            }
		});
		// Closing a sticky
		$('.st-close').click(function()
			{
				$('#' + $(this).attr('rel')).dequeue().slideUp(settings['speed'], function(){
					var closest = $(this).closest('.sticky-queue');
					var elem = closest.find('.sticky');
					$(this).remove();
					if(elem.length == '1'){
						closest.remove()
					}
				});
				
            });
		
		
		// Callback data
		var response =  {
			'id'		:	uniqID,
			'duplicate'	:	duplicate,
			'displayed'	: 	display,
			'position'	:	settings.position,
			'type'	    :	settings.type
		}
		
		// Callback function?
		if(callback) {
            callback(response);
        }
		else {
            return(response);
        }
		
	}
    
})( jQuery );