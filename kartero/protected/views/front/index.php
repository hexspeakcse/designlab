
<div class="banner">  
	<div class="col-md-8 border col">
	   <div class="inner border">
	   
	    <h1><?php echo t("Simple, Powerful & Highly Flexible way to")?> <span><?php echo t("manage your company")?>.</span></h1>
	    
	    <p><?php echo t("Ditch the lengthy message threads, disruptive calls, out of place spreadsheets and clunky software to run your operations")?>. <?php echo FrontFunctions::getCompanyName()?> <?php echo t("lets you take back the control and allows you to focus on growing your business")?>.</p>
	   
	    <div class="line margin"></div>
	    
	    <h3><b><?php echo t("Only now")?>!</b> <?php echo t("Try")?> <?php echo FrontFunctions::getCompanyName()?> <?php echo t("free")?>:</h3>
	    
	    <form id="frm-trytrial" method="POST" onsubmit="return false;">
	    <div class="row">
	      <div class="col-md-5 border">
	          <?php echo CHtml::textField('email_address','',array(
	            'class'=>"rounded3",
	            'placeholder'=>t("Your email address"),
	            'required'=>true
	          ));?>
	      </div> <!--col-->
	      <div class="col-md-5 border">
	          <button type="submit" class="rounded relative yellow-button large">
	          <?php echo t("SIGN UP FOR FREE")?> 
	          <i class="ion-ios-arrow-thin-right"></i>
	          </button>
	      </div> <!--col-->
	    </div><!-- row-->
	    </form>
	    
	    <div class="line margin"></div>
	    
	    <h3><?php echo t("Available on")?>:</h3>
	    
	    <div class="available-wrap">
	     <!--<img src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/web-version.png";?>">
	     <img src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/phone-icon.png";?>">-->
	     <a href="#"><i class="ion-social-apple"></i></a>
	     <a href="#"><i class="ion-social-android"></i></a>
	    </div>
	    
	    
	   </div> <!--inner-->
	</div> <!--col-->
	<div class="col-md-4 border yellow-col col">
	   <img class="phone" src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/phone.png";?>">
	</div> <!--col-->
</div> <!--banner-->


<div class="sections section-1">

  <div class="container border">
      <div class="row">
         <div class="col-md-3 border">
            
            <div class="yellow-col relative">
            <h2><?php echo t("What")?> <?php echo FrontFunctions::getCompanyName()?> <?php echo t("help you do")?>?</h2>
            <div class="line margin dim"></div>
            </div>
           
         </div> <!--col-->
         <div class="col-md-9 border">
         
            <div class="row top150">
              <div class="col-sm-4 border">
                 <img src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/logistic.png";?>">
                 <h4><?php echo t("Streamline the logistics")?></h4>
                 <div class="line margin"></div>
                 <p><?php echo t("An interactive map based interface lets you streamline your entire process from allocation you dispatch and from scheduling to tracking a delivery. It enables you locate your workforce on the map in real time")?>.</p>
              </div> <!--col-->
              <div class="col-sm-4 border">
              
                 <img src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/communicate.png";?>">
                 <h4><?php echo t("Communicate seamlessly")?></h4>
                 <div class="line margin"></div>
                 <p><?php echo FrontFunctions::getCompanyName()?> <?php echo t("comes with an integrated 2-way notification which can be used to serve and update your customers about their delivery at regular intervals and manage your mobileworkforce efficiently with instant updates")?>.</p>
                
              </div> <!--col-->
              <div class="col-sm-4 border">
              
                 <img src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/driven-decision.png";?>">
                 <h4><?php echo t("Take data driven decisions")?></h4>
                 <div class="line margin"></div>
                 <p><?php echo t("Analytics and graphical report feature available within the dashboard helps you monitor performance of the workforce. Data can be used for decision making to increase customer satisfaction and loyalty")?></p>
              
              </div> <!--col-->
            </div>
         
         </div> <!--col-->
      </div> <!--row-->
  </div> <!--container-->
