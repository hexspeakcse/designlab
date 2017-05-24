<?php
class UpdateController extends CController
{
	public function actionIndex()
	{
		$prefix=Yii::app()->db->tablePrefix;		
		$table_prefix=$prefix;
		
		$DbExt=new DbExt;
		
		$stmt="	
		CREATE TABLE IF NOT EXISTS ".$table_prefix."driver (
		  `driver_id` int(14) NOT NULL,
		  `user_type` varchar(50) NOT NULL,
		  `user_id` int(14) NOT NULL,
		  `on_duty` int(1) NOT NULL DEFAULT '2',
		  `first_name` varchar(255) NOT NULL,
		  `last_name` varchar(255) NOT NULL,
		  `email` varchar(100) NOT NULL,
		  `phone` varchar(20) NOT NULL,
		  `username` varchar(100) NOT NULL,
		  `password` varchar(100) NOT NULL,
		  `team_id` int(14) NOT NULL,
		  `transport_type_id` varchar(50) NOT NULL,
		  `transport_description` varchar(255) NOT NULL,
		  `licence_plate` varchar(255) NOT NULL,
		  `color` varchar(255) NOT NULL,
		  `status` varchar(255) NOT NULL DEFAULT 'active',
		  `date_created` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  `last_login` datetime NOT NULL,
		  `last_online` int(14) NOT NULL,
		  `location_address` text NOT NULL,
		  `location_lat` varchar(50) NOT NULL,
		  `location_lng` varchar(50) NOT NULL,
		  `ip_address` varchar(50) NOT NULL,
		  `forgot_pass_code` varchar(10) NOT NULL,
		  `token` varchar(255) NOT NULL,
		  `device_id` text NOT NULL,
		  `device_platform` varchar(50) NOT NULL DEFAULT 'Android',
		  `enabled_push` int(1) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`driver_id`),
		  KEY `team_id` (`team_id`),
		  KEY `user_type` (`user_type`),
		  KEY `user_id` (`user_id`),
		  KEY `status` (`status`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";		
		echo "Creating Table driver..<br/>";	
		$DbExt->qry($stmt);
		echo "(Done)<br/>";    		
		
		$stmt="
		ALTER TABLE ".$table_prefix."driver
        MODIFY `driver_id` int(14) NOT NULL AUTO_INCREMENT;
		";
		echo "ALTER Table driver..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";    	
		
		
		$stmt="
		CREATE TABLE IF NOT EXISTS ".$table_prefix."driver_pushlog (
		  `push_id` int(14) NOT NULL,
		  `device_platform` varchar(50) NOT NULL,
		  `device_id` text NOT NULL,
		  `push_title` varchar(255) NOT NULL,
		  `push_message` varchar(255) NOT NULL,
		  `push_type` varchar(50) NOT NULL DEFAULT 'task',
		  `actions` varchar(255) NOT NULL,
		  `status` varchar(255) NOT NULL DEFAULT 'pending',
		  `json_response` text NOT NULL,
		  `order_id` int(14) NOT NULL,
		  `driver_id` int(14) NOT NULL,
		  `task_id` int(14) NOT NULL,
		  `date_created` datetime NOT NULL,
		  `date_process` datetime NOT NULL,
		  `ip_address` varchar(50) NOT NULL,
		  `is_read` int(1) DEFAULT '2',
		   PRIMARY KEY (`push_id`),
		   KEY `device_platform` (`device_platform`),
		   KEY `status` (`status`),
		   KEY `order_id` (`order_id`),
		   KEY `task_id` (`task_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		echo "Creating Table driver_pushlog..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";    	
		
		$stmt="
		  ALTER TABLE ".$table_prefix."driver_pushlog
           MODIFY `push_id` int(14) NOT NULL AUTO_INCREMENT;
		";
		echo "ALTER Table driver_pushlog..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";
		
		$stmt="
		  CREATE TABLE IF NOT EXISTS ".$table_prefix."driver_task (
		  `task_id` int(14) NOT NULL,
		  `order_id` int(14) NOT NULL,
		  `user_type` varchar(100) NOT NULL,
		  `user_id` int(14) NOT NULL,
		  `task_description` varchar(255) NOT NULL,
		  `trans_type` varchar(255) NOT NULL,
		  `contact_number` varchar(50) NOT NULL,
		  `email_address` varchar(200) NOT NULL,
		  `customer_name` varchar(255) NOT NULL,
		  `delivery_date` datetime NOT NULL,
		  `delivery_address` varchar(255) NOT NULL,
		  `team_id` int(14) NOT NULL,
		  `driver_id` int(14) NOT NULL,
		  `task_lat` varchar(50) NOT NULL,
		  `task_lng` varchar(50) NOT NULL,
		  `customer_signature` varchar(255) NOT NULL,
		  `status` varchar(255) NOT NULL DEFAULT 'unassigned',
		  `date_created` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  `ip_address` varchar(50) NOT NULL,
		   PRIMARY KEY (`task_id`),
		   KEY `order_id` (`order_id`),
		   KEY `user_type` (`user_type`),
		   KEY `user_id` (`user_id`),
		   KEY `team_id` (`team_id`),
		   KEY `driver_id` (`driver_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		echo "Creating Table driver_task..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";
		
		$stmt="
		  ALTER TABLE ".$table_prefix."driver_task
          MODIFY `task_id` int(14) NOT NULL AUTO_INCREMENT;
		";
		echo "ALTER Table driver_task..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";
		
		$stmt="		 
		CREATE TABLE IF NOT EXISTS ".$table_prefix."driver_team (
		  `team_id` int(14) NOT NULL,
		  `user_type` varchar(100) NOT NULL,
		  `user_id` int(14) NOT NULL,
		  `team_name` varchar(255) NOT NULL,
		  `location_accuracy` varchar(50) NOT NULL,
		  `status` varchar(255) NOT NULL,
		  `date_created` datetime NOT NULL,
		  `date_modified` datetime NOT NULL,
		  `ip_address` varchar(50) NOT NULL,
		   PRIMARY KEY (`team_id`),
		   KEY `user_type` (`user_type`),
		   KEY `user_id` (`user_id`),
		   KEY `status` (`status`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		echo "Creating Table driver_team..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";
		
	
		$stmt="
		  ALTER TABLE ".$table_prefix."driver_team
          MODIFY `team_id` int(14) NOT NULL AUTO_INCREMENT;
		";
		echo "ALTER Table driver_team..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";
				
		$stmt="
		  Create OR replace view ".$table_prefix."driver_task_view as
			SELECT a.*,
			concat(b.first_name,' ',b.last_name) as driver_name,
			b.device_id,
			b.phone as driver_phone,
			b.email as driver_email,
			b.device_platform,
			b.enabled_push,
			c.merchant_id,
			d.restaurant_name as merchant_name,
			e.team_name
				
			FROM
			".$table_prefix."driver_task a
					
			LEFT JOIN ".$table_prefix."driver b
			ON
			b.driver_id=a.driver_id
			
			left join ".$table_prefix."order c
			ON 
			c.order_id=a.order_id
			
			left join ".$table_prefix."merchant d
			ON 
			d.merchant_id=c.merchant_id
			
			left join ".$table_prefix."driver_team e
			ON 
			e.team_id=a.team_id
		";
		echo "ALTER view driver_task_view..<br/>";
		$DbExt->qry($stmt);
		echo "(Done)<br/>";
				
		
		echo "Updating task_history<br/>";
		$new_field=array( 
		   'task_id'=>"int(14) NOT NULL",
		   'reason'=>"text NOT NULL",
		   'customer_signature'=>"varchar(255) NOT NULL",
		   'notification_viewed'=>"int(1) NOT NULL DEFAULT '2'",
		   'driver_id'=>"int(14) NOT NULL",
		   'driver_location_lat'=>"varchar(50) NOT NULL",
		   'driver_location_lng'=>"varchar(50) NOT NULL"		   
		);
		$this->alterTable('task_history',$new_field);
		
		
		$stmt="ALTER TABLE ".$table_prefix."driver_task AUTO_INCREMENT = 100000;";
		echo "Altering table driver_task<br/>";
		$DbExt->qry($stmt);
		
		echo "(FINISH)<br/>";  		
	} /*end index*/
	
	public function addIndex($table='',$index_name='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		
		$table=$prefix.$table;
		
		$stmt="
		SHOW INDEX FROM $table
		";		
		$found=false;
		if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {				
				if ( $val['Key_name']==$index_name){
					$found=true;
					break;
				}
			}
		} 
		
		if ($found==false){
			echo "create index<br>";
			$stmt_index="ALTER TABLE $table ADD INDEX ( $index_name ) ";
			dump($stmt_index);
			$DbExt->qry($stmt_index);
			echo "Creating Index $index_name on $table <br/>";		
            echo "(Done)<br/>";		
		} else echo 'index exist<br>';
	}
	
	public function alterTable($table='',$new_field='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		$existing_field='';
		if ( $res = Yii::app()->functions->checkTableStructure($table)){
			foreach ($res as $val) {								
				$existing_field[$val['Field']]=$val['Field'];
			}			
			foreach ($new_field as $key_new=>$val_new) {				
				if (!in_array($key_new,$existing_field)){
					echo "Creating field $key_new <br/>";
					$stmt_alter="ALTER TABLE ".$prefix."$table ADD $key_new ".$new_field[$key_new];
					dump($stmt_alter);
				    if ($DbExt->qry($stmt_alter)){
					   echo "(Done)<br/>";
				   } else echo "(Failed)<br/>";
				} else echo "Field $key_new already exist<br/>";
			}
		}
	}	
	
} /*end class*/