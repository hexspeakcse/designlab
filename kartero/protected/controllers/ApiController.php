<?php
class ApiController extends CController
{	
	public $data;
	public $code=2;
	public $msg='';
	public $details='';
	
	public function __construct()
	{
		$this->data=$_GET;
		
		$website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 
	    if (!empty($website_timezone)){
	 	   Yii::app()->timeZone=$website_timezone;
	    }		 
	    
	    if(isset($_GET['lang_id'])){
		 	Yii::app()->language=$_GET['lang_id'];
		}	    
	}
	
	public function beforeAction($action)
	{				
		/*check if there is api has key*/	
		$action=Yii::app()->controller->action->id;
		$continue=true;
		if($action=="getLanguageSettings" || $action=="GetAppSettings"){
	   	   $continue=false;
	    }
	    if($continue){	    	
	    	$key=getOptionA('mobile_api_key');
	    	if(!empty($key)){
		    	if(!isset($this->data['api_key'])){
		    		$this->data['api_key']='';
		    	}
		    	if(trim($key)!=trim($this->data['api_key'])){
				   $this->msg=$this->t("api hash key is not valid");
			       $this->output();
			       Yii::app()->end();
				}
	    	}
	    }
		return true;
	}	
	
	public function actionIndex(){
		echo 'Api is working';
	}		
	
	private function q($data='')
	{
		return Yii::app()->db->quoteValue($data);
	}
	
	private function t($message='')
	{
		return Yii::t("default",$message);
	}
		
    private function output()
    {
	   $resp=array(
	     'code'=>$this->code,
	     'msg'=>$this->msg,
	     'details'=>$this->details,
	     'request'=>json_encode($this->data)		  
	   );		   
	   if (isset($this->data['debug'])){
	   	   dump($resp);
	   }
	   
	   if (!isset($_GET['callback'])){
  	   	   $_GET['callback']='';
	   }    
	   
	   if (isset($_GET['json']) && $_GET['json']==TRUE){
	   	   echo CJSON::encode($resp);
	   } else echo $_GET['callback'] . '('.CJSON::encode($resp).')';		    	   	   	  
	   Yii::app()->end();
    }		
    
    public function actionLogin()
    {
    	if(!empty($this->data['username']) && !empty($this->data['password'])){
	    	if ( $res=Driver::driverAppLogin($this->data['username'],$this->data['password'])){	
	    		$token=md5(Driver::generateRandomNumber(5) . $this->data['username']);
	    		$params=array(
	    		  'last_login'=>date('c'),
	    		  'last_online'=>strtotime("now"),
	    		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
	    		  'token'=>$token,
	    		  'device_id'=>isset($this->data['device_id'])?$this->data['device_id']:'',
	    		  'device_platform'=>isset($this->data['device_platform'])?$this->data['device_platform']:'Android'
	    		);	    		
	    		if(!empty($res['token'])){
	    			unset($params['token']);
	    			$token=$res['token'];
	    		}
	    		$db=new DbExt;
	    		if ( $db->updateData("{{driver}}",$params,'driver_id',$res['driver_id'])){	    			
	    			$this->code=1;
	    			$this->msg=self::t("Login Successful");
	    			
	    			//get location accuracy
	    			$location_accuracy=2;
	    			if ( $team=Driver::getTeam($res['team_id'])){
	    				//dump($team);
	    				if($team['location_accuracy']=="high"){
	    					$location_accuracy=1;
	    				}
	    			}
	    			
	    			$this->details=array(
	    			  'username'=>$this->data['username'],
	    			  'password'=>$this->data['password'],
	    			  'remember'=>isset($this->data['remember'])?$this->data['remember']:'',
	    			  'todays_date'=>Yii::app()->functions->translateDate(date("M, d")),
	    			  'todays_date_raw'=>date("Y-m-d"),
	    			  'on_duty'=>$res['on_duty'],
	    			  'token'=>$token,
	    			  'duty_status'=>$res['on_duty'],
	    			  'location_accuracy'=>$location_accuracy
	    			);
	    		} else $this->msg=self::t("Login failed. please try again later");
	    	} else $this->msg=self::t("Login failed. either username or password is incorrect");
    	} else $this->msg=self::t("Please fill in your username and password");
    	$this->output();
    }
    
