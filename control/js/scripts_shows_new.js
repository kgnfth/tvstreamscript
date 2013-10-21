jQuery(document).ready(function(){
	var button = jQuery('#uploader');
	new AjaxUpload(button,{
		action: 'ajax/shows_thumbnail_upload.php', 
		name: 'userfile',
		onSubmit : function(file, ext){	
			this.disable();
		},
		onComplete: function(file, response){
			response = eval('(' + response + ')');
			if (response.status == 0){
				alert(response.message);
			} else {
				jQuery('#show_thumbnail').attr("src",baseurl+"/thumbs/"+response.message);
				jQuery('#thumbnail_hidden').val(response.message);
			}
		}
	});
});