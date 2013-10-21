$(document).ready(function() {
	user_select.init();
});

user_select = {
	init: function() {
		var tags = getHidden();
		
		
		$("#array_tag_handler").tagHandler({
			assignedTags: tags,
			availableTags: [ ],
			getURL: "ajax/users_autocomplete.php",
			autocomplete: true,
			initLoad: false,
			onAdd: function(tag){ setTimeout(function(){ updateHidden(); },100) },
			onDelete: function(tag){ setTimeout(function(){ updateHidden(); },100) }
		});
	}
};

function getHidden(){
	var tags = jQuery('#user_list').val();
	
	if (!tags || tags==''){
		return [];
	} else {
		return tags.split(",");
	}
}

function updateHidden(){
	tags = [];
	jQuery('li.tagItem').each(function(){
		tags.push(jQuery(this).text());
	});
	
	jQuery('#user_list').val(tags.join(","));
}
