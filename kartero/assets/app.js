jQuery.fn.exists = function(){return this.length>0;}
var data_table;
var map;
var primary_map;
var marker;
var dashboard_task_handle;
var dashboard_agent_handle;
var map_location;
var dashboard_run_silent=2;

$(document).ready(function(){
		
    $("ul#tabs li").click(function(e){
        if (!$(this).hasClass("active")) {
            var tabNum = $(this).index();
            var nthChild = tabNum+1;
            /*$("ul#tabs li.active").removeClass("active");
            $(this).addClass("active");*/
            
            var parent=$(this).parent().parent();
            //dump(parent);
            
            parent.find("ul#tabs li.active").removeClass("active");
            $(this).addClass("active");
            
            parent.find("ul#tab li.active").removeClass("active");
            parent.find("ul#tab li:nth-child("+nthChild+")").addClass("active");
            
            /*$("ul#tab li.active").removeClass("active");
            $("ul#tab li:nth-child("+nthChild+")").addClass("active");*/
        }
    });    
    
     $( document ).on( "click", ".show-forgot-pass", function() {
    	$("#frm").hide();    	
    	$("#frm-forgotpass").show();    	
    });
    $( document ).on( "click", ".show-login", function() {
    	$("#frm-forgotpass").hide(); 
    	$("#frm").show();    	    	
    });
    
    $( document ).on( "click", ".menu-pop", function() {
    	$(".popup_menu.nav").toggle();
    	$(".popup_menu.notification").hide();
    });
    
    $( document ).on( "click", ".menu-notification", function() {
    	$(".popup_menu.notification").toggle();
    	$(".popup_menu.nav").hide();
    });
    
    $('body').click(function(e) {
      if ($(e.target).closest('.popup_menu,.menu-pop,.menu-notification').length === 0) {
          $(".popup_menu.nav").hide();
          $(".popup_menu.notification").hide();
      }
    });       
    
        
    $( document ).on( "click", ".close-modal", function() {    	
    	var id=$(this).data("id");
    	$(id).modal('hide');
    });
        
    $( document ).on( "click", ".show-lang-list", function() {    	
    	$(".lang-wrapper").slideToggle( "fast" );
    });
        
    if ( $(".mobile_inputs").exists()){
      $(".mobile_inputs").intlTelInput({      
        autoPlaceholder: false,		
        defaultCountry: default_country,            
        autoHideDialCode:true,    
        nationalMode:false,
        autoFormat:false,
        utilsScript: site_url+"/assets/intel/lib/libphonenumber/build/utils.js"
      });
    }	     
    	       
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      var params= $("#frm").serialize();
	      var action = $("#frm #action").val();
	      var button = $('#frm button[type="submit"]');
	      dump(button);
	      callAjax(action,params,button);
	      return false;
	    }  
	});    
	
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm-forgotpass',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      var params= $("#frm-forgotpass").serialize();
	      var action = $("#frm-forgotpass #action").val();
	      var button = $('#frm-forgotpass button[type="submit"]');
	      dump(button);
	      callAjax(action,params,button);
	      return false;
	    }  
	});    
	
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm_task',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      var params= $("#frm_task").serialize();
	      var action = $("#frm_task #action").val();
	      var button = $('#frm_task button[type="submit"]');
	      dump(button);
	      callAjax(action,params,button);
	      return false;
	    }  
	});    	
	
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm_changes_status',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      var params= $("#frm_changes_status").serialize();
	      var action = $("#frm_changes_status #action").val();
	      var button = $('#frm_changes_status button[type="submit"]');
	      dump(button);
	      callAjax(action,params,button);
	      return false;
	    }  
	});    	
	
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm_notification_tpl',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      var params= $("#frm_notification_tpl").serialize();
	      var action = $("#frm_notification_tpl #action").val();
	      var button = $('#frm_notification_tpl button[type="submit"]');
	      dump(button);
	      callAjax(action,params,button);
	      return false;
	    }  
	});    	
	
	if( $(".chosen").exists() ) {
       $(".chosen").chosen({
       	  allow_single_deselect:true,
       	  width: '100%'
       });     
    } 
    
    if ( $(".frm_table").exists() ){
    	initTable();
    }
		
}); /*end docu*/

function empty(data)
{
	if (typeof data === "undefined" || data==null || data=="" ) { 
		return true;
	}
	return false;
}

function dump(data)
{
	console.debug(data);
}

var ajax_request;

