<?php
if (!isset($_SESSION)) { session_start(); }

class AjaxfrontController extends CController
{
	public $code=2;
	public $msg;
	public $details;
	public $data;
	
	public function __construct()
	{
		$this->data=$_POST;	
	}
	
	public function init()
	{			
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone" );		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 			
		 
		 if(isset($this->data['language'])){
		 	Yii::app()->language=$this->data['language'];
		 }	 
		 unset($this->data['language']);	 
	}
	
	private function jsonResponse()
	{
		$resp=array('code'=>$this->code,'msg'=>$this->msg,'details'=>$this->details);
		echo CJSON::encode($resp);
		Yii::app()->end();
	}
	
	public function actionSignup()
	{		
		if ( $this->data['cpassword']!=$this->data['password']){
			$this->msg=t("Confirm password does not match");
			$this->jsonResponse();
			Yii::app()->end();
		}
		if (isset($this->data['plan_id'])){
			$params=$this->data;
			unset($params['action']);
			unset($params['cpassword']);
			$params['date_created']=AdminFunctions::dateNow();
			$params['ip_address']=$_SERVER['REMOTE_ADDR'];									
			$params['password']=CPasswordHelper::hashPassword($params['password']);			
			$plan_price=FrontFunctions::getPlansPrice($this->data['plan_id']);
			
			$token=md5(AdminFunctions::generateCode(10));
			$verification_code=AdminFunctions::generateNumericCode(5);
			$params['token']=$token;
			$params['verification_code']=$verification_code;
			
			if ($plan_details=FrontFunctions::getPlansByID($params['plan_id'])){							
				$price=$plan_details['price'];
				if($plan_details['promo_price']>0.0001){
					$price=$plan_details['promo_price'];
				}
				$params['plan_price']=$price;
				$days=$plan_details['expiration'];
				$plan_type=$plan_details['plan_type'];
				$params['plan_expiration']=date("Y-m-d",strtotime("+$days $plan_type"));
				$params['plan_currency_code']=FrontFunctions::getCurrenyCode(true);
				$params['with_sms']=$plan_details['with_sms'];
			}
			
			/*dump($params);
			die();*/
			
			$db=new DbExt();
			if ( !FrontFunctions::getCustomerByEmail($this->data['email_address'])){
				if ( $db->insertData("{{customer}}",$params)){
					$customer_id=Yii::app()->db->getLastInsertID();
					$this->code=1;
					$this->msg=t("Registration successul");
					
					$signup_needs_approval=getOptionA('signup_needs_approval');
					$signup_verification_enabled=getOptionA('signup_verification_enabled');
					$signup_verification=getOptionA('signup_verification');
					
					if ( $plan_price>0.0001){
					    $this->details=Yii::app()->createUrl('front/payment',array(
					   'hash'=>$token
					   ));
					} else {
						if ( $signup_needs_approval==1){
							$this->details=Yii::app()->createUrl('front/signupty',array(
							   'hash'=>$token,
							   'needs_approval'=>1
							));							
							FrontFunctions::sendEmailWelcome($this->data);
						} else {
							if ( $signup_verification_enabled==1){
																
								if($signup_verification=="sms"){		
									//send sms verification
									
									$signup_tpl_sms=getOptionA('signup_tpl_sms');
									if(!empty($signup_tpl_sms) && !empty($this->data['mobile_number']) ){
										$company_name=getOptionA('company_name');
										$signup_tpl_sms=smarty('first_name',$params['first_name'],$signup_tpl_sms);
										$signup_tpl_sms=smarty('first_name',$params['first_name'],$signup_tpl_sms);
										$signup_tpl_sms=smarty('verification_code',$verification_code,$signup_tpl_sms);
										$signup_tpl_sms=smarty('company_name',$company_name,$signup_tpl_sms);
										sendSMS($this->data['mobile_number'],$signup_tpl_sms);
									}
									
								} else {									
									//send email verification
									FrontFunctions::sendEmailSignVerification($this->data,$verification_code);
								}
								
								$this->details=Yii::app()->createUrl('front/verification',array(
								  'hash'=>$token,
								  'type'=>$signup_verification
								));
										
							} else {
								$db->updateData("{{customer}}",array(
								  'status'=>'active'
								),'customer_id',$customer_id);
								
								$this->details=Yii::app()->createUrl('front/signupty',array(
								  'hash'=>$token
								));
								
								FrontFunctions::sendEmailWelcome($this->data);
							}
						}
					}
					    
				} else $this->msg=t("Something went wrong during processing your request");
			} else {
				$this->msg=t("Email address already exist in our records");
			}
			
		} else $this->msg=t("Plan id is missing");
		$this->jsonResponse();
	}
	
