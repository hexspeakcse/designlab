<?php 
class Driver
{
	
	public static function assetsUrl()
	{
		return Yii::app()->baseUrl.'/assets';
	}
	
	public static function jsLang()
	{
		return array(
		  'are_your_sure'=>self::t("Are you sure"),
		  'create_agent'=>self::t("Create Agent"),
		  'update_agent'=>self::t("Update Agent"),
		  'create_team'=>self::t("Create Team"),
		  'update_team'=>self::t("Update Team"),
		  'add_driver'=>self::t("Add Driver"),
		  'update_driver'=>self::t("Update Driver"),
		  'pickup_before'=>self::t("Pickup before"),
		  'delivery_before'=>self::t("Delivery before"),
		  'delivery_address'=>self::t("Delivery Address"),
		  'pickup_address'=>self::t("Pickup Address"),
		  'location_on_map'=>self::t("Location on Map"),
		  'no_history'=>self::t("No history"),
		  'reason'=>self::t("Reason"),
		  'assign_agent'=>self::t("Assign Agent"),
		  're_assign_agent'=>self::t("Re-assign Agent"),
		  'details'=>self::t("Details"),
		  'name'=>self::t("Name"),
		  'task_id'=>self::t("Task ID"),
		  'undefine_result'=>self::t("Undefined Result"),
		  'connection_lost'=>self::t("Connection Lost"),
		  'task'=>Driver::t("Task"),
		  'online'=>Driver::t("Online"),
		  'offline'=>Driver::t("Offline"),
		  'not_available'=>Driver::t("Not available"),
		  'no_notification'=>self::t("No notifications for today"),
		  'currentlocation'=>self::t("Current Location"),
		  'autoassigning'=>self::t("Auto assigning"),
		  'account_expired'=>self::t("Your account is expired")
		);
	}
	

	public static function driverStatus()
	{
		return array(		  
		 'active'=>Yii::t("default",'active'),	 
		 'pending'=>Yii::t("default",'pending for approval'),
		 'suspended'=>Yii::t("default",'suspended'),
		 'blocked'=>Yii::t("default",'blocked'),
		 //'expired'=>Yii::t("default",'expired')
		);
	}	
	
	public static function parseValidatorError($error='')
	{
		$error_string='';
		if (is_array($error) && count($error)>=1){
			foreach ($error as $val) {
				$error_string.="$val\n";
			}
		}
		return $error_string;		
	}			
	
	public static function deliveryTimeOption()
	{
		$data[]=self::t("Please select");
		for ($i = 1; $i <= 5; $i++) {
            $data[$i]=self::t("after". " ". $i ." " ."hour of purchase" );
        }
        return $data;
	}
	
	public static function t($message='')
	{
		return Yii::t("default",$message);
	}
	
	public static function q($data)
	{
		return Yii::app()->db->quoteValue($data);
	}
	
	public static function transportType()
	{
		return array(
		  'truck'=>self::t("Truck"),
		  'car'=>self::t("Car"),
		  'bike'=>self::t("Bike"),
		  'bicycle'=>self::t("Bicycle"),
		  'scooter'=>self::t("Scooter"),
		  'walk'=>self::t("Walk"),
		);
	}
	
    public static function prettyPrice($amount='')
	{
		if(!empty($amount)){
			return displayPrice(getCurrencyCode(),prettyFormat($amount));
		}
		return 0;
	}	
			
	public static function islogin()
	{
		if(isset($_SESSION['kartero'])){
			if(is_numeric($_SESSION['kartero']['customer_id'])){
				return true;
			}
		}
		return false;
	}
	
	public static function getLoginType()
	{		
		return false;	
	}
	
	public static function getUserType()
	{		
		return false;
	}
	
	public static function getUserId()
	{
		if (self::islogin()){
			return $_SESSION['kartero']['customer_id'];
		}
		return false;
	}
	
	public static function getPlanID()
	{
		if (self::islogin()){
			return $_SESSION['kartero']['plan_id'];
		}
		return false;
	}	
	
	public static function getUserToken()
	{
		if (self::islogin()){
			return $_SESSION['kartero']['token'];
		}
		return false;
	}		
	
	public static function getUserStatus()
	{
		if (self::islogin()){
			return $_SESSION['kartero']['status'];
		}
		return false;
	}		
	
	public static function uploadURL()
	{
	    return Yii::app()->getBaseUrl(true)."/upload";
    }
    
    public static function uploadPath()
    {
    	return Yii::getPathOfAlias('webroot')."/upload";  
    }
	
    public static function moduleUrl()
	{
	    return Yii::app()->getBaseUrl(true);
    }
    
