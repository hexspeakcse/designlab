
<?php
$visible=true;

$menu =  array(  		    		    
    'activeCssClass'=>'active', 
    'encodeLabel'=>false,
    'items'=>array(
    
        array('visible'=>true,'label'=>'<i class="ion-grid"></i>&nbsp; '.t('Dashboard'),
        'url'=>array('/app/index'),'linkOptions'=>array()),               
        
        array('visible'=>true,'label'=>'<i class="ion-android-contacts"></i>&nbsp; '.t("Teams"),
        'url'=>array('/app/teams'),'linkOptions'=>array()),       
        
        array('visible'=>true,'label'=>'<i class="ion-android-contact"></i>&nbsp; '.t("Driver"),
        'url'=>array('/app/agents'),'linkOptions'=>array()),       
        
        array('visible'=>true,'label'=>'<i class="ion-ios-checkmark"></i>&nbsp; '.t("Tasks"),
        'url'=>array('/app/tasks'),'linkOptions'=>array()),       
                        
        /*array('visible'=>$visible,'label'=>'<i class="ion-flag"></i>&nbsp; '.t("Language"),
        'url'=>array('/app/language'),'linkOptions'=>array()),        */
        
        array('visible'=>$visible,'label'=>'<i class="ion-gear-b"></i>&nbsp; '.t("Settings"),
        'url'=>array('/app/settings'),'linkOptions'=>array()),               
        
        array('visible'=>$visible,'label'=>'<i class="ion-ios-bell"></i>&nbsp; '.t("Notifications"),
        'url'=>array('/app/notifications'),'linkOptions'=>array()),        
        
        array('visible'=>$visible,'label'=>'<i class="ion-android-list"></i>&nbsp; '.t("Push Logs"),
        'url'=>array('/app/pushlogs'),'linkOptions'=>array()),        
                
        array('visible'=>true,'label'=>'<i class="ion-android-car"></i>&nbsp; '.t("Assignment"),
        'url'=>array('/app/assignment'),'linkOptions'=>array()),                
        
        array('visible'=>true,'label'=>'<i class="ion-ios-list"></i>&nbsp; '.t("Reports"),
        'url'=>array('/app/reports'),'linkOptions'=>array()),       
        
     )   
);       
?>

<div class="left-menu">
  <?php $this->widget('zii.widgets.CMenu', $menu);?>
</div> <!--left-menu-->