/*mycall*/
function callAjax(action,params,button)
{
	dump(ajax_url+"/"+action+"?"+params);
	
	params+="&language="+language;
	
	ajax_request = $.ajax({
		url: ajax_url+"/"+action, 
		data: params,
		type: 'post',                  
		//async: false,
		dataType: 'json',
		timeout: 6000,		
	 beforeSend: function() {
	 	dump("before=>");
	 	dump( ajax_request );
	 	if(ajax_request != null) {
	 	   ajax_request.abort();
	 	   dump("ajax abort");
	 	   busy(false,button);	 	   
	 	} else {
	 	   busy(true,button);	 	  
	 	}
	 },
	 complete: function(data) {					
		ajax_request= (function () { return; })();
		dump( 'Completed');
		dump(ajax_request);
		busy(false,button);	
	 },
	 success: function (data) {	  
	 	dump(data);
	 	if (data.code==1){
	 		
	 		switch (action)
	 		{
	 			case "retryAutoAssign":
	 			break;
	 			
	 			case "login":	 			
	 			window.location.href =  data.details;
	 			break;
	 				 			
	 			case "createTeam":
	 			case "addAgent":	 			
	 			$("."+data.details).modal('hide');
	 			nAlert(data.msg,"success");
	 			tableReload();
	 			break;
	 			
	 			case "getTeam":
	 			  $("#team_name").val( data.details.team_name );
	 			  $("#location_accuracy").val( data.details.location_accuracy );
	 			  $("#status").val( data.details.status );
	 			  dump(data.details.team_member);
	 			  $("#team_member").val( data.details.team_member );	
	 			  $('#team_member').trigger("chosen:updated"); 
	 			break;
	 			
	 			case "deleteRecords":
	 			tableReload();
	 			break;
	 			
	 			case "getDriverInfo":
	 			$("#first_name").val(  data.details.first_name );
	 			$("#last_name").val(  data.details.last_name );
	 			$("#username").val(  data.details.username );
	 			$("#email").val(  data.details.email );
	 			$("#phone").val(  data.details.phone );
	 			$(".team_id_driver_new").val(  data.details.team_id );
	 			
	 			//$("input[name='team_id'][value='"+data.details.team_id+"']").prop('selected', true);
	 			
	 			$("#transport_type_id").val(  data.details.transport_type_id );
	 			$("#transport_description").val(  data.details.transport_description );
	 			$("#licence_plate").val(  data.details.licence_plate );
	 			$("#color").val(  data.details.color );
	 			$("#status").val(  data.details.status );
	 			$("#password").removeAttr("required");
	 			break;
	 				 			
	 			case "addTask":
	 			nAlert(data.msg,"success");	 			
	 			$(".new-task").modal('hide');

	 			if ( $("body.dashboard").exists() ){	 				
	 			} else {
	 				window.location.href = home_url;
	 				return;
	 			}

	 			loadDashboardTask(); 			
	 			break;
	 			
	 			case "getDashboardTask":
	 			/*$(".task_"+data.msg).html( data.details.html );
	 			$(".task-total-"+data.msg).html( data.details.total );*/
	 			
	 			$.each( data.details , function( key, val ) {     	 				
	 				if ( !empty(val)){	 					
	 					$(".task_"+key).html( val.html );
	 			        $(".task-total-"+key).html( val.total );
	 				} else {	 					
	 				   $(".task_"+key).html( '');
	 			       $(".task-total-"+key).html( "0" );
	 				}
	 			});
	 			
	 			
	 			dump( "coordinates=>" + data.msg.length);
	 			plotMainMap( data.msg );
	 			
	 			break;
	 			
	 			case "changeStatus":
	 			case "assignTask":
	 			nAlert(data.msg,"success");	 			
	 			$("."+data.details).modal('hide');
	 			
	 			loadDashboardTask();
	 			break;
	 				 			
	 			case "getTaskDetails":
	 			
	 			if ( data.details.status_raw=="successful"){
	 				$(".action-1").hide();
	 				$(".action-2").show();
	 			} else {
	 				$(".action-1").show();
	 				$(".action-2").hide();
	 			}
	 			
	 			$(".task_status").html( "<span class=\"rounded tag "+ data.details.status_raw+"\" >"+data.details.status+"</spa>"  );
	 			$(".delivery_date").html( data.details.delivery_date);
	 			$(".customer_name").html( data.details.customer_name );
	 			$(".contact_number").html( data.details.contact_number);
	 			$(".email_address").html( data.details.email_address);
	 			$(".delivery_address").html( data.details.delivery_address);
	 			$(".team_name").html( data.details.team_name);
	 			$(".driver_name").html( data.details.driver_name);
	 			$(".task_description").html( data.details.task_description);
	 			$(".transaction_type").html(data.details.trans_type );
	 			
	 			$(".assign-agent").attr("data-id", data.details.task_id );
	 			$(".edit-task").attr("data-id", data.details.task_id );
	 			$(".change-status").attr("data-id", data.details.task_id );	 			
	 			$(".delete-task").attr("data-id", data.details.task_id );
	 			
	 			if (data.details.history_data.length>0){
	 				history_html = tplTaskHistory( data.details.history_data );
	 				$("#task-history").html( history_html );	 				
	 			} else {
	 				dump('no history');
	 				$("#task-history").html("<p class=\"alert alert-danger\">"+jslang.no_history+"</p>");
	 			}
	 			
	 			if ( data.details.driver_id>0){
	 				dump('has driver assign');
	 				$(".re_assign_agent").html( jslang.re_assign_agent );
	 			} else {
	 				$(".re_assign_agent").html( jslang.assign_agent );
	 			}
	 			
	 			/*show and hide order no*/
	 			if ( data.details.order_id>0){
	 				$("#order-id-wrap").show();
	 				$(".order-id").html( data.details.order_id );
	 				$(".merchant_name").html( data.details.merchant_name );
	 			} else {
	 				$("#order-id-wrap").hide();
	 			}
	 			
	 			/*show order details*/
	 			if (!empty(data.details.order_details)){
	 			   $("#order-details").html( data.details.order_details);
	 			} else {
	 				$("#order-details").html("<p class=\"alert alert-danger\">"+jslang.not_available+"</p>");
	 			}
	 			
	 			break;
	 			
	 			case "deleteTask":
	 			$(".task-details-modal").modal('hide');
	 			loadDashboardTask();
	 			break;	 			
	 			
	 			case "getTaskInfo":
	 			$("#task_description").val( data.details.task_description );
	 			
	 			//$("input[name='trans_type'][value='"+data.details.trans_type+"']").attr("checked", true);
	 			$("input[name='trans_type'][value='"+data.details.trans_type+"']").prop('checked', true);
	 			
	 			dump('dxxx');
	 			switchTransactionType( data.details.trans_type );
	 			
	 			$("#contact_number").val( data.details.contact_number );
	 			$("#email_address").val( data.details.email_address );
	 			$("#customer_name").val( data.details.customer_name );
	 			$("#delivery_date").val( data.details.delivery_date );
	 			$("#delivery_address").val( data.details.delivery_address );
	 			
	 			$("#task_lat").val( data.details.task_lat );
	 			$("#task_lng").val( data.details.task_lng );
	 			
	 			if ( data.details.team_id > 0){
	 				dump('has team id');
	 				$("#team_id").val( data.details.team_id );	 					 				
	 				swicthDriver( data.details.team_id  , data.details.driver_id);
	 			} 
	 			
	 			if ( !empty( data.details.task_lat )){
	 				dump('has lat');
	 				SetMapPlot( data.details.task_lat , data.details.task_lng );
	 			} else {
	 				dump('no lat');
	 		 	    setMarkerByAddress( $(".geocomplete").val() );
	 			}
	 			
	 			
	 			break;	 	
	 			
	 			
	 			case "getDriverDetails":
	 				 			  
	 			  $(".driver_name").html( data.details.info.name );
	 			  $(".phone").html( data.details.info.phone );
	 			  $(".email").html( data.details.info.email );
	 			  $(".team_name").html( data.details.info.team_name );
	 			  $(".transport_type_id").html( data.details.info.transport_type_id );
	 			  $(".licence_plate").html( data.details.info.licence_plate );
	 			  
	 			  if ( data.details.task.length>0){
	 			  	  var html_task='';
	 			  	  $.each( data.details.task , function( tkey, tval ) {      
	 			  	  	  html_task+=formatTableRow(tval);
	 			  	  });
	 			  	  dump(html_task);
	 			  	  $(".driver-task-list tbody").html(html_task);
	 			  } else {
	 			  	  $(".driver-task-list tbody tr").remove();  
	 			  }
	 			  
	 			break;
	 				 			
	 			
	 			case "GetNotificationTPL":	 			
	 			$.each( data.details , function( key, value ) {  	 				
	 				$("#"+key).val( value );
	 			});
	 			break;

	 			case "SaveNotificationTemplate":
	 			$(".notification-pop").modal('hide');
	 			break;
	 			
	 			
	 			case "chartReports":
	 			$(".report_div").html(data.details.charts);
	 			$(".table_charts").html(data.details.table);
	 			break;
	 			
	 			case "forgotPassword":	 			
	 			window.location.href = data.details;	 			
	 			break;
	 			
	 			case "resetPassword":
	 			nAlert(data.msg,"success");
	 			window.location.href = data.details;	 			
	 			break;
	 			
	 			case "sendPush":
	 			$(".push-form-modal").modal('hide');
	 			nAlert(data.msg,"success");
	 			break;
	 			 			
	 			default:
	 			nAlert(data.msg,"success");
	 			break;
	 		}
	 		
	 	} else {
	 		
	 		// failed mycon
	 		switch ( action )
	 		{	 	
	 			case "getDashboardTask":	 
	 			$(".task_"+data.details).html( '' );
	 			$(".task-total-"+data.details).html( "0" );
	 			break;
	 			
	 			case "getTaskDetails":	 			
	 			//$(".task-details-modal").modal('hide');
	 			setTimeout('$(".task-details-modal").modal("hide")', 100);
	 			nAlert(data.msg,"warning");
	 			break;
	 			
	 			//silent
	 			case "loadAgentDashboard":	
	 			case "break;":
	 			break;
	 			
	 			case "getDriverDetails":
	 			$(".driver-details-moda").modal("hide");
	 			break;
	 			
	 			default :
	 			nAlert(data.msg,"warning");
	 			break;
	 		}
	 			 		
	 	}
	 },
	 error: function (request,error) {	    
	 	 	 		
	 }
    });       
}

