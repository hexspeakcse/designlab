
<div class="page-content sections">
<div class="container"> 

   <h2 class="text-center"><?php echo t("Signup")?></h2>
   
   
   <form id="frm" method="POST" onsubmit="return false;">
   <?php echo CHtml::hiddenField('action','signup')?>
   <?php echo CHtml::hiddenField('plan_id',$plan_id)?>
   
   <div class="row">
   
	   <div class="col m6">
		    <div class="input-field">
			      <?php echo CHtml::textField('first_name',
			      ''
			      ,array('class'=>"validate",
			      'data-validation'=>"required"
			      ))?>
			      <label for="first_name"><?php echo t("First name")?></label>
		   </div>
	   </div> <!--col-->
	   
	   <div class="col m6">
		    <div class="input-field">
			      <?php echo CHtml::textField('last_name',
			      ''
			      ,array('class'=>"validate",
			      'data-validation'=>"required"
			      ))?>
			      <label for="last_name"><?php echo t("Last name")?></label>
		   </div>
	   </div> <!--col-->
   
   </div> <!--row-->
   
   <div class="row">
    <div class="col m6">   
	   <div class="input-field">
	      <?php echo CHtml::textField('mobile_number',
	      ''
	      ,
	       array(
	        'class'=>"validate mobile_inputs",
	        'data-validation'=>"required",
	        'Placeholder'=>t("Mobile number")
	      ))?>
	      <!--<label for="mobile_number"><?php echo t("Mobile number")?></label>-->
	   </div>   
    </div>
   
    <div class="col m6">
	   <div class="input-field">
	      <?php echo CHtml::textField('email_address',
	      $email_address
	      ,
	       array(
	        'class'=>"validate",	       
	        'data-validation'=>"required"
	      ))?>
	      <label><?php echo t("Email address")?></label>
	   </div>
    </div>
   </div><!-- row-->   
          
   
   <div class="row">
    <div class="col m6">   
     <div class="input-field">
	      <?php echo CHtml::passwordField('password',
	      ''
	      ,
	       array(
	        'class'=>"validate",	       
	        'data-validation'=>"required"
	      ))?>
	      <label><?php echo t("Password")?></label>
	   </div>
	</div>   
	<div class="col m6">   
     <div class="input-field">
	      <?php echo CHtml::passwordField('cpassword',
	      ''
	      ,
	       array(
	        'class'=>"validate",	       
	        'data-validation'=>"required"
	      ))?>
	      <label><?php echo t("Confirm Password")?></label>
	   </div>
	</div>   
   </div>  <!--row-->
   
   <div class="row">
    <div class="col m6">   
	   <div class="input-field">
	      <?php echo CHtml::textField('company_name',
	      ''
	      ,
	       array(
	        'class'=>"validate",
	        //'data-validation'=>"required"
	      ))?>
	      <label><?php echo t("Company name")?></label>
	   </div>   
    </div>
   
    <div class="col m6">
	   <div class="input-field">
	      <?php echo CHtml::textField('company_address',
	      ''
	      ,
	       array(
	        'class'=>"validate",	       
	        //'data-validation'=>"required"
	      ))?>
	      <label><?php echo t("Company address")?></label>
	   </div>
    </div>
   </div><!-- row-->      
   
  <div class="row">
    <div class="col m6"> 
       <div class="input-field">
	       <?php echo CHtml::dropDownList('country_code',
	        getOptionA('website_default_country'),
	        AdminFunctions::getCountryList(),array(
	          'class'=>"select-material"
	        ))?>
		    <label><?php echo t("Country")?></label>
	   </div>
    </div> <!--col-->
  </div> <!--row-->
	
  
  
     <div class="card-action" style="margin-top:20px;">
     <button class="btn waves-effect waves-light" type="submit" name="action">
       <?php echo t("Sign up")?>
     </button>
     </div>
     
     
  
   </form>
   
   
   
   <form id="frm-existing" method="POST" onsubmit="return false;">
   <?php echo CHtml::hiddenField('action','getSignup')?>   
   <p class="top40">
   <?php echo t("have existing application")?>?
   <a href="javascript:;" class="existing-click"><?php echo t("Click here")?></a>
   </p>
   
   <div class="existing-application-wrap">
   <div class="row">
   <div class="col s6">
         <div class="input-field">
	      <?php echo CHtml::textField('email_address',
	      $email_address
	      ,
	       array(
	        'class'=>"validate",	       
	        'data-validation'=>"required"
	      ))?>
	      <label><?php echo t("Email address")?></label>
	   </div>
	   
	     <div class="card-action" style="margin-top:20px;">
          <button class="btn waves-effect waves-light" type="submit" name="action">
           <?php echo t("Submit")?>
          </button>
        </div>
	   
   </div>     
   </div>     
   </div>
   </form>

</div> <!--container-->
</div> <!--sections-->