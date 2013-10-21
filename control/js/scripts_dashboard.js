/* [ ---- Gebo Admin Panel - dashboard ---- ] */

function randomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.round(Math.random() * 15)];
    }
    return color;
}

$(document).ready(function() {
	//* charts
	gebo_charts.fl_1();
	gebo_charts.fl_2();
	gebo_charts.fl_3();

	//* calendar
	//gebo_calendar.init();
	//* responsive table
	gebo_media_table.init();
	//* resize elements on window resize
	var lastWindowHeight = $(window).height();
	var lastWindowWidth = $(window).width();
	$(window).on("debouncedresize",function() {
		if($(window).height()!=lastWindowHeight || $(window).width()!=lastWindowWidth){
			lastWindowHeight = $(window).height();
			lastWindowWidth = $(window).width();
			//* rebuild calendar
			$('#calendar').fullCalendar('render');
		}
	});
	//* small gallery grid
	gebo_gal_grid.shows();
	gebo_gal_grid.movies();
    
    
});

//* responsive tables
gebo_media_table = {
    init: function() {
		$('.mediaTable').mediaTable();
    }
};



//* charts
gebo_charts = {
    fl_1: function() {
        // Setup the placeholder reference
        f1_elem = $('#fl_1');
        f1_elem.html("<center><br /><br /><img src='img/ajax_loader.gif' /></center>");
        
        $.get("ajax/graph.php",{
        	report_type: "daily"
        }, function(daily_data){
			var daily_data = eval('('+daily_data+')');
			
			if (daily_data.data){
				daily_data = daily_data.data;
            	
	            // Setup the flot chart using our data

				
	            $.plot(f1_elem, 
	                [
	                    { label: "Visits",  data: daily_data.visits},
	                    { label: "Pageviews",  data: daily_data.pageviews}
	                ], 
	                {
	                    lines: { show: true },
	                    points: { show: true },
	                    xaxis: {
	                    	mode: "time",
	                    	minTickSize: [1, "day"],
	                        min: (new Date(daily_data.min_date)).getTime(),
	                        max: (new Date(daily_data.max_date)).getTime(),		                        
	                        timeformat: "%y-%0m-%0d"
	                      },
	                    grid: {
	                        hoverable: true,
	                        borderWidth: 1
	                    },
						colors: [ "#8cc7e0", "#2d83a6" ]
	                }
	            );
	            // Create a tooltip on our chart
	            f1_elem.qtip({
	                prerender: true,
	                content: 'Loading...', // Use a loading message primarily
	                position: {
	                    viewport: $(window), // Keep it visible within the window if possible
	                    target: 'mouse', // Position it in relation to the mouse
	                    adjust: { x: 8, y: -30 } // ...but adjust it a bit so it doesn't overlap it.
	                },
	                show: false, // We'll show it programatically, so no show event is needed
	                style: {
	                    classes: 'ui-tooltip-shadow ui-tooltip-tipsy',
	                    tip: false // Remove the default tip.
	                }
	            });
	         
	            // Bind the plot hover
	            f1_elem.on('plothover', function(event, coords, item) {
	                // Grab the API reference
	                var self = $(this),
	                    api = $(this).qtip(),
	                    previousPoint, content,
	         
	                // Setup a visually pleasing rounding function
	                round = function(x) { return Math.round(x * 1000) / 1000; };
	         
	                // If we weren't passed the item object, hide the tooltip and remove cached point data
	                if(!item) {
	                    api.cache.point = false;
	                    return api.hide(event);
	                }
	         
	                // Proceed only if the data point has changed
	                previousPoint = api.cache.point;
	                if(previousPoint !== item.dataIndex)
	                {
	                    // Update the cached point data
	                    api.cache.point = item.dataIndex;
	         
	                    // Setup new content
	                    var newDate = new Date(item.datapoint[0]);
	                    content = newDate.toDateString() + ' - ' + round(item.datapoint[1]) + ' ' + item.series.label;
	         
	                    // Update the tooltip content
	                    api.set('content.text', content);
	         
	                    // Make sure we don't get problems with animations
	                    //api.elements.tooltip.stop(1, 1);
	         
	                    // Show the tooltip, passing the coordinates
	                    api.show(coords);
	                }
	            });
			} else {
				f1_elem.html("<center><br /><br /><a href='index.php?menu=settings_accounts'>Click here</a> to add your Google Analytics account</center>");
			}
        });
    },
    fl_2 : function() {
        // Setup the placeholder reference
        elem = $('#fl_2');
        elem.html("<center><img src='img/ajax_loader.gif' /></center>");
       
        $.get("ajax/graph.php",{
        	
        }, function(data){
			var data = eval('('+data+')');
			
			if (data.data){
				data = data.data;
				// Setup the flot chart using our data
	            $.plot(elem, data,         
	                {
						label: "Visitors by Location",
	                    series: {
	                        pie: {
	                            show: true,
								highlight: {
									opacity: 0.2
								}
	                        }
	                    },
	                    grid: {
	                        hoverable: true,
	                        clickable: true
	                    },
						colors: [ "#b3d3e8", "#8cbddd", "#65a6d1", "#3e8fc5", "#3073a0", "#245779", "#183b52" ]
	                }
	            );
	            // Create a tooltip on our chart
	            elem.qtip({
	                prerender: true,
	                content: 'Loading...', // Use a loading message primarily
	                position: {
	                    viewport: $(window), // Keep it visible within the window if possible
	                    target: 'mouse', // Position it in relation to the mouse
	                    adjust: { x: 7 } // ...but adjust it a bit so it doesn't overlap it.
	                },
	                show: false, // We'll show it programatically, so no show event is needed
	                style: {
	                    classes: 'ui-tooltip-shadow ui-tooltip-tipsy',
	                    tip: false // Remove the default tip.
	                }
	            });
	         
	            // Bind the plot hover
	            elem.on('plothover', function(event, pos, obj) {
	                
	                // Grab the API reference
	                var self = $(this),
	                    api = $(this).qtip(),
	                    previousPoint, content,
	         
	                // Setup a visually pleasing rounding function
	                round = function(x) { return Math.round(x * 1000) / 1000; };
	         
	                // If we weren't passed the item object, hide the tooltip and remove cached point data
	                if(!obj) {
	                    api.cache.point = false;
	                    return api.hide(event);
	                }
	         
	                // Proceed only if the data point has changed
	                previousPoint = api.cache.point;
	                if(previousPoint !== obj.seriesIndex)
	                {
	                    percent = parseFloat(obj.series.percent).toFixed(2);
	                    // Update the cached point data
	                    api.cache.point = obj.seriesIndex;
	                    // Setup new content
	                    content = obj.series.label + ' ( ' + percent + '% )' + " " + obj.series.data + " visits";
	                    // Update the tooltip content
	                    api.set('content.text', content);
	                    // Make sure we don't get problems with animations
	                    //api.elements.tooltip.stop(1, 1);
	                    // Show the tooltip, passing the coordinates
	                    api.show(pos);
	                }
	            });
            
			} else {
				elem.html("<center><a href='index.php?menu=settings_accounts'>Click here</a> to add your Google Analytics account</center>");
			}
        });
    },
    fl_3: function() {
        // Setup the placeholder reference
        f3_elem = $('#fl_3');
        f3_elem.html("<center><br /><br /><img src='img/ajax_loader.gif' /></center>");
        
        $.get("ajax/graph.php",{
        	report_type: "referrer"
        }, function(referrer_data){
			var data = eval('('+referrer_data+')');
			
			if (data.data){
				f3_elem.html(data.data);				
			} else {
				f3_elem.html("<center><br /><a href='index.php?menu=settings_accounts'>Click here</a> to add your Google Analytics account</center><br /><br />");
			}
        });
        
    }
};


