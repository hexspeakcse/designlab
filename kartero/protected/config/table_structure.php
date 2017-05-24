<?php
$tbl['admin']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."admin (
  `admin_id` int(14) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."admin
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `username` (`username`);
 
ALTER TABLE ".$table_prefix."admin
  MODIFY `admin_id` int(14) NOT NULL AUTO_INCREMENT;
";


$tbl['currency']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."currency (
  `curr_id` int(14) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `currency_symbol` varchar(20) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."currency
  ADD PRIMARY KEY (`curr_id`);
  
ALTER TABLE ".$table_prefix."currency
  MODIFY `curr_id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;  
";

$tbl['customer']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."customer (
  `customer_id` int(14) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `enabled_auto_assign` int(1) NOT NULL,
  `include_offline_driver` int(1) NOT NULL,
  `autoassign_notify_email` varchar(255) NOT NULL,
  `request_expire` int(14) NOT NULL,
  `auto_assign_type` varchar(50) NOT NULL,
  `assign_request_expire` int(14) NOT NULL,
  `plan_id` int(14) NOT NULL,
  `plan_price` float(14,4) NOT NULL,
  `plan_expiration` date NOT NULL,
  `plan_currency_code` varchar(3) NOT NULL,
  `with_sms` int(1) NOT NULL DEFAULT '2',
  `token` varchar(255) NOT NULL,
  `verification_code` varchar(50) NOT NULL,
  `verification_confirm_date` datetime NOT NULL,
  `needs_approval` int(1) NOT NULL DEFAULT '2',
  `renew_plan_id` int(14) NOT NULL,
  `driver_assign_radius` int(14) NOT NULL  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."customer
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `email_address` (`email_address`),
  ADD KEY `status` (`status`),
  ADD KEY `token` (`token`);

ALTER TABLE ".$table_prefix."customer
  MODIFY `customer_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['driver']="

