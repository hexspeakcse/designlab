
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
	      </div>
	    </div>
	  </nav>        
	  
	  <div class="card-content">
	  
	  <div class="row">
	    <div class="col m6">	    
	       <div class="input-field">
		      <?php echo CHtml::textField('company_name',
		      ''
		      ,
		       array(
		        'class'=>"validate",
		        'data-validation'=>"required"
		      ))?>
		      <label>Website Name</label>
		   </div>      	    
	    </div> <!--col-->	    
	  </div> <!--row-->
	  
	 
	  <div class="row">
	    <div class="col m6">	    
	       <div class="input-field">
		      <?php echo CHtml::textField('username',
		      ''
		      ,
		       array(
		        'class'=>"validate",
		        'data-validation'=>"required"
		      ))?>
		      <label>Username</label>
		   </div>      	    
	    </div> <!--col-->	    
	    <div class="col m6">	    
	       <div class="input-field">
		      <?php echo CHtml::passwordField('password',
		      ''
		      ,
		       array(
		        'class'=>"validate",
		        'data-validation'=>"required"
		      ))?>
		      <label>Password</label>
		   </div>      	    
	    </div> <!--col-->	 
	  </div> <!--row-->  
	  
	  </div> <!--card-content-->
	  	    
		 <div class="card-action" style="margin-top:20px;">
		 <button class="btn waves-effect waves-light" type="submit" name="action">
		   Next
		 </button>
		 </div>	 
	  
	</div> <!--card--> 
 
   </div> <!--top30-->	 
</div> <!--container-->
</form>

<script type="text/javascript">
function checkform()
{
	var err='';
	
	if ( $("#company_name").val()=="" ){
		err="Company name is required \n";
	}
	if ( $("#username").val()=="" ){
		err+="Username name is required \n";
	}
	if ( $("#password").val()=="" ){
		err+="Password name is required \n";
	}
	
	if(err==""){
	   return true;
	}
	alert(err);
	return false;
}
</script>