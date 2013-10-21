function startTagHandler(tags){
    if (!tags){
        tags = [];
    }
    $("#array_tag_handler").tagHandler({
        assignedTags: tags,
        autocomplete: true,
        afterAdd: function(){
            $('#tags').val($("#array_tag_handler").tagHandler("getSerializedTags"));
        },
        afterDelete: function(){
            $('#tags').val($("#array_tag_handler").tagHandler("getSerializedTags"));
        }
    });
}

jQuery(document).ready(function(){
    var button = jQuery('#uploader');
    new AjaxUpload(button,{
        action: baseurl + '/plugins/blogger/ajax/thumbnail_upload.php', 
        name: 'userfile',
        onSubmit : function(file, ext){    
            this.disable();
        },
        onComplete: function(file, response){
            response = eval('(' + response + ')');
            if (response.status == 0){
                alert(response.message);
            } else {
                jQuery('#post_thumbnail').attr("src",baseurl+"/thumbs/"+response.message);
                jQuery('#thumbnail_hidden').val(response.message);
            }
        }
    });
    
    var tags = jQuery('#tags').val().split(",");
    if (!tags || tags == ''){
        tags = [];
    }
    
    
    gebo_wysiwg.init();
    startTagHandler(tags);
});