    public function actionForgotPassword()
    {
    	if (empty($this->data['email'])){
    		$this->msg=self::t("Email address is required");
    		$this->output();
    		Yii::app()->end();
    	}
    	$db=new DbExt;    	
    	if ( $res=Driver::driverForgotPassword($this->data['email'])){
    		$driver_id=$res['driver_id'];    		
    		$code=Driver::generateRandomNumber(5);
    		$params=array('forgot_pass_code'=>$code);
    		if($db->updateData('{{driver}}',$params,'driver_id',$driver_id)){
    			$this->code=1;
    			$this->msg=self::t("We have send the a password change code to your email");
    			
    			$tpl=EmailTemplate::forgotPasswordRequest();
    			$tpl=smarty('first_name',$res['first_name'],$tpl);
    			$tpl=smarty('code',$code,$tpl);
    			$subject='Forgot Password';
    			if ( sendEmail($res['email'],'',$subject,$tpl)){
    				$this->details="send email ok";
    			} else $this->msg="send email failed";
    			
    		} else $this->msg=self::t("Something went wrong please try again later");
    	} else $this->msg=self::t("Email address not found");
    	$this->output();
    }
    
    public function actionChangePassword()
    {    	
    	$Validator=new Validator;
    	$req=array(
    	  'email_address'=>self::t("Email address is required"),
    	  'code'=>self::t("Code is required"),
    	  'newpass'=>self::t("New Password is required")
    	);
    	$Validator->required($req,$this->data);
    	if ( $Validator->validate()){
    		if ( $res=Driver::driverForgotPassword($this->data['email_address'])){    			
    			if ( $res['forgot_pass_code']==$this->data['code']){
    				$params=array( 
    				  'password'=>md5($this->data['newpass']),
    				  'date_modified'=>date('c'),
    				  'forgot_pass_code'=>Driver::generateRandomNumber(5)
    				 );
    				$db=new DbExt;    				
    				if ( $db->updateData("{{driver}}",$params,'driver_id',$res['driver_id'])){
    				    $this->code=1;
    				    $this->msg=self::t("Password successfully changed");
    				} else $this->msg=self::t("Something went wrong please try again later");    				
    			} else $this->msg=self::t("Invalid password code");
    		} else $this->msg=self::t("Email address not found");
    	} else $this->msg=Driver::parseValidatorError($Validator->getError());		
    	$this->output();
    }
    
