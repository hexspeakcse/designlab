


<div class="card">
 <div class="card-content">
   
   <form id="frm" method="POST" onsubmit="return false;">
   <?php echo CHtml::hiddenField('action','saveMobileSettings')?>
   
   <h5><?php echo t("Mobile API URL")?></h5>  
   <p class="rounded" style="background:#009688;color:#fff;display:table;padding:3px 5px;"><?php echo Yii::app()->getBaseUrl(true)."/api"?></p>   
   
   <h5 class="top30"><?php echo t("Mobile API Key")?></h5>  
   
    <div class="row">
     <div class="col s6">
          <div class="input-field">	    
	       <?php echo CHtml::textField('mobile_api_key',
	       getOptionA('mobile_api_key')
	       ,array(
	         'class'=>"validate",	        
	         ))?>
	       <label><?php echo t("Your Mobile API key")?></label>
	      </div>      
	      <p class="grey lighten-5"><?php echo t("This fields is optional if you want to secure api fill this fields")?></p>
     </div>
   </div>
   
   
   <h5><?php echo t("Android Settings")?></h5>  
   
   <div class="row">
     <div class="col s6">
          <div class="input-field">	    
	       <?php echo CHtml::textField('push_api_key',
	       getOptionA('push_api_key')
	       ,array(
	         'class'=>"validate",	        
	         ))?>
	       <label><?php echo t("Push API key")?></label>
	      </div>      
     </div>
   </div>
   
   <h5><?php echo t("iOS Settings")?></h5> 
   <div class="row">
      <div class="col s6">
          <div class="input-field">
          <?php 
          echo CHtml::dropDownList('ios_mode',
          getOptionA('ios_mode')
          ,array(
            'development'=>t("development"),
            'production'=>t("production"),
          ),array(
            'placeholder'=>t("IOS Push Mode")
          ))
          ?>
          <label><?php echo t("IOS Push Mode")?></label>
          </div>
      </div>
      <div class="col s6">
          <div class="input-field">	    
	       <?php echo CHtml::textField('ios_password',
	       getOptionA('ios_password')
	       ,array(
	         'class'=>"validate",	        
	         ))?>
	       <label><?php echo t("IOS Push Certificate PassPhrase")?></label>
	      </div>      
      </div>
   </div>
   
   <p class="bottom20"><?php echo t("Upload Certificate")?></p>
   <br/>
   
   <div class="row">
     <div class="col s3">
         <a id="upload-ios-dev" class="waves-effect blue lighten-1 btn"><?php echo t("development")?></a>
         
         <div id="progressBar"></div>
         <div id="progressOuter"></div>
         <div id="msgBox"></div>         
                  
     </div>
     <div class="col s6">     
          <div class="input-field">	    
	       <?php echo CHtml::textField('ios_dev_certificate',
	       getOptionA('ios_dev_certificate')
	       ,array(
	         'class'=>"validate",	
	         'disabled' =>true
	         ))?>
	       <label><?php echo t("development")?></label>
	      </div>      
     </div>
   </div>
   
    <div class="row">
     <div class="col s3">
         <a id="upload-ios-prod" class="waves-effect blue lighten-1 btn"><?php echo t("production")?></a>
         
         <div id="progressBar1"></div>
         <div id="progressOuter1"></div>
         <div id="msgBox1"></div>         
                  
     </div>
     <div class="col s6">     
          <div class="input-field">	    
	       <?php echo CHtml::textField('ios_prod_certificate',
	       getOptionA('ios_prod_certificate')
	       ,array(
	         'class'=>"validate",	
	         'disabled' =>true
	         ))?>
	       <label><?php echo t("production")?></label>
	      </div>      
     </div>
   </div>
   
   <br/>
   
    <div class="card-action" style="margin-top:20px;">
     <button class="btn waves-effect waves-light" type="submit" name="action">
       <?php echo t("Save settings")?>
     </button>
    </div>
   
   </form> 
    
 </div>  <!--card content-->
</div> <!--card-->