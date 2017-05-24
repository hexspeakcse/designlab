jQuery.fn.exists = function(){return this.length>0;}
var ajax_request;

function busy(e,button)
{
	if (e) {
        $('body').css('cursor', 'wait');	
    } else $('body').css('cursor', 'auto');        

    if (e) {    
    	dump('busy loading');        
        $(".main-preloader").show();
        if (!empty(button)){           
	 	   button.css({ 'pointer-events' : 'none' });	 	   	  
	 	}	 		 	
    } else {    	
    	dump('done loading');
    	$(".main-preloader").hide();    	
    	if (!empty(button)){
	 	   button.css({ 'pointer-events' : 'auto' });	 	   	  
	 	}
    }       
}

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

$(document).ready(function(){
	
	if ( $(".readmore").exists() ){
		$('.readmore').readmore({
		  speed: 75,
		  collapsedHeight:25,
		  moreLink: '<a href="javascript:;">'+js_lang.read_more+'</a>',
		  lessLink: '<a href="javascript:;">'+js_lang.read_less+'</a>'
		});
	}
	
	if ( $(".select-material").exists() ) {
       $('.select-material').material_select();
    }	
    
     if ( $(".mobile_inputs").exists()){
      try {	
	      $(".mobile_inputs").intlTelInput({      
	        autoPlaceholder: false,		
	        defaultCountry: default_country,            
	        autoHideDialCode:true,    
	        nationalMode:false,
	        autoFormat:false,
	        utilsScript: site_url+"/assets/intel/lib/libphonenumber/build/utils.js"
	      });
	   }
	   catch(err) {
		 dump(err.message);
	   }   
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
	    form : '#frm-trytrial',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      window.location.href = home_url+"/pricing/?email="+ $("#email_address").val();
	      return false;
	    }  
	}); 
	
	$( document ).on( "click", ".resend-code", function() {
	    callAjax("resendCode","hash="+$("#hash").val() +"&verification_type="+$("#verification_type").val() , $(".btn") );
	}); 
				
	$( document ).on( "click", ".language-selector", function() {	
		var h=$("#lang-list").height()+3;
		dump(h);	
		$("#lang-list").css({"top":"-"+h+"px"});
		$("#lang-list").slideToggle( "fast" );
	});
	
			
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm-existing',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      var params= $("#frm-existing").serialize();
	      var action = $("#frm-existing #action").val();
	      var button = $('#frm-existing button[type="submit"]');
	      dump(button);
	      callAjax(action,params,button);
	      return false;
	    }  
	}); 
	
	$( document ).on( "click", ".existing-click", function() {		
		$(".existing-application-wrap").slideToggle( "fast" );
	});
	
	$( document ).on( "click", ".mobile-toggle", function() {				
	   $( ".mobile-menu-wrap" ).slideToggle( "fast", function() {    		  	  
       });
	});
	
}); /*end docu*/

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
		//timeout: 10000,		
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
	 	
	 	dump("action->"+action);
	 	
	 	if (data.code==1){
	 			 	
	 		switch (action)
	 		{				
	 			case "signup":
	 			case "verifySignupCode":	
	 			case "getSignup": 			
	 			toast(data.msg);
	 			window.location.href = data.details;
	 			break;
	 			
	 			case "paymentOption":	 
	 			case "paypalExpressCheckout":
	 			case "PaymentStripe":
	 			
	 			setTimeout(function () {
                   button.css({ 'pointer-events' : 'none' });	 
                }, 200);
                
	 			toast(data.msg);
	 			window.location.href = data.details;
	 			break;
	 			
	 			default:	 	 			
	 			toast(data.msg);
	 			break;
	 		}
	 		
	 	} else {
	 		
	 		// failed mycon
	 		switch ( action )
	 		{	 		 			
	 			
	 			default :	 			 			
	 			toastf(data.msg);	
	 			break;
	 		}
	 			 		
	 	}
	 },
	 error: function (request,error) {	    
	 	 	 		
	 }
    });       
}

function toast(message)
{
	 Materialize.toast(message, 4000,'toast-success');
}
function toastf(message)
{
	 Materialize.toast(message, 4000);
}

$(document).ready(function(){
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm-stripe',    
	    onError : function() {      
	    },
	    onSuccess : function() { 	           
	      	    	
	       var cards = $("#card_number").val();       
	       var cvv = $("#cvc").val();  
	       var expiration_yr = $("#expiration_year").val(); 
	       var expiration_month = $("#expiration_month").val();  	       
	            
	       dump("cards->"+cards);
	       dump("cvv->"+cvv);	       
	       dump("expiration_yr->"+expiration_yr);
	       dump("expiration_month->"+expiration_month);
	       busy(true);
	       
	       Stripe.setPublishableKey( $("#publish_key").val() );
	       Stripe.card.createToken({
			  number: cards ,
			  cvc: cvv,
			  exp_month: expiration_month ,
			  exp_year: expiration_yr
		   }, stripeResponseHandler);	
		   
	       return false;
	    }  
	}); 
}); 

function stripeResponseHandler(status, response)
{
	dump('stripe response');
	dump(status);
	dump(response);
	if (response.error) {
		busy(false);
		toastf( response.error.message );
	} else {
		busy(false);
		var button = $('#frm-stripe button[type="submit"]');
		var params = $( "#frm-stripe").serialize();     
		params+="&stripe_token="+response.id;   
        callAjax("PaymentStripe",params,button);	       
	}
}