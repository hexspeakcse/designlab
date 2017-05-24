
<div id="layout_1">
<?php 
$this->renderPartial('/tpl/layout1_top',array(   
));
?> 

<div class="dashboard-work-area">
 <div class="content_1">   
    <?php 
	$this->renderPartial('/tpl/task_panel',array(   
	));
	?> 	
 </div> <!--content_1-->
 
 <div class="content_2">
  
  <div id="primary_map" class="primary_map"></div>
 
 </div> <!--content_2-->
 
 <div class="content_3">   
   <?php 
	$this->renderPartial('/tpl/task_pane2',array(   
	));
	?> 
 </div> <!--content_3-->

</div> <!--dashboard-work-area-->


</div> <!--layout_1-->
