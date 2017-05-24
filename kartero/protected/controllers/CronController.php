<?php
class CronController extends CController
{
	public function init()
	{			
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");	 		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 				 
	}
	
	public function actionIndex()
	{		
		
	}
	
	public function actionProcessPush()
	{
		$db=new DbExt;
		$status='';
		
		$ring_tone_filename = 'food_song';
		$api_key=Yii::app()->functions->getOptionAdmin('push_api_key');		
		
		$driver_ios_push_mode=getOptionA('ios_mode');		
		$driver_ios_pass_phrase=getOptionA('ios_password');		
		$driver_ios_push_dev_cer=getOptionA('ios_dev_certificate');
		$driver_ios_push_prod_cer=getOptionA('ios_prod_certificate');	
		
		$DriverIOSPush=new DriverIOSPush;
		$DriverIOSPush->pass_prase=$driver_ios_pass_phrase;
		$DriverIOSPush->dev_certificate=$driver_ios_push_dev_cer;
		$DriverIOSPush->prod_certificate=$driver_ios_push_prod_cer;
		
		$production=$driver_ios_push_mode=="production"?true:false;	
		
		$stmt="
		SELECT * FROM
		{{driver_pushlog}}
		WHERE
		status='pending'
		ORDER BY date_created ASC
		LIMIT 0,10
		";
		if ( $res=$db->rst($stmt)){
			foreach ($res as $val) {
				dump($val);
				$push_id=$val['push_id'];
				if (!empty($val['device_id'])){
					if(!empty($api_key)){
						$message=array(		 
						 'title'=>$val['push_title'],
						 'message'=>$val['push_message'],
						 'soundname'=>$ring_tone_filename,
						 'count'=>1,
						 'data'=>array(
						   'push_type'=>$val['push_type'],
						   //'order_id'=>$val['order_id'],
						   'actions'=>$val['actions'],
						 )
					   );		
					   
					   dump($message);
					   
					   if ( strtolower($val['device_platform']) =="android"){
						   $resp=AndroidPush::sendPush($api_key,$val['device_id'],$message);
						   if(is_array($resp) && count($resp)>=1){
						   	   dump($resp);
				   	       	   if( $resp['success']>0){			   	       	   	   
				   	       	   	   $status="process";
				   	       	   } else {		   	       	   	   
				   	       	   	   $status=$resp['results'][0]['error'];
				   	       	   }
						   }  else $status="uknown push response";						   
					   } elseif ( strtolower($val['device_platform']) =="ios"  ) {	   
					   	
					   	   $additional_data=array(
					   	     'push_type'=>$val['push_type'],
						     //'order_id'=>$val['order_id'],
						     'actions'=>$val['actions'],
					   	   );					   	   
					   	   if ( $DriverIOSPush->push($val['push_message'],
					   	        $val['device_id'],$production,$additional_data) ){
					   	   	    $status="process";
					   	   } else $status=$DriverIOSPush->get_msg();
					   	
					   } else {
					   	   $status="Uknown device";
					   }
					   				
					} else $status= "API key is empty";
				} else $status= "Device id is empty";
				
				$params=array(
				  'status'=>$status,
				  'date_process'=>date('c'),
				  'json_response'=>isset($resp)?json_encode($resp):'',
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				dump($params);
				
				$db->updateData("{{driver_pushlog}}",$params,'push_id',$push_id);
				
			}
		} else echo 'no record to process';
	}

    public function actionAutoAssign()
    {
        $db=new DbExt;		
		$distance_exp=3959;  $radius=3000;			
		
		$date_now=date('Y-m-d');

		$stmt="SELECT a.*,
		b.enabled_auto_assign,
		b.include_offline_driver,
		b.autoassign_notify_email,
		b.request_expire,
		b.auto_assign_type,
		b.assign_request_expire,
		b.driver_assign_radius
		
		 FROM
		{{driver_task}} a
		LEFT JOIN {{customer}} b
        ON
        a.customer_id=b.customer_id
		WHERE 1
		AND a.status IN ('unassigned')  
		AND a.auto_assign_type=''
		AND a.delivery_date like '$date_now%'	
		AND b.enabled_auto_assign='1'	
		ORDER BY task_id ASC
		LIMIT 0,10
		";
		dump($stmt);		
		if ( $res=$db->rst($stmt)){
			foreach ($res as $val) {
				dump($val);
				
				if($val['driver_assign_radius']>0){
					$radius=$val['driver_assign_radius'];
				}
				
				$notify_email=$val['autoassign_notify_email'];
				
				$lat=$val['task_lat'];
				$lng=$val['task_lng'];
				$task_id=$val['task_id'];				
				dump($lat); dump($lng); dump($task_id);

				$and='';
				$todays_date=date('Y-m-d');			
		        $time_now = time() - 600;
		        
		        $limit="LIMIT 0,100";
		        
		        $assignment_status="waiting for driver acknowledgement";
		        
		        if ( $val['include_offline_driver']==""){
		        	$and.=" AND a.on_duty ='1' ";
                    $and.=" AND a.last_online >='$time_now' ";
                    $and.=" AND a.last_login like '".$todays_date."%'";
		        }
							
		        if ( $val['auto_assign_type']=="one_by_one"){
		        	dump("one_by_one");
		        			        	
					$and.=" AND a.driver_id NOT IN (
					  select driver_id
					  from
					  {{driver_assignment}}
					  where
					  driver_id=a.driver_id
					  and
					  task_id=".Driver::q($task_id)."
					) ";
										
					$stmt2="
					SELECT a.driver_id, a.first_name,a.last_name,a.location_lat,a.location_lng,
					a.on_duty, a.last_online, a.last_login
					, 
					( $distance_exp * acos( cos( radians($lat) ) * cos( radians( location_lat ) ) 
			        * cos( radians( location_lng ) - radians($lng) ) 
			        + sin( radians($lat) ) * sin( radians( location_lat ) ) ) ) 
			        AS distance
			        FROM {{driver}} a
			        HAVING distance < $radius
					$and
					ORDER BY distance ASC
					$limit
					";
					
		        } else {
		        	dump("send_to_all");
		        	
		        	$and.=" AND a.driver_id NOT IN (
					  select driver_id
					  from
					  {{driver_assignment}}
					  where
					  driver_id=a.driver_id
					  and
					  task_id=".Driver::q($task_id)."					  
					) ";
					
					$stmt2="SELECT a.* FROM {{driver}} a		
					WHERE 1
					$and			
					";			
		        }
		        
		        dump($stmt2);
		        if ( $res2=$db->rst($stmt2)){
		        	foreach ($res2 as $val2) {
		        		$params=array(
						  'auto_assign_type'=>$val['auto_assign_type'],
						  'task_id'=>$val['task_id'],
						  'driver_id'=>$val2['driver_id'],
						  'first_name'=>$val2['first_name'],
						  'last_name'=>$val2['last_name'],
						  'date_created'=>date('c'),
						  'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
						echo "<h3>driver_assignment</h3>";
						dump($params);		
						$db->insertData("{{driver_assignment}}",$params);				
		        	}
		        } else {
		        	// unable to assign
		        	$assignment_status = "unable to auto assign";
		        	if (!empty($val['autoassign_notify_email'])){
		        		$email_enabled=getOption($val['customer_id'],'FAILED_AUTO_ASSIGN_EMAIL');
		        		if($email_enabled){
		        		   $tpl=getOption($val['customer_id'],'FAILED_AUTO_ASSIGN_EMAIL_TPL');
						   $tpl=Driver::smarty('TaskID',$task_id,$tpl);
						   $tpl=Driver::smarty('CompanyName',getOptionA('website_title'),$tpl);
						   dump($tpl);
						   sendEmail($notify_email,"","Unable to auto assign Task $task_id",$tpl);
		        		}
		        	}
		        }
		        
		        $less="-1";
			    if($val['assign_request_expire']>0){
				   $less="-".$val['assign_request_expire'];
			    }
		        
			    $params_task=array(
				 'auto_assign_type'=>$val['auto_assign_type'],
				 'assign_started'=>date('c',strtotime("$less min")),
				 'assignment_status'=> $assignment_status
				);
				dump($params_task);
				$db->updateData("{{driver_task}}",$params_task,'task_id',$task_id);
			    
			} /*end foreach*/
		} else echo 'no record to process';
    }		
    
