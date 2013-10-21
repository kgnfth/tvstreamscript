$(document).ready(function() {
	$("input[type=checkbox].switch").each(function() {
	    $(this).before(
	    		'<span class="switch">' +
	    		'<span class="mask" /><span class="background" />' +
	    		'</span>'
	    );
	
	    // Hide checkbox
	    $(this).hide();
	    
	    // Set inital state
	    if (!$(this)[0].checked) {
	    	$(this).prev().find(".background").css({left: "-56px"});
	    }
	});
	
	$("span.switch").click(function() {
	    if ($(this).next()[0].checked) {
	    	$(this).find(".background").animate({left: "-56px"}, 200);
	    } else {
	      $(this).find(".background").animate({left: "0px"}, 200);
	    }
	
	    $(this).next()[0].checked = !$(this).next()[0].checked;
	});
});