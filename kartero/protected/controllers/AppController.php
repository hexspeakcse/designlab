<?php
if (!isset($_SESSION)) { session_start(); }

class AppController extends CController
{
	public $layout='layout';	
	public $body_class='';
	
	public function init()
	{			
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone" );		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 				 
		 		 
		 if(isset($_GET['lang'])){
		 	Yii::app()->language=$_GET['lang'];
		 }
	}
	
	public function beforeAction($action)
	{		
		/*if (Yii::app()->controller->module->require_login){
			if(! DriverModule::islogin() ){
			   $this->redirect(Yii::app()->createUrl('/admin/noaccess'));
			   Yii::app()->end();		
			}
		}*/
		$action_name= $action->id ;
		$accept_controller=array('login','ajax','resetpassword');
		if(!Driver::islogin()){			
			if(!in_array($action_name,$accept_controller)){
				$this->redirect(Yii::app()->createUrl('/app/login'));
			}
		}
		
		
		/*check user status*/
		$status=Driver::getUserStatus();
		if($status=="expired"){
			if($action_name!="profile"){
				if($action_name!="logout"){
				$this->redirect(Yii::app()->createUrl('/app/profile',array(
				  'tabs'=>2
				)));
				Yii::app()->end();
				}
			}
		}
		
		ScriptManager::scripts();
		
		$cs = Yii::app()->getClientScript();
		$jslang=json_encode(Driver::jsLang());
		$cs->registerScript(
		  'jslang',
		 "var jslang=$jslang;",
		  CClientScript::POS_HEAD
		);
				
		$js_lang_validator=Yii::app()->functions->jsLanguageValidator();
		$js_lang=Yii::app()->functions->jsLanguageAdmin();
		$cs->registerScript(
		  'jsLanguageValidator',
		  'var jsLanguageValidator = '.json_encode($js_lang_validator).'
		  ',
		  CClientScript::POS_HEAD
		);				
		$cs->registerScript(
		  'js_lang',
		  'var js_lang = '.json_encode($js_lang).';
		  ',
		  CClientScript::POS_HEAD
		);
		
		$cs->registerScript(
		  'account_status',
		 "var account_status='$status';",
		  CClientScript::POS_HEAD
		);
		
		$language=Yii::app()->language;
		$cs->registerScript(
		  'language',
		 "var language='$language';",
		  CClientScript::POS_HEAD
		);
				
		return true;				
	}
	
	public function actionLogin()
	{
		$this->body_class='login-body';
		
		/*unset(Yii::app()->request->cookies['kt_username']);
		unset(Yii::app()->request->cookies['kt_password']);*/
		
		$kt_username = isset(Yii::app()->request->cookies['kt_username']) ? Yii::app()->request->cookies['kt_username']->value : '';
		$kt_password = isset(Yii::app()->request->cookies['kt_password']) ? Yii::app()->request->cookies['kt_password']->value : '';
		
		if(!empty($kt_password) && !empty($kt_username)){
		   $kt_password=Yii::app()->securityManager->decrypt( $kt_password );		
		}
		
		$this->render('login',array(
		  'email_address'=>$kt_username,
		  'password'=>$kt_password
		));
	}
	
	public function actionLogout()
	{
		unset($_SESSION['kartero']);
		$this->redirect(Yii::app()->createUrl('/app/login'));
	}
	
	public function actionIndex(){		
		$this->body_class="dashboard";		
		$this->render('dashboard');
	}	
	
	public function actionDashboard()
	{
		$this->body_class="dashboard";		
		$this->render('dashboard');
	}

	public function actionAgents()
	{
		$this->render('agents-list');
	}
	
	public function actionTasks()
	{
		$this->render('task-list');
	}
	
	public function actionSettings()
	{		
				
        $country_list=require_once('CountryCode.php');
        $this->body_class='settings-page';
                     
        if ( Driver::getUserType()=="merchant"){
        	$this->render('error',array(
        	  'msg'=>Driver::t("Sorry but you don't have access to this page")
        	));
        } else {
			$this->render('settings',array(			  
			  'country_list'=>$country_list
			));
        }
	}
	
	public function actionTeams()
	{
		$this->render('teams');
	}
	
	public function actionlanguage()
	{
		$lang=Driver::availableLanguages();		
		$dictionary=require_once('MobileTranslation.php');		
		
		$mobile_dictionary=getOptionA('driver_mobile_dictionary');
        if (!empty($mobile_dictionary)){
	       $mobile_dictionary=json_decode($mobile_dictionary,true);
        } else $mobile_dictionary=false;
		
		$this->render('language',array(
		  'lang'=>$lang,
		  'dictionary'=>$dictionary,
		  'mobile_dictionary'=>$mobile_dictionary
		));
	}
	
	public function actionNotifications()
	{
		$this->render('notifications');
	}
	
	public function actionPushlogs()
	{
		$this->render('push-logs');
	}
		
	public function actionReports()
	{
		$cs = Yii::app()->getClientScript(); 
		
		Yii::app()->clientScript->registerScriptFile(
        "//amcharts.com/lib/3/amcharts.js",CClientScript::POS_END);		
        
        Yii::app()->clientScript->registerScriptFile(
        "//amcharts.com/lib/3/serial.js",CClientScript::POS_END);		
        
        Yii::app()->clientScript->registerScriptFile(
        "//amcharts.com/lib/3/themes/light.js",CClientScript::POS_END);		
		
        $team_list=Driver::teamList( Driver::getUserId());
		if($team_list){
			 $team_list=Driver::toList($team_list,'team_id','team_name',
			   Driver::t("All Team")
			 );
		}
		
		$all_driver=Driver::getAllDriver(
           Driver::getUserId()
        );   

        $start= date('Y-m-d', strtotime("-7 day") );
	    $end=date("Y-m-d", strtotime("+1 day")); 
        
		$this->render('reports',array(
		  'team_list'=>$team_list,
		  'all_driver'=>$all_driver,
		  'start_date'=>$start,
		  'end_date'=>$end
		));
	}
	
	public function actionAssignment()
	{
		$this->render('assignment');
	}
	
	public function actionResetPassword()
	{
		$this->body_class='login-body';		
		$this->render('resetpassword',array(
		 'hash'=>isset($_GET['hash'])?$_GET['hash']:''
		));
	}
	
	public function actionprofile()
	{
		if($data=AdminFunctions::getCustomerByID( Driver::getUserId())){				
			$plans=Driver::getPlansByID( $data['plan_id']);			
			$this->render('profile',array(
			  'data'=>$data,
			  'plans'=>$plans,
			  'tabs'=>isset($_GET['tabs'])?$_GET['tabs']:1,
			  'history'=>AdminFunctions::getCustomerPaymentLogs(Driver::getUserId())
			));
		} else {
			$this->render('error',array(
			  'msg'=>t("Profile not available")
			));
		}
	}
	
    public function actionsetlang()
	{
		if(!empty($_GET['action'])){
			$url=Yii::app()->createUrl("app/".$_GET['action'],array(
			  'lang'=>$_GET['lang']
			));
		} else {
			$url=Yii::app()->createUrl("app/dashboard",array(
			  'lang'=>$_GET['lang']
			));
		}				
		$this->redirect($url);
	}	
		
}/* end class*/