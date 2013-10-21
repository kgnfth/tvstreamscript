jQuery(document).ready(function(){
	var button = jQuery('#upload_button');
	new AjaxUpload(button,{
		action: 'ajax/episode_thumbnail_upload.php', 
		name: 'userfile',
		onSubmit : function(file, ext){	
			this.disable();
		},
		onComplete: function(file, response){
			response = eval('(' + response + ')');
			if (response.status == 0){
				alert(response.message);
			} else {
				jQuery('#episode_thumbnail').attr("src",baseurl+"/thumbs/"+response.message);
				jQuery('#thumbnail_hidden').val(response.message);
			}
		}
	});
});
