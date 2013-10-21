// JavaScript Document
/*
Author: Bagga Creatives Australia
Author URI: http://www.bcreatives.com.au/
*/

(function($) {
    $(document).ready(function() {
        
        var inputField = "search-query";
        var inputWidth = 0;
        
        var absPath = "";

        $('input#'+inputField).result(function(event, data, formatted) {
            $('#result').html( !data ? "No match!" : "Selected: " + formatted);
        }).blur(function(){    
    
        });
        
        $(function() {        
            function format(mail) {
                return "<a href='"+mail.permalink+"'><img src='" + mail.image + "' /><span class='title'>" + mail.title +"</span><br /><span class='meta' style='padding:0px !important; margin:0px !important;'>" + mail.meta + "</span></a>";
            }
            
            function link(mail) {
                return mail.permalink
            }

            function title(mail) {
                return mail.title
            }
            
            inputWidth = 300;
                        
            $.ajaxSetup({ type: "post" });
            $('input#'+inputField).autocomplete(baseurl + "/ajax/search.php", {
                extraParams: {verifiedCheck: ""},
                minChars: 2,
                //you may set your own width here
                width: inputWidth,
                max: 5,
                scroll: false,
                dataType: "json",
                parse: function(data) {
                    return $.map(data, function(row) {
                        return {
                            data: row,
                            value: row.title,
                            result: $('input#'+inputField).val()
                        }
                    });
                },

                formatItem: function(item) {
                    return format(item);
                }
                }).result(function(e, item) {
                    $('input#'+inputField).val(title(item));
                    location.href = link(item);
                });
                

        });
                
    });
})(jQuery);