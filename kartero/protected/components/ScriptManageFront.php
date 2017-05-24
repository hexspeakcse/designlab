<?php
class ScriptManageFront
{
	
	public static function scripts()
	{
		$ajaxurl=Yii::app()->baseUrl.'/ajaxfront';
		$site_url=Yii::app()->baseUrl.'/';
		$home_url=Yii::app()->baseUrl.'/front';

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
		
		
		$default_country=getOptionA('website_default_country');
		if(empty($default_country)){
		  $default_country='US';
		}
		
		$cs->registerScript(
		  'default_country',
		 "var default_country='$default_country';",
		  CClientScript::POS_HEAD
		);
		
		/*JS FILE*/
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/jquery-1.10.2.min.js',
		CClientScript::POS_END
		);
		
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
				
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/form-validator/jquery.form-validator.min.js',
		CClientScript::POS_END
		);		
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/js.kookie.js',
		CClientScript::POS_END
		);								
		
		/*Yii::app()->clientScript->registerScriptFile(
        "//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js",
		CClientScript::POS_END
		);			*/

		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/intel/build/js/intlTelInput.js?ver=2.1.5',
		CClientScript::POS_END
		);			
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/readmore.min.js',
		CClientScript::POS_END
		);						
					
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/front.js?ver=1.0',
		CClientScript::POS_END
		);						
		
		/*CSS FILE*/
		$baseUrl = Yii::app()->baseUrl.""; 
		$cs = Yii::app()->getClientScript();				
		$cs->registerCssFile($baseUrl."/assets/bootstrap/css/bootstrap.min.css");		
		
		$cs->registerCssFile($baseUrl."/assets/chosen/chosen.min.css");		
		$cs->registerCssFile($baseUrl."/assets/animate.css");	
		$cs->registerCssFile("//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css");
		$cs->registerCssFile("//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css");
		
		$cs->registerCssFile($baseUrl."/assets/intel/build/css/intlTelInput.css");								
		
		//$cs->registerCssFile("//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css");
		$cs->registerCssFile("//fonts.googleapis.com/icon?family=Material+Icons");
		$cs->registerCssFile("//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,400italic,700italic,900,900italic");		
		
		$cs->registerCssFile($baseUrl."/assets/front.css?ver=1.0");		
		$cs->registerCssFile($baseUrl."/assets/front-responsive.css?ver=1.0");		
		
	}
	
} /*END CLASS*/