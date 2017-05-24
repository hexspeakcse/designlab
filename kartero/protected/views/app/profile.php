
<div id="layout_1">
<?php 
$this->renderPartial('/tpl/layout1_top',array(   
));
?> 
</div> <!--layout_1-->

<div class="parent-wrapper">

 <div class="content_1 white">   
   <?php 
   $this->renderPartial('/tpl/menu',array(   
   ));
   ?>
 </div> <!--content_1-->
 
 <div class="content_main">       
    
    <ul id="tabs">
	 <li class="<?php echo $tabs==1?"active":''?>"><?php echo t("Profile")?></li>
	 <li class="<?php echo $tabs==2?"active":''?>"><?php echo t("Plans")?></li>	 
	 <li><?php echo t("Payment history")?></li>	 
	</ul>
	
	<ul id="tab">  	
	  <li class="<?php echo $tabs==1?"active":''?>">
	  <div class="inner">
 	     
		<form id="frm" class="frm form-horizontal">
		<?php echo CHtml::hiddenField('action','updateProfile')?>
		
		<div class="row">
		  <div class="col-md-6 ">
		    <?php echo CHtml::textField('first_name',
		    isset($data['first_name'])?$data['first_name']:''
		    ,array(
		      'placeholder'=>t("First Name"),
		      'required'=>true
		    ))?>
		  </div>
		  <div class="col-md-6 ">
		    <?php echo CHtml::textField('last_name',
		    isset($data['last_name'])?$data['last_name']:''
		    ,array(
		      'placeholder'=>t("Last Name"),
		       'required'=>true
		    ))?>
		  </div>
		</div> <!--row-->    
		
		 <div class="row top20">
		  <div class="col-md-6 ">
		    <?php echo CHtml::textField('mobile_number',
		    isset($data['mobile_number'])?$data['mobile_number']:''
		    ,array(
		      'placeholder'=>t("Mobile Number"),
		      'class'=>"mobile_inputs",
		      'required'=>true
		    ))?>
		  </div>
		  <div class="col-md-6 ">
		    <?php echo CHtml::textField('email_address',
		    isset($data['email_address'])?$data['email_address']:''
		    ,array(
		      'placeholder'=>t("Email address"),
		       'required'=>true
		    ))?>
		  </div>
		</div> <!--row-->   
		
		
		<div class="row top20">
		  <div class="col-md-6 ">
		    <?php echo CHtml::textField('company_name',
		    isset($data['company_name'])?$data['company_name']:''
		    ,array(
		      'placeholder'=>t("Company name"),                    
		    ))?>
		  </div>
		  <div class="col-md-6 ">
		    <?php echo CHtml::textField('company_address',
		    isset($data['company_address'])?$data['company_address']:''
		    ,array(
		      'placeholder'=>t("Company address"),           
		    ))?>
		  </div>
		</div> <!--row-->    
		
		
		 <div class="row top20">
		  <div class="col-md-6 ">
		    <?php echo CHtml::passwordField('password','',array(
		      'placeholder'=>t("Password"),                    
		    ))?>
		  </div>
		  <div class="col-md-6 ">
		    <?php echo CHtml::passwordField('cpassword','',array(
		      'placeholder'=>t("Confirm password"),           
		    ))?>
		  </div>
		</div> <!--row-->    
		
		 <div class="row top20">
          <div class="col-md-5">
          <button type="submit" class="orange-button medium rounded"><?php echo t("Updare Profile")?></button>    
         </div>  
         </div>  
    
		</form>
    
	  </div> <!--inner-->
	  </li>
	  
	  <li class="<?php echo $tabs==2?"active":''?>">
	    <div class="inner">
	    
	    <h3><?php echo t("Your current Plan")?></h3>
	    
	    <?php if(is_array($plans) && count($plans)>=1):?>
	    
	    <div class="row top20">
	       <div class="col-md-2"><b><?php echo t("Membership status")?></b></div>
	       <div class="col-md-9">: <span class="tag rounded"><?php echo t(Driver::getUserStatus())?></span></div>
	    </div>
	    
	    <div class="row top20">
	       <div class="col-md-2"><b><?php echo t("Plan name")?></b></div>
	       <div class="col-md-9">: <?php echo $plans['plan_name']?></div>
	    </div>
	    <div class="row top10">
	       <div class="col-md-2"><b><?php echo t("Description")?></b></div>
	       <div class="col-md-9">: <?php echo $plans['plan_name_description']?></div>
	    </div>
	    
	    <div class="row top10">
	       <div class="col-md-2"><b><?php echo t("Expiration")?></b></div>
	       <div class="col-md-9">: <?php echo prettyDate($data['plan_expiration'])?></div>
	    </div>
	    
	    <div style="padding-top:20px;padding-bottom:20px;">
	    <p>- <?php echo t("Allowed")." ".$plans['allowed_driver']." ".t("driver")?></p>
	    <p>- <?php echo t("Allowed")." ".$plans['allowed_task']." ".t("Task")?></p>
	    <?php if ( $plans['with_sms']==1):?>
	    <p>- <?php echo t("With SMS Features")?></p>
	    <?php else :?>
	    <p>- <?php echo t("NO SMS Features")?></p>
	    <?php endif;?>
	    </div>
	    
	    <?php else :?>
	    <p class="text-danger"><?php echo t("Plans information not available")?></p>
	    <?php endif;?>
	    
	    <p>
	    <?php echo t("Upgrade plans")?>? <a target="_blank" href="<?php echo Yii::app()->createUrl("/front/pricing",array(
	      'hash'=>Driver::getUserToken()
	    ))?>"><?php echo t("Click here")?></a>
	    </p>
	    
	    </div> <!--inner-->
	  </li>
	  
	  <li>
	  <div class="inner">
	  <?php if(is_array($history) && count($history)>=1):?>
	  <table class="table table-striped">
	    <thead>
	     <tr>
	       <th><?php echo t("Date")?></th>
	       <th><?php echo t("Transaction Type")?></th>
	       <th><?php echo t("Payment Gateway")?></th>
	       <th><?php echo t("Memo")?></th>
	       <th><?php echo t("Amount")?></th>
	       <th><?php echo t("Transaction Ref")?></th>	       
	     </tr>
	    </thead>
	    <tbody>
	    <?php foreach ($history as $val):?>	    
	    <tr>
	      <td><?php echo prettyDate($val['date_created'],true)?></td>
	      <td><?php echo $val['transaction_type']?></td>
	      <td><?php echo AdminFunctions::prettyGateway($val['payment_provider'])?></td>
	      <td><?php echo $val['memo']?></td>
	      <td><?php echo prettyPrice($val['total_paid'])?></td>
	      <td><?php echo $val['transaction_ref']?></td>	      
	    </tr>
	    <?php endforeach;?>
	    </tbody>
	  </table>
	  <?php else :?>
	  <p><?php echo t("No payment history")?></p>
	  <?php endif;?>
	  </div>
	  </li>
	  
	</ul>
         
 </div> <!--content_2-->

</div> <!--parent-wrapper-->