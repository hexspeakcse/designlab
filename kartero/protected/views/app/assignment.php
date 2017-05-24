
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

   <div class="nav_option">
      <div class="row">
        <div class="col-md-6 ">
         <b><?php echo t("Assignment")?></b>
        </div> <!--col-->
        <div class="col-md-6  text-right">
                     
        </div> <!--col-->
      </div> <!--row-->
   </div> <!--nav_option-->
  
   <div class="inner">
      
   <form id="frm" class="frm">
   <?php echo CHtml::hiddenField('action',"saveAssigmentSettings")?>
      
   <p class="text-muted"><?php echo Driver::t("Automatically assign the tasks to your driver and choose from various auto assignment options like one by one and send to all")?>.</p>
   
   <div class="panel panel-default text-muted top20" style="width:50%;">    
     <div class="panel-heading">
      <?php echo CHtml::checkBox('driver_enabled_auto_assign',
      getOption( Driver::getUserId(), 'driver_enabled_auto_assign')==1?true:false
      ,array(
        'value'=>1,
        'class'=>"switch-boostrap"
      ))?>
      <?php echo Driver::t("Enable Auto Assignment")?>
     </div>
     <div class="panel-body">
    
        <div class="row">
         <div class="col-md-2"><?php echo CHtml::checkBox('driver_include_offline_driver',
         getOption( Driver::getUserId(), 'driver_include_offline_driver')==1?true:false
         ,array(
           'value'=>1,
           'class'=>"switch-boostrap"
         ))?></div>
         <div class="col-md-4"><?php echo Driver::t("Include Offline Driver")?></div>
       </div>
       
       <div class="row top10">
         <div class="col-md-3"><?php echo Driver::t("Notify Email")?></div>
         <div class="col-md-6">
           <?php echo CHtml::textField('driver_autoassign_notify_email',
           getOption( Driver::getUserId(), 'driver_autoassign_notify_email'),array(
             'class'=>"form-control"
           ))?>
           <p class="text-muted small-font"><?php echo Driver::t("Email address that will receive email if unable to auto assign")?>.</p>
         </div>
       </div>
       
       <div class="row top10">
         <div class="col-md-3"><?php echo Driver::t("Request expire")?></div>
         <div class="col-md-4">
           <?php echo CHtml::textField('driver_request_expire',
           getOption( Driver::getUserId(), 'driver_request_expire'),array(
             'class'=>"form-control",
             'placeholder'=>Driver::t("Default is 1 min")
           ))?>
           <p class="text-muted small-font">
           </p>
         </div>
         <div class="col-md-1" style="padding-top: 5px;"><?php echo Driver::t("min")?></div>
       </div>
       
     
       <hr/>
       
       <div class="row top20">
         <div class="col-md-4">
         <?php echo CHtml::radioButton('driver_auto_assign_type',
         getOption( Driver::getUserId(), 'driver_auto_assign_type')=="one_by_one"?true:false
         ,array('class'=>"choosen",'value'=>"one_by_one"))?>
         <?php echo Driver::t("One By One")?>
         </div>
         <div class="col-md-4">
         <?php echo CHtml::radioButton('driver_auto_assign_type',
         getOption( Driver::getUserId(), 'driver_auto_assign_type')=="send_to_all"?true:false
         ,array('class'=>"choosen",'value'=>"send_to_all"))?>
         <?php echo Driver::t("Send To All")?>
         </div>
       </div>  
       
       <div class="top20"></div>
       
       <div class="section_one_by_one">
       <p class="text-muted"><?php echo Driver::t("This will send the task notification to the Agent one by one and first to who is currently at the shortest distance from the new task's destination")?>.</p>
       
       <p><?php echo Driver::t("Request Interval in")?>:</p>
       <div class="row">
         <div class="col-md-4">
         <?php echo CHtml::textField('driver_assign_request_expire',
         getOption( Driver::getUserId(), 'driver_assign_request_expire')
         ,array(
        'class'=>"form-control numeric_only",
        'placeholder'=>Driver::t("Default is 1 min")
       ))?>
         </div>
         <div class="col-md-1" style="padding-top: 5px;"><?php echo Driver::t("min")?></div>
       </div>
       
       <div class="row top10"> 
         <div class="col-md-4">
         <?php echo CHtml::textField('driver_assign_radius',
         getOption( Driver::getUserId(), 'driver_assign_radius')
         ,array(
        'class'=>"form-control numeric_only",
        'placeholder'=>Driver::t("Radius")
       ))?>
         </div>
         <div class="col-md-1" style="padding-top: 5px;"><?php echo Driver::t("Miles")?></div>
       </div>
       
       </div> <!--section_one_by_One-->
       
       
       <div class="section_send_to_all">
         <p><?php echo Driver::t("This will send the task notification to all the Agent at once and will be assigned to Agent who accepts it first")?>.</p>
       </div>  <!--section_send_to_all-->
     
     </div> <!--body-->
   </div> <!--panel-->
    
   
     <div class="form-group">
	    <button type="submit" class="orange-button medium rounded">
		  <?php echo Driver::t("Save")?>
		  </button>
	  </div>
   
   </form> 
   
   </div> <!--inner-->
 
 </div> <!--content_2-->

</div> <!--parent-wrapper-->