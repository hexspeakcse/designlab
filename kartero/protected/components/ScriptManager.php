<?php
class ScriptManager
{
	
	public static function scripts()
	{
		$ajaxurl=Yii::app()->baseUrl.'/ajax';
		$site_url=Yii::app()->baseUrl.'/';
		$home_url=Yii::app()->baseUrl.'/app';
		
		
        Yii::app()->clientScript->scriptMap=array(
          'jquery.js'=>false,
          'jquery.min.js'=>false
        );

		$cs = Yii::app()->getClientScript();  
		$cs->registerScript(
		  'ajaxurl',
		 "var ajax_url='$ajaxurl';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'site_url',
		 "var site_url='$site_url';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'home_url',
		 "var home_url='$home_url';",
		  CClientScript::POS_HEAD
		);
				
		/*MAP MARKER*/
		$delivery_icon=Yii::app()->baseUrl.'/assets/images/restaurant-pin-32.png';
		$delivery_icon_success=Yii::app()->baseUrl.'/assets/images/delivery-successful.png';
		$delivery_icon_failed=Yii::app()->baseUrl.'/assets/images/delivery-failed.png';
		
		$pickup_icon=Yii::app()->baseUrl.'/assets/images/pickup-icon-32.png';
		$pickup_icon_success=Yii::app()->baseUrl.'/assets/images/pickup-successful.png';
		$pickup_icon_failed=Yii::app()->baseUrl.'/assets/images/pickup-failed.png';
		
		$driver_icon_online=Yii::app()->baseUrl.'/assets/images/driver-online.png';
		$driver_icon_offline=Yii::app()->baseUrl.'/assets/images/driver-offline.png';
		
		
		$cs->registerScript(
		  'map_marker_delivery',
		 "var map_marker_delivery='$delivery_icon';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'delivery_icon_success',
		 "var delivery_icon_success='$delivery_icon_success';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'delivery_icon_failed',
		 "var delivery_icon_failed='$delivery_icon_failed';",
		  CClientScript::POS_HEAD
		);
		
		$cs->registerScript(
		  'pickup_icon',
		 "var map_pickup_icon='$pickup_icon';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'pickup_icon_success',
		 "var pickup_icon_success='$pickup_icon_success';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'pickup_icon_failed',
		 "var pickup_icon_failed='$pickup_icon_failed';",
		  CClientScript::POS_HEAD
		);
		
		$cs->registerScript(
		  'driver_icon_online',
		 "var driver_icon_online='$driver_icon_online';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'driver_icon_offline',
		 "var driver_icon_offline='$driver_icon_offline';",
		  CClientScript::POS_HEAD
		);
		
		$customer_id=Driver::getUserId();		
		
		$default_country=Yii::app()->functions->getOption('drv_default_location' , $customer_id   );
		if(empty($default_country)){
			$default_country='US';
		}
		$default_location_lat=Yii::app()->functions->getOption(  'drv_default_location_lat' , $customer_id );
		$default_location_lng=Yii::app()->functions->getOption( 'drv_default_location_lng' , $customer_id  );
		$drv_map_style=Yii::app()->functions->getOption(  'drv_map_style' , $customer_id);
		
		$default_location_lat=!empty($default_location_lat)?$default_location_lat:-12.043333;
		$default_location_lng=!empty($default_location_lng)?$default_location_lng:-77.028333;
		
		
		$driver_include_offline_driver_map=Yii::app()->functions->getOption('driver_include_offline_driver_map', $customer_id);
				
		$driver_disabled_auto_refresh=Yii::app()->functions->getOption('driver_disabled_auto_refresh', $customer_id);		
		/** START Set general settings */
		$cs->registerScript(
		  'default_country',
		 "var default_country='$default_country';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'default_location_lat',
		 "var default_location_lat='$default_location_lat';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'default_location_lng',
		 "var default_location_lng='$default_location_lng';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'driver_include_offline_driver_map',
		 "var driver_include_offline_driver_map='$driver_include_offline_driver_map';",
		  CClientScript::POS_HEAD
		);
		$cs->registerScript(
		  'disabled_auto_refresh',
		 "var disabled_auto_refresh='$driver_disabled_auto_refresh';",
		  CClientScript::POS_HEAD
		);
		
		$drv_map_style_res = json_decode($drv_map_style);
					
		if ( is_array($drv_map_style_res) && !empty($drv_map_style)){
			$cs->registerScript(
			  'map_style',
			 "var map_style=$drv_map_style",
			  CClientScript::POS_HEAD
			);
		} else {
			$map_style='[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0f252e"},{"lightness":17}]}];';
			$cs->registerScript(
			  'map_style',
			 "var map_style=$map_style",
			  CClientScript::POS_HEAD
			);
		}
		/** END Set general settings */
		
		
		/*JS FILE*/
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/jquery-1.10.2.min.js',
		CClientScript::POS_END
		);
				
