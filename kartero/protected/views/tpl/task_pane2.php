

<div class="blue_panel">
   <div class="row">
     <div class="col-md-6"><?php echo t("Agent")?></div>
     <div class="col-md-6 text-right">
     
     <a href="javascript:loadAgentDashboard();"><i class="ion-android-refresh"></i></a>
     <!--<a href="javascript:;"><i class="ion-ios-search"></i></a>-->
     
     </div><!-- col-->
   </div> <!--row-->   
</div> <!--blue_panel-->


<ul id="tabs">
 <li class="active"><span class="agent-active-total" >0</span> <?php echo t("Active")?></li>
 <li><span class="agent-offline-total" >0</span> <?php echo t("Offline")?></li>
 <li><span class="agent-total-total">0</span> <?php echo t("Total")?></li>
</ul>

<ul id="tab" class="list_row">
 <li class="active agent-active">

 </li>
 <li class="agent-offline">

 </li>
 
 <li class="agent-total">

 </li>
</ul>