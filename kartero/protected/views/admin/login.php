
<div class="container">
   <div class="login-wrapper">
    
   <img src="<?php echo Yii::app()->baseUrl.'/assets/images/logo@2x.png'; ?>">
   
     <div class="grey-box rounded3">
         <div class="inner">

         <h5 class="center-align">Kartero Back Office</h5>        
         
         <form id="frm" method="POST" onsubmit="return false;">
         <?php echo CHtml::hiddenField('action','login')?>
          <div class="input-field">
            <i class="material-icons prefix">account_circle</i>
            <?php echo CHtml::textField('username','',array('class'=>"validate",'data-validation'=>"required"))?>
            <label for="icon_prefix"><?php echo t("Username")?></label>
          </div>  
          
          <div class="input-field">
            <i class="material-icons prefix">lock</i>
            <?php echo CHtml::passwordField('password','',array('class'=>"validate",'data-validation'=>"required"))?>
            <label for="icon_prefix"><?php echo t("Password")?></label>
          </div>  
          
          
          <div class="center-align">
          <button class="btn waves-effect waves-light" type="submit" name="action">
          <?php echo t("LOG IN")?>    
          </button>
          </div>
          
          </form>
        
         
         </div> <!--inner-->
     </div> <!--rounded-box-->
    
   </div> <!--login-wrapper-->
</div> <!--container-->