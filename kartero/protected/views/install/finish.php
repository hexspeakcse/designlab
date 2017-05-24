<nav class="teal lighten-1">
<div class="nav-wrapper">
  <a href="<?php echo Yii::app()->createUrl('/install/index')?>">
     <img style="margin-top: 5px;margin-left: 20px;" class="logo" src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/logo.png"?>">     
  </a>       
</div>
</nav>
  
<form method="POST" onsubmit="return checkform();" action="<?php echo Yii::app()->createUrl('install/finish')?>">
<div class="container">
   <div style="margin-top:30px;">

    <div class="card">
      
	  <nav class="teal lighten-1">
	    <div class="nav-wrapper">
	      <div class="col s12" style="padding-left:20px;">
	        <a href="<?php echo Yii::app()->createUrl('install')?>" class="breadcrumb">Checking requirements</a>
	        <a href="#!" class="breadcrumb">Database Tables</a>
	        <a href="#!" class="breadcrumb">Website Information</a>
	        <a href="#!" class="breadcrumb">Finish</a>
	      </div>
	    </div>
	  </nav>        
	  
	  <div class="card-content">
	  
	   <?php if($code==1):?>
	   <h4>Installation done...  C|O|D|E|L|I|S|T|.|C|C </h5>
	   
	   <?php $base=Yii::app()->getBaseUrl(true);?>
	   <p>Admin link</p>
	   <a href="<?php echo $base?>/admin" target="_blank"><?php echo $base?>/admin</a>
	   
	   <p style="margin-top:10px">Front link</p>
	   <a href="<?php echo $base?>/" target="_blank"><?php echo $base?>/</a>
	   
	   <p style="margin-top:10px">Customer app link</p>
	   <a href="<?php echo $base?>/app" target="_blank"><?php echo $base?>/app</a>
	   
	   <p style="color:red;margin-top:50px;">
	   Important : For security purposes you can delete or rename the file controllers/InstallController.php
	   </p>
	   
	   <?php else :?>
	   <h4>Installation has failed.</h5>
	   <p style="color:red;"><?php echo $msg;?></p>
	   <p style="margin-top:50px;"><a href="<?php echo Yii::app()->createUrl('install/')?>">CLick here</a> to install again</p>
	   <?php endif;?>
	  
	  </div> <!--card-content-->
	  	    
		
	  
	</div> <!--card--> 
 
   </div> <!--top30-->	 
</div> <!--container-->
</form>