//* gallery grid
gebo_gal_grid = {
	shows: function() {
        //* small gallery grid
        $('#shows_small_grid ul').imagesLoaded(function() {
            // Prepare layout options.
            var options = {
              autoResize: true, // This will auto-update the layout when the browser window is resized.
              container: $('#shows_small_grid'), // Optional, used for some extra CSS styling
              offset: 6, // Optional, the distance between grid items
              itemWidth: 120, // Optional, the width of a grid item (li)
              flexibleItemWidth: true
            };
            
            // Get a reference to your grid items.
            var handler = $('#shows_small_grid ul li');
            
            // Call the layout function.
            handler.wookmark(options);
            /*
            $('#shows_small_grid ul li > a').attr('rel', 'gallery').colorbox({
                maxWidth	: '80%',
                maxHeight	: '80%',
                opacity		: '0.2', 
                loop		: false,
                fixed		: true
            });
            */
        });
    },
	movies: function() {
        //* small gallery grid
        $('#movies_small_grid ul').imagesLoaded(function() {
            // Prepare layout options.
            var options = {
              autoResize: true, // This will auto-update the layout when the browser window is resized.
              container: $('#movies_small_grid'), // Optional, used for some extra CSS styling
              offset: 6, // Optional, the distance between grid items
              itemWidth: 120, // Optional, the width of a grid item (li)
              flexibleItemWidth: true
            };
            
            // Get a reference to your grid items.
            var handler = $('#movies_small_grid ul li');
            
            // Call the layout function.
            handler.wookmark(options);
            /*
            $('#movies_small_grid ul li > a').attr('rel', 'gallery').colorbox({
                maxWidth	: '80%',
                maxHeight	: '80%',
                opacity		: '0.2', 
                loop		: false,
                fixed		: true
            });
            */
        });
    }
};