    public function actionChangeDutyStatus()
    {    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	} 
    	$driver_id=$token['driver_id'];
    	$params=array(
    	  'on_duty'=>isset($this->data['onduty'])?$this->data['onduty']:2,
    	  'last_online'=>strtotime("now")
    	);
    	if ( $this->data['onduty']==2){
    		$params['last_online']=time() - 300;
    	}
    	$db=new DbExt;
    	if ( $db->updateData('{{driver}}',$params,'driver_id',$driver_id)){
    		$this->code=1;
    		$this->msg="OK";
    		$this->details=$this->data['onduty'];
    	} else $this->msg=self::t("Something went wrong please try again later");   
    	$this->output();
    }
    
    public function actionGetTaskByDate()
    {    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];    	
    	
    	if (isset($this->data['onduty'])){
    		if ($this->data['onduty']==1){
    	        Driver::updateLastOnline($driver_id);
    		}
    	}
    	
    	//if ( $res=Driver::getTaskByDriverID($driver_id,$this->data['date'])){
    	if ( $res=Driver::getTaskByDriverIDWithAssigment($driver_id,$this->data['date'])){
    		$this->code=1;
    		$this->msg="OK";
    		$data='';
    		foreach ($res as $val) {
    			$val['delivery_time']=Yii::app()->functions->timeFormat($val['delivery_date'],true);
    			$val['status_raw']=$val['status'];
    			$val['status']=self::t($val['status']);    			
    			$val['trans_type_raw']=$val['trans_type'];
    			$val['trans_type']=self::t($val['trans_type']);    			
    			$data[]=$val;
    		}
    		$this->details=$data;
    	} else $this->msg=self::t("No task for the day");
    	$this->output();
    }
    
    public function actionviewTaskDescription()
    {
    	$this->actionTaskDetails();
    }
    public function actionTaskDetails()
    {    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}   
    	
    	if (isset($this->data['task_id'])){
    		if ( $res=Driver::getTaskId($this->data['task_id']) ){
    			
    			//check task belong to current driver    			    			
    			if ( $res['status']!="unassigned"){
	    			$driver_id=$token['driver_id'];
	    			if ($driver_id!=$res['driver_id']){
	    				$this->msg=Driver::t("Sorry but this task is already been assigned to others");
	    				$this->output();
	    				Yii::app()->end();
	    			}    			
    			}
    			
    			$this->code=1;
    			$this->msg=self::t("Task").":".$this->data['task_id'];
    			
    			$res['delivery_time']=Yii::app()->functions->timeFormat($res['delivery_date'],true);    			
    			$res['status_raw']=$res['status'];
    			$res['status']=self::t($res['status']);    			
    			$res['trans_type_raw']=$res['trans_type'];
    			$res['trans_type']=self::t($res['trans_type']);
    			
    			$res['history']=Driver::getDriverTaskHistory($this->data['task_id']);
    			
    			/*get signature if any*/
    			$res['customer_signature_url']='';
    			if (!empty($res['customer_signature'])){
    				$res['customer_signature_url']=Driver::uploadURL()."/".$res['customer_signature'];
    				if (!file_exists(Driver::uploadPath()."/".$res['customer_signature'])){
    					$res['customer_signature_url']='';
    				}
    			}
    			    						
    			$this->details=$res;
    		} else $this->msg=self::t("Task not found");
    	} else $this->msg=self::t("Task id is missing");
    	$this->output();
    }
	
    public function actionChangeTaskStatus()
    {
    	
    	if(isset($_GET['debug'])){
    	   dump($this->data);
    	}
    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];    	
    	$team_id=$token['team_id']; 
    	$driver_name=$token['first_name'] ." " .$token['last_name'];    	
    	
    	$db=new DbExt;	
    	
    	if (isset($this->data['status_raw']) && isset($this->data['task_id'])){
    		
    		$task_id=$this->data['task_id'];
    		$task_info=Driver::getTaskId($task_id);
    		if(!$task_info){
    			$this->msg=self::t("Task not found");
    			$this->output();
    			Yii::app()->end();
    		}    		
    		
    		$params_history='';    		
    		$params_history['ip_address']=$_SERVER['REMOTE_ADDR'];
    	    $params_history['date_created']=date('c');
    	    $params_history['task_id']=$task_id;    	    
    	    $params_history['driver_id']=$driver_id;        	    
    	    $params_history['driver_location_lat']=isset($token['location_lat'])?$token['location_lat']:'';
    	    $params_history['driver_location_lng']=isset($token['location_lng'])?$token['location_lng']:'';
    	    
    				
    		
    		switch ($this->data['status_raw']) {
    			
    			case "failed":
    			case "cancelled":    	
    			   $params=array('status'=>$this->data['status_raw']);    				
    				// update task id
    				$db->updateData("{{driver_task}}",$params,'task_id',$task_id);
    				
    				$remarks=Driver::driverStatusPretty($driver_name,$this->data['status_raw']);    				
    				$params_history['status']=$this->data['status_raw'];
    				$params_history['remarks']=$remarks; 			    				
    				$params_history['reason']=isset($this->data['reason'])?$this->data['reason']:'' ; 
    				// insert history    				
    				$db->insertData("{{task_history}}",$params_history);
    				
    				$this->code=1;
    				$this->msg="OK";
    				$this->details=array(
    				  'task_id'=>$this->data['task_id'],
    				  'status_raw'=>$params['status'],
    				  'reload_functions'=>'getTodayTask'
    				);    				
    				
    				//send notification to customer
    				if ( $task_info['trans_type']=="delivery"){    					
    				    Driver::sendNotificationCustomer('DELIVERY_FAILED',$task_info);
    				} else {
    					Driver::sendNotificationCustomer('PICKUP_FAILED',$task_info);
    				}
    							
    				break;
    				
    			case "declined":
    				
    				if ( $assigment_info=Driver::getAssignmentByDriverTaskID($driver_id,$task_id)){
    					
    					$stmt_assign="UPDATE 
    					{{driver_assignment}}
    					SET task_status='declined',
    					date_process=".Driver::q(date('c')).",
    					ip_address=".Driver::q($_SERVER['REMOTE_ADDR'])."
    					WHERE
    					task_id=".Driver::q($task_id)."
    					AND
    					driver_id=".Driver::q($driver_id)."
    					";
    					//dump($stmt_assign);
    					$db->qry($stmt_assign);
    					
    					$this->code=1;
	    				$this->msg="OK";
	    				$this->details=array(
	    				  'task_id'=>$this->data['task_id'],
	    				  'status_raw'=>'declined',
	    				  'reload_functions'=>'getTodayTask'
	    				);    				
	    				
    				} else {
	    				$params=array('status'=>"declined");    				
	    				// update task id
	    				$db->updateData("{{driver_task}}",$params,'task_id',$task_id);
	    				
	    				$remarks=Driver::driverStatusPretty($driver_name,'declined');    				
	    				$params_history['status']='declined';
	    				$params_history['remarks']=$remarks;    				    				
	    				// insert history    				
	    				$db->insertData("{{task_history}}",$params_history);
	    				
	    				$this->code=1;
	    				$this->msg="OK";
	    				$this->details=array(
	    				  'task_id'=>$this->data['task_id'],
	    				  'status_raw'=>$params['status'],
	    				  'reload_functions'=>'getTodayTask'
	    				);    				
	    				
	    				//send email to admin or merchant
    				}
    				
    				break;
    				
    				
    			case "acknowledged":    		
    			
    			    // double check if someone has already the accept task   			    
    			    if($task_info['status']!="unassigned"){        			    	
    			    	if ( $task_info['driver_id']!=$driver_id){			    	
    			           $this->msg=Driver::t("Sorry but this task is already been assigned to others");
    			           $this->output();
    			    	   Yii::app()->end();
    			    	}
    			    }
    			    
    				$params=array(
    				  'driver_id'=>$driver_id,
    				  'status'=>"acknowledged",
    				  'team_id'=>$team_id
    				);    	
    				
    				// update task id    				
    				$db->updateData("{{driver_task}}",$params,'task_id',$task_id);
    				
    				$remarks=Driver::driverStatusPretty($driver_name,'acknowledged');
    				$params_history['status']='acknowledged';
    				$params_history['remarks']=$remarks;    				    				
    				// insert history     				
    				$db->insertData("{{task_history}}",$params_history);
    				
    				$this->code=1;
    				$this->msg="OK";
    				$this->details=array(
    				  'task_id'=>$this->data['task_id'],
    				  'status_raw'=>$params['status'],
    				  'reload_functions'=>'TaskDetails'
    				);    				
    				
    				//update driver_assignment
    				$stmt_assign="UPDATE
    				{{driver_assignment}}
    				SET task_status='acknowledged'
    				WHERE task_id=".Driver::q($task_id)."
    				";
    				$db->qry($stmt_assign);
    				
    				//send notification to customer
    				if ( $task_info['trans_type']=="delivery"){  
    				   Driver::sendNotificationCustomer('DELIVERY_REQUEST_RECEIVED',$task_info);
    				} else {
    				   Driver::sendNotificationCustomer('PICKUP_REQUEST_RECEIVED',$task_info);
    				}
    				
    				break;
    				
    			case "started":	
    			    $params=array('status'=>"started");
    			    $db->updateData("{{driver_task}}",$params,'task_id',$task_id);
    				// update task id
    				
    				$remarks=Driver::driverStatusPretty($driver_name,'started');   
    				$params_history['status']='started';
    				$params_history['remarks']=$remarks;    				
    				// insert history
    				$db->insertData("{{task_history}}",$params_history);
    				
    				$this->code=1;
    				$this->msg="OK";
    				$this->details=array(
    				  'task_id'=>$this->data['task_id'],
    				  'status_raw'=>$params['status'],
    				  'reload_functions'=>'TaskDetails'
    				);    		
    				
    				//send notification to customer
    				if ( $task_info['trans_type']=="delivery"){  
    				    Driver::sendNotificationCustomer('DELIVERY_DRIVER_STARTED',$task_info);
    				} else {
    					Driver::sendNotificationCustomer('PICKUP_DRIVER_STARTED',$task_info);
    				}
    						
    				break;    			   
    		
    			case "inprogress":
    				 $params=array('status'=>"inprogress");
    				 $db->updateData("{{driver_task}}",$params,'task_id',$task_id);
    				// update task id
    				
    				$remarks=Driver::driverStatusPretty($driver_name,'inprogress');    				
    				$params_history['status']='inprogress';
    				$params_history['remarks']=$remarks;    				
    				// insert history
    				$db->insertData("{{task_history}}",$params_history);
    				
    				$this->code=1;
    				$this->msg="OK";
    				$this->details=array(
    				  'task_id'=>$this->data['task_id'],
    				  'status_raw'=>$params['status'],
    				  'reload_functions'=>'TaskDetails'
    				);    			
    				
    				//send notification to customer
    				if ( $task_info['trans_type']=="delivery"){  
    				   Driver::sendNotificationCustomer('DELIVERY_DRIVER_ARRIVED',$task_info);
    				} else {
    				   Driver::sendNotificationCustomer('PICKUP_DRIVER_ARRIVED',$task_info);
    				}
    				
    				break;
    				
    			case "successful":	    			   
    			    $params=array('status'=>"successful");
    			    $db->updateData("{{driver_task}}",$params,'task_id',$task_id);
    				// update task id
    				
    				$remarks=Driver::driverStatusPretty($driver_name,'successful');    				
    				$params_history['status']='successful';
    				$params_history['remarks']=$remarks;    				
    				// insert history
    				$db->insertData("{{task_history}}",$params_history);
    				
    				$this->code=1;
    				$this->msg="OK";
    				$this->details=array(
    				  'task_id'=>$this->data['task_id'],
    				  'status_raw'=>$params['status'],
    				  'reload_functions'=>'getTodayTask'
    				);    			
    				
    				//send notification to customer
    				if ( $task_info['trans_type']=="delivery"){  
    				    Driver::sendNotificationCustomer('DELIVERY_SUCCESSFUL',$task_info);
    				} else {
    					Driver::sendNotificationCustomer('PICKUP_SUCCESSFUL',$task_info);
    				}
    				
    				break;
    				   
    			default:
    				$this->msg=self::t("Missing status");
    				break;
    		}
    	} else $this->msg=self::t("Missing parameters");
    	
    	$this->output();
    }
    
    public function actionAddSignatureToTask()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];    	
    	
    	if ( isset($this->data['image'])){
	    	$path_to_upload=Yii::getPathOfAlias('webroot')."/upload";      	
	    	if (!file_exists($path_to_upload)){
	    		if (!@mkdir($path_to_upload,0777)){           	    
	    			$this->msg=self::t("Failed cannot create folder"." ".$path_to_upload);
           	        Yii::app()->end();
                }		    
	    	}
	    	
	    	$filename="signature_".$this->data['task_id'] . "-" . Driver::generateRandomNumber(10) .".png";
	    	//$filename="signature_".$this->data['task_id'] . "-.png";
	    	
	    	/*$img = $this->data['image'];
	    	$img = str_replace('data:image/png;base64,', '', $img);
	        $img = str_replace(' ', '+', $img);
	        $data = base64_decode($img);
	        @file_put_contents($path_to_upload."/$filename", $data);*/
	    	
	    	
	    	$img = $this->data['image'];	   	    	
	    	Driver::base30_to_jpeg($img, $path_to_upload."/$filename");	    	
	        	        
	        $params=array(
	          'customer_signature'=>$filename,
	          'date_modified'=>date('c'),
	          'ip_address'=>$_SERVER['REMOTE_ADDR']
	        );
	        
	        $task_id=$this->data['task_id'];	  
	        $driver_name=$token['first_name'] ." " .$token['last_name'];         

	        $db=new DbExt;		        
	        
	        $task_id=$this->data['task_id'];
    		$task_info=Driver::getTaskId($task_id);
    		if(!$task_info){
    			$this->msg=self::t("Task not found");
    			$this->output();
    			Yii::app()->end();
    		}    		
	        
	        if ( $db->updateData("{{driver_task}}",$params,'task_id',$task_id)){
		        $this->code=1;
		        $this->msg="Successful";      
		        $this->details=$this->data['task_id'];	
		        
		        $remarks=Driver::driverStatusPretty($driver_name,'sign');  
		        $params_history=array(
		           'status'=>'sign',
		           'remarks'=>$remarks,
		           'date_created'=>date('c'),
		           'ip_address'=>$_SERVER['REMOTE_ADDR'],
		           'task_id'=>$task_id,
		           'customer_signature'=>$filename ,		           
		           'driver_id'=>$driver_id,
		           'driver_location_lat'=>isset($token['location_lat'])?$token['location_lat']:'',
		           'driver_location_lng'=>isset($token['location_lng'])?$token['location_lng']:''
		        );
                $db->insertData("{{task_history}}",$params_history);
		        	       
	        } else $this->msg=self::t("Something went wrong please try again later");
	        
    	} else $this->msg=self::t("Signature is required");
    	$this->output();     
    }
    
    public function actionCalendarTask()
    {    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];    	
    	
    	if (isset($this->data['start']) && isset($this->data['end'])){
    		$start=$this->data['start'] ." 00:00:00";
    		$end=$this->data['end'] ." 23:59:00";    		
    		$data='';
    		if ( $res=Driver::getDriverTaskCalendar($driver_id,$start,$end)){
    			//dump($res);
    			 foreach ($res as $val) {    			 	
    			 	$data[]=array(
    			 	  'title'=> Driver::getTotalTaskByDate($driver_id,$val['delivery_date']),
    			 	  'id'=>$val['delivery_date'],
    			 	  'year'=>date("Y",strtotime($val['delivery_date'])),
    			 	  'month'=>date("m",strtotime($val['delivery_date'] ." -1 months" )),
    			 	  'day'=>date("d",strtotime($val['delivery_date'])),
    			 	);
    			 }
    			 $this->code=1;
    			 $this->msg="OK";
    			 $this->details=$data;
    		}
    	} else $this->msg=self::t("Missing parameters");
    	
    	$this->output();     
    }
    
    public function actionGetProfile()
    {    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];    	
    	$info=Driver::driverInfo($driver_id);    	
    	$this->code=1;
    	$this->msg="OK";
    	$this->details=array(
    	  'team_name'=>$info['team_name'],
    	  'email'=>$info['email'],
    	  'phone'=>$info['phone'],
    	  'transport_type_id'=>$info['transport_type_id'],
    	  'transport_type_id2'=>ucwords(self::t($info['transport_type_id'])),
    	  'transport_description'=>$info['transport_description'],
    	  'licence_plate'=>$info['licence_plate'],
    	  'color'=>$info['color'],
    	);
    	$this->output();     
    }
    
    public function actionGetTransport()
    {    	
    	$this->code=1;
    	$this->code=1;
    	$this->details=Driver::transportType();
    	$this->output();     
    }
    
    public function actionUpdateProfile()
    {    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id']; 
    	
    	$Validator=new Validator;
    	$req=array(
    	  'phone'=>self::t("Phone is required")    	  
    	);
    	$Validator->required($req,$this->data);
    	if ( $Validator->validate()){
    		$params=array(
    		  'phone'=>$this->data['phone'],
    		  'date_modified'=>date('c'),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR']
    		);
    		$db=new DbExt;
    		if ( $db->updateData("{{driver}}",$params,'driver_id',$driver_id)){
    			$this->code=1;
    			$this->msg=self::t("Profile Successfully updated");
    		} else $this->msg=self::t("Something went wrong please try again later");
    	} else $this->msg=Driver::parseValidatorError($Validator->getError());
    	$this->output();     
    }
    
    public function actionUpdateVehicle()
    {    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id']; 
    	
    	$Validator=new Validator;
    	$req=array(
    	  'transport_type_id'=>self::t("Transport Type is required"),
    	  'transport_description'=>self::t("Description is required"),
    	  /*'licence_plate'=>self::t("License Plate is required"),
    	  'color'=>self::t("Color is required"),*/
    	);
    	if ( $this->data['transport_type_id']=="truck"){
    		unset($req);
    		$req=array(
    		  'transport_type_id'=>self::t("Transport Type is required")
    		);
    	}
    	$Validator->required($req,$this->data);
    	if ( $Validator->validate()){
    		$params=array(
    		  'transport_type_id'=>$this->data['transport_type_id'],
    		  'transport_description'=>$this->data['transport_description'],
    		  'licence_plate'=>isset($this->data['licence_plate'])?$this->data['licence_plate']:'',
    		  'color'=>isset($this->data['color'])?$this->data['color']:'',
    		  'date_modified'=>date('c'),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR']
    		);
    		$db=new DbExt;
    		if ( $db->updateData("{{driver}}",$params,'driver_id',$driver_id)){
    			$this->code=1;
    			$this->msg=self::t("Vehicle Info updated");
    		} else $this->msg=self::t("Something went wrong please try again later");
    	} else $this->msg=Driver::parseValidatorError($Validator->getError());
    	$this->output();     
    }
    
    public function actionProfileChangePassword()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id']; 
    	
    	$Validator=new Validator;
    	$req=array(
    	  'current_pass'=>self::t("Current password is required"),
    	  'new_pass'=>self::t("New password is required"),
    	  'confirm_pass'=>self::t("Confirm password is required")    	  
    	);    	
    	if ( $this->data['new_pass']!=$this->data['confirm_pass']){
    		$Validator->msg[]=self::t("Confirm password does not macth with your new password");
    	}
    	
    	$Validator->required($req,$this->data);
    	if ( $Validator->validate()){
    		    		    		
    		if (!Driver::driverAppLogin($token['username'],$this->data['current_pass'])){
    			$this->msg=self::t("Current password is invalid");
    			$this->output();     
    			Yii::app()->end();
    		}    		
    		$params=array(
    		  'password'=>md5($this->data['new_pass']),
    		  'date_modified'=>date('c'),
    		  'ip_address'=>$_SERVER['REMOTE_ADDR']
    		);
    		$db=new DbExt;
    		if ( $db->updateData("{{driver}}",$params,'driver_id',$driver_id)){
    			$this->code=1;
    			$this->msg=self::t("Password Successfully Changed");
    			$this->details=$this->data['new_pass'];
    		} else $this->msg=self::t("Something went wrong please try again later");
    	} else $this->msg=Driver::parseValidatorError($Validator->getError());
    	$this->output();     
    }
    
    public function actionSettingPush()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id']; 
    	    	
    	$params=array(
    	  'enabled_push'=>$this->data['enabled_push'],
    	  'date_modified'=>date('c'),
    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
    	);
    	$db=new DbExt;
		if ( $db->updateData("{{driver}}",$params,'driver_id',$driver_id)){
			$this->code=1;
			$this->msg=self::t("Setting Saved");			
		} else $this->msg=self::t("Something went wrong please try again later");
		$this->output();     
    }
    
    public function actionGetSettings()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id']; 
    	
    	$lang=Driver::availableLanguages();
    	$lang='';
    	
    	$resp=array(
    	  'enabled_push'=>$token['enabled_push'],
    	  'language'=>$lang
    	);
    	$this->code=1;
    	$this->msg="OK";
    	$this->details=$resp;
    	$this->output();     
    }
    
    public function actionLanguageList()
    {
    	$final_list='';
    	$lang=getOptionA('language_list');
    	if(!empty($lang)){
    		$lang=json_decode($lang,true);
    	}
    	if(is_array($lang) && count($lang)>=1){
    		foreach ($lang as $lng) {
    			$final_list[$lng]=$lng;
    		}
    		$this->code=1; $this->msg="OK";
    	} else $this->msg=t("No language");
    	$this->details=$final_list;    	
		$this->output();
    }
    
    public function actionGetAppSettings()
    {    	
    	
    	$translation=Driver::getMobileTranslation();    	
    	$this->code=1;
    	$this->msg="OK";
    	$this->details=array(
    	  'notification_sound_url'=>Driver::moduleUrl()."/sound/food_song.mp3",    	  
    	  'translation'=>$translation
    	);
    	$this->output();
    }
    
    public function actionViewOrderDetails()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id']; 
    	$order_id= $this->data['order_id'];
    	
		$_GET['backend']='true';
		if ( $data=Yii::app()->functions->getOrder2($order_id)){	
			//dump($data);					
			$json_details=!empty($data['json_details'])?json_decode($data['json_details'],true):false;
			if ( $json_details !=false){
			    Yii::app()->functions->displayOrderHTML(array(
			       'merchant_id'=>$data['merchant_id'],
			       'order_id'=>$order_id,
			       'delivery_type'=>$data['trans_type'],
			       'delivery_charge'=>$data['delivery_charge'],
			       'packaging'=>$data['packaging'],
			       'cart_tip_value'=>$data['cart_tip_value'],
				   'cart_tip_percentage'=>$data['cart_tip_percentage'],
				   'card_fee'=>$data['card_fee'],
				   'donot_apply_tax_delivery'=>$data['donot_apply_tax_delivery'],
				   'points_discount'=>isset($data['points_discount'])?$data['points_discount']:'' /*POINTS PROGRAM*/
			     ),$json_details,true);
			     $data2=Yii::app()->functions->details;
			     unset($data2['html']);			     
			     $this->code=1;
			     $this->msg="OK";
			     
			     $admin_decimal_separator=getOptionA('admin_decimal_separator');
		         $admin_decimal_place=getOptionA('admin_decimal_place');
		         $admin_currency_position=getOptionA('admin_currency_position');
		         $admin_thousand_separator=getOptionA('admin_thousand_separator');
			     
			     $data2['raw']['settings']=Driver::priceSettings();
			     $data2['raw']['order_info']=array(
			       'order_id'=>$data['order_id'],
			       'order_change'=>$data['order_change'],
			     );
			     
			     $this->details=$data2['raw'];			     
			     
			} else $this->msg = self::t("Record not found");
		} else $this->msg = self::t("Record not found");    	
    	$this->output();
    }
    
    public function actionGetNotifications()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];
    	if ( $res=Driver::getDriverNotifications($driver_id)) {
    		 $data='';
    		 foreach ($res as $val) {
    		 	$val['date_created']=Driver::prettyDate($val['date_created']);
    		 	//$val['date_created']=date("h:i:s",strtotime($val['date_created']));
    		 	$data[]=$val;
    		 }
    		 $this->code=1;
    		 $this->msg="OK";
    		 $this->details=$data;
    	} else $this->msg=self::t("No notifications");
    	$this->output();
    }
    
    public function actionUpdateDriverLocation()
    {    	
    	//demo
    	//die();
    	
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];
    	$params=array(
    	  'location_lat'=>$this->data['lat'],
    	  'location_lng'=>$this->data['lng'],
    	  'last_login'=>date('c'),
	      'last_online'=>strtotime("now")
    	);
    	$db=new DbExt;
    	if ( $db->updateData("{{driver}}",$params,'driver_id',$driver_id)){
    		$this->code=1;
    		$this->msg="Location set";
    	} else $this->msg="Failed";
    	$this->output();    	
    }
    
    public function actionClearNofications()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	}     	
    	$driver_id=$token['driver_id'];
    	$stmt="UPDATE 
    	{{driver_pushlog}}
    	SET
    	is_read='1'
    	WHERE
    	driver_id=".self::q($driver_id)."
    	AND
    	is_read='2'
    	";
    	$this->code=1;
    	$this->msg="OK";
    	$db=new DbExt;
    	$db->qry($stmt);
    	$this->output();    	
    }
    
    public function actionLogout()
    {
    	if ( !$token=Driver::getDriverByToken($this->data['token'])) {
    		$this->msg=self::t("Token not valid");
    		$this->output();
    		Yii::app()->end();
    	} 
    	$driver_id=$token['driver_id'];
    	$params=array(    	  
    	  'last_online'=>time() - 300,
    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
    	);
    	
    	$db=new DbExt;
    	$db->updateData('{{driver}}',$params,'driver_id',$driver_id);
    	$this->code=1;
    	$this->msg="OK";
    	$this->output();
    }

    
} /*end class*/