function busy(e,button)
{
	if (e) {
        $('body').css('cursor', 'wait');	
    } else $('body').css('cursor', 'auto');        

    if (e) {    
    	dump('busy loading');
        /*NProgress.set(0.0);		
        NProgress.inc(); */
        $(".main-preloader").show();
        if (!empty(button)){           
	 	   button.css({ 'pointer-events' : 'none' });	 	   	  
	 	}	 		 	
    } else {    	
    	dump('done loading');
    	$(".main-preloader").hide();
    	//NProgress.done();    	
    	if (!empty(button)){
	 	   button.css({ 'pointer-events' : 'auto' });	 	   	  
	 	}
    }       
}

function nAlert(msg,alert_type)
{
	var n = noty({
		 text: msg,
		 type        : alert_type ,		 
		 theme       : 'relax',
		 layout      : 'topCenter',		 
		 timeout:2000,
		 animation: {
	        open: 'animated fadeInDown', // Animate.css class names
	        close: 'animated fadeOut', // Animate.css class names	        
	    }
	});
}

function initTable()
{		
	var params=$("#frm_table").serialize();	
	var action= $("#frm_table #action").val();	
	
	params+="&language="+language;
		
	data_table = $('#table_list').dataTable({		
		   "iDisplayLength": 20,
	       "bProcessing": true, 	       
	       "bServerSide": true,	            
	       "sAjaxSource": ajax_url+"/"+ action +"/?currentController=admin&"+params,	       
	       "aaSorting": [[ 0, "DESC" ]],	       
           "sPaginationType": "full_numbers",   
           //"bFilter":false,            
           "bLengthChange": false,
	       "oLanguage":{	       	 
	       	 "sProcessing": "<p>Processing.. <i class=\"fa fa-spinner fa-spin\"></i></p>"
	       },	    
	       "oLanguage": {
	       	  "sEmptyTable":    js_lang.tablet_1,
			    "sInfo":           js_lang.tablet_2,
			    "sInfoEmpty":      js_lang.tablet_3,
			    "sInfoFiltered":   js_lang.tablet_4,
			    "sInfoPostFix":    "",
			    "sInfoThousands":  ",",
			    "sLengthMenu":     js_lang.tablet_5,
			    "sLoadingRecords": js_lang.tablet_6,
			    "sProcessing":     js_lang.tablet_7,
			    "sSearch":         js_lang.tablet_8,
			    "sZeroRecords":    js_lang.tablet_9,
			    "oPaginate": {
			        "sFirst":    js_lang.tablet_10,
			        "sLast":     js_lang.tablet_11,
			        "sNext":     js_lang.tablet_12,
			        "sPrevious": js_lang.tablet_13
			    },
			    "oAria": {
			        "sSortAscending":  js_lang.tablet_14,
			        "sSortDescending": js_lang.tablet_15
			    }
	       },   
	       "fnInitComplete": function(oSettings, json) {	       	  		      
		   }		
	});
}

function tableReload()
{	
	data_table.fnReloadAjax(); 
}

function clearFormElements(ele) {

    $(ele).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });

}