	public function actionverifySignupCode()
	{		
		if($res=FrontFunctions::getCustomerByToken($this->data['hash'])){			
			$customer_id=$res['customer_id'];			
			if ( $res['verification_code']==$this->data['verification_code']){
				$this->code=1;
				$this->msg=t("Successful");
				
				$signup_needs_approval=getOptionA('signup_needs_approval');
								
				if($signup_needs_approval!=1){
					$db=new DbExt();
					$db->updateData("{{customer}}",array(
					  'status'=>'active',
					  'verification_confirm_date'=>AdminFunctions::dateNow()
					),'customer_id',$customer_id);
				}
				
				$this->details=Yii::app()->createUrl('front/signupty',array(
				  'hash'=>$this->data['hash'],
				  'needs_approval'=>$signup_needs_approval
				));
				
				FrontFunctions::sendEmailWelcome($res);
				
			} else $this->msg=t("Verification code is invalid");
		} else $this->msg=t("Token not found");
		$this->jsonResponse();
	}
	
	public function actionpaymentOption()
	{		
		if(empty($this->data['payment_provider'])){
			$this->msg=t("Please choose payment options");
			$this->jsonResponse();
			Yii::app()->end();
		}
		if($res=FrontFunctions::getCustomerByToken($this->data['token'])){				
			$this->code=1;
			$this->msg=t("Please wait while we redirect you");
			$this->details=Yii::app()->createUrl('front/payment-'.$this->data['payment_provider'],array(
			  'hash'=>$this->data['token'],
			  'lang'=>Yii::app()->language
			));
		} else $this->msg=t("Token not found");
		$this->jsonResponse();
	}
	
