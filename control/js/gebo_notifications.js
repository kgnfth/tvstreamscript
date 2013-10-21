/* [ ---- Gebo Admin Panel - notifications ---- ] */

	$(document).ready(function() {
		gebo_notifications.sticky();
	});

    gebo_notifications = {
        sticky: function() {
            $('#sticky_a').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;.", {autoclose : 5000, position: "top-right", type: "st-error" });
            });
            $('#sticky_b').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right", type: "st-success" });
            });
            $('#sticky_c').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right", type: "st-info" });
            });
            $('#sticky_d').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right" });
            });
            $('#sticky_d_st').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : false, position: "top-right" });
            });
            $('#sticky_e').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-right" });
            });
            $('#sticky_f').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-center" });
            });
            $('#sticky_g').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "top-left" });
            });
            $('#sticky_h').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "bottom-right" });
            });
            $('#sticky_i').click(function(e){
                $.sticky("Lorem ipsum dolor sit&hellip;", {autoclose : 5000, position: "bottom-left" });
            });
        }
    };