$(document).ready(function(){
	
	$('.create-team').on('show.bs.modal', function (e) {        
        $(".modal-title").html( jslang.create_team );	            
        if ( !empty($("#id").val())){
           $(".modal-title").html( jslang.update_team );	
           callAjax("getTeam", "id="+$("#id").val() ,  $("#frm .orange-button") ) ;
        }
    });     
    $('.create-team').on('hide.bs.modal', function (e) {
        $("#id").val('');      
        clearFormElements("#frm");
    });     	
    $( document ).on( "click", ".table-edit", function() {    	
    	var id= $(this).data("modal");
    	dump( $(this).data("id") );
    	$("#id").val(  $(this).data("id") );
    	$(id).modal('show');
    });
        
    $( document ).on( "click", ".table-delete", function() {    	
    	  dump(jslang);
    	  c=confirm(jslang.are_your_sure+"?");
    	  if(c){
    	     callAjax("deleteRecords", $(this).data("data") );
    	  }
    }); 	    
        
    $( document ).on( "click", "#transport_type_id", function() {    	
    	var selected=$(this).val();    	
    	switchTransportType( selected ); 	
    });	    
    $('.new-agent').on('hide.bs.modal', function (e) {                        
        $("#id").val('');           
        clearFormElements("#frm");
    });     
    $('.new-agent').on('show.bs.modal', function (e) {            	
    	$("#password").attr("required",1);
    	switchTransportType( $("#transport_type_id").val()  );
        $(".modal-title").html( jslang.add_driver );	            
        if ( !empty($("#id").val())){
           $(".modal-title").html( jslang.update_driver );	
           callAjax("getDriverInfo", "id="+$("#id").val() ,  $("#frm .orange-button") ) ;
        }
    });     
        
    $( document ).on( "click", ".refresh-table", function() {    	
    	tableReload();
    });	
    
    $( document ).on( "click", ".add-new-task", function() {   
    	if ( account_status=="expired"){    	    
    	    nAlert(jslang.account_expired,"warning");
    	} else {
    		$(".task_id").val('');    		
            $(".new-task").modal('show');
    	}
    });
    
    /*task modal*/
    $('.new-task').on('show.bs.modal', function (e) {            	
    	dump('show modal');    	
    	switchTransactionType( $(".trans_type:checked").val() );
    	swicthDriver( $("#driver_id:selected").val() );    	
    	var task_id=$(".task_id").val();
    	dump(task_id);
    	if(!empty(task_id)){
    		dump("task_id=>"+task_id);    		
    		callAjax("getTaskInfo", "id="+task_id ) ; 
    	}
    });	
    $('.new-task').on('shown.bs.modal', function (e) {                        
        dump('modal totally loaded');     
        setDefaultMapLocation();       
    });     
    $('.new-task').on('hide.bs.modal', function (e) {                        
        dump('hide modal');        
        $(".task_id").val('');
        $(".order_id").val('');
        $(".task_lat").val('');
        $(".task_lng").val('');
        clearFormElements("#frm_task");
    });         
    
    $( document ).on( "click", ".trans_type", function() {    	
        switchTransactionType( $(".trans_type:checked").val() );
    });
        
    /*missing translation*/
    var today_date=moment().format('YYYY/MM/DD'); 
    if ( $('.datetimepicker').exists() ){
    	dump('datetimepicker exists');    	
    	dump(today_date);
	    $('.datetimepicker').datetimepicker({
	    	/*format:'Y-m-d g:i A',
	    	formatTime:'g:i A', */
	        //format:'d.m.Y H:i'      
	        formatTime:'g:i A',
	        format:'Y-m-d H:i',
	        minDate:today_date
	    });	    
    }
    
    if ( $("#calendar").exists() ){    	
	    $('#calendar').datetimepicker({	    
	    	timepicker:false,	
	        format:'d M Y',	   	        
	        onChangeDateTime:function(dp,$input){	        		        	    
	        	var date_formated=dp.format("YYYY-MM-DD");
	        	dump(date_formated);
	        	$(".calendar_formated").val( date_formated );
	        	loadDashboardTask();	        	
            }      
	    });	    
    }
    
    /*geocomplete*/
    if ( $(".geocomplete").exists() ){    	    	
	   //$(".geocomplete").geocomplete().
	   $(".geocomplete")
	   .geocomplete({
	  	 country: default_country
	   })
	   .bind("geocode:result", function(event, result){	     	   	  
	      setMarkerByAddress( $(".geocomplete").val() );
	   });
    }
    
    if ( $("#search_map").exists() ){
    	$("#search_map")
	   .geocomplete({
	  	 country: default_country
	   })
	   .bind("geocode:result", function(event, result){	     	       
	       var t_lat = result.geometry.location.lat();
	       var t_lng = result.geometry.location.lng();
	       dump(t_lat); dump(t_lng);
	       if (!empty(t_lat)){
	          map.setCenter(t_lat,t_lng);
	          map.setZoom(10);
	       } else {
	       	  nAlert(jslang.undefine_result,'warning');
	       }
	   });
    }
        
    $( document ).on( "change", ".task_team_id", function() { 
    	 var team_id=$(this).val();     	 
    	 swicthDriver(team_id);
    });
    
    $( document ).on( "keyup", ".delivery_address_task", function() { 
    	 var address=$(this).val();
    	 setMarkerByAddress( $(".geocomplete").val() );
    });    
    
    if ( $(".dashboard-work-area").exists() ){    	
    	loadDashboardTask();
    	if ( disabled_auto_refresh!=1){
      	    dashboard_task_handle = setInterval(function(){loadDashboardTaskSilent()}, 9000);
    	}
    }
    
    /*assign task*/    
    $( document ).on( "click", ".assign-agent", function() {    	       
        var task_id=$(this).data('id');        
        var modalclose=$(this).data("modalclose");
        if(!empty(modalclose)){
        	 $("."+modalclose).modal('hide');
        }
        $(".assign-task").modal('show');
        $(".task-id").html( task_id );
        $(".task_id").val( task_id );
    });
    $('.assign-task').on('show.bs.modal', function (e) {                        
        dump('modal totally loaded'); 
        swicthDriver(0);         
    });     
    
    var task_id_details='';
    /*task details*/    
    $( document ).on( "click", ".task-details", function() {  
    	$(".driver-details-moda").modal('hide');  	       
    	var task_id=$(this).data('id');     	
    	dump('modal show');  
    	$(".task-id").html( task_id );
        $(".task_id_details").val( task_id );
    	$(".task-details-modal").modal('show');        
    });    
    $('.task-details-modal').on('show.bs.modal', function (e) {                        
        dump('modal totally loaded');  
        dump( $(".task_id_details").val() );
        callAjax("getTaskDetails", "id="+$(".task_id_details").val() ) ;        
    });     
    
    /*delete task*/
    $( document ).on( "click", ".delete-task", function() {    	       
    	var task_id= $(".task_id_details").val();
    	c=confirm(jslang.are_your_sure+"?");
    	if(c){
    	   callAjax("deleteTask", "task_id="+task_id );
    	}
    });	
    
    /*edit task*/
    $( document ).on( "click", ".edit-task", function() {   
    	var task_id= $(".task_id_details").val();
    	dump(task_id);
    	$(".task_id").val( task_id );
    	$(".task-details-modal").modal("hide");
    	$(".new-task").modal('show');
    });	
    
    /*change status*/    
    $( document ).on( "click", ".change-status", function() {   
    	var task_id= $(".task_id_details").val();
    	dump(task_id);   
    	$(".task-id").html( task_id );    	
    	$(".task_id").val( task_id );
    	$("#reason").val('');
    	$(".task-details-modal").modal("hide");
    	$(".task-change-status-modal").modal("show"); 	
    });	
    $('.task-details-modal').on('show.bs.modal', function (e) {                        
        dump('modal totally loaded');  
        dump( $(".task_id").val() );   
        $(".status").val('');     
        $(".reason_wrap").hide();
        $(".reason").val('');
    });     
    
    
    /*show reason text area*/
    $( document ).on( "change", ".status_task_change", function() {   
    	 var status=$(this).val();    	 
    	 switchReason( status );
    });
    
    /*set focus on map*/
    $( document ).on( "click", ".task-map", function() {           
        var t_lat=$(this).data("lat");
        var t_lng=$(this).data("lng");
        map.setCenter(t_lat,t_lng);
        $(".task-map").removeClass("active");
        $(this).addClass("active");
        //map.setZoom(16);
    });
    
    /*load agent list*/
    if ( $(".agent-active").exists() ){    	
    	loadAgentDashboardSilent();
    	dashboard_agent_handle = setInterval(function(){loadAgentDashboardSilent()}, 8500);
    }
    
    //show agent details
    //$( document ).on( "click", ".show-agent-details", function() {     
    $( document ).on( "click", ".view-driver-details", function() {     
    	var driver_id=$(this).data("id");    	
    	$(".driver_id_details").val( driver_id );
    	$(".driver-details-moda").modal('show');
    });	    
    
    $('.driver-details-moda').on('show.bs.modal', function (e) {                               
        callAjax("getDriverDetails","driver_id="+ $(".driver_id_details").val()+"&date="+$(".calendar_formated").val() );
    });     
    
    
    if ( $(".sticky").exists() ){
    	dump('sticky');
        $(".sticky").sticky({topSpacing:0});
    }
    
    $(".switch-boostrap").bootstrapSwitch({
    	size:"mini"
    });
    
    $( document ).on( "click", ".notification_tpl", function() {     
    	$("#option_name").val( $(this).data("id") );
    	$(".option-name").html( $(this).data("id") );
    	$(".notification-pop").modal('show');    	
    });	
    $('.notification-pop').on('show.bs.modal', function (e) {        	  
    	callAjax("GetNotificationTPL", "option_name=" + $("#option_name").val() );
    });     
    
    if ( $("#jplayer").exists() ){
    	initJplayer();
    }
           
    $( document ).on( "change", "#team", function() {     
    	loadAgentDashboard();
    });
    
    if (empty( Cookies.get('drv_sound_on') )){
    	Cookies.set('drv_sound_on', '1', { expires: 500, path: '/' });
    } else {
    	var drv_sound_on = Cookies.get('drv_sound_on');
    	dump( "drv_sound_on->"+Cookies.get('drv_sound_on') );
    	if ( drv_sound_on==2){    		
    		$(".menu-sound i").addClass("ion-android-volume-off");
    		$(".menu-sound i").removeClass("ion-volume-high");
    	}
    }
        
    $( document ).on( "click", ".menu-sound", function() {     
    	var f=$(this).find("i");
    	if (f.hasClass("ion-android-volume-off")){
    		f.removeClass("ion-android-volume-off");
    		f.addClass("ion-volume-high");
    		dump('on');
    		Cookies.set('drv_sound_on', '1', { expires: 500, path: '/' });
    	} else {
    		f.addClass("ion-android-volume-off");
    		f.removeClass("ion-volume-high");
    		dump('off');
    		Cookies.set('drv_sound_on', '2', { expires: 500, path: '/' });
    	}
    });
    
    $( document ).on( "click", ".show-location-map", function() {  
    	var lat=$(this).data("lat");   
    	var lng=$(this).data("lng");   
    	$(".task-details-modal").modal('hide');
    	$("#map_location_lat").val( lat );
    	$("#map_location_lng").val( lng );
    	$("#map_task_id_ref").val( $(this).data("taskid"));
    	$(".show-location-map-modal").modal('show');
    });
    $('.show-location-map-modal').on('shown.bs.modal', function (e) {       	
    	var lat=$("#map_location_lat").val();
    	var lng=$("#map_location_lng").val();
    	map_location = new GMaps({
		  div: '.map-location',
		   lat: lat,
	       lng: lng,
		   zoom: 5,
		   styles: map_style 
		}); 
		 map_location.setCenter( lat , lng );
	     map_location.setZoom(10);
	     
	   var location_marker = map_location.addMarker({
         lat: lat ,
         lng: lng          
        });
    });	
             
}); /*end docu*/

