<?php
if (!isset($_SESSION)) { session_start(); }

class InstallController extends CController
{
	public $layout='install_layout';	
	
	
	public function beforeAction($action)
	{
		$cs = Yii::app()->getClientScript();
		$baseUrl = Yii::app()->baseUrl.""; 
		
		Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl . '/assets/jquery-1.10.2.min.js',
		CClientScript::POS_END
		);
		
		Yii::app()->clientScript->registerScriptFile(
        "//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js",
		CClientScript::POS_END
		);			
		$cs->registerCssFile("//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css");
        $cs->registerCssFile("//fonts.googleapis.com/icon?family=Material+Icons");		 
                
        return true;
	}
	
	public function actionIndex()
	{
		$this->render('install');
	}
	
	public function actionStep2()
	{
		if($_SESSION['kt_install']==1){		   
		   $this->render('install_step2');
		} else $this->redirect(Yii::app()->createUrl('/install/index',array(
		 'error'=>"Session install not found"
		)));
	}
	
	public function actionstep3()
	{
		$this->render('install_step3');
	}
	
	public function actionfinish()
	{
		$data=$_POST;				
		if(isset($data['username'])){			
		   $db_ext=new DbExt;

		   $db_ext->qry("TRUNCATE TABLE {{admin}}");
		    
		   $params=array(
		     'first_name'=>'admin',
		     'last_name'=>'admin',
		     'username'=>$data['username'],
		     'password'=>CPasswordHelper::hashPassword($data['password']),
		     'date_created'=>AdminFunctions::dateNow(),
		     'date_modified'=>AdminFunctions::dateNow(),
		     'last_login'=>AdminFunctions::dateNow(),
		     'ip_address'=>$_SERVER['REMOTE_ADDR']
		   );		   
		   $db_ext->insertData("{{admin}}",$params);
		   		   	
		   $db_ext->qry("TRUNCATE TABLE {{option}}");
		   
		    $db_ext->insertData("{{option}}",array(
		     'option_name'=>'company_name',
		     'option_value'=>$data['company_name']
		    ));
		   	    
		   $this->installData();
		   
		   $this->render('finish',array(
		     'code'=>1
		   ));
		} else $this->render('finish',array(
		   'code'=>2,
		   'msg'=>"Missing required information"
		));
	}
	
	private function installData()
	{
		$db_ext=new DbExt;
		
		
		$db_ext->insertData("{{option}}",array(
		     'option_name'=>'forgot_password_tpl',
		     'option_value'=>"hi [first_name]

your verification code is : [verification_code]


cheers"
		 ));
		 
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'signup_tpl_sms',
	     'option_value'=>"your sms verification code is [verification_code]"
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'signup_tpl_email',
	     'option_value'=>"hi [first_name]

your email verification code is [verification_code]

Regards"
	    ));
	    	    	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'signup_tpl_email_subject',
	     'option_value'=>"Your Signup Verification code"
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'forgot_password_subject',
	     'option_value'=>"You have requested for your password"
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'welcome_tpl_subject',
	     'option_value'=>"Welcome [first_name]"
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'currency_decimal_places',
	     'option_value'=>2
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'approved_tpl_subject',
	     'option_value'=>"Your account has been approved"
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'approved_tpl',
	     'option_value'=>"Hi [first_name]

your account is approved
username : [username]
password : your password

you can login here [login_link]

cheers"
	    ));
	    
	    $db_ext->insertData("{{currency}}",array(	     
	        'currency_code'=>"USD",
	        'currency_symbol'=>"$",
	        'status'=>"published",
	        'date_created'=>AdminFunctions::dateNow(),
	        'date_modified'=>AdminFunctions::dateNow(),
	        'ip_address'=>$_SERVER['REMOTE_ADDR']
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'website_currency',
	     'option_value'=>1
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'welcome_tpl',
	     'option_value'=>"hi [first_name]

Thank you for signup 


thanks
"
	    ));
	    
	    $db_ext->insertData("{{option}}",array(
	     'option_name'=>'email_provider',
	     'option_value'=>"php_mail"
	    ));
	    	   
	}
	
} /*end class*/