	public function actionpaypalExpressCheckout()
	{		
		$db=new DbExt();
		$signup_needs_approval=getOptionA('signup_needs_approval');
		if ( $con=FrontFunctions::getPaypalConnection()){
			if($res=FrontFunctions::getCustomerByToken($this->data['hash'])){
				$customer_id=$res['customer_id'];			
				$currency_code=FrontFunctions::getCurrenyCode(true);				
				$paypal=new Paypal($con);	
				$paypal->debug=false;			
				$paypal->params['PAYERID']=$this->data['payerid'];
	            $paypal->params['AMT']=AdminFunctions::normalPrettyPrice($this->data['amt']);   
	            $paypal->params['TOKEN']=$this->data['token'];     
	            $paypal->params['CURRENCYCODE']=$currency_code;
	            
	            /*dump($this->data['amt']);
	            die();*/
	            if ($resp_paypal=$paypal->expressCheckout()){  
	            	
	            	//dump($resp_paypal);
	            	
	            	  /*check if transaction is renew*/
		            if($res['renew_plan_id']>0){
		               if ($plan_details=FrontFunctions::getPlansByID($res['renew_plan_id'])){	
		               	    $price=$plan_details['price'];
						    if($plan_details['promo_price']>0.0001){
								$price=$plan_details['promo_price'];
							}					
									
							$days=$plan_details['expiration'];
							$plan_type=$plan_details['plan_type'];
							
							$renew_params['plan_price']=$price;
							$renew_params['plan_id']=$res['renew_plan_id'];
							$renew_params['plan_expiration']=date("Y-m-d",strtotime($res['plan_expiration']." +$days $plan_type"));
							$renew_params['plan_currency_code']=FrontFunctions::getCurrenyCode(true);
							$renew_params['status']="active";
						
							$db->updateData("{{customer}}",$renew_params,'customer_id',$customer_id);
							
							$this->code=1;
							$this->msg=t("Payment successful");
							$this->details=Yii::app()->createUrl('front/signupty',array(
							  'hash'=>$this->data['hash'],
							  'renew'=>1
							));
							
							$memo="Payment by ".$res['first_name']." ".$res['last_name'];					
							FrontFunctions::savePaymentLogs($customer_id,
							  'signup','pyp',$memo,
							  $price,
							  $currency_code,
							  $resp_paypal['TRANSACTIONID']
							);
							
		               } else $this->msg=t("Payment is successful but cannot find plan details");
		                 
		            } else {
	            	
		            	if ($signup_needs_approval!=1){		            	
							$db->updateData("{{customer}}",array(
							  'status'=>'active',
							  'verification_confirm_date'=>AdminFunctions::dateNow()
							),'customer_id',$customer_id);
		            	}
						$this->code=1;
						$this->msg=t("Payment successful");
						$this->details=Yii::app()->createUrl('front/signupty',array(
						  'hash'=>$this->data['hash'],
						  'needs_approval'=>$signup_needs_approval
						));
						
						$memo="Payment by ".$res['first_name']." ".$res['last_name'];					
						FrontFunctions::savePaymentLogs($customer_id,
						  'signup','pyp',$memo,
						  $res['plan_price'],
						  $currency_code,
						  $resp_paypal['TRANSACTIONID']
						);
						
						FrontFunctions::sendEmailWelcome($res);
		            }
	            	
	            } else $this->msg=$paypal->getError();
			} else $this->details=t("Plan details not found");
		} else $this->msg=t("Paypal credentials invalid");
		$this->jsonResponse();
	}
	
	public function actionPaymentStripe()
	{	
		$db=new DbExt();
		if (isset($this->data['stripe_token'])){		 	
			if($res=FrontFunctions::getCustomerByToken($this->data['hash'])){		
				$customer_id=$res['customer_id'];
				$original_amount=$res['plan_price'];
				$amount=AdminFunctions::normalPrettyPrice($res['plan_price']);
				
				if($res['renew_plan_id']>0){
					if ($plan_details=FrontFunctions::getPlansByID($res['renew_plan_id'])){	
					$price=$plan_details['price'];
					    if($plan_details['promo_price']>0.0001){
							$price=$plan_details['promo_price'];
						}			
					}
					$amount=AdminFunctions::normalPrettyPrice($price);
				}
								
				if($amount>0.0001){
					$amount=$amount*100;
				} else $amount=0;
								
				try {
				    	
					$stripe_mode=getOptionA('stripe_mode'); $secret_key='';
					if ($stripe_mode=="sandbox"){
						$secret_key=getOptionA('stripe_sandbox_secret_key');
					} else {
						$secret_key=getOptionA('stripe_live_secret_key');
					} 
					
					$currency_code=FrontFunctions::getCurrenyCode(true);
										
					require_once('stripe/lib/Stripe.php');
					Stripe::setApiKey($secret_key);
					
					$customer = Stripe_Customer::create(array(			    
			         'card'  => $this->data['stripe_token']
			        ));
			        
			        $charge = Stripe_Charge::create(array(
			          'customer' => $customer->id,
			          'amount'   => $amount,
			          'currency' => $currency_code
			        ));	
			        
			        $chargeArray = $charge->__toArray(true);			        			        
			        $ref_id=$chargeArray['id'];
			        
			        /*check if renew*/
			        if($res['renew_plan_id']>0){
			        	
			        	if ($plan_details=FrontFunctions::getPlansByID($res['renew_plan_id'])){	
		               	    $price=$plan_details['price'];
						    if($plan_details['promo_price']>0.0001){
								$price=$plan_details['promo_price'];
							}					
									
							$days=$plan_details['expiration'];
							$plan_type=$plan_details['plan_type'];
							
							$renew_params['plan_price']=$price;
							$renew_params['plan_id']=$res['renew_plan_id'];
							$renew_params['plan_expiration']=date("Y-m-d",strtotime($res['plan_expiration']." +$days $plan_type"));
							$renew_params['plan_currency_code']=FrontFunctions::getCurrenyCode(true);
							$renew_params['status']="active";
						
							$db->updateData("{{customer}}",$renew_params,'customer_id',$customer_id);
							
							$this->code=1;
							$this->msg=t("Payment successful");
							$this->details=Yii::app()->createUrl('front/signupty',array(
							  'hash'=>$this->data['hash'],
							  'renew'=>1
							));
							
							$memo="Payment by ".$res['first_name']." ".$res['last_name'];					
							FrontFunctions::savePaymentLogs($customer_id,
							  'signup','stp',$memo,
							  $price,
							  $currency_code,
							  $ref_id
							);
							
		                } else $this->msg=t("Payment is successful but cannot find plan details");
			        				        	
			        	$this->jsonResponse();
			        	Yii::app()->end();
			        } /*end renew*/
			        			        
			        $signup_needs_approval=getOptionA('signup_needs_approval');
	            	if ($signup_needs_approval!=1){		            	
						$db->updateData("{{customer}}",array(
						  'status'=>'active',
						  'verification_confirm_date'=>AdminFunctions::dateNow()
						),'customer_id',$customer_id);
	            	}
					$this->code=1;
					$this->msg=t("Payment successful");
					$this->details=Yii::app()->createUrl('front/signupty',array(
					  'hash'=>$this->data['hash'],
					  'needs_approval'=>$signup_needs_approval
					));
					
					$memo="Payment by ".$res['first_name']." ".$res['last_name'];					
					FrontFunctions::savePaymentLogs($customer_id,
					  'signup','stp',$memo,
					  $res['plan_price'],
					  $currency_code,
					  $ref_id
					);
					
					FrontFunctions::sendEmailWelcome($res);
			        
				} catch (Exception $e)   {
					 $this->msg=$e->getMessage();
				}
				
			} else $this->msg=t("Plan details not found");
		} else $this->msg=t("Stripe token is invalid");
		$this->jsonResponse();
	}
	