function loadDashboardTask()
{
	if ( $(".dashboard-work-area").exists() ){
	    callAjax("getDashboardTask","status=unassigned&date="+ $(".calendar_formated").val() );
	}
	if ( $(".task-list-area").exists() ){
		tableReload();
	}
}

function loadDashboardTaskSilent()
{
	dump('loadDashboardTaskSilent');
	callAjaxSilent("getDashboardTask","status=unassigned&date="+ $(".calendar_formated").val() );
}

function loadAgentDashboard()
{
	callAjax2("loadAgentDashboard", "date="+ $(".calendar_formated").val() + "&team_id=" + $("#team").val() );
}

function loadAgentDashboardSilent()
{
	dump('loadAgentDashboardSilent');
	callAjaxSilent2("loadAgentDashboard", "date="+ $(".calendar_formated").val() + "&team_id=" + $("#team").val()  );
}

function switchReason(status)
{
	dump(status);
	switch (status)
	{
		case "failed":
		case "canceled":
		case "cancelled":
		$(".reason_wrap").show();
		break;
		
		default:
		$(".reason_wrap").hide();
		break;
		
	}
}

function switchTransportType(selected)
{	
	switch (selected)
	{
		case "bike":
		$("#licence_plate").hide();
		break;
		
		case "walk":
		$("#licence_plate").hide();
		$("#transport_description").hide();
		$("#color").hide();
		$(".description").hide();
		break;
		
		default:
		$(".description").show();
		$("#licence_plate").show();
		$("#transport_description").show();
		$("#color").show();
		break;
	}
}

function switchTransactionType(transaction_type)
{
	dump(transaction_type);	
	switch (transaction_type)
	{
		case "pickup":
		$(".delivery-info").show();
		$("#delivery_date").attr("placeholder", jslang.pickup_before);
		$("#delivery_address").attr("placeholder", jslang.pickup_address);
		break;
		
		case "delivery":
		$(".delivery-info").show();
		$("#delivery_date").attr("placeholder", jslang.delivery_before);
		$("#delivery_address").attr("placeholder", jslang.delivery_address);
		break;
		
		default:
		$(".delivery-info").hide();
		break;
	}
}

