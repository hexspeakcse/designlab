
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
         <b><?php echo t("Settings")?></b>
        </div> <!--col-->
        <div class="col-md-6  text-right">
                     
        </div> <!--col-->
      </div> <!--row-->
   </div> <!--nav_option-->
  
   <div class="inner">
   
   <ul id="tabsx">
	 <!--<li class="active"><?php echo t("General Settings")?></li>-->
	 <!--<li><?php echo t("Cron Jobs")?></li>	 -->
	 <!--<li><?php echo t("Update Database")?></li>	 -->
	</ul>
	
   <ul id="tab">  	
	
   <li class="active top30">
   
    <form id="frm" class="frm form-horizontal">
	 <?php echo CHtml::hiddenField('action','generalSettings')?>
	  
     <div class="form-group">
	    <label class="col-sm-2 control-label"><?php echo Driver::t("Send Push only to online driver")?></label>
	    <div class="col-sm-6">
	      <?php
	      echo CHtml::checkBox('driver_send_push_to_online',
	      getOption( Driver::getUserId(), 'driver_send_push_to_online')==1?true:false,array(
	        'class'=>"switch-boostrap"
	      ))
	      ?>	
	      <p class="text-muted top5">
	      <?php echo Driver::t("Send push notification only to online drivers when assigning task")?>.
	      </p>      
	    </div>
	  </div>	  	 
	   
	   <div class="form-group">
	    <label class="col-sm-2 control-label"><?php echo Driver::t("Include offline driver on map")?></label>
	    <div class="col-sm-6">
	      <?php
	      echo CHtml::checkBox('driver_include_offline_driver_map',
	      getOption( Driver::getUserId(), 'driver_include_offline_driver_map')==1?true:false,array(
	        'class'=>"switch-boostrap"
	      ))
	      ?>	      
	    </div>
	  </div>	  
	  
	  <div class="form-group">
	    <label class="col-sm-2 control-label"><?php echo Driver::t("Disabled Map Auto Refresh")?></label>
	    <div class="col-sm-6">
	      <?php
	      echo CHtml::checkBox('driver_disabled_auto_refresh',
	      getOption( Driver::getUserId(), 'driver_disabled_auto_refresh')==1?true:false,array(
	        'class'=>"switch-boostrap"
	      ))
	      ?>	      
	    </div>
	  </div>	  
	  
	  <hr/>  	  
	  
	   <div class="form-group">
	    <label class="col-sm-2 control-label"><?php echo Driver::t("Default Map Country")?></label>
	    <div class="col-sm-6">	      
	      <?php
	      $drv_default_location=getOption( Driver::getUserId() , 'drv_default_location');
	      echo CHtml::dropDownList('drv_default_location',
	      !empty($drv_default_location)?$drv_default_location:"US",
	      (array)$country_list,array(
	        'class'=>"form-control"
	      ))
	      ?>
	      <p class="text-muted top5">
	      <?php echo Driver::t("Set the default country to your map")?>
	      </p>
	    </div>
	  </div>	  
	  
	  
	  <!-- <div class="form-group">
	    <label class="col-sm-2 control-label"><?php echo Driver::t("Delivery Time")?></label>
	    <div class="col-sm-6">
	      <?php
	      echo CHtml::dropDownList('drv_delivery_time',
	      getOption( Driver::getUserId(),  'drv_delivery_time'),	      
	      Driver::deliveryTimeOption()
	      ,array(
	        'class'=>"form-control"
	      ))
	      ?>
	      <p class="text-muted top5">	      
	      </p>
	    </div>
	  </div>-->	  
	  
	    <div class="form-group">
	    <label class="col-sm-2 control-label"><?php echo Driver::t("Map Style")?></label>
	    <div class="col-sm-6">
	      <?php
	      echo CHtml::textArea('drv_map_style',getOption( Driver::getUserId(),'drv_map_style'),array(
	         'class'=>"form-control",
	         'style'=>"height:250px;"
	      ))
	      ?>
	      <p class="text-muted top5">
	      <?php echo Driver::t("Set the style of your map")?>.
	      <?php echo Driver::t("get it on")?> <a target="_blank" href="https://snazzymaps.com">https://snazzymaps.com</a>
	      <br/>
	      <?php echo Driver::t("leave it empty if if you are unsure")?>.
	      </p>
	    </div>
	  </div>	  
	  	
	  
	  <div class="form-group">
	    <label class="col-sm-2 control-label"></label>
	    <div class="col-sm-6">
		  <button type="submit" class="orange-button medium rounded">
		  <?php echo Driver::t("Save")?>
		  </button>
	    </div>	 
	  </div>
	  
     </form>		 
    </li> 
    
    <li>
     <div class="inner">
     <h4><?php echo t("Run the following cron jobs link in your cpanel")?></h4>     
     <p>
     <a href="<?php echo Yii::app()->getBaseUrl(true)."/app/cron/processpush"?>" target="_blank">
     <?php echo Yii::app()->getBaseUrl(true)."/app/cron/processpush"?>
     </a>
     </p>
     <p>
      <b><?php echo t("example")?>: curl <?php echo Yii::app()->getBaseUrl(true)."/app/cron/processpush"?></b>
     </p>
     </div>
    </li>
    
    <li>
    <div class="inner">
    <h4><?php echo t("Click below to update your database")?></h4>     
    
    <a href="<?php echo Yii::app()->getBaseUrl(true)."/app/update"?>" target="_blank">
    <?php echo Yii::app()->getBaseUrl(true)."/app/update"?>
    </a>
    
    </div>
    </li>
   
   </div> <!--inner-->
 
 </div> <!--content_2-->

</div> <!--parent-wrapper-->