</div> <!-- section-1-->

<div class="sections section-2">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 border relative">
    
       <div class="inner border">
       <h2><?php echo t("Watch")?> <?php echo FrontFunctions::getCompanyName()?> <?php echo t("in Action")?>!</h2>
       <div class="line margin dim"></div>
       
       <h1><?php echo t("Discover what");?><br/>
		<?php echo FrontFunctions::getCompanyName()?> <?php echo t("can")?> <br/>
		<?php echo t("do for your")?> <br/>
		<?php echo t("business")?></h1>
		
		<a href="<?php echo Yii::app()->createUrl('front/pricing')?>" class="brown-button large relative top30 rounded">
		<?php echo t("SIGN UP FOR FREE")?>
		<i class="ion-ios-arrow-thin-right"></i>
		</a>
		
		<div class="video-wrapper">		
		<img src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/macbook.png";?>">
		</div> <!--video-wrapper-->
		
		
		</div> <!--inner-->
				
       
    </div> <!--col-->
    <div class="col-md-4 border brown-col relative">
       <img class="layer" src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/layer.png";?>">
    </div> <!--col-->
  </div> <!--row-->
</div>
</div> <!--sections-->

<div class="sections section-3">
<div class="container border">
    <div class="row">
        <div class="col-md-4 border">
           <h2><?php echo t("Professionals & Businesses of All Types Use")?> <?php echo FrontFunctions::getCompanyName()?></h2>
           <div class="line margin white"></div>
        </div> <!--col-->
        <div class="col-md-8 border">
           <div class="row">
              <div class="col-sm-4 border">
                 <h4><?php echo t("Pickup & Delivery")?></h4>
                 <div class="line margin"></div>
                 
                 <ul>
                   <li>- <?php echo t("Courier Service")?></li>
                   <li>- <?php echo t("Laundry Service")?></li>
                   <li>- <?php echo t("Field Service")?></li>
                   <li>- <?php echo t("Grocery Delivery")?></li>
                   <li>- <?php echo t("Food Delivery")?></li>
                 </ul>
                 
              </div> <!--col-->
              <div class="col-sm-4 border">
              
                <h4><?php echo t("Beauty Services")?></h4>
                 <div class="line margin"></div>
                 
                 <ul>
                   <li>- <?php echo t("Make Up Artist")?></li>
                   <li>- <?php echo t("Wedding Stylist")?></li>
                   <li>- <?php echo t("Manicurist")?></li>
                   <li>- <?php echo t("Hair Stylist")?></li>
                   <li>- <?php echo t("Aesthetician")?></li>
                 </ul>
              
              </div> <!--col-->
              <div class="col-sm-4 border">
              
                 <h4><?php echo t("Repair Services")?></h4>
                 <div class="line margin"></div>
                 <ul>
                  <li>- <?php echo t("Electrical Works")?></li>
                  <li>- <?php echo t("Computer Repair")?></li>
                  <li>- <?php echo t("Appliances Repair")?></li>
                  <li>- <?php echo t("Plumbing Repair")?></li>
                  <li>- <?php echo t("Auto Repair")?></li>                  
                 </ul>
              
              </div> <!--col-->
           </div> <!--row-->
           
           <div class="row top20">
              <div class="col-sm-4 border">
                 <h4><?php echo t("Home Services")?></h4>
                 <div class="line margin"></div>
                 
                 <ul>
                   <li>- <?php echo t("Home Cleaning")?></li>
                   <li>- <?php echo t("Landscaping")?></li>
                   <li>- <?php echo t("Maid Service")?></li>
                   <li>- <?php echo t("Alarm & Security")?></li>
                   <li>- <?php echo t("Personal Organizer")?></li>
                 </ul>
                 
              </div> <!--col-->
               <div class="col-sm-4 border">
                 <h4><?php echo t("Health and Well Being")?></h4>
                 <div class="line margin"></div>
                 
                 <ul>                   
                   <li>- <?php echo t("Yoga Instructor")?></li>
                   <li>- <?php echo t("Personal Trainer")?></li>
                   <li>- <?php echo t("Family Physician")?></li>
                   <li>- <?php echo t("Alternative Healing")?></li>
                   <li>- <?php echo t("Massage Therapist")?></li>                   
                 </ul>
                 
              </div> <!--col-->
           </div> <!--row-->
           
           
        </div> <!--col-->
    </div> <!--row-->