function swicthDriver(team_id , id_selected)
{
	if ( team_id>0){    	 	
	 	$(".assign-agent-wrap").show();	 	
	 	$(".team_opion").hide();
	 	$(".option_"+team_id).show();    	 	
	 	$(".map1").css({"height":"470px"});
	 	
	 	if(!empty(id_selected)){
	 	   $("#driver_id").val( id_selected );
	 	} else {
	 	   $("#driver_id").val('');
	 	}
	 } else {
	 	$(".task_team_id").val(0);
	 	$(".driver_id").val("");
	 	$(".assign-agent-wrap").hide();
	 	$(".team_opion").hide();    	 	
	 	$(".map1").css({"height":"360px"});
	 }
}

function setDefaultMapLocation()
{
	dump('setDefaultMapLocation');		    
	primary_map = new GMaps({
	  div: '.map_task',
	  lat: default_location_lat,
	  lng: default_location_lng	,
	  zoom: 5,
	  styles: map_style 
	}); 
	
	var task_id = $(".task_id").val();	
	if ( task_id > 0 ) {
		dump('it means edit ');
		SetMapPlot( $("#task_lat").val(), $("#task_lng").val() );
		return;
	}
	
	/*GMaps.geolocate({
	  success: function(position) {
	    map.setCenter(position.coords.latitude, position.coords.longitude);
	  },
	  error: function(error) {
	    nAlert('Geolocation failed: '+error.message ,'warning');
	  },
	  not_supported: function() {
	    nAlert("Your browser does not support geolocation",'warning');
	  },
	  always: function() {	
	  }
	});*/
}

function setMarkerByAddress( address )
{
	primary_map.removeMarkers();
	
	
	map_marker_task = map_marker_delivery;
	if( $(".trans_type").exists() ){		
		if ( $(".trans_type:checked").val() =="pickup"){
			map_marker_task = map_pickup_icon;
		}
	}
	
	//dump(map_marker);
	
	if($('.new-task-submit').is(':visible')) {
		$(".new-task-submit").css({ 'pointer-events' : 'none' });
	}
	
	dump(address);
	GMaps.geocode({
	  address: address,
	  callback: function(results, status) {
	    if (status == 'OK') {
	    	
	      if($('.new-task-submit').is(':visible')) {
		    $(".new-task-submit").css({ 'pointer-events' : 'auto' });
	      }
	    	
	      var latlng = results[0].geometry.location;
	      primary_map.setCenter(latlng.lat(), latlng.lng());
	      primary_map.setZoom(10);
	      
	      $("#task_lat").val( latlng.lat() );
	      $("#task_lng").val( latlng.lng() );
	      
	      var marker = primary_map.addMarker({
	         lat: latlng.lat(),
	         lng: latlng.lng(),
	         draggable: true,
	         icon : map_marker_task 
	      });
	      
	      marker.addListener('dragend',function(event) {	      	 
	         /*dump( "lat=>"+event.latLng.lat() );        
	         dump( "long=>"+event.latLng.lng() );*/
	         $("#task_lat").val( event.latLng.lat() );
	         $("#task_lng").val( event.latLng.lng() );
	         
	         dump("drag event");
	         if($('.new-task-submit').is(':visible')) {
	         	convertLatLongToAddress( event.latLng.lat() , event.latLng.lng() );
	         }
	         
	      });
	      
	    } else {
	    	if($('.new-task-submit').is(':visible')) {
		        $(".new-task-submit").css({ 'pointer-events' : 'auto' });
	        }	    	
	    }
	  }
	});	
}

function SetMapPlot( lat , lng )
{
	 primary_map.removeMarkers();
	
	 dump(lat); dump(lng);
	 primary_map.setCenter( lat , lng );
	 primary_map.setZoom(10);
	 
	 map_marker = map_marker_delivery;
	if( $(".trans_type").exists() ){		
		if ( $(".trans_type:checked").val() =="pickup"){
			map_marker = map_pickup_icon;
		}
	}
	 	 
     var marker = primary_map.addMarker({
         lat: lat ,
         lng: lng ,
         draggable: true,
         icon : map_marker 
      });
            
      //marker.addListener('drag',function(event) {
      marker.addListener('dragend',function(event) {
         dump( "latx=>"+event.latLng.lat() );        
         dump( "longx=>"+event.latLng.lng() );
         $("#task_lat").val( event.latLng.lat() );
         $("#task_lng").val( event.latLng.lng() );
         convertLatLongToAddress( event.latLng.lat() , event.latLng.lng() );
      });
}

function tplTaskHistory(data)
{
	if (data.length<=0){
		return;
	}
	var html='';
	$.each( data , function( key, val ) {     
	 	dump(val);
	 	html+='<div class="grey-box top10">';
	 	html+='<div class="row">';
	 	   html+='<div class="col-md-2">';
	       html+= '<span class="tag rounded '+val.status_raw+'">'+val.status+'</span>';	       	      
	       html+='</div>';
	       html+='<div class="col-md-6">';
	       html+= val.remarks
	       
	       if (!empty(val.reason)){
	       	  html+='<p>'+jslang.reason+': '+val.reason+'</p>';
	       }
	       
	       if (!empty(val.customer_signature)){
	       	  html+='<p>';
	       	  html+='<a href="'+val.customer_signature_url+'" target="_blank">'
	       	  html+='<img class="customer-signature" src="'+val.customer_signature_url+'">';
	       	  html+='</a>';
	       	  html+='</p>';
	       }
	       
	       
	       html+='</div>';
	       html+='<div class="col-md-4">';
	         html+='<i class="ion-ios-clock-outline"></i> '+val.date_created+' <br/>';
	         if (!empty(val.driver_location_lat)){
	             html+='<i class="ion-ios-location"></i>';
	             html+='<a href="javascript:;" class="show-location-map" data-lat="'+val.driver_location_lat+'" data-lng="'+val.driver_location_lng+'" data-taskid="'+val.task_id+'"  >'+jslang.location_on_map+'</a>';
	         }
	       html+='</div>';
	     html+='</div> ';
	     html+='</div>';
    });	 
    return html;
}

function plotMainMap(data)
{	 	
	dump("plotMainMap");
	 map = new GMaps({ 
			div: '.primary_map',
			lat: default_location_lat ,
			lng: default_location_lng ,
			//scrollwheel: false ,
			zoom: 5,
			styles: map_style ,				
		    markerClusterer: function(map) {
                return new MarkerClusterer(map);
            }
	 }); 	 
	 plotTaskMap(data);
}