    public function actionProcessAutoAssign()
    {
    	$and='';		
				
		$and.="AND task_id IN (
		  select task_id from {{driver_assignment}}
		  where
		  task_id=a.task_id
		  and
		  status='pending'		  
		)";
		
		$db=new DbExt;
		$stmt="SELECT a.*,
		b.enabled_auto_assign,
		b.include_offline_driver,
		b.autoassign_notify_email,
		b.request_expire,
		b.auto_assign_type,
		b.assign_request_expire
		FROM
		{{driver_task}} a
		
		LEFT JOIN {{customer}} b
        ON
        a.customer_id=b.customer_id
        
		WHERE 1
		AND a.status IN ('unassigned') 
		$and				
		ORDER BY task_id ASC
		LIMIT 0,10
		";
		dump($stmt);
		if ( $res=$db->rst($stmt)){
			foreach ($res as $val) {
				dump($val);
				
				$task_id=$val['task_id'];
				$assign_type=$val['auto_assign_type'];
				$assign_started=date("Y-m-d g:i:s a",strtotime($val['assign_started']));
				$date_now=date('Y-m-d g:i:s a');
				$request_expire=$val['assign_request_expire'];
				
				if ( $assign_type=="one_by_one"){
					dump("one_by_one");
					$time_diff=Yii::app()->functions->dateDifference($assign_started,$date_now);
				    dump($time_diff);
				    if (is_array($time_diff) && count($time_diff)>=1){
				    	if ( $time_diff['hours']>0 || $time_diff['minutes']>=$request_expire){
				    		 if ( $driver=Driver::getUnAssignedDriver($task_id)){
				    		 	
				    		 	$params['assignment_status']="waiting for"." ".$driver['first_name'].
					   	      " ".$driver['last_name']." "."to acknowledge"; 
					   	      					   	      					   	     					   	      
					   	        $assigment_id=$driver['assignment_id'];
					   	        $params_driver=array('status'=>'process','date_process'=>date('c'));
					   	        dump($params_driver);
					   	        $db->updateData('{{driver_assignment}}',$params_driver,'assignment_id',$assigment_id);
					   	      					   	      
					   	        $task_info=Driver::getTaskByDriverNTask($task_id, $driver['driver_id']);
					   	        Driver::sendDriverNotification('ASSIGN_TASK',$task_info);	
				    		 }
				    		 
				    		 $params['assign_started']=date('c');
				    		 dump($params);
				    		 $db->updateData("{{driver_task}}",$params,'task_id',$task_id);
				    		 
				    	} else echo "Not request $request_expire a";
				    } else echo "Not request $request_expire b";
				} else {
					dump("send_to_all");
					if ( $res2 = Driver::getUnAssignedDriver2($task_id)){
						foreach ($res2 as $val2) {
							dump($val2);
							$assigment_id=$val2['assignment_id'];
							$params_driver=array('status'=>'process','date_process'=>date('c'));
					   	    dump($params_driver);
					   	    $db->updateData('{{driver_assignment}}',$params_driver,'assignment_id',$assigment_id);
					   	    
					   	    $task_info=Driver::getTaskByDriverNTask($val2['task_id'], $val2['driver_id'] );
					   	    Driver::sendDriverNotification('ASSIGN_TASK',$task_info);	
						}
						
						$params='';
						$params['assign_started']=date('c');
				        dump($params);
				    	$db->updateData("{{driver_task}}",$params,'task_id',$task_id);
					}
				}
				
			}
		} else echo 'No results';
    }
    
    public function actionCheckAutoAssign()
    {
    	$db=new DbExt;
				
		$stmt="SELECT a.*,
		b.enabled_auto_assign,
		b.include_offline_driver,
		b.autoassign_notify_email,
		b.request_expire,
		b.auto_assign_type,
		b.assign_request_expire
		
		 FROM
		{{driver_task}} a
		
		LEFT JOIN {{customer}} b
        ON
        a.customer_id=b.customer_id
        
		WHERE 1
		AND a.status IN ('unassigned') 	
		AND a.auto_assign_type IN ('one_by_one','send_to_all')	
		AND a.assignment_status NOT IN ('','unable to auto assign')
		AND a.task_id NOT IN (
		  select task_id from {{driver_assignment}}
		  where
		  task_id=a.task_id
		  and
		  status='pending'  
		)
		ORDER BY a.task_id ASC
		LIMIT 0,10
		";
		dump($stmt);
		if ( $res=$db->rst($stmt)){
			foreach ($res as $val) {
				dump($val);
				$task_id=$val['task_id'];
				$assign_type=$val['auto_assign_type'];
				$assign_started=date("Y-m-d g:i:s a",strtotime($val['assign_started']));
				$request_expire=$val['request_expire'];
				$date_now=date('Y-m-d g:i:s a');
				$notify_email=$val['autoassign_notify_email'];
				
				dump("TASK ID :".$task_id);
				dump("expire in :".$request_expire);				
				dump($assign_type);
				dump("assign_started :".$assign_started);
				dump("date now : ".$date_now);
				
				if(!is_numeric($request_expire)){
			        $request_expire=1;
				}
				
				$time_diff=Yii::app()->functions->dateDifference($assign_started,$date_now);
				if (is_array($time_diff) && count($time_diff)>=1){
					dump($time_diff);
					if ( $time_diff['hours']>0 || $time_diff['minutes']>=$request_expire){
						
						$params=array('assignment_status'=>"unable to auto assign");
				    	dump($params);
				    	$db->updateData("{{driver_task}}",$params,'task_id',$task_id);
				    	
				    	/*$stmt_assign="
				    	UPDATE {{driver_assignment}}
				    	SET task_status='unable to auto assign'
				    	WHERE
				    	task_id=".Driver::q($task_id)."
				    	";
				    	dump($stmt_assign);
				    	$db->qry($stmt_assign);
				    	*/				    	
				    	
				    	if ( $res2 = Driver::getUnAssignedDriver3($task_id)){				    		
				    		foreach ($res2 as $val2) {	
				    		   				    		   				    			
				    		   $assigment_id=$val2['assignment_id'];
							   $params_driver=array('task_status'=>'unable to auto assign',
							                        'date_process'=>date('c'));
							   
					   	       dump($params_driver);
					   	       $db->updateData('{{driver_assignment}}',$params_driver,'assignment_id',$assigment_id);
				    				    		   
				    		   $task_info=Driver::getTaskByDriverNTask($val2['task_id'], $val2['driver_id'] );
				    		   Driver::sendDriverNotification('CANCEL_TASK',$task_info);
				    		}
				    	} 
				    	
				    	if(!empty($notify_email)){
				    		dump($notify_email);				    		
				    		$email_enabled=getOption($val['customer_id'],'FAILED_AUTO_ASSIGN_EMAIL');
				    		dump($email_enabled);
				    		if($email_enabled){
							   $tpl=getOption($val['customer_id'],'FAILED_AUTO_ASSIGN_EMAIL_TPL');
							   $tpl=Driver::smarty('TaskID',$task_id,$tpl);
							   $tpl=Driver::smarty('CompanyName',getOptionA('website_title'),$tpl);
							   dump($tpl);
				    		   sendEmail($notify_email,"","Unable to auto assign Task $task_id",$tpl);
				    		}
				    	}   		
					}
				}
			} /*end foreach*/
		} else echo "No results";
    }
    
    public function actionCheckCustomerExpiry()
    {
    	$db=new DbExt;
    	$date_now=date("Y-m-d");
    	$stmt="UPDATE 
    	{{customer}}
    	SET status='expired'
    	WHERE 
    	plan_expiration<".Driver::q($date_now)."
    	";    	
    	$db->qry($stmt);
    }
		
} /*end class*/