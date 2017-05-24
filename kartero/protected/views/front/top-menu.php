
<div class="top-menu">
 <div class="row">
   <div class="col-xs-6 border">
     <a href="<?php echo Yii::app()->createUrl('/front/index')?>">
     <img class="logo" src="<?php echo FrontFunctions::getLogoURL();?>">     
     </a>
   </div>
   <div class="col-xs-6 border text-right">
      <?php $this->widget('zii.widgets.CMenu', FrontFunctions::getMenu('top-nav'));?>
      
      <a class="mobile-toggle" href="javascript:;"><i class="ion-android-menu"></i></a>
      
   </div>
 </div> <!--row-->
</div> <!--top menu-->

<div class="mobile-menu-wrap">
<?php $this->widget('zii.widgets.CMenu', FrontFunctions::getMenu('mobile-nav'));?>
</div><!-- mobile-menu-wrap-->