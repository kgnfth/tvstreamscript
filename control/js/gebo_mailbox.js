/* [ ---- Gebo Admin Panel - mailbox ---- ] */

    $(document).ready(function() {
		//* make row clickable
		gebo_mailbox.msg_rowLink();
       
	    //* new message
        gebo_mailbox.new_message();
		//* inbox
        gebo_mailbox.inbox();
		//* outbox
        gebo_mailbox.outbox();
        //* outbox
        gebo_mailbox.trash();
		
		//* defaults actions: selecting rows, stars
		gebo_mailbox.actions();
    });

    gebo_mailbox = {
        inbox: function() {
            $('#dt_inbox').dataTable({
				"oLanguage": {
					"sLengthMenu": "_MENU_ messages",
					"sZeroRecords": "No messages to display"
				},
				"sDom": "<'row'<'span6'<'dt_actions'>l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "iDisplayLength": 25,
				"aaSorting": [[ 5, "desc" ]],
				"aoColumns": [
					{ "bSortable": false, 'sWidth': '13px' },
					{ "bSortable": false, 'sWidth': '16px' },
					{ "bSortable": false, 'sWidth': '16px' },
					{ "sType": "string" },
					{ "sType": "string" },
					{ "sType": "eu_date" },
                    { "sType": "formatted-num" },
					{ "bSortable": false, 'sWidth': '16px' }
				]
            });
			
			//* copy actions buttons to datatable
			$('#dt_inbox_wrapper .dt_actions').html($('.dt_inbox_actions').html());
			//* add tootlips for buttons
			$('#dt_inbox_wrapper .dt_actions a').addClass('ttip_t');
			//* reinitialize tooltips
			gebo_tips.init();
        },
        outbox: function() {
			$('#dt_outbox').dataTable({
				"oLanguage": {
					"sLengthMenu": "_MENU_ messages",
					"sZeroRecords": "No messages to display"
				},
				"sDom": "<'row'<'span6'<'dt_actions'>l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
				"sPaginationType": "bootstrap",
				"iDisplayLength": 25,
				"aaSorting": [[ 5, "desc" ]],
				"aoColumns": [
					{ "bSortable": false, 'sWidth': '13px' },
					{ "bSortable": false, 'sWidth': '16px' },
					{ "bSortable": false, 'sWidth': '16px' },
					{ "sType": "string" },
					{ "sType": "string" },
					{ "sType": "eu_date" },
                    { "sType": "formatted-num" },
					{ "bSortable": false, 'sWidth': '16px' }
				]
			});
			
			//* copy actions buttons to datatable
			$('#dt_outbox_wrapper .dt_actions').html($('.dt_outbox_actions').html());
			//* add tootlips for buttons
			$('#dt_outbox_wrapper .dt_actions a').addClass('ttip_t');
			//* reinitialize tooltips
			gebo_tips.init();
        },
        trash: function() {
			$('#dt_trash').dataTable({
				"oLanguage": {
					"sLengthMenu": "_MENU_ messages",
					"sZeroRecords": "No messages to display"
				},
				"sDom": "<'row'<'span6'<'dt_actions'>l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
				"sPaginationType": "bootstrap",
				"iDisplayLength": 25,
				"aaSorting": [[ 5, "desc" ]],
				"aoColumns": [
					{ "bSortable": false, 'sWidth': '13px' },
					{ "bSortable": false, 'sWidth': '16px' },
					{ "bSortable": false, 'sWidth': '16px' },
					{ "sType": "string" },
					{ "sType": "string" },
					{ "sType": "eu_date" },
                    { "sType": "formatted-num" },
					{ "bSortable": false, 'sWidth': '16px' }
				]
			});
			
			//* copy actions buttons to datatable
			$('#dt_trash_wrapper .dt_actions').html($('.dt_trash_actions').html());
			//* add tootlips for buttons
			$('#dt_trash_wrapper .dt_actions a').addClass('ttip_t');
			//* reinitialize tooltips
			gebo_tips.init();
        },
		actions: function() {
			$('.table').on('click', '.mbox_star', function(){
				$(this).toggleClass('splashy-star_empty splashy-star_full');
			});
			
			$('.table').on('mouseenter','.starSelect', function(){
				if($(this).children('i.splashy-star_empty').length) {
					$(this).children('i.mbox_star').css('visibility','visible');
				}
			}).on('mouseleave', '.starSelect', function(){
				if($(this).children('i.splashy-star_empty').length) {
					$(this).children('i.mbox_star').css('visibility','');
				}
			});
			
			$('.table').on('click', '.select_msgs', function () {
				var tableid = $(this).data('tableid');
				$('#'+tableid).find('input[name=msg_sel]').attr('checked', this.checked).closest('tr').addClass('rowChecked')
				if($(this).is(':checked')) {
					$('#'+tableid).find('input[name=msg_sel]').closest('tr').addClass('rowChecked')
				} else {
					$('#'+tableid).find('input[name=msg_sel]').closest('tr').removeClass('rowChecked')
				}
			});
			
			$('input[name=msg_sel]').on('click',function() {
				if($(this).is(':checked')) {
					$(this).closest('tr').addClass('rowChecked')
				} else {
					$(this).closest('tr').removeClass('rowChecked')
				}
			});
            
            $(".dt_actions").on('click', '.delete_msg', function (e) {
				e.preventDefault();
				var tableid = $(this).data('tableid'),
                    oTable = $('#'+tableid).dataTable();
                if($('input[name=msg_sel]:checked', '#'+tableid).length) {
                    $.colorbox({
                        initialHeight: '0',
                        initialWidth: '0',
                        href: "#confirm_dialog",
                        inline: true,
                        opacity: '0.3',
                        onComplete: function(){
                            $('.confirm_yes').click(function(e){
                                e.preventDefault();
                                $('input[name=msg_sel]:checked', oTable.fnGetNodes()).closest('tr').fadeTo(300, 0, function () {
                                    $(this).remove();
									oTable.fnDeleteRow( this );
                                    $('.select_msgs','#'+tableid).attr('checked',false);
                                });
                                $.colorbox.close();
                            });
                            $('.confirm_no').click(function(e){
                                e.preventDefault();
                                $.colorbox.close(); 
                            });
                        }
                    });
                } else {
					$.sticky("Please select message(s) to delete.", {autoclose : 5000, position: "top-center", type: "st-info" });
				}
			});
		},
		msg_rowLink: function() {
			$('*[data-msg_rowlink]').each(function () {
				var target = $(this).attr('data-msg_rowlink');
				
				(this.nodeName == 'tr' ? $(this) : $(this).find('tr:has(td)')).each(function() {
					var link = $(this).find(target).first();
					if (!link.length) return;
					
					var href = link.attr('href');
		
					$(this).find('td').not('.nohref').click(function() {
						//* coment $(link).tab('show') and uncoment window.location = href to open message in new window
						//window.location = href;
						$(link).tab('show');
						$('.mbox .nav-tabs > .active').removeClass('active');
					});
		
					link.replaceWith(link.html());
				});
			});
		},
        new_message: function() {
			//* recipients
            $("#mail_recipients").tagHandler({
				availableTags: [ 'email1@example.com', 'email2@example.com', 'email3@example.com', 'email4@example.com', 'email5@example.com' ],
				autocomplete: true
			});
            //* autosize textarea
            $('.auto_expand').autosize();
            //* attachments
            $("#mail_attachments").pluploadQueue({
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
            
            //* hide upload button
            $('.plupload_start').hide();
           
            //* hide multiupload and show after add_files link
            $(".mail_uploader").hide();
            $('.add_files').click(function(e){
                e.preventDefault();
                $(".mail_uploader").show();
                $(this).remove();
            })
            
            $('#new_message_form').submit(function(e) {
                var uploader = $('#mail_attachments').pluploadQueue();
        
                // Files in queue upload them first
                if (uploader.files.length > 0) {
                    // When all files are uploaded submit form
                    uploader.bind('StateChanged', function() {
                        if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                            //* uncoment next line to submit form after all files are uploaded
                            //$('#new_message_form')[0].submit();
                        }
                    });
                    uploader.start();
                }
                return false;
            });
            
        }
    };
