var oCache = {
    iCacheLower: -1
};
 
function fnSetKey( aoData, sKey, mValue ){
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )   {
        if ( aoData[i].name == sKey ) {
            aoData[i].value = mValue;
        }
    }
}
 
function fnGetKey( aoData, sKey ){
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ ) {
        if ( aoData[i].name == sKey ) {
            return aoData[i].value;
        }
    }
    return null;
}


function deleteUser(user_id){
	$('#user_checkbox_'+user_id).attr("checked",true);
	$(".delete_rows_dt").trigger("click");
}
 
function fnDataTablesPipeline ( sSource, aoData, fnCallback ) {
    var iPipe = 5; /* Ajust the pipe size */
     
    var bNeedServer = false;
    var sEcho = fnGetKey(aoData, "sEcho");
    var iRequestStart = fnGetKey(aoData, "iDisplayStart");
    var iRequestLength = fnGetKey(aoData, "iDisplayLength");
    var iRequestEnd = iRequestStart + iRequestLength;
    oCache.iDisplayStart = iRequestStart;
     
    /* outside pipeline? */
    if ( oCache.iCacheLower < 0 || iRequestStart < oCache.iCacheLower || iRequestEnd > oCache.iCacheUpper )   {
        bNeedServer = true;
    }
     
    /* sorting etc changed? */
    if ( oCache.lastRequest && !bNeedServer ) {
        for( var i=0, iLen=aoData.length ; i<iLen ; i++ )  {
            if ( aoData[i].name != "iDisplayStart" && aoData[i].name != "iDisplayLength" && aoData[i].name != "sEcho" ) {
                if ( aoData[i].value != oCache.lastRequest[i].value ) {
                    bNeedServer = true;
                    break;
                }
            }
        }
    }
     
    /* Store the request for checking next time around */
    oCache.lastRequest = aoData.slice();
     
    if ( bNeedServer ) {
        if ( iRequestStart < oCache.iCacheLower ){
            iRequestStart = iRequestStart - (iRequestLength*(iPipe-1));
            if ( iRequestStart < 0 ){
                iRequestStart = 0;
            }
        }
         
        oCache.iCacheLower = iRequestStart;
        oCache.iCacheUpper = iRequestStart + (iRequestLength * iPipe);
        oCache.iDisplayLength = fnGetKey( aoData, "iDisplayLength" );
        fnSetKey( aoData, "iDisplayStart", iRequestStart );
        fnSetKey( aoData, "iDisplayLength", iRequestLength*iPipe );
         
        $.getJSON( sSource, aoData, function (json) { 
            /* Callback processing */
            oCache.lastJson = jQuery.extend(true, {}, json);
             
            if ( oCache.iCacheLower != oCache.iDisplayStart ){
                json.aaData.splice( 0, oCache.iDisplayStart-oCache.iCacheLower );
            }
            json.aaData.splice( oCache.iDisplayLength, json.aaData.length );
            
            setTimeout(function(){ gebo_colorbox_single.init(); },500);
            
            fnCallback(json)
            
        } );
    } else {
        json = jQuery.extend(true, {}, oCache.lastJson);
        json.sEcho = sEcho; /* Update the echo for each response */
        json.aaData.splice( 0, iRequestStart-oCache.iCacheLower );
        json.aaData.splice( iRequestLength, json.aaData.length );
        
        setTimeout(function(){ gebo_colorbox_single.init(); },500);
        
        fnCallback(json);
        return;
    }
}

//* select all rows
gebo_select_row = {
	init: function() {
		$('.select_rows').click(function () {
			var tableid = $(this).data('tableid');
            $('#'+tableid).find('input[name=row_sel]').attr('checked', this.checked);
		});
	}
};
	

//* delete rows
gebo_delete_rows = {
    dt: function() {
		$(".delete_rows_dt").on('click',function (e) {
			e.preventDefault();
			var tableid = $(this).data('tableid'),
                oTable = $('#'+tableid).dataTable();
            if($('.row_sel:checked', '#'+tableid).length) {
                $.colorbox({
                    initialHeight: '0',
                    initialWidth: '0',
                    href: "#confirm_dialog",
                    inline: true,
                    opacity: '0.3',
                    onComplete: function(){
                        $('.confirm_yes').click(function(e){
                            e.preventDefault();
                            
                            var to_delete = [];
                            $('input[name=row_sel]:checked').each(function(){
                        		to_delete.push($(this).val());
                        	});
                        	
                        	if (to_delete.length>0){
                        		$.post('ajax/users_delete.php',{
                        			user_ids: to_delete.join(',')
                        		},function(data){
                                    $('input[name=row_sel]:checked', oTable.fnGetNodes()).closest('tr').fadeTo(300, 0, function () {
                                        $(this).remove();
                                        
        								//oTable.fnDeleteRow( this );
                                        $('.select_rows','#'+tableid).attr('checked',false);
                                    });
                                    $.colorbox.close();                        			
                        		});
                        	}
                        });
                        $('.confirm_no').click(function(e){
                            e.preventDefault();
                            $.colorbox.close(); 
                        });
                    }
                });
            }    
		});
	}
};
	
//* gallery table view
gebo_galery_table = {
    init: function() {
       $('#dt_gal').dataTable({
			"sDom": "<'row'<'span6'<'dt_actions'>l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
			"sPaginationType": "bootstrap",
			"iDisplayLength": 50,
			"bServerSide": true,
			"bProcessing": true,
			"bStateSave": false,
            "aaSorting": [],
            "sAjaxSource": "ajax/users_paginate.php",
			"fnServerParams": function ( aoData ) {
				
			},			
			"aoColumns": [
				{ "bSortable": false },
				{ "bSortable": false },
				{ "sType": "string" },
				{ "sType": "string" },
				{ "sType": "string" },
				{ "sType": "string" },
				{ "bSortable": false }
			],
			"fnServerData": fnDataTablesPipeline
		});
       $('.dt_actions').html($('.dt_gal_actions').html());
    }
};

$(document).ready(function() {
    gebo_galery_table.init();
    //* actions for tables, datatables
    gebo_select_row.init();
	gebo_delete_rows.dt();
});