		/*Yii::app()->clientScript->registerScriptFile(
        '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
		CClientScript::POS_END
		);*/
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/bootstrap/js/bootstrap.min.js',
		CClientScript::POS_END
		);
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/chosen/chosen.jquery.min.js',
		CClientScript::POS_END
		);
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/noty-2.3.7/js/noty/packaged/jquery.noty.packaged.min.js',
		CClientScript::POS_END
		);						
		
		/*Yii::app()->clientScript->registerScriptFile(
        '//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js',
		CClientScript::POS_END
		);		
		Yii::app()->clientScript->registerScriptFile(
        '//cdn.datatables.net/plug-ins/1.10.9/api/fnReloadAjax.js',
		CClientScript::POS_END
		);*/		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/DataTables/jquery.dataTables.min.js',
		CClientScript::POS_END
		);						
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/DataTables/fnReloadAjax.js',
		CClientScript::POS_END
		);						
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/jquery.sticky2.js',
		CClientScript::POS_END
		);
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/SimpleAjaxUploader.min.js',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/summernote/summernote.min.js',
		CClientScript::POS_END
		);		
		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/markercluster.js?ver=1.0',
		CClientScript::POS_END
		);		
				
		/*Yii::app()->clientScript->registerScriptFile(
	        '//google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/src/markerclusterer.js',
			CClientScript::POS_END
		);		*/
		
		//$google_key=Yii::app()->functions->getOption( 'google_api_key',$customer_id);		
		$google_key=getOptionA('google_api_key');
		if (!empty($google_key)){
			Yii::app()->clientScript->registerScriptFile(
	        '//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key='.urlencode($google_key),
			CClientScript::POS_END
			);		
		} else {
			Yii::app()->clientScript->registerScriptFile(
	        '//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places',
			CClientScript::POS_END
			);		
		}
				
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/gmaps.js',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/jquery.geocomplete.min.js',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/form-validator/jquery.form-validator.min.js',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/intel/build/js/intlTelInput.js?ver=2.1.5',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/nprogress/nprogress.js',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/datetimepicker/jquery.datetimepicker.full.min.js',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/moment.js',
		CClientScript::POS_END
		);								
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/js-date-format.min.js',
		CClientScript::POS_END
		);								
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/switch/bootstrap-switch.min.js',
		CClientScript::POS_END
		);								
		
		Yii::app()->clientScript->registerScriptFile(
        "//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js",
		CClientScript::POS_END
		);								
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/jplayer/jquery.jplayer.min.js',
		CClientScript::POS_END
		);								
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/js.kookie.js',
		CClientScript::POS_END
		);								
					
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/app.js?ver=1.0',
		CClientScript::POS_END
		);								
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/driver-js.js?ver=1.0',
		CClientScript::POS_END
		);								
				
		/*CSS FILE*/
		$baseUrl = Yii::app()->baseUrl.""; 
		$cs = Yii::app()->getClientScript();		
		//$cs->registerCssFile("//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css");		
		$cs->registerCssFile($baseUrl."/assets/bootstrap/css/bootstrap.min.css");		
		
		$cs->registerCssFile($baseUrl."/assets/chosen/chosen.min.css");		
		$cs->registerCssFile($baseUrl."/assets/animate.css");	
		$cs->registerCssFile($baseUrl."/assets/summernote/summernote.css");	
		$cs->registerCssFile("//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css");		
		//$cs->registerCssFile($baseUrl."/assets/DataTables");	
		$cs->registerCssFile("//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css");
		$cs->registerCssFile("//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css");
		
		$cs->registerCssFile($baseUrl."/assets/intel/build/css/intlTelInput.css");
		$cs->registerCssFile($baseUrl."/assets/nprogress/nprogress.css");	
		$cs->registerCssFile($baseUrl."/assets/datetimepicker/jquery.datetimepicker.css");	
		$cs->registerCssFile($baseUrl."/assets/switch/bootstrap-switch.min.css");
		
		$cs->registerCssFile("//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css");
		
		$cs->registerCssFile($baseUrl."/assets/style.css?ver=1.0");		
		
		$cs->registerCssFile($baseUrl."/assets/app-responsive.css?ver=1.0");		
		
	}
	
} /*END CLASS*/