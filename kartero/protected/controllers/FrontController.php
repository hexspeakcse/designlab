<?php
if (!isset($_SESSION)) { session_start(); }

class FrontController extends CController
{
	
	public $layout='front_layout';	
	public $body_class='';
	public $action_name='';
	
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
		$action_name= $action->id ;		
		$this->body_class="page-$action_name";
		
		ScriptManageFront::scripts();
		
		$cs = Yii::app()->getClientScript();
		$jslang=json_encode(AdminFunctions::jsLang());
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
		
		$language=Yii::app()->language;
		$cs->registerScript(
		  'language',
		 "var language='$language';",
		  CClientScript::POS_HEAD
		);
		
		return true;
	}
		
	public function actionIndex()
	{				
		$this->render('index',array(
		  'pricing'=>FrontFunctions::getPlans()
		));
	}
	
	public function actionPricing()
	{
		$exlude_free=isset($_GET['hash'])?true:false;		
		$this->render('pricing',array(
		  'data'=>FrontFunctions::getPlans($exlude_free),
		  'email'=>isset($_GET['email'])?$_GET['email']:'',
		  'hash'=>isset($_GET['hash'])?$_GET['hash']:''
		));
	}
	
	private function includeMaterial()
	{
		$cs = Yii::app()->getClientScript();
		$baseUrl = Yii::app()->baseUrl.""; 
		Yii::app()->clientScript->registerScriptFile(
        "//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js",
		CClientScript::POS_END
		);			
		$cs->registerCssFile("//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css");
        $cs->registerCssFile("//fonts.googleapis.com/icon?family=Material+Icons");		 
	}
	
	public function actionSignup()
	{
		if ( isset($_GET['plan_id'])){
			if(is_numeric($_GET['plan_id'])){
				
				$this->body_class.=" page-material";
				$this->includeMaterial();
				
				$this->render('signup',array(
				  'plan_id'=>$_GET['plan_id'],
				  'email_address'=>isset($_GET['email'])?$_GET['email']:''
				));
			} else  $this->redirect(Yii::app()->createUrl('/front/pricing'));
		} else $this->redirect(Yii::app()->createUrl('/front/pricing'));
	}
	
	public function actionVerification()
	{		
		if($res=FrontFunctions::getCustomerByToken($_GET['hash'])){		   
			
		   $this->body_class.=" page-material";
		   $this->includeMaterial();
		   
		   $this->render('verification',array(
		     'data'=>$res,
		     'verification_type'=>isset($_GET['type'])?$_GET['type']:''
		   ));
		} else $this->render('error',array(
		  'msg'=>t("token is invalid")
		));		
	}
	
	public function actionSignupTy()
	{
		if($res=FrontFunctions::getCustomerByToken($_GET['hash'])){		  
		   if(isset($_GET['needs_approval'])){
		   	  if($_GET['needs_approval']==1){
		   	  	 $client_id=$res['customer_id'];
		   	  	 $db=new DbExt;
		   	  	 $db->updateData("{{customer}}",array(
		   	  	   'needs_approval'=>1,
		   	  	   'date_modified'=>AdminFunctions::dateNow()
		   	  	 ),'customer_id',$client_id);
		   	  }
		   }
		   $this->render('signupty',array(
		     'needs_approval'=>isset($_GET['needs_approval'])?$_GET['needs_approval']:'',
		     'renew'=>isset($_GET['renew'])?$_GET['renew']:''
		   ));
		} else $this->render('error',array(
		  'msg'=>t("token is invalid")
		));		
	}
	
	public function actionPayment()
	{
		if($res=FrontFunctions::getCustomerByToken($_GET['hash'])){
			
		   /*update plan_renew_id */
		   if(isset($_GET['plan_id'])){
		   	  if(is_numeric($_GET['plan_id'])){
			   	  $db=new DbExt;
			   	  $db->updateData("{{customer}}",array('renew_plan_id'=>$_GET['plan_id']),
			   	  'customer_id',$res['customer_id']);		   	  
			   	  $res['plan_id']=$_GET['plan_id'];
		   	  }
		   }
			
		   $plan_id=$res['plan_id'];
		   $plan_details=FrontFunctions::getPlansByID($plan_id);
		   $this->body_class.=" page-material";
		   $this->includeMaterial();
		   $this->render('payment-details',array(
		     'data'=>$res,
		     'plan_details'=>$plan_details,
		     //'payment_options'=>AdminFunctions::paymentGatewayList()
		     'payment_options'=>AdminFunctions::getEnabledPaymentList()
		   ));
		} else $this->render('error',array(
		  'msg'=>t("token is invalid")
		));		
	}
	
	public function actionpaymentPyp()
	{
		
		if(!isset($_GET['hash'])){
			 $this->render('error',array(
		      'msg'=>t("token is invalid")
		    ));		
			return ;
		}
		
		if($res=FrontFunctions::getCustomerByToken($_GET['hash'])){									
			
			/*check if transaction is renew*/
			if($res['renew_plan_id']>0){
			   $res['plan_id']=$res['renew_plan_id'];
			}
			
			if ($plan_details=FrontFunctions::getPlansByID($res['plan_id'])){
				
				$price=$plan_details['price'];
				if($plan_details['promo_price']>0.0001){
					$price=$plan_details['promo_price'];
				}
				
				$customer_token=$res['token'];
				$customer_id=$res['customer_id'];
				
				/*$db=new DbExt();
				$db->updateData("{{customer}}",array(
				  'plan_price'=>$price
				),'customer_id',$customer_id);*/
								
				if ( $con=FrontFunctions::getPaypalConnection()){
					
					if($currency=FrontFunctions::getCurrenyCode()){
						
																		
					    $params['CANCELURL']="http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/front/payment/?hash=".urlencode($customer_token)."&lang=".Yii::app()->language;
					    $params['RETURNURL']="http://".$_SERVER['HTTP_HOST'].Yii::app()->request->baseUrl."/front/payment-pyp-confirm/?hash=".urlencode($customer_token)."&lang=".Yii::app()->language;
					    
				        $params['NOSHIPPING']='1';
			            $params['LANDINGPAGE']='Billing';
			            $params['SOLUTIONTYPE']='Sole';
			            $params['CURRENCYCODE']=$currency['currency_code'];
			            
			            $x=0;
			            $params['L_NAME'.$x]=$plan_details['plan_name'];
			            $params['L_NUMBER'.$x]=$plan_details['plan_name_description'];
			            $params['L_DESC'.$x]='';
			            $params['L_AMT'.$x]=AdminFunctions::normalPrettyPrice($price);
			            $params['L_QTY'.$x]=1;
			            $params['AMT']=AdminFunctions::normalPrettyPrice($price);
			            			            
			            $paypal=new Paypal($con);
			            $paypal->params=$params;
			            $paypal->debug=false;
			            if ($resp=$paypal->setExpressCheckout()){ 
			            	header('Location: '.$resp['url']);
			            	Yii::app()->end();
			            }  else  $this->render('error',array(
		                           'msg'=>$paypal->getError()
		                        ));					             
						
					} else $this->render('error',array(
		                'msg'=>t("Currency code not yet set")
		            ));		
					
				} else $this->render('error',array(
		            'msg'=>t("Paypal credentials not yet set")
		        ));		
				
			} else $this->render('error',array(
		        'msg'=>t("Total to pay is not valid")
		    ));		
		} else $this->render('error',array(
		  'msg'=>t("token is invalid")
		));		
	}
	
	public function actionpaymentPypConfirm()
	{
		$error='';
		if ( $con=FrontFunctions::getPaypalConnection()){
			if($res=FrontFunctions::getCustomerByToken($_GET['hash'])){									
				
			   /*check if transaction is renew*/
			   if($res['renew_plan_id']>0){
			      $res['plan_id']=$res['renew_plan_id'];
			   }
				
			   $plan_details=FrontFunctions::getPlansByID($res['plan_id']);
			   			  
			   $paypal=new Paypal($con);
			   if ($res_paypal=$paypal->getExpressDetail()){			   	   
			   } else $error=$paypal->getError();
			} else $error=t("Plan details not found");
		} else $error=t("Paypal credentials invalid");
		
		if(empty($error)){
			
			$this->body_class.=" page-material";
		    $this->includeMaterial();
			
			$this->render('pyp-confirm',array(
			  'plan_details'=>$plan_details,
			  'res_paypal'=>$res_paypal,
			  'hash'=>isset($_GET['hash'])?$_GET['hash']:''
			));
		} else $this->render('error',array(
		  'msg'=>$error
		));	
	}
	
	public function actionpaymentStp()
	{
		$error=''; $publish_key='';
		
		$stripe_enabled=trim(getOptionA('stripe_enabled'));
		if ($stripe_enabled==""){
			$error=t("Stripe is disabled");
		}
		
		$stripe_mode=trim(getOptionA('stripe_mode'));
		if ($stripe_mode=="sandbox"){
			$publish_key=trim(getOptionA('stripe_sandbox_publish_key'));
		} else if ($stripe_mode=="live") {
			$publish_key=trim(getOptionA('stripe_live_publish_key'));
		} else $error=t("Stripe mode is not defined");
							
		if($res=FrontFunctions::getCustomerByToken($_GET['hash'])){	
			
			/*check if transaction is renew*/
		    if($res['renew_plan_id']>0){
		       $res['plan_id']=$res['renew_plan_id'];
		    }		    		    
			
			$plan_details=FrontFunctions::getPlansByID($res['plan_id']);						
			$price=$res['plan_price'];
			
			/*check if transaction is renew*/
			if($res['renew_plan_id']>0){
				$price=$plan_details['price'];
				if($plan_details['promo_price']>0.0001){
					$price=$plan_details['promo_price'];
				}
			}
			
			$this->body_class.=" page-material";
		    $this->includeMaterial();
		    		   
		    Yii::app()->clientScript->registerScriptFile(
              "https://js.stripe.com/v2/",
		      CClientScript::POS_END
		    );			
		    			
		} else $error = t("Plan details not found");
		
		if(empty($error)){
			$this->render('stripe-init',array(
			  'plan_details'=>$plan_details,
			  'publish_key'=>$publish_key,
			  'hash'=>isset($_GET['hash'])?$_GET['hash']:'',
			  'price'=>$price
			)); 
		} else $this->render('error',array(
		      'msg'=>$error
		  ));		
			
	}
	
	/*public function missingAction($action_name)
	{
		dump($action_name);
	}*/
	
	public function actionPage()
	{				
		$url=isset($_SERVER['REQUEST_URI'])?explode("/",$_SERVER['REQUEST_URI']):false;
		if(is_array($url) && count($url)>=1){
			$page_slug=$url[count($url)-1];
			$page_slug=str_replace('page-','',$page_slug);			
			if(isset($_GET)){				
				$c=strpos($page_slug,'?');
				if(is_numeric($c)){
					$page_slug=substr($page_slug,0,$c);
				}
			}
			//dump($page_slug);
			if ( $res=AdminFunctions::getCustomPageByPageSlug($page_slug,'published')){
				$this->render('page',array(
				 'data'=>$res
				));
			} else $this->render('error',array(
		       'msg'=>t("Sorry but we cannot find what you are looking for")
		   ));
		} else $this->render('error',array(
		  'msg'=>t("Sorry but we cannot find what you are looking for")
		));
	}
	
	public function actionsetlang()
	{
		if(!empty($_GET['action'])){
			$url=Yii::app()->createUrl("front/".$_GET['action'],array(
			  'lang'=>$_GET['lang']
			));
		} else {
			$url=Yii::app()->createUrl("front/dashboard",array(
			  'lang'=>$_GET['lang']
			));
		}		
		$this->redirect($url);
	}
	
	public function xactionpaymentList()
	{
		
	}
	
} /*end class*/