	public static function Login($email='',$password='')
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{customer}}
		WHERE
		email_address=".self::q($email)."		
		LIMIT 0,1
		";		
		if($res=$db->rst($stmt)){
			$data=$res[0];			
			$hash=$data['password'];			
			if (CPasswordHelper::verifyPassword($password, $hash)){
				return $data;
			}
		}
		return false;
	}
		
	public static function cleanText($text='')
	{
		return stripslashes($text);
	}
	
	public static function getTeam($team_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver_team}}
		WHERE
		team_id=".self::q($team_id)."
		LIMIT 0,1
		";
		if($res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public static function teamList($customer_id='' , $status='publish')
	{
		$and='';
		$and.=" AND customer_id=".self::q($customer_id)."  ";
		
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver_team}}		
		WHERE 1
		$and
		AND status ='$status'
		ORDER BY team_name ASC
		";		
		//dump($stmt);
		if($res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function getTeamAll()
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver_team}}		
		WHERE
		status='publish'		
		ORDER BY team_name ASC	
		";
		if($res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}	
	
	public static function toList($data='',$key='',$value='',$default_value='')
	{
		$list='';
		if(is_array($data) && count($data)>=1){
			if(!empty($default_value)){
				$list[]=$default_value;
			}
			foreach ($data as $val) {
				$list[$val[$key]]=$val[$value];
			}
			return $list;
		}		
		return false;
	}
	
	public static function driverInfo($driver_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT a.*,
		b.team_name
		FROM
		{{driver}} a
		LEFT JOIN {{driver_team}} b
		On
        a.team_id = b.team_id 		
		WHERE
		driver_id=".self::q($driver_id)."
		LIMIT 0,1
		";
		if($res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}	
	
	public static function driverList($customer_id='')
	{		
        	
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver}}
		WHERE		
		customer_id =".self::q($customer_id)."
		ORDER BY first_name ASC
		";		
		//dump($stmt);
		if($res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}	
	
	public static function getAllDriver($customer_id='')
	{
		$and='';
		$and.=" AND customer_id=".self::q($customer_id)."  ";
		
		$db=new DbExt;
		$stmt="
		SELECT * FROM				
		{{driver}}		
		WHERE 1
		$and
		AND status='active'
		ORDER BY first_name ASC
		";		
		if($res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}		
	
	public static function updateDriverTeam($driver='',$team_id='')
	{
		$db=new DbExt;
		if(!empty($driver)){
			$driver=json_decode($driver,true);
			if(is_array($driver) && count($driver)>=1){
				foreach ($driver as $driver_id) {
					$params['team_id']=$team_id;
					$db->updateData("{{driver}}",$params,'driver_id',$driver_id);
					unset($params);
				}
			}
		}
	}
	
	public static function updateTeamDriver($driver_id='',$team_id='')
	{
		dump($driver_id);
		dump($team_id);
		if ($res=self::getTeam($team_id)){
			dump($res);
			if(!empty($res['team_member'])){
				$team_member=json_decode($res['team_member'],true);
				$team_member=array_flip($team_member);
				dump($team_member);
			}
		}
	}
	
	public static function getDriverByTeam($team_id='')
	{
		$db=new DbExt;
		if(!empty($team_id)){
			$stmt="SELECT * FROM
			{{driver}}
			WHERE
			team_id=".self::q($team_id)."
			";
			if($res=$db->rst($stmt)){
			   return $res;
			}
		}
		return false;
	}
	
	public static function getTask($customer_id='')
	{
		$and='';
		$and=" AND customer_id=".self::q($customer_id)." ";
		
		$db=new DbExt;		
		$stmt="SELECT * FROM
		{{driver_task}}
		WHERE 1		
		$and
		ORDER BY date_created ASC
		";
		if($res=$db->rst($stmt)){
		   return $res;
		}	
		return false;
	}	
	
	public static function getTaskByDriverID($driver_id='',$delivery_date='')
	{
		$db=new DbExt;		
		$db->qry("SET SQL_BIG_SELECTS=1");
		$stmt="SELECT * FROM
		{{driver_task_view}}
		WHERE
		driver_id=".self::q($driver_id)."
		AND
		delivery_date LIKE '".$delivery_date."%'
		ORDER BY delivery_date ASC
		";		
		//dump($stmt);
		if($res=$db->rst($stmt)){
		   return $res;
		}	
		return false;
	}		
	
	public static function getTaskId($task_id='')
	{
		$db=new DbExt;		
		$db->qry("SET SQL_BIG_SELECTS=1");		
		$stmt="
		SELECT * FROM
		{{driver_task_view}}
		WHERE
		task_id=".self::q($task_id)."		
		LIMIT 0,1
		";
		if($res=$db->rst($stmt)){
		   return $res[0];
		}	
		return false;
	}		
	
	public static function deleteTask($task_id='')
	{
		$db=new DbExt;	
		$stmt="
		DELETE FROM
		{{driver_task}}
		WHERE
		task_id=".self::q($task_id)."
		";
		if($db->qry($stmt)){
			
			//delete all history
			$stmt2="
			DELETE FROM
			{{task_history}}
			WHERE
			task_id=".self::q($task_id)."
			";
			$db->qry($stmt2);
			
			$stmt3="
			DELETE FROM
			{{driver_assignment}}
			WHERE
			task_id=".self::q($task_id)."
			";
			$db->qry($stmt3);
			
			return true;
		}
		return false;
	}
	
	public static function getTaskByStatus($customer_id='',$status='',$date='')
	{
		
		//$where="WHERE 1";
		$where =" WHERE customer_id =".self::q($customer_id)." ";
		
		
		$where_status='';		
						
		$and_date='';
		if (!empty($date)){			
			$and_date=" AND delivery_date LIKE '".$date."%' ";
		}
		
		switch ($status) {
			case "unassigned":								
				$where_status="AND status IN ('declined','unassigned')";
				break;
				
			case "assigned":	
			    $where_status="AND status IN ('assigned','started','inprogress','acknowledged')";
		        break;
			
			case "completed":	
			    $where_status="AND status IN ('successful','failed','cancelled','canceled')";
			    break;
			    
			default:
				$where_status="AND status =".self::q($status)."";
				break;
		}
		
		$db=new DbExt;		
		$db->qry("SET SQL_BIG_SELECTS=1");
		$stmt="
		SELECT * FROM
		{{driver_task_view}}		
		$where		
		$and_date
		$where_status
		ORDER BY task_id DESC
		";
		//dump($stmt);
		if($res=$db->rst($stmt)){
		   //dump($res);
		   return $res;
		}	
		return false;
	}		
	
	public static function formatTask($data='')
	{
		if (is_array($data) && count($data)>=1){			
			//dump($data);
			$trans_type=self::t("D");
			if ( $data['trans_type']=="pickup"){
				$trans_type= self::t("P");
			}
			ob_start();
			?>
			<div class="row box task-map" 
			data-lat="<?php echo $data['task_lat']?>"
			data-lng="<?php echo $data['task_lng']?>"
			data-id="<?php echo $data['task_id']?>" >
						
		      <div class="col-md-2 center">
		       <div class="tag rounded <?php echo $data['trans_type'];?>"><?php echo $trans_type;?></div>
		       <div class="top10"><i class="ion-ios-location"></i></div>
		       <div class="top10"><i class="ion-ios-time-outline"></i></div>
		       <?php if ($data['driver_id']>0):?>
		       <div class="top10"><i class="ion-android-person"></i></div>
		       <?php endif;?>
		      </div> <!--row-->
		      
		      <div class="col-md-10">      
		      
		        <div class="row ">
		          <?php if ( $data['task_id']>0 ):?>
		          <div class="col-md-6 small">
		          <?php echo Driver::t("Task ID")?>. <b><?php echo $data['task_id']?></b></div>
		          <?php endif;?>		          
		        </div>
		        
		        <?php if ( Driver::getUserType()=="admin"):?>
		        <?php if (!empty($data['merchant_name'])): ?>
		        <div class="row top10">
		         <div class="col-md-12"> 
		           <?php 
		           echo Driver::t("Merchant name").": <span class=\"text-primary\">".
		           self::cleanString($data['merchant_name'])."</span>";
		           ?>
		         </div>
		        </div>
		        <?php endif;?>
		        <?php endif;?>
		        
		        <div class="row top10">
		          <div class="col-md-5"><?php echo $data['customer_name']?></div>
		          <div class="col-md-7 text-right">
		          
		          <?php if ($data['status']=="unassigned"):?>
		           <a href="javascript:;" class="assign-agent inline orange-button-small rounded"
		           data-id="<?php echo $data['task_id']?>" >
		           <?php echo self::t("Assign Driver")?>
		           </a>
		          <?php else :?>
		          <p class="rounded tag <?php echo $data['status']?>">
		             <?php echo Driver::t($data['status'])?>
		          </p>
		          <?php endif;?> 
		           
		          </div> <!--col-->
		        </div> <!--row-->
		        
		        <div class="row top5">
		         <div class="col-md-8">
		          <p class="task_address top10 concat-text"><?php echo $data['delivery_address']?></p>
		          <p class="task_time">
		          <?php echo date('g:i a',strtotime($data['delivery_date']))?>
		          </p>        
		         </div>
		         <div class="col-md-4">
		           <a href="javascript:;" class="task-details" data-id="<?php echo $data['task_id']?>" >
		           <?php echo Driver::t("Details")?>
		           </a>
		         </div>
		        </div> <!--row-->
		        
		        <?php if ($data['driver_id']>0):?>
		        <p class="concat-text top10">
		        <?php echo $data['driver_name']?>
		        </p>
		        <?php endif;?>
		        		        		        
		      </div> <!--row-->
		      
		       <?php if(!empty($data['assignment_status']) && $data['status']=="unassigned"):?>
		      <?php if ($data['assignment_status']=="unable to auto assign"):?>
		      
		           <div class="col-md-7 top5 center autoassign-col-1-<?php echo $data['task_id']?>">
		           <p class="small-font text-danger"><?php echo Driver::t($data['assignment_status'])?></p>
		           </div>
		           		           
		           <div class="col-md-5 top5 text-right autoassign-col-2-<?php echo $data['task_id']?>">
		           <a href="javascript:retryAutoAssign('<?php echo $data['task_id']?>');"  class="small-font">
		              <?php echo Driver::t("Retry")?>
		           </a>
		           </div>
		          <?php else :?>
		          <div class="col-md-12 top5 center">		        		        
		          <p class="small-font text-primary">
		            <?php echo Driver::t($data['assignment_status'])?>... 
		            <i class="small-font fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
		          </p>
		        <?php endif;?>
		      </div>
		      <?php endif;?>
		      
		    </div> <!--row-->    
			<?php
			$forms = ob_get_contents();
            ob_end_clean();
            return $forms;
		} 
	}
	
	public static function statusList()
	{
		//acknowledged
		return array(
		  ''=>self::t("Please select status"),
		  'unassigned'=>self::t("unassigned"),
		  'assigned'=>self::t("assigned"),
		  'started'=>self::t("started"),
		  'inprogress'=>self::t("inprogress"),
		  'successful'=>self::t("successful"),
		  'failed'=>self::t("failed"),
		  'declined'=>self::t("declined"),
		  'cancelled'=>self::t("cancelled"),
		);
	}
	
	public static function prettyStatus($from='', $to='')
	{
		if(!empty($from) && !empty($to)){
			$prety= self::t("Status updated from");
			$prety.=" $from ". self::t("to") ." $to";
			return $prety;
		}
		return Driver::t("Status changed");
	}
	
	public static function getTaskHistory($task_id='')
	{
		
		$db=new DbExt;		
		$and='';
		$or='';		
		if ( $task_id>0){
			if (!empty($or)){
			   $or.=" OR task_id=".self::q($task_id)." ";
			} else {
			   $or="task_id=".self::q($task_id)." ";
			}
		}
				
		if(!empty($or)){
			$and=" AND ( $or ) ";
		}
		
		$stmt="SELECT * FROM
		{{task_history}}
		WHERE
		1
		$and
		ORDER BY id ASC
		";
		//dump($stmt);
		if ( $res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function hasModuleAddon($modulename='')
	{
		if (Yii::app()->hasModule($modulename)){
		   $path_to_upload=Yii::getPathOfAlias('webroot')."/protected/modules/$modulename";	
		   if(file_exists($path_to_upload)){
		   	   return true;
		   }
		}
		return false;
	}
	
	public static function AdminStatusTpl()
	{
		//$team_list=Driver::teamList( 'merchant',  Yii::app()->functions->getMerchantID() );
		$team_list=Driver::teamList( self::getUserId() );
        if($team_list){
      	  $team_list=Driver::toList($team_list,'team_id','team_name',
      	    Driver::t("Select a team")
      	  );
        }
        //dump($team_list);
        
        $all_driver=Driver::getAllDriver();  
		?>
		<div class="uk-form-row">
    	  <label class="uk-form-label"><?php echo t('Select Team')?></label>
    	  <?php 
           echo CHtml::dropDownList('team_id','', (array)$team_list,array(
            'class'=>"task_team_id"
           ))
          ?>
    	 </div>
    	 
    	 <div class="uk-form-row">
    	   <label class="uk-form-label"><?php echo t('Assign Agent')?></label>
    	   <select name="driver_id" id="driver_id" class="driver_id">
		   <?php if(is_array($all_driver) && count($all_driver)>=1):?>
		    <option value=""><?php echo Driver::t("Select driver")?></option>
		    <?php foreach ($all_driver as $val):?>
		    <option class="<?php echo "team_opion option_".$val['team_id']?>" value="<?php echo $val['driver_id']?>">
		      <?php echo $val['first_name']." ".$val['last_name']?>
		    </option>
		    <?php endforeach;?>
		   <?php endif;?>
		  </select>
    	 </div>
		<?php
	}
			
	public static function addressToLatLong($address='')
	{		
		$protocol = isset($_SERVER["https"]) ? 'https' : 'http';
		if ($protocol=="http"){
			$api="http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address);
		} else $api="https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address);
		
		/*check if has provide api key*/
		$key=Yii::app()->functions->getOptionAdmin('drv_google_api');		
		if ( !empty($key)){
			$api="https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".urlencode($key);
		}	
					
		if (!$json=@file_get_contents($api)){
			$json=$this->Curl($api,'');					
		}
		
		/*dump($api);
	    dump($json);*/
			
		if (!empty($json)){
			$json = json_decode($json);	
			if (isset($json->error_message)){
				return false;
			} else {				
				if($json->status=="OK"){					
					$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		            $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
				} else {
					$lat=''; $long='';
				}
	            return array(
	              'lat'=>$lat,
	              'long'=>$long
	            );
			}
		}			
		return false;
	}	
	
	public static function getDriverByStats($customer_id='',$stats='',$transaction_date='',
	   $driver_status='active' , $team_id='')
	{
		
		$db=new DbExt;
		$todays_date=date('Y-m-d');		
		//$time_now = time() - 600;
		$time_now = time() - 200;
		$and='';
		
		/*dump($time_now);
		dump("->".strtotime("now"));*/
		
		$and =" AND customer_id=".self::q($customer_id)." ";
				
		switch ($stats) {
			case "active":
				$and.=" AND on_duty ='1' ";
				$and.=" AND last_online >='$time_now' ";
				$and.=" AND last_login like '".$todays_date."%'";
				break;
		
			case "offline":
				$date_now=date("now",strtotime('-6 minutes'));
				$and.=" AND last_online <='$time_now' ";
			default:
				
				break;
		}
		
		$and .=" AND status=".self::q($driver_status)."";
		
		if ($team_id>0){
			$and.=" AND team_id=".self::q($team_id)." ";
		}
		
		$stmt="
		SELECT a.*,
		(
		  select count(*)
		  from
		  {{driver_task}}
		  where
		  driver_id=a.driver_id
		  and 
		  delivery_date like '".$transaction_date."%'
		) as total_task
		FROM
		{{driver}} a
		WHERE 1
		$and
		ORDER BY first_name ASC
		";
		//dump($stmt);
		if ( $res = $db->rst($stmt)){			
			$data='';
			foreach ($res as $val) {				
				$val['is_online']=2;
				$last_login=date('Y-m-d',strtotime($val['last_login']));
				if ( $last_login==$todays_date && $val['on_duty']==1){
					if ( $val['last_online']>=$time_now){
					   $val['is_online']=1;
					}
				} 			
				$data[]=$val;
			}
			return $data;
		}
		return false;
	}
	
	public static function driverAppLogin($username='', $password='',$status='active')
	{
		$db=new DbExt;
		
		$stmt="SELECT * FROM
		{{driver}}
		WHERE
		username=".self::q($username)."
		AND
		password=".self::q(md5($password))."
		AND
		status='$status'
		LIMIT 0,1
		";		
		//dump($stmt);
		if ( $res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
    public static function generateRandomNumber($range=10) 
    {
	    $chars = "0123456789";	
	    srand((double)microtime()*1000000);	
	    $i = 0;	
	    $pass = '' ;	
	    while ($i <= $range) {
	        $num = rand() % $range;	
	        $tmp = substr($chars, $num, 1);	
	        $pass = $pass . $tmp;	
	        $i++;	
	    }
	    return $pass;
    }	
	
	public static function driverForgotPassword($email_address='')
	{
		$db=new DbExt;	
		$stmt="SELECT * FROM
		{{driver}}
		WHERE
		email=".self::q($email_address)."		
		LIMIT 0,1
		";		
		if ( $res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public static function getDriverByToken($token='')
	{
		if (empty($token)){
			return false;
		}
		$db=new DbExt;	
		$stmt="SELECT * FROM
		{{driver}}
		WHERE
		token=".self::q($token)."		
		LIMIT 0,1
		";		
		if ( $res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
	public static function driverStatusPretty($driver_name='',$status='')
	{		
		$msg='';		
		switch ($status) {
			
			case "sign":
			case "signature":
				$msg=$driver_name." ".self::t("added a signature");
				break;
				break;
				
			case "failed":
				$msg=$driver_name." ".self::t("marked the task as failed");
				break;
				
			case "cancelled":
				$msg=$driver_name." ".self::t("marked the task as cancelled");
				break;
				
			case "declined":
				$msg=$driver_name." ".self::t("declined the task");
				break;
				
			case "acknowledged":
				$msg=$driver_name." ".self::t("accepted the task");
				break;
		
			case "started":	
			    $msg= $driver_name." ".self::t("started this task");
			    break;
			    
			case "inprogress":    
			    $msg= $driver_name." ".self::t("reached the destination");
			    break;
			    
			case "successful":    
			    $msg= $driver_name." ".self::t("Completed the task successfully");
			    break;    
			    
			default:
				$msg=self::t("Status changed");
				break;
		}
		return $msg;
	}
	
	public static function getDriverTaskHistory($task_id='')
	{
		$db=new DbExt;	
		$stmt="SELECT * FROM
		{{task_history}}
		WHERE
		task_id=".self::q($task_id)."		
		AND status NOT IN ('assigned')
		ORDER BY id ASC
		";		
		if ( $res=$db->rst($stmt)){
			$data='';
			foreach ($res as $val) {				
				$val['status_raw']=$val['status'];
				$val['status']=self::t($val['status']);
				$val['time']=Yii::app()->functions->timeFormat($val['date_created'],true);
				$val['date']=Yii::app()->functions->FormatDateTime($val['date_created'],false);
				$data[]=$val;
			}
			return $data;
		}
		return false;
	}
	
	public static function getDriverTaskCalendar($driver_id='', $start='', $end='')
	{
		$db=new DbExt;	
		$stmt="SELECT 
		DISTINCT DATE_FORMAT(a.delivery_date,'%Y-%m-%d') as delivery_date		
		FROM
		{{driver_task}} a
		WHERE
		driver_id=".self::q($driver_id)."		
		AND
		delivery_date BETWEEN '$start' AND '$end'
		";				
		$db->qry("SET SQL_BIG_SELECTS=1");
		if ( $res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function getTotalTaskByDate($driver_id='',$date='')
	{
		$db=new DbExt;	
		$stmt="
		  SELECT count(*) as total
		  FROM
		  {{driver_task}}
		  WHERE
		  delivery_date LIKE '".$date."%'
		  AND
		  driver_id=".self::q($driver_id)."		
		";
		if ( $res=$db->rst($stmt)){
			return $res[0]['total'];
		}
		return 0;
	}
	
   public static function availableLanguages()
    {
    	$lang['en']='English';
    	/*$stmt="
    	SELECT * FROM
    	{{languages}}
    	WHERE
    	status in ('publish','published')
    	";
    	$db_ext=new DbExt; 
    	if ($res=$db_ext->rst($stmt)){
    		foreach ($res as $val) {
    			$lang[$val['lang_id']]=$val['language_code'];
    		}    		
    	}*/
    	return $lang;
    }   
    
    public static function notificationListPickup()
    {
    	$data['PICKUP']['REQUEST_RECEIVED']=array(
    	  'REQUEST_RECEIVED_PUSH',
    	  'REQUEST_RECEIVED_SMS',
    	  'REQUEST_RECEIVED_EMAIL'
    	);
    	$data['PICKUP']['DRIVER_STARTED']=array(
    	  'DRIVER_STARTED_PUSH',
    	  'DRIVER_STARTED_SMS',
    	  'DRIVER_STARTED_EMAIL'
    	);
    	$data['PICKUP']['DRIVER_ARRIVED']=array(
    	  'DRIVER_ARRIVED_PUSH',
    	  'DRIVER_ARRIVED_SMS',
    	  'DRIVER_ARRIVED_EMAIL'
    	);
    	$data['PICKUP']['SUCCESSFUL']=array(
    	  'SUCCESSFUL_PUSH',
    	  'SUCCESSFUL_SMS',
    	  'SUCCESSFUL_EMAIL'
    	);
    	$data['PICKUP']['FAILED']=array(
    	  'FAILED_PUSH',
    	  'FAILED_SMS',
    	  'FAILED_EMAIL'
    	);
    	return $data;
    } 	
    
    public static function notificationListDelivery()
    {
    	$data['DELIVERY']['REQUEST_RECEIVED']=array(
    	  'REQUEST_RECEIVED_PUSH',
    	  'REQUEST_RECEIVED_SMS',
    	  'REQUEST_RECEIVED_EMAIL'
    	);
    	$data['DELIVERY']['DRIVER_STARTED']=array(
    	  'DRIVER_STARTED_PUSH',
    	  'DRIVER_STARTED_SMS',
    	  'DRIVER_STARTED_EMAIL'
    	);
    	$data['DELIVERY']['DRIVER_ARRIVED']=array(
    	  'DRIVER_ARRIVED_PUSH',
    	  'DRIVER_ARRIVED_SMS',
    	  'DRIVER_ARRIVED_EMAIL'
    	);
    	$data['DELIVERY']['SUCCESSFUL']=array(
    	  'SUCCESSFUL_PUSH',
    	  'SUCCESSFUL_SMS',
    	  'SUCCESSFUL_EMAIL'
    	);
    	$data['DELIVERY']['FAILED']=array(
    	  'FAILED_PUSH',
    	  'FAILED_SMS',
    	  'FAILED_EMAIL'
    	);
    	return $data;
    } 	

    public static function tagAvailableList()
    {
    	return array(
    	  t('Available Tags'),
    	  'TaskID','CustomerName',
    	  'CustomerAddress','DeliveryDateTime',
    	  'PickUpDateTime','DriverName',
    	  'OrderNo','CompanyName','CompletedTime'
    	);
    }   
    
    public static function getNotifications($customer_id='' ,$viewed=2)
    {
    	$date_now=date("Y-m-d");
    	
    	$and =" AND customer_id=".self::q($customer_id)."  ";
    	
    	$db_ext=new DbExt; 
    	$stmt="
    	SELECT a.* FROM
    	{{task_history}} a
    	WHERE
    	notification_viewed='$viewed'
    	AND 
    	driver_id > 0
    	AND
    	date_created LIKE '".$date_now."%'
    	AND
    	task_id = (
    	  select task_id 
    	  from
    	  {{driver_task}}
    	  where 
    	  task_id=a.task_id
    	  $and    	  
    	  limit 0,1
    	)    	
    	LIMIT 0,3
    	";    	
    	//dump($stmt);
    	if ($res=$db_ext->rst($stmt)){
    		return $res;
    	}
    	return false;
    }
    
    public static function base30_to_jpeg($base30_string, $output_file) {
	
	    $data = str_replace('image/jsignature;base30,', '', $base30_string);
	    $converter = new jSignature_Tools_Base30();
	    $raw = $converter->Base64ToNative($data);
	//Calculate dimensions
		$width = 0;
		$height = 0;
		foreach($raw as $line)
		{
		    if (max($line['x'])>$width)$width=max($line['x']);
		    if (max($line['y'])>$height)$height=max($line['y']);
		}
		
		// Create an image
		    $im = imagecreatetruecolor($width+20,$height+20);
				
		// Save transparency for PNG
		    imagesavealpha($im, true);
		// Fill background with transparency
		    $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
		    imagefill($im, 0, 0, $trans_colour);
		// Set pen thickness
		    imagesetthickness($im, 2);
		// Set pen color to black
		    $black = imagecolorallocate($im, 0, 0, 0);   
		// Loop through array pairs from each signature word
		    for ($i = 0; $i < count($raw); $i++)
		    {
		        // Loop through each pair in a word
		        for ($j = 0; $j < count($raw[$i]['x']); $j++)
		        {
		            // Make sure we are not on the last coordinate in the array
		            if ( ! isset($raw[$i]['x'][$j])) 
		                break;
		            if ( ! isset($raw[$i]['x'][$j+1])) 
		            // Draw the dot for the coordinate
		                imagesetpixel ( $im, $raw[$i]['x'][$j], $raw[$i]['y'][$j], $black); 
		            else
		            // Draw the line for the coordinate pair
		            imageline($im, $raw[$i]['x'][$j], $raw[$i]['y'][$j], $raw[$i]['x'][$j+1], $raw[$i]['y'][$j+1], $black);
		        }
		    } 
	
	    //Create Image
	    $ifp = fopen($output_file, "wb"); 
	    imagepng($im, $output_file);
	    fclose($ifp);  
	    imagedestroy($im);
	    return $output_file; 
	}    
	
	public static function priceSettings()
	{
		 $admin_decimal_separator=getOptionA('admin_decimal_separator');
         $admin_decimal_place=getOptionA('admin_decimal_place');
         $admin_currency_position=getOptionA('admin_currency_position');
         $admin_thousand_separator=getOptionA('admin_thousand_separator');
         
         return array(
	        'decimal_place'=> strlen($admin_decimal_place)>0?$admin_decimal_place:2,
		    'currency_position'=>!empty($admin_currency_position)?$admin_currency_position:'left',
		    'currency_set'=>getCurrencyCode(),
		    'thousand_separator'=>!empty($admin_thousand_separator)?$admin_thousand_separator:'',
		    'decimal_separator'=>!empty($admin_decimal_separator)?$admin_decimal_separator:'.',
	     );
	}
	
	public static function getDriverNotifications($driver_id='')
	{
		$db_ext=new DbExt; 
		$stmt="SELECT * FROM
		{{driver_pushlog}}
		WHERE
		driver_id=".self::q($driver_id)."
		AND
		status='process'
		AND
		is_read='2'
		ORDER BY date_created DESC
		LIMIT 0,10
		";
		if($res=$db_ext->rst($stmt)){
		   return $res;	
		}
		return false;
	}
	
	public static function prettyDate($date='',$show_time=true)
	{
		if(!empty($date)){
			return Yii::app()->functions->translateDate(Yii::app()->functions->FormatDateTime($date,$show_time));
		}		
		return '';	
	}
	
	public static function sendDriverNotification($key='',$info='')
	{				
		if(!is_array($info) && count($info)<=0){
			return false;
		}
				
		/*check if driver is online */		
		$driver_send_push_to_online=getOption(Driver::getUserId() , 'driver_send_push_to_online');		
		if ( $driver_send_push_to_online==1){						
			if ( !$driver_inf=self::isDriverOnline($info['driver_id'])){				
				return ;
			} 
		}
		
		$db_ext=new DbExt; 
		//dump($info);		
		//PUSH
		$key_value=getOption(Driver::getUserId(),$key."_PUSH");						
		if ($key_value==1 && $info['enabled_push']==1){
			$push_message=getOption(Driver::getUserId(),$key."_PUSH_TPL");			
			$push_message=self::smarty('TaskID',$info['task_id'],$push_message);
			$push_message=self::smarty('CustomerName',$info['customer_name'],$push_message);
			$push_message=self::smarty('CustomerAddress',$info['delivery_address'],$push_message);
			$push_message=self::smarty('DeliveryDateTime',self::prettyDate($info['delivery_date']),$push_message);
			$push_message=self::smarty('PickUpDateTime',self::prettyDate($info['delivery_date']),$push_message);
			$push_message=self::smarty('DriverName',$info['driver_name'],$push_message);			
			$push_message=self::smarty('CompanyName',getOptionA('website_title'),$push_message);
			//$push_message=self::smarty('CompletedTime',$info[''],$push_message);				
			$params=array(
			  'customer_id'=>isset($info['customer_id'])?$info['customer_id']:Driver::getUserId(),
			  'device_platform'=>isset($info['device_platform'])?$info['device_platform']:'',
			  'device_id'=>isset($info['device_id'])?$info['device_id']:'',
			  'push_title'=>str_replace("_",' ',$key),
			  'push_message'=>$push_message,
			  'actions'=>$key,			  
			  'driver_id'=>isset($info['driver_id'])?$info['driver_id']:'',
			  'task_id'=>isset($info['task_id'])?$info['task_id']:'',
			  'date_created'=>date('c'),
			  'ip_address'=>$_SERVER['REMOTE_ADDR']
			);								
			$db_ext->insertData("{{driver_pushlog}}",$params);			
			$push_id=Yii::app()->db->getLastInsertID();
			self::RunPush( $push_id );
		}
		
		//SMS		
		if(self::canCustomerSendSMS($info['customer_id'])){
			$key_value=getOption(Driver::getUserId(),$key."_SMS");				
			if ($key_value==1 && $info['driver_phone']!=""){
			   $sms_message=getOption(Driver::getUserId(),$key."_SMS_TPL");		   			   
			   $sms_message=self::smarty('TaskID',$info['task_id'],$sms_message);
			   $sms_message=self::smarty('CustomerName',$info['customer_name'],$sms_message);
			   $sms_message=self::smarty('CustomerAddress',$info['delivery_address'],$sms_message);
			   $sms_message=self::smarty('DeliveryDateTime',self::prettyDate($info['delivery_date']),$sms_message);
			   $sms_message=self::smarty('PickUpDateTime',self::prettyDate($info['delivery_date']),$sms_message);
			   $sms_message=self::smarty('DriverName',$info['driver_name'],$sms_message);		   
			   $sms_message=self::smarty('CompanyName',getOptionA('website_title'),$sms_message);		   
			   if ( $send_sms= Yii::app()->functions->sendSMS($info['driver_phone'],$sms_message)){		   	    
			   	    $params=array(		   	      
					  'to_number'=>$info['driver_phone'],
					  'sms_text'=>$sms_message,
					  'msg'=>isset($send_sms['msg'])?$send_sms['msg']:'',
					  'raw'=>isset($send_sms['raw'])?$send_sms['raw']:'',
					  'provider'=>$send_sms['sms_provider'],
					  'date_created'=>date('c'),
					  'ip_address'=>$_SERVER['REMOTE_ADDR']
					);				
					//$db_ext->insertData("{{sms_logs}}",$params);
			   }
			}
		}
		
		//EMAIL
		$key_value=getOption(Driver::getUserId(),$key."_EMAIL");				
		if ($key_value==1 && $info['driver_email']!=""){
		   $email_message=getOption(Driver::getUserId(),$key."_EMAIL_TPL");		   		   
		   $email_message=self::smarty('TaskID',$info['task_id'],$email_message);
		   $email_message=self::smarty('CustomerName',$info['customer_name'],$email_message);
		   $email_message=self::smarty('CustomerAddress',$info['delivery_address'],$email_message);
		   $email_message=self::smarty('DeliveryDateTime',self::prettyDate($info['delivery_date']),$email_message);
		   $email_message=self::smarty('PickUpDateTime',self::prettyDate($info['delivery_date']),$email_message);
		   $email_message=self::smarty('DriverName',$info['driver_name'],$email_message);		   
		   $email_message=self::smarty('CompanyName',getOptionA('website_title'),$email_message);		   
		   $resp_email=sendEmail($info['driver_email'],'',$key,$email_message);		   
		}
		
	}
	
	public static function smarty($search='',$value='',$subject='')
	{
		return str_replace("[".$search."]",$value,$subject);
	}
		
	public static function sendNotificationCustomer($key='',$info='')
	{
		
		//return ;
		/*dump($key);
		dump($info);*/
		
		$db_ext=new DbExt; 
		
		$key_is_enabled=getOption(Driver::getUserId(),$key."_PUSH");			
		//dump($key_is_enabled);
				
		$key_is_enabled=getOption(Driver::getUserId(),$key."_EMAIL");
		if ( $key_is_enabled==1 && !empty($info['email_address'])){
			$message=getOptionA($key."_EMAIL_TPL");		
			$message=self::smarty('TaskID',$info['task_id'],$message);
			$message=self::smarty('CustomerName',$info['customer_name'],$message);
			$message=self::smarty('CustomerAddress',$info['delivery_address'],$message);
			$message=self::smarty('DeliveryDateTime',self::prettyDate($info['delivery_date']),$message);
			$message=self::smarty('PickUpDateTime',self::prettyDate($info['delivery_date']),$message);
			$message=self::smarty('DriverName',$info['driver_name'],$message);			
			$message=self::smarty('CompanyName',getOptionA('website_title'),$message);	
			//dump($message);				
			sendEmail($info['email_address'],'',
			str_replace("_"," ",$key)
			,$message);
		}
		
		
		
		$key_is_enabled=getOption(Driver::getUserId() , $key."_SMS");		
		
		/*plan check*/
		if ( !self::planCheckCanSendSMS( self::getPlanID() )){
			$key_is_enabled=2;
		}
		
		if ( $key_is_enabled==1 && $info['contact_number']!=""){
			$message=getOption(Driver::getUserId(), $key."_SMS_TPL");		
			$message=self::smarty('TaskID',$info['task_id'],$message);
			$message=self::smarty('CustomerName',$info['customer_name'],$message);
			$message=self::smarty('CustomerAddress',$info['delivery_address'],$message);
			$message=self::smarty('DeliveryDateTime',self::prettyDate($info['delivery_date']),$message);
			$message=self::smarty('PickUpDateTime',self::prettyDate($info['delivery_date']),$message);
			$message=self::smarty('DriverName',$info['driver_name'],$message);			
			$message=self::smarty('CompanyName',getOptionA('website_title'),$message);								
			if ( $send_sms= Yii::app()->functions->sendSMS($info['contact_number'],$message)){		   	    
		   	    $params=array(		   	      
				  'to_number'=>$info['driver_phone'],
				  'sms_text'=>$message,
				  'msg'=>isset($send_sms['msg'])?$send_sms['msg']:'',
				  'raw'=>isset($send_sms['raw'])?$send_sms['raw']:'',
				  'provider'=>$send_sms['sms_provider'],
				  'date_created'=>date('c'),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);				
				$db_ext->insertData("{{sms_logs}}",$params);
		   }
		}		
	}
	
	public static function RunPush( $push_id='')
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
		
		$and='';
		if(!empty($push_id)){
			$and=" AND push_id=".self::q($push_id)." ";
		}
				
		$stmt="
		SELECT * FROM
		{{driver_pushlog}}
		WHERE
		status='pending'
		$and
		ORDER BY date_created ASC
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			foreach ($res as $val) {				
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
//						   'order_id'=>$val['order_id'],
						   'actions'=>$val['actions'],
						 )
					   );		
					   
					   //dump($message);
					   
					   if ( strtolower($val['device_platform']) =="android"){
						   $resp=AndroidPush::sendPush($api_key,$val['device_id'],$message);
						   if(is_array($resp) && count($resp)>=1){
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
				$db->updateData("{{driver_pushlog}}",$params,'push_id',$push_id);
				
			}
		} //else echo 'no record to process';
	}	
	
	public static function cleanString($text='')
	{
		if(!empty($text)){
			return stripslashes($text);
		}
		return ;
			
	}
	
    public static function updateLastOnline($driver_id='')
	{
		$params=array(    	 
    	  'last_online'=>strtotime("now"),
    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
    	);
    	$db=new DbExt;
    	$db->updateData("{{driver}}",$params,'driver_id',$driver_id);
	}	

	public static function isDriverOnline($driver_id='')
	{
		$db=new DbExt;
		$todays_date=date('Y-m-d');			
		$time_now = time() - 200;
		$and='';
		
		$and.=" AND on_duty ='1' ";
        $and.=" AND last_online >='$time_now' ";
        $and.=" AND last_login like '".$todays_date."%'";
        
        $stmt="SELECT * FROM
        {{driver}}
        WHERE driver_id=".self::q($driver_id)."
        $and
        LIMIT 0,1
        ";        
        if ( $res=$db->rst($stmt)){
        	return $res;
        }
        return false;
	}	

	public static function getUnAssignedDriver($task_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver_assignment}}
		WHERE
		status='pending'
		AND task_id=".self::q($task_id)."
		ORDER BY assignment_id ASC
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}
	
    public static function getUnAssignedDriver2($task_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver_assignment}}
		WHERE
		status='pending'
		AND task_id=".self::q($task_id)."
		ORDER BY assignment_id ASC
		LIMIT 0,5
		";
		if ( $res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}		
	
    public static function getUnAssignedDriver3($task_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver_assignment}}
		WHERE		
		task_id=".self::q($task_id)."
		ORDER BY assignment_id ASC
		LIMIT 0,10
		";
		if ( $res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}			
	
	public static function getTaskByDriverNTask($task_id='', $driver_id='')
	{
		$res=''; $res2='';
		
		$db=new DbExt;
		$stmt="
		SELECT a.*,a.driver_id as driver_id_task
		 FROM
		{{driver_task}} a
		WHERE
		task_id=".self::q($task_id)."
		LIMIT 0,1
		";		
		if ( $res=$db->rst($stmt)){
			$res=$res[0];			
			$stmt2="
			SELECT 
			b.driver_id,
			concat(b.first_name,' ',b.last_name) as driver_name,
			b.device_id,
			b.phone as driver_phone,
			b.email as driver_email,
			b.device_platform,
			b.enabled_push,
			b.location_lat as driver_lat,
			b.location_lng as driver_lng
			FROM {{driver}} b
			WHERE
			driver_id=".self::q($driver_id)."
			LIMIT 0,1
			";
			if($res2=$db->rst($stmt2)){
			  $res2=$res2[0];
			  //dump($res2);
			}			
			$merge_data=array_merge( (array) $res, (array) $res2);
			return $merge_data;
		}
		return false;
	}	
	
	public static function getTaskByDriverIDWithAssigment($driver_id='',$delivery_date='')
	{
		$db=new DbExt;
		$db->qry("SET SQL_BIG_SELECTS=1");
					
		$or="
		OR task_id IN (
		  select task_id 
		  from
		  {{driver_assignment}}
		  where
		  task_id=a.task_id
		  and
		  driver_id=".self::q($driver_id)."
		  and
		  status='process'
		  and
		  task_status='unassigned'
		)
		";
		
		$stmt="SELECT a.* FROM
		{{driver_task_view}} a
		WHERE
		driver_id=".self::q($driver_id)."
		AND
		delivery_date LIKE '".$delivery_date."%'	
		$or
		ORDER BY delivery_date ASC
		";
		
		if(isset($_GET['debug'])){
		  dump($stmt);	
		}
		if($res=$db->rst($stmt)){
		   return $res;
		}	
		return false;
	}			

	public static function getAssignmentByDriverTaskID($driver_id='',$task_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{driver_assignment}}
		WHERE
		driver_id=".self::q($driver_id)."
		AND task_id=".self::q($task_id)."		
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}		
	
	public static function generateReports($chart_type='',$time='',$team='',$driver='',$chart_option='',
	$start_date='' , $end_date='' )
	{
	
		$db=new DbExt;
		$and='';
		switch ($time) {			
			case "week":
				$start= date('Y-m-d', strtotime("-7 day") );
			    $end=date("Y-m-d", strtotime("+1 day"));
				$and.= " AND delivery_date BETWEEN '$start' AND '$end' ";
				break;
								
			case "month":	
			    $start= date('Y-m-d', strtotime("-30 day") );
			    $end=date("Y-m-d", strtotime("+1 day"));
				$and.= " AND delivery_date BETWEEN '$start' AND '$end' ";
			   break;
			   
			case "custom":		
			   $and.= " AND delivery_date BETWEEN '$start_date' AND '$end_date' ";
			   break;
			   
			default:
				break;
		}
		
		if ($team>0){
			$and.=" AND team_id=".self::q($team)." ";
		}
		if($driver>0){
			$and.=" AND driver_id=".self::q($driver)." ";
		}
		
		$and.=" AND driver_id <>'' ";		
		
		$user_type=self::getUserType();		
		if ( $user_type=="merchant"){
			$user_id=self::getUserId();
			$and=" AND user_type='merchant' AND user_id=".self::q($user_id)." ";
		}
		
		
		$group="GROUP BY DATE_FORMAT(delivery_date,'%Y-%m-%d'),status";
		if ( $chart_option=="agent"){
			$group="GROUP BY driver_name,status";
		}
		
		if ( $chart_type=="task_completion"){
			$stmt="
			SELECT DATE_FORMAT(a.delivery_date,'%Y-%m-%d') as delivery_date ,a.status,
			count(*) as total,
			(
			  select concat(first_name,' ',last_name)
			  from
			  {{driver}}
			  where
			  driver_id=a.driver_id
			) as driver_name
			FROM {{driver_task}} a
			WHERE customer_id=".self::q(self::getUserId())."
			$and
			$group
			ORDER BY delivery_date ASC
			";
		} else {
			$stmt="
			";
		}
		//dump($stmt);
		if ( $res=$db->rst($stmt)){
			return $res;
		}
		return false;
	}
	
	public static function getPlansByID($plan_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT * FROM
		{{plan}}
		WHERE
		plan_id=".Driver::q($plan_id)."
		LIMIT 0,1
		";
		if ( $res=$db->rst($stmt)){
			return $res[0];
		}
		return false;
	}	
	
	public static function getPlansPrice($plan_id='')
	{
		if ( $res=self::getPlansByID($plan_id)){			
			$price=$res['price'];
			if($res['promo_price']>0.0001){
				$price=$res['promo_price'];
			}
			return $price;
		}
		return 0;
	}	
	
	public static function planCheckCanAddDriver($customer_id='',$plan_id='')
	{
		$db=new DbExt;
		$stmt="SELECT count(*) as total_driver,
		(
		select allowed_driver
		from {{plan}}
		where
		plan_id=".self::q($plan_id)."
		) as allowed_driver
		FROM {{driver}} 
		WHERE
		customer_id=".self::q($customer_id)."
		";
		//dump($stmt);	
		if($res=$db->rst($stmt)){
			$res=$res[0];				

			if($res['allowed_driver']=="unlimited"){
				return true;
			}
					
			if($res['allowed_driver']>$res['total_driver']){
				return true;
			}			
		}
		return false;
	}
	
	public static function planCheckCAnAddTask($customer_id='',$plan_id='')
	{
		$db=new DbExt;		
		$stmt="SELECT count(*) as total_task,
		(
		select allowed_task
		from {{plan}}
		where
		plan_id=".self::q($plan_id)."
		) as allowed_task
		FROM {{driver_task}} 
		WHERE
		customer_id=".self::q($customer_id)."
		";
		//dump($stmt);	
		if($res=$db->rst($stmt)){
			$res=$res[0];									
			
			if($res['allowed_task']=="unlimited"){
				return true;
			}
			
			if($res['allowed_task']>$res['total_task']){
				return true;
			}			
		}
		return false;
	}
	
	public static function planCheckCanSendSMS($plan_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT *
		FROM {{plan}}
		WHERE
		plan_id=".self::q($plan_id)."
		LIMIT 0,1
		";
	    if($res=$db->rst($stmt)){
			$res=$res[0];									
			if($res['with_sms']==1){
				return true;
			}
	    }
	    return false;	
	}
	
	public static function canCustomerSendSMS($customer_id='')
	{
		$db=new DbExt;
		$stmt="
		SELECT *
		FROM {{customer}}
		WHERE
		customer_id=".self::q($customer_id)."
		LIMIT 0,1
		";				
	    if($res=$db->rst($stmt)){
			$res=$res[0];				
			if($res['with_sms']==1){
				return true;
			}
	    }
	    return false;	
	}
	
	public static function getMobileTranslation()
	{
		$language_list=getOptionA('language_list');
    	if(!empty($language_list)){
    		$language_list=json_decode($language_list,true);
    	}
    	
    	$final_lang='';
    	$path=Yii::getPathOfAlias('webroot')."/protected/messages";    	
    	if(is_array($language_list) && count($language_list)>=1){
    		foreach ($language_list as $val) {    			
    			$lang_path=$path."/$val/mobile.php";    			
    			if(file_exists($lang_path)){    				
    				$temp_lang='';
    				$temp_lang=require_once($lang_path);    				
    				foreach ($temp_lang as $key=>$val_lang) {
    					$final_lang[$key][$val]=$val_lang;
    				}
    			}
    		}    		
    	}        	
    	return $final_lang;
	}
	
	public static function checkDriverUserExist($username='',$driver_id='')
	{
        $db=new DbExt;
        $and="";
        if(is_numeric($driver_id)){
        	$and=" AND driver_id!=".self::q($driver_id)." ";
        }
		$stmt="
		SELECT * FROM
		{{driver}}
		WHERE
		username=".self::q($username)."
		$and
		";		
		if($res=$db->rst($stmt)){				
			return $res[0];
	    }
	    return false;	
	}
		
}/* end class*/