function plotTaskMap(data)
{
	if ( data.length >0) {
		
		 // remove all pin
		 dump('remove all pin');
		 map.removeMarkers();
  	   		 
		 var last_lat='';
    	 var last_lng='';
         var bounds = [];
    			   
		 $.each( data , function( key, val ) {  
		 if (!empty(val.lat)){			 		
		 		 	 
		 	 info_html='';
		 	 
		 	 if ( val.map_type=="restaurant"){
		 	 info_html+="<div class=\"map-info-window\">";
		 	    info_html+="<h4>"+ jslang.task_id + ": " + val.task_id+"</h4>";
		 	    info_html+="<h5>"+ jslang.name + ": " + val.customer_name+"</h5>";
		 	    info_html+="<p>"+val.address+"</p>";		 	    
		 	    info_html+="<p class=\"inline green-button small rounded\">"+val.trans_type+"</p>";
		 	    info_html+="<p class=\"inline orange-button-small rounded\">"+val.status+"</p>";		 	    
		 	    info_html+="<a href=\"javascript:;\"  class=\"top10 task-details\" data-id=\""+val.task_id+"\"  >"+jslang.details+"</a>";
		 	 info_html+="</div>";
		 	 } else {
		 	 	info_html+=val.first_name+" ";
	  	 	    info_html+=val.last_name;
		 	 }
		 	 
		 	 last_lat=val.lat;
		   	 last_lng=val.lng;
		   	  
		   	 var latlng = new google.maps.LatLng( last_lat , last_lng );
		   	 bounds.push(latlng);
		   	 
		   	 if ( val.map_type=="restaurant"){
			   	 if ( val.trans_type=="delivery"){
			   	 	
			   	 	switch (val.status_raw)	{
			   	 					   	 
			   	 		case "successful":	
			   	 		map_marker=delivery_icon_success;
			   	 		break;
			   	 		
			   	 		case "failed":
			   	 		case "declined":
			   	 		case "cancelled":
			   	 		map_marker=delivery_icon_failed;
			   	 		break;
			   	 		
			   	 		default: 
			   	 		map_marker=map_marker_delivery;
			   	 		break;
			   	 	}
			   	 		
			   	 } else {
			   	 				   	 	
			   	 	switch (val.status_raw)	{
			   	 		case "successful":	
			   	 		map_marker=pickup_icon_success;
			   	 		break;
			   	 		
			   	 	    case "failed":
			   	 		case "declined":
			   	 		case "cancelled":
			   	 		map_marker=pickup_icon_failed;
			   	 		break;
			   	 		
			   	 		default: 
			   	 		map_marker=map_pickup_icon;
			   	 		break;
			   	 	}
			   	 }
			   	 
			   	map.addMarker({
					lat: val.lat,
					lng: val.lng,			
					icon : map_marker ,	
					infoWindow: {
					  content: info_html
					}
			    });		
			    
		   	 } else {
		   	 	  if (val.is_online==1){
		   	 	  	map_marker=driver_icon_online;
		   	 	  } else {
		   	 	  	map_marker=driver_icon_offline;
		   	 	  }
		   	 	  plotDriverToMap( val.lat, val.lng , map_marker , info_html );
		   	 }		        
		 } 	 
		 }); /*end each*/
		 
		 if(dashboard_run_silent==2){
		    map.fitLatLngBounds(bounds);
		 }		 
	} else {
		dump('no task to map');		
	}
}

function setDriverMarker(lat , lng , info_html )
{
	if(empty(lat)){
		return ;
	}
	if(empty(lng)){
		return ;
	}
	
	dump('setDriverMarker');
			
	var marker = map.addMarker({
         lat: lat ,
         lng: lng ,
         icon : driver_icon ,	
         draggable: false,
         infoWindow: {
		    content: info_html
		 }
    });
}

function scroll(id){
   if ( $(id) ) {
      $('.content_main').animate({scrollTop: $(id).offset().top-100},'slow');
   }
}


var notification_handle='';

$(document).ready(function(){
					
	if ( $("#layout_1").exists() ){
	   getInitialNotifications();
	   setTimeout('getNotifications()', 1100);
	}
	
}); /*end docu*/
function getInitialNotifications()
{
	 action="getInitialNotifications";
	 params='';
	 
	 params+="&language="+language;
	 
	 var notification_handle2;
	
	 notification_handle2 = $.ajax({
		url: ajax_url+"/"+action, 
		data: params,
		type: 'post',                  		
		dataType: 'json',
		timeout: 6000,		
	 beforeSend: function() {	 	
	 	 if(notification_handle2 != null) {
	 	   notification_handle2.abort();
	 	   dump("ajax abort");	 	   	  
	 	} 
	 },
	 complete: function(data) {		
	 	notification_handle2 = (function () { return; })();
	 },
	 success: function (data) {	  		 	
	 	if (data.code==1){	 			 		
	 		$.each( data.details , function( key, val ) { 	 		 			 
	 			 fillPopUpNotification( val.message, val.title , val.task_id , val.status );
	 		});	
	 	} else {
	 		$("#notification_list").prepend('<p class="no-noti text-info">'+jslang.no_notification+'</p>');
	 	}
     },
	 error: function (request,error) {	    	 	 
	 }
    });     	 
}

function getNotifications()
{
	 action="GetNotifications";
	 params='';
	 
	 params+="&language="+language;
	 
	 notification_handle = $.ajax({
		url: ajax_url+"/"+action, 
		data: params,
		type: 'post',                  		
		dataType: 'json',
		timeout: 6000,		
	 beforeSend: function() {	 	
	 	 window.clearInterval(notification_handle);	 	
	 },
	 complete: function(data) {		
	 	notification_handle = setInterval(function(){getNotifications()}, 10000); 	
	 },
	 success: function (data) {	  		 	
	 	if (data.code==1){	 	
	 		$(".no-noti").remove();	
	 		playNotification(); 
	 		$.each( data.details , function( key, val ) { 	 	
	 			 toastMessage( val.message, val.title );   
	 			 fillPopUpNotification( val.message, val.title , val.task_id , val.status );
	 		});	
	 	}
     },
	 error: function (request,error) {	    
	 	 window.clearInterval(notification_handle);
	 }
    });     	 
}

function fillPopUpNotification(message,title , task_id  , status )
{
	var link='<a data-id="'+task_id+'" class="task-details" href="javascript:;">'+task_id+'</a>';
	var new_title = status + " "+ jslang.task_id + ":"+ link ;
	var html='';
	html+='<li>';
	html+='<i class="ion-ios-circle-filled"></i> '+message +" <br/>"+new_title;
	html+='</li>';
	$("#notification_list").prepend(html);
}

