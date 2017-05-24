<?php $this->renderPartial('/layouts/header');?>

<body class="<?php echo isset($this->body_class)?$this->body_class:'';?>">

<?php echo $content;?>

<div id="jplayer"></div>
<?php 
$this->renderPartial('/app/new-task',array(   
));

$this->renderPartial('/app/assign-task',array(   
));

$this->renderPartial('/app/task-details',array(   
));

$this->renderPartial('/app/task-change-status',array(   
));

$this->renderPartial('/app/driver-details',array(   
));

$this->renderPartial('/app/notification-tpl',array(   
));

$this->renderPartial('/app/map-location',array(   
));

$this->renderPartial('/app/push-form',array(   
));
?>
<div class="main-preloader">
   <div class="inner">
   <div class="ploader"></div>
   </div>
</div> 
</body>
<?php $this->renderPartial('/layouts/footer');?>