	public function actionResendCode()
	{
		
		if ($res=FrontFunctions::getCustomerByToken($this->data['hash'])){
			
			$verification_code=$res['verification_code'];
			if($this->data['verification_type']=="mail"){				
				FrontFunctions::sendEmailSignVerification($res,$verification_code);
				$this->code=1;
				$this->msg=t("We have sent your verification code to your email");
			} else if ($this->data['verification_type']=="sms") {
				
				$signup_tpl_sms=getOptionA('signup_tpl_sms');
				if(!empty($signup_tpl_sms) && !empty($res['mobile_number']) ){
					$company_name=getOptionA('company_name');
					$signup_tpl_sms=smarty('first_name',$res['first_name'],$signup_tpl_sms);
					$signup_tpl_sms=smarty('first_name',$res['first_name'],$signup_tpl_sms);
					$signup_tpl_sms=smarty('verification_code',$verification_code,$signup_tpl_sms);
					$signup_tpl_sms=smarty('company_name',$company_name,$signup_tpl_sms);
					sendSMS($res['mobile_number'],$signup_tpl_sms);					
					$this->code=1;
				    $this->msg=t("We have sent your verification code to your mobile");
				} else $this->msg=t("SMS template not available");
				
			} else $this->msg=t("Invalid verification type");
		} else $this->msg=t("hash not found");
		$this->jsonResponse();
	}
	
	public function actiongetSignup()
	{		
		if($res=FrontFunctions::getCustomerByEmail($this->data['email_address'])){						
			if($res['status']=="pending"){
				$this->code=1;
				$this->msg=t("Application found");
				$this->details=Yii::app()->createUrl('/front/payment',array(
				  'hash'=>$res['token'],
				  'lang'=>Yii::app()->language
				));
			} else $this->msg=t("Your application found but the status is already")." ".t($res['status']);
		} else $this->msg=t("Email not found");
		$this->jsonResponse();
	}
	
}/* end class*/