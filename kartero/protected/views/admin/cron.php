
<div class="card">
 <div class="card-content">

 
 <h5><?php echo t("CronJobs")?></h5>  
 
 <p>
 <?php echo t("Run the following cron jobs in your server")?>.<br/>
 <?php echo t("set the interval to every minutes depends in your requirements")?>
 </p>
 
 <ul class="collection">
  <li class="collection-item"><?php echo Yii::app()->getBaseUrl(true)."/cron/ProcessPush"?></li>
  <li class="collection-item"><?php echo Yii::app()->getBaseUrl(true)."/cron/AutoAssign"?></li>
  <li class="collection-item"><?php echo Yii::app()->getBaseUrl(true)."/cron/ProcessAutoAssign"?></li>
  <li class="collection-item"><?php echo Yii::app()->getBaseUrl(true)."/cron/CheckAutoAssign"?></li>
  <li class="collection-item"><?php echo Yii::app()->getBaseUrl(true)."/cron/CheckCustomerExpiry"?></li>
 </ul>
 
 <p>
 <b><?php echo t("Example command")?></b>
 <br/>
 curl <?php echo Yii::app()->getBaseUrl(true)."/cron/ProcessPush"?>
 <br/>
 <b><?php echo t("or")?></b>
 <br/>
 wget <?php echo Yii::app()->getBaseUrl(true)."/cron/ProcessPush"?>
 
 </p>
 
 <p style="margin-top:30px;">
 <?php echo t("How to setup cron jobs video tutorial")?>
 <a target="_blank" href="https://www.youtube.com/watch?v=caDqkYfmtQw">https://www.youtube.com/watch?v=caDqkYfmtQw</a>
 </p>
 
 </div>
</div>