/* [ ---- Gebo Admin Panel - location finder ---- ] */

	$(document).ready(function() {
        gebo_maps.init();
	});
    
    
    location_add_form = $('.location_add_form');
    location_table = $('.location_table');
    g_Map = $('#g_map');

	//* clear search input
    function clear_search() {
        $('#gmap_search input').val('');
    };
	//* clear location add form
    function clear_form() {
        location_add_form.hide().find('input').val('');
    };
	//* marker message 
    function marker_message() {
        $.sticky('Drag marker to adjust position.', {autoclose : 5000, position: "top-center", type: "st-info" });
    };
	// marker callback after drag end
    function marker_callback(marker) {
        $('#comp_lat_lng').val(marker.position.lat().toFixed(6)+', '+marker.position.lng().toFixed(6));
        g_Map.gmap3({
            action: 'getAddress',
            latLng: marker.getPosition(),
            callback: function(results){
                $('#comp_address').val(results[0].formatted_address);
            }
        });
    };
    
    gebo_maps = {
        init: function() {
            gebo_maps.create();
            gebo_maps.save_location();
            gebo_maps.edit_location();
            gebo_maps.show_location();
            
        },
        create: function() {
			//* create basic map
            g_Map.gmap3({
                action: 'init',
                options:{
                    center  : [48.71, 49.87],
                    zoom    : 3
                },
                callback: function(){
                    $('#gmap_search').on('submit', function(){
                       location_add_form.find('input').val('');
                       gebo_maps.drop_marker_search();
                       return false;
                    })
                }
            });
        },
        save_location: function() {
            //* save location
			location_add_form.on('click','button', function() {
                $('html,body').animate({ scrollTop: location_table.offset().top }, 'fast');
                var last_row = location_table.find('tbody:last');
                var comp_id = $('#comp_id').val();
                var last_id = parseInt( last_row.find('tr:last td:first').text() );
                if(comp_id != ''){
                    location_table.find('tbody > tr:nth-child('+comp_id+')').html('<td>'+comp_id+'</td><td>'+location_add_form.find('#comp_name').val()+'</td><td>'+location_add_form.find('#comp_contact').val()+'</td><td class="address">'+$('#comp_address').val()+'</td><td>'+location_add_form.find('#comp_lat_lng').val()+'</td><td>'+location_add_form.find('#comp_phone').val()+'</td><td><a href="javascript:void(0)" class="show_on_map btn btn-mini btn-gebo">Show</a> <a href="javascript:void(0)" class="comp_edit btn btn-mini">Edit</a></td>');
                    $('#comp_id').val('');
                }else {
                    last_row.append('<tr><td>'+(last_id + 1)+'</td><td>'+location_add_form.find('#comp_name').val()+'</td><td>'+location_add_form.find('#comp_contact').val()+'</td><td class="address">'+$('#comp_address').val()+'</td><td>'+location_add_form.find('#comp_lat_lng').val()+'</td><td>'+location_add_form.find('#comp_phone').val()+'</td><td><a href="javascript:void(0)" class="show_on_map btn btn-mini btn-gebo">Show</a> <a href="javascript:void(0)" class="comp_edit btn btn-mini">Edit</a></td></tr>');
                };
                clear_form();
                clear_search();
                g_Map.gmap3({action:'clear'});
                $.sticky("Location Successfuly Saved.", {autoclose : 5000, position: "top-center", type: "st-info" });
            });  
        },
        edit_location: function() {
            //* edit location
			location_table.on('click','.comp_edit',function(){
                location_add_form.show();
                var this_item = $(this).closest('tr');
                $('#comp_id').val(this_item.find('td:nth-child(1)').text());
                $('#comp_name').val(this_item.find('td:nth-child(2)').text());
                $('#comp_contact').val(this_item.find('td:nth-child(3)').text());
                $('#comp_address').val(this_item.find('td:nth-child(4)').text());
                var show_lat_lng = $('#comp_lat_lng').val(this_item.find('td:nth-child(5)').text());
                var latLng_array = show_lat_lng.val().split(',');
                $('#comp_phone').val(this_item.find('td:nth-child(6)').text());
                $('html,body').animate({ scrollTop: $('.main_content').offset().top - 40 }, 'fast', function(){
                    g_Map.gmap3(
                        {
                            action:'clear',
                            name:'marker'
                        },
                        {
                            action: 'addMarker',
                            latLng: latLng_array,
                            map: { center:true, zoom: 18 },
                            marker: {
                                options: { draggable: true },
                                events: {
                                    dragend: function(marker) {
                                        marker_callback(marker);
										g_Map.gmap3('get').panTo(marker.position);
                                    }
                                },
                                callback: function() {
                                    marker_message();
                                }
                            }
                        }
                    );
                });
            });
        },
        show_location: function() {
            //* show location
			location_table.on('click','.show_on_map',function(){
                clear_search();
                clear_form();
                //* Get lat,lng values from table
                var this_item = $(this).closest('tr');
                var show_lat_lng = $('#comp_lat_lng').val(this_item.find('td:nth-child(5)').text());
                var latLng_array = show_lat_lng.val().split(',');
                $('html,body').animate({ scrollTop: $('.main_content').offset().top - 40 }, 'fast', function(){
                    g_Map.gmap3(
                        {
                            action: 'clear',
                            name:'marker'
                        },
                        {   action: 'addMarker',
                            latLng: latLng_array,
                            map: { center:true, zoom: 18 }
                        }
                    );
                });
            });
        },
        drop_marker_search: function() {
            //* drop marker on map after location search
			var search_query = $('#gmap_search input').val();
            if(search_query != ''){
                g_Map.gmap3(
                    {
                        action: 'clear',
                        name: 'marker'
                    },
                    {   action: 'addMarker',
                        address: search_query,
                        map: {
                            center:true,
                            zoom: 15
                        },
                        marker: {
                            options: { draggable: true },
                            events: {
                                dragend: function(marker){
                                    marker_callback(marker);
									g_Map.gmap3('get').panTo(marker.position);
                                }
                            },
                            callback: function(marker){
                                if(marker){
                                    location_add_form.slideDown('normal');
                                    marker_callback(marker);
                                    marker_message();
                                } else {
                                    clear_form();
                                    $.sticky("No adress found. Try again.", {autoclose : 5000, position: "top-center" });
                                }
                            }
                        }
                    }
                )
            } else {
				//* if location name not entered show message
                clear_form();
                $.sticky("Please Enter Location Name.", {autoclose : 5000, position: "top-center" });
            }
        }
    };