</div> <!--container-->
</div> <!--sections-->

<div class="sections section-4">
<div class="container">
   <div class="row">
      <div class="col-md-4 border">
        <h2><?php echo FrontFunctions::getCompanyName()?> <?php echo t("pricing")?></h2>
        <div class="line margin dim"></div>
      </div> <!--col-->
      <div class="col-md-8 border">
         <p class="top20"><?php echo t("No commitment, no hidden charges and no complications;")?> <?php echo t("simple and transparent pricing. Your business is")?> 
         <?php echo t("unique and our pricing structure is flexible. Let's get started")?></p>
      </div> <!--col-->
   </div> <!--row-->
   
   <div class="rowx pricing top20">
      
   
   <?php if (is_array($pricing) && count($pricing)>=1):?>
   <div class="row pricing top20">
   
      <?php foreach ($pricing as $val):?>
      <?php        
       $price=$val['price']; $promo_price=0;
       if($val['promo_price']>0.0001){
       	  $price=$val['promo_price'];
       	  $promo_price=$val['promo_price'];
       }
      ?>
      <div class="col-md-4 border">
         <div class="box">
           <h5><?php echo $val['plan_name']?></h5>
           
           <div class="section">
           <!--<price>0.00 <span>$</span></price>-->
           <?php if($final_price=FrontFunctions::formatPricing($price)):?>
           <?php echo $final_price?>
              <?php if ($promo_price>0):?>
	           <p><?php echo t("Before")?> <span class="promo-price"><?php echo prettyPrice($val['price'])?></span></p>
	           <?php endif;?>
           <?php else :?>
           <price>-</price>
           <?php endif;?>
           <!--<p class="uppercase"><?php echo t("PER")." ".t($val['plan_type'])?></p>-->
           <p class="uppercase"><?php echo t("Membership Limit")?> <?php echo $val['expiration']?> <?php echo t($val['plan_type'])?> </p>
           
           <?php if(!empty($val['plan_name_description'])):?>
           <p class="plan_description readmore"><?php echo $val['plan_name_description']?></p>
           <?php endif;?>
           
           </div>
           
           <div class="section text-left">
             <ul> 
              <li>- <?php echo t("Allowed")." ".t($val['allowed_driver'])." ".t("driver")?>.</li>
              <li>- <?php echo t("Allowed")." ".t($val['allowed_task'])." ".t("Task")?>.</li>
              <?php if ( $val['with_sms']==1):?>
              <li>- <?php echo t("With SMS Features")?></li>
              <?php else :?>
              <li>- <?php echo t("NO SMS Features")?></li>
              <?php endif;?>
             </ul>
           </div>
           
           <div class="action">
           <a href="<?php echo Yii::app()->createUrl('front/signup',array( 
             'plan_id'=>$val['plan_id']
           ))?>" class="brown-button large relative top30 rounded">
		   <?php echo t("START FREE TRIAL")?>
		   <i class="ion-ios-arrow-thin-right"></i>
		   </a>
		   </div>
           
         </div> <!--box-->
      </div> <!--col-->
      <?php endforeach;?>
           
   </div> <!--row-->   
   <?php endif;?>      
   
      
      </div> <!--col-->
   </div> <!--row-->
      
   <img src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/headphone.png";?>">
   
</div> <!--container-->
</div> <!--section-4-->

<div class="sections section-5">   
</div>  <!--section-5-->
