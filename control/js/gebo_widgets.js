/* [ ---- Gebo Admin Panel - widgets ---- ] */

    $(document).ready(function() {
		gebo_widgets.upload();
        //* autosize textarea
        $('.auto_expand').autosize();
    });

    gebo_widgets = {
        upload: function() {
            function w_upload() {
                $("#widget_upload").pluploadQueue({
                    // General settings
                    runtimes : 'html5,flash,silverlight',
                    url : 'lib/plupload/examples/upload.php',
                    max_file_size : '10mb',
                    chunk_size : '1mb',
                    unique_names : true,
            
                    // Specify what files to browse for
                    filters : [
                        {title : "Image files", extensions : "jpg,gif,png"},
                        {title : "Zip files", extensions : "zip"}
                    ],
            
                    // Flash settings
                    flash_swf_url : 'lib/plupload/js/plupload.flash.swf',
            
                    // Silverlight settings
                    silverlight_xap_url : 'lib/plupload/js/plupload.silverlight.xap'
                });
                
            }
            w_upload();
            
            $('#upload_refresh').css({'cursor':'pointer','margin-left':'10px'}).click(function(e) {
                $('#widget_upload').pluploadQueue().destroy();
                w_upload();
                return false;
            });
            
        }
    };
