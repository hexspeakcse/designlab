<?php 
$team_list=Driver::teamList( Driver::getUserId());
if($team_list){
	 $team_list=Driver::toList($team_list,'team_id','team_name',
	   Driver::t("All Team")
	 );
}
?>
<div class="container-fluid border">
<div class="row top">
  <div class="col-md-5 border">
  
    <div class="search-team-wrap">
    <?php echo CHtml::dropDownList('team','',(array)$team_list,array(
     'class'=>"lightblue-fields rounded3"
    ))?>
    <?php echo CHtml::textField('search_map','',array(
      'placeholder'=>Driver::t("Search map"),
      'class'=>"blue-fields rounded3"
    ))?>
    </div> <!--search-team-wrap-->
    
    <div class="back-dashboard">
     <a href="<?php echo Yii::app()->createUrl('/app/index')?>">
     <i class="ion-ios-arrow-thin-left"></i> <?php echo Driver::t("Back To Dashboard")?>
     </a>
    </div>
   
  </div> <!--row-->
  
  <div class="col-md-2 border">    
    <a href="<?php echo Yii::app()->createUrl('/app/index')?>" class="logo">
     <img src="<?php echo FrontFunctions::getLogoURL() ?>">
    </a>
  </div>
  
   <div class="col-md-5 border text-right">
      
    <a href="javascript:;" class="green-button left rounded add-new-task">
    <?php echo t("Add New Task")?>
    </a>
    
    <!--<a href="<?php echo Yii::app()->createUrl('/app/agentnew')?>" class="black-button left rounded">
    <?php echo t("Add New Agent")?>
    </a>-->
    
    <div class="left">
    <ul class="menu">
      <li>
        <a href="javascript:;" class="menu-pop"><i class="ion-grid"></i></a>
        
        <div class="popup_menu nav">
          <div class="row">
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/index')?>">
                  <i class="ion-grid"></i>
                  <p><?php echo Driver::t("Dashboard")?></p>
                </a>
            </div> <!--col-->
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/tasks')?>">
                  <i class="ion-ios-checkmark"></i>
                  <p><?php echo Driver::t("Tasks")?></p>
                </a>
            </div> <!--col-->            
            
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/agents')?>">
                  <i class="ion-android-contacts"></i>
                  <p><?php echo Driver::t("Drivers")?></p>
                </a>
            </div> <!--col-->            
            
            
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/settings')?>">
                  <i class="ion-gear-b"></i>
                  <p><?php echo Driver::t("Settings")?></p>
                </a>
            </div> <!--col-->            
            
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/assignment')?>">
                  <i class="ion-android-car"></i>
                  <p><?php echo Driver::t("Assignment")?></p>
                </a>
            </div> <!--col-->                
            
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/reports')?>">
                  <i class="ion-ios-paper"></i>
                  <p><?php echo Driver::t("Reports")?></p>
                </a>
            </div> <!--col-->    
            
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/pushlogs')?>">
                  <i class="ion-chatbox-working"></i>
                  <p><?php echo Driver::t("Push")?></p>
                </a>
            </div> <!--col-->    
            
            <div class="col-md-6 ">
                <a href="<?php echo Yii::app()->createUrl('/app/notifications')?>">
                  <i class="ion-ios-bell"></i>
                  <p><?php echo Driver::t("Notifications")?></p>
                </a>
            </div> <!--col-->    
            
<?php 
$language_list=getOptionA('language_list');
if(!empty($language_list)){
   $language_list=json_decode($language_list,true);	
}   
$action_name=Yii::app()->controller->action->id;
?>

<?php if(is_array($language_list) && count($language_list)>=1):?> 
            <div class="col-md-6 relative ">
                <a href="javascript:;" class="show-lang-list">
                  <i class="ion-ios-flag"></i>
                  <p><?php echo strtoupper(Yii::app()->language)?></p>
                </a>
                <div class="lang-wrapper rounded2">
                 
                <?php if(is_array($language_list) && count($language_list)>=1):?>
                
                <ul id="lang-list">
                 <?php foreach ($language_list as $val_lang) :?>
	             <li>
	               <a href="<?php echo Yii::app()->getBaseUrl(true)."/app/setlang/?lang=$val_lang&action=$action_name"?>">
	                 <?php echo $val_lang?>
	               </a>
	              </li>	           
	             <?php endforeach;?>
	           </ul>
	           
                <?php endif;?>
                 
                </div>
            </div> <!--col-->   
<?php endif;?>
            
            
          </div><!-- row-->
        </div> <!--popup_menu-->
        
      </li>
      <li>
        <a href="javascript:;" class="menu-sound"><i class="ion-volume-high"></i></a>
      </li>
      <li>
         <a href="javascript:;" class="menu-notification"><i class="ion-ios-bell-outline"></i></a>
         
         <div class="popup_menu notification">
           <ul id="notification_list">
           </ul>
         </div>
         
      </li>     
      
      <li>
        <a href="<?php echo Yii::app()->createUrl('app/profile')?>" 
        title="<?php echo Driver::t("Profile")?>" ><i class="ion-android-contact"></i></a>
      </li>
      
      <li>
        <a href="<?php echo Yii::app()->createUrl('app/logout')?>" 
        title="<?php echo Driver::t("logout")?>" ><i class="ion-log-out"></i></a>
      </li>
       
    </ul>
    </div>
   
  </div> <!--row-->
</div> <!--row-->
</div>