

<div class="container">
  <div class="login-wrap rounded">
  <img src="<?php echo Yii::app()->baseUrl.'/assets/images/logo@2x.png'; ?>">
     
    <form id="frm" class="frm rounded3" method="POST" onsubmit="return false;">
    <div class="inner">
    <?php echo CHtml::hiddenField('action','login')?>
    <div>
    <?php 
    echo CHtml::textField('email_address',
    $email_address
    ,array(
      'placeholder'=>Driver::t("Enter email"),
      'class'=>"lightblue-fields rounded",
      'required'=>true
    ));
    ?>
    </div>
    
    <div class="top20">
    <?php 
    echo CHtml::passwordField('password',
    $password
    ,array(
      'placeholder'=>Driver::t("Password"),
      'class'=>"lightblue-fields rounded",
      'required'=>true
    )); 
    ?>
    </div>
    
    <div class="top20">
    <button class="yellow-button large rounded3 relative">
       <?php echo Driver::t("LOG IN")?> <i class="ion-ios-arrow-thin-right"></i>
    </button>
    </div>
    
    </div> <!--inner-->
    
    <div class="sub-section">
       <div class="row">
         <div class="col-md-6">
            <?php echo CHtml::checkBox('remember',true,array('value'=>1))?>
            <?php echo t("Remember me")?>
         </div> <!--col-->
         <div class="col-md-6 text-right">
            <a href="javascript:;" class="show-forgot-pass"><?php echo t("Forgot password")?>?</a>
         </div> <!--col-->
       </div> <!--row-->
    </div> <!--sub-section-->
    
    </form> <!--login-->
    
    <form id="frm-forgotpass" class="frm rounded3" method="POST" onsubmit="return false;">
    <?php echo CHtml::hiddenField('action','forgotPassword')?>
    <div class="inner">
    
       <p class="center">
       <?php echo t("Enter your email address and we'll send you a link to reset your password")?>
       </p>
    
       <div class="top20">
	    <?php 
	    echo CHtml::textField('email_address','',array(
	      'placeholder'=>Driver::t("Enter email"),
	      'class'=>"lightblue-fields rounded",
	      'required'=>true
	    ));
	    ?>
	    </div>  
	    
	    <div class="top20">
	    <button class="yellow-button large rounded3 relative">
	       <?php echo Driver::t("SUBMIT")?> <i class="ion-ios-arrow-thin-right"></i>
	    </button>
	    </div>	    
    </div> <!--inner-->
    
        <div class="sub-section">
	      <a href="javascript:;" class="show-login"><?php echo t("Back")?></a>
	    </div>
	    
    </form> <!--forgot pass-->
    
    <hr/>
    <p class="center white"><?php echo t("You don't have account")?>?</p>
    
    <a href="<?php echo Yii::app()->createUrl('front/signup')?>" class="yellow-button large rounded3 relative">
       <?php echo Driver::t("SIGNUP FOR FREE")?> <i class="ion-ios-arrow-thin-right"></i>
    </a>
  
  </div> <!--login-wrap-->
</div> <!--container-->