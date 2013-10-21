jQuery(document).ready(function(){
	jQuery("#is_smtp").click(function(){
		if (jQuery(this).attr("checked")){
			jQuery('#smtp_details').show();
		} else {
			jQuery('#smtp_details').hide();
		}
	});
});