function toastMessage(message,title)
{
	 if (empty(title)){
	 	title='';
	 }
	 if (empty(message)){
	 	return;
	 }
	 toastr.options = {	  
	  "positionClass": "toast-bottom-right",
	  "preventDuplicates": false,
	  "onclick": null,
	  "showDuration": "500",
	  "hideDuration": "1000",
	  "timeOut": "5000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};
	toastr.info(message,title);
}

function initJplayer()
{	
   $("#jplayer").jPlayer({
    ready: function() {
      $(this).jPlayer("setMedia", {
        mp3: site_url+"/assets/audio/fb-alert.mp3"
      })
    },
    swfPath: site_url+ "/assets/jplayer",
    loop: false
   });
}

function playNotification()
{
	var drv_sound_on = Cookies.get('drv_sound_on');
	if (drv_sound_on==2){
		// do nothing
	} else {
	   $("#jplayer").jPlayer("play");
	}
}


$(document).ready(function(){
	
	$( document ).on( "keyup", ".numeric_only", function() {
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });	 
    
    if ( $("#driver_auto_assign_type").exists() ){
    	switchAutoAssign();
    }
    $( document ).on( "click", "#driver_auto_assign_type", function() {
       switchAutoAssign();
    });	 
	
	$( document ).on( "click", ".locate-driver-onmap", function() {
		var t_lat = $(this).data("lat");
		var t_lng = $(this).data("lng");
		if ( !empty(t_lat) && !empty(t_lng) ){		   
		   map.setCenter(t_lat,t_lng);
		   map.setZoom(14);		   
		}
	});
	
	
	
	if ( $(".report_div").exists() ){
		loadChart();
	}	
	
	$( document ).on( "change", "#team_selection", function() { 		
		team_id=$(this).val();
		$("#driver_selection").val("all");
		$("#driver_selection .team_opion").hide();
		$("#driver_selection .option_"+team_id).show();
	});
	
	$( document ).on( "change", "#time_selection", function() { 		
		if ( $(this).val()=="custom" ){
			$(".custom_selection").show();			
		} else {
			$(".custom_selection").hide();			
			loadChart();
		}		
	});		
	
	$( document ).on( "click", ".change_charts", function() { 		
		$("#chart_type_option").val( $(this).data("id") );
		loadChart();
	});	
	$( document ).on( "change", "#team_selection,#driver_selection", function() { 		
		loadChart();
	});	
	    
}); /*end docu*/


function switchAutoAssign()
{
	var selected=$("#driver_auto_assign_type:checked").val();
	dump(selected);
	switch (selected)
	{
		case "one_by_one":
			$(".section_one_by_one").show();
			$(".section_send_to_all").hide();
		break;
		
		case "send_to_all":
		   $(".section_one_by_one").hide();
		   $(".section_send_to_all").show();
		break;
		
		default:
		     $(".section_one_by_one").hide();
			$(".section_send_to_all").hide();
		break;
	}
}

function plotDriverToMap(lat, lng , map_marker , info_html )
{
	if (empty(lat) && empty(lng)){
		return;
	}
	
/*	map.addMarker({
		lat: lat,
		lng: lng,			
		icon : map_marker ,	
		infoWindow: {
		  content: info_html
		}
	});
	return;*/		 
	
	var latlng = new google.maps.LatLng(lat, lng);
	var geocoder = geocoder = new google.maps.Geocoder();
	geocoder.geocode({ 'latLng': latlng }, function (results, status) {
		dump(status);
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
            	dump('driver address');
                dump( results[1].formatted_address );      
                
                info_html+='<p><b>'+jslang.currentlocation+'</b>: '+results[1].formatted_address+'</p>';
                map.addMarker({
					lat: lat,
					lng: lng,			
					icon : map_marker ,	
					infoWindow: {
					  content: info_html
					}
				 });		          
            }
        } else {
        	// getting of address has failed
        	 map.addMarker({
				lat: lat,
				lng: lng,			
				icon : map_marker ,	
				infoWindow: {
				  content: info_html
				}
			 });
        }
    });
}

function convertLatLongToAddress(lat, lng)
{	
	if(empty(lat) || empty(lng)){
		return;
	}	
	
	$(".map_task_loader").show();
	
	setTimeout(function(){
		var latlng = new google.maps.LatLng(lat, lng);
		var geocoder = geocoder = new google.maps.Geocoder();
		geocoder.geocode({ 'latLng': latlng }, function (results, status) {
			dump("GEOCODE =>"+status);
	        if (status == google.maps.GeocoderStatus.OK) {        	
	            if (results[1]) {
	            	$(".map_task_loader").hide();
	                dump( results[1].formatted_address );
	                $("#delivery_address").val( results[1].formatted_address );
	            } else {            	
	            	$(".map_task_loader").hide();
	            }
	        } else {        	
	        	$(".map_task_loader").hide();
	        }
	    });    
    },300);
}

function retryAutoAssign(task_id)
{	
	$(".autoassign-col-1-"+task_id).html('');
	$(".autoassign-col-2-"+task_id).html('<p class="small-font text-primary">'+jslang.autoassigning+'...</p>');	
	callAjax("retryAutoAssign","task_id="+ task_id);
}

function loadChart()
{
	params="chart_type="+$("#chart_type").val();
	params+='&chart_type_option='+ $("#chart_type_option").val();
	params+='&time_selection='+ $("#time_selection").val();
	params+='&team_selection='+ $("#team_selection").val();
	params+='&driver_selection='+ $("#driver_selection").val();
	params+='&start_date='+ $("#start_date").val();
	params+='&end_date='+ $("#end_date").val();
	$(".table_charts").html('');
	callAjax("chartReports", params );
}


$(document).ready(function(){
	
   $( document ).on( "click", ".back-task-details", function() {   	
   	    $(".show-location-map-modal").modal('hide');  	       
    	var task_id= $("#map_task_id_ref").val();    	
    	$(".task-id").html( task_id );
        $(".task_id_details").val( task_id );
    	$(".task-details-modal").modal('show');           	
   });
   
     $( document ).on( "click", ".open-modal-push", function() {      	
    	$("#driver_id_push").val( $(this).data("id") );
    	$(".push-form-modal").modal('show');
    });
    
    $('.push-form-modal').on('shown.bs.modal', function (e) {   
    	$("#x_push_title").val(''); 
    	$("#x_push_message").val(''); 
    });	
    
    $.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm-send-push',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      var params= $("#frm-send-push").serialize();
	      var action = $("#frm-send-push #action").val();
	      var button = $('#frm-send-push button[type="submit"]');
	      dump(button);
	      callAjax(action,params,button);
	      return false;
	    }  
	});    
   
}); /*end doc*/