CREATE TABLE IF NOT EXISTS ".$table_prefix."driver (
  `driver_id` int(14) NOT NULL,
  `customer_id` int(14) NOT NULL,
  `on_duty` int(1) NOT NULL DEFAULT '1',
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
  `enabled_push` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE ".$table_prefix."driver
  ADD PRIMARY KEY (`driver_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `user_id` (`customer_id`),
  ADD KEY `status` (`status`);


ALTER TABLE ".$table_prefix."driver
  MODIFY `driver_id` int(14) NOT NULL AUTO_INCREMENT;
";


$tbl['driver_assignment']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."driver_assignment (
  `assignment_id` int(14) NOT NULL,
  `auto_assign_type` varchar(50) NOT NULL,
  `task_id` int(14) NOT NULL,
  `driver_id` int(14) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `task_status` varchar(255) NOT NULL DEFAULT 'unassigned',
  `date_created` datetime NOT NULL,
  `date_process` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."driver_assignment
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `driver_id` (`driver_id`);

ALTER TABLE ".$table_prefix."driver_assignment
  MODIFY `assignment_id` int(14) NOT NULL AUTO_INCREMENT;
";


$tbl['driver_pushlog']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."driver_pushlog (
  `push_id` int(14) NOT NULL,
  `customer_id` int(14) NOT NULL,
  `device_platform` varchar(50) NOT NULL,
  `device_id` text NOT NULL,
  `push_title` varchar(255) NOT NULL,
  `push_message` varchar(255) NOT NULL,
  `push_type` varchar(50) NOT NULL DEFAULT 'task',
  `actions` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `json_response` text NOT NULL,
  `driver_id` int(14) NOT NULL,
  `task_id` int(14) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_process` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `is_read` int(1) DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."driver_pushlog
  ADD PRIMARY KEY (`push_id`),
  ADD KEY `device_platform` (`device_platform`),
  ADD KEY `status` (`status`),
  ADD KEY `task_id` (`task_id`);
  
ALTER TABLE ".$table_prefix."driver_pushlog
  MODIFY `push_id` int(14) NOT NULL AUTO_INCREMENT;
";


$tbl['driver_task']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."driver_task (
  `task_id` int(14) NOT NULL,
  `customer_id` int(14) NOT NULL,
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
  `auto_assign_type` varchar(50) NOT NULL,
  `assign_started` datetime NOT NULL,
  `assignment_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."driver_task
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `user_id` (`customer_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `driver_id` (`driver_id`);

ALTER TABLE ".$table_prefix."driver_task
  MODIFY `task_id` int(14) NOT NULL AUTO_INCREMENT;  
";


$tbl['driver_team']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."driver_team (
  `team_id` int(14) NOT NULL,
  `customer_id` int(14) NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `location_accuracy` varchar(50) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE ".$table_prefix."driver_team
  ADD PRIMARY KEY (`team_id`),
  ADD KEY `user_id` (`customer_id`),
  ADD KEY `status` (`status`);
  
ALTER TABLE ".$table_prefix."driver_team
  MODIFY `team_id` int(14) NOT NULL AUTO_INCREMENT;  
";


$tbl['email_logs']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."email_logs (
  `id` int(14) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."email_logs
  ADD PRIMARY KEY (`id`);

ALTER TABLE ".$table_prefix."email_logs
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";



$tbl['option']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."option (
  `id` int(14) NOT NULL,
  `customer_id` int(14) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `".$table_prefix."option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `option_name` (`option_name`);
  
ALTER TABLE `".$table_prefix."option`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";


$tbl['page']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."page (
  `page_id` int(14) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'published',
  `active` int(1) NOT NULL DEFAULT '2',
  `assign_to` varchar(100) NOT NULL DEFAULT 'bottom-1',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `sequence` int(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."page
  ADD PRIMARY KEY (`page_id`),
  ADD KEY `slug` (`slug`);
  
ALTER TABLE ".$table_prefix."page
  MODIFY `page_id` int(14) NOT NULL AUTO_INCREMENT;
";



$tbl['payment_logs']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."payment_logs (
  `id` int(14) NOT NULL,
  `customer_id` int(14) NOT NULL,
  `transaction_type` varchar(255) NOT NULL DEFAULT 'signup',
  `payment_provider` varchar(100) NOT NULL,
  `memo` text NOT NULL,
  `total_paid` float(14,4) NOT NULL,
  `currency_code` varchar(3) NOT NULL,
  `transaction_ref` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."payment_logs
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE ".$table_prefix."payment_logs
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";


$tbl['plan']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."plan (
  `plan_id` int(14) NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `plan_name_description` text NOT NULL,
  `price` float(14,4) NOT NULL,
  `promo_price` float(14,4) NOT NULL,
  `plan_type` varchar(255) NOT NULL,
  `expiration` varchar(50) NOT NULL,
  `allowed_driver` varchar(50) NOT NULL,
  `allowed_task` varchar(50) NOT NULL,
  `with_sms` int(1) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `sequence` int(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."plan
  ADD PRIMARY KEY (`plan_id`);

ALTER TABLE ".$table_prefix."plan
  MODIFY `plan_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['sms_logs']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."sms_logs (
  `id` int(14) NOT NULL,
  `to_number` varchar(100) NOT NULL,
  `sms_text` text NOT NULL,
  `provider` varchar(100) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `raw` text NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."sms_logs
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE ".$table_prefix."sms_logs
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['task_history']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."task_history (
  `id` int(14) NOT NULL,
  `status` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `task_id` int(14) NOT NULL,
  `reason` text NOT NULL,
  `customer_signature` varchar(255) NOT NULL,
  `notification_viewed` int(1) NOT NULL DEFAULT '2',
  `driver_id` int(14) NOT NULL,
  `driver_location_lat` varchar(50) NOT NULL,
  `driver_location_lng` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `ip_address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE ".$table_prefix."task_history
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `notification_viewed` (`notification_viewed`);

ALTER TABLE ".$table_prefix."task_history
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['driver_task_view']="
Create OR replace view ".$table_prefix."driver_task_view as
SELECT a.*,
concat(b.first_name,' ',b.last_name) as driver_name,
b.device_id,
b.phone as driver_phone,
b.email as driver_email,
b.device_platform,
b.enabled_push,
e.team_name
	
FROM
".$table_prefix."driver_task a
		
LEFT JOIN ".$table_prefix."driver b
ON
b.driver_id=a.driver_id

left join ".$table_prefix."driver_team e
ON 
e.team_id=a.team_id
";