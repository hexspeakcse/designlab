<?php 
$table_prefix=Yii::app()->db->tablePrefix;
$DbExt=new DbExt;

$path=Yii::getPathOfAlias('webroot')."/protected";
require_once($path.'/config/table_structure.php');
?>

<nav class="teal lighten-1">
<div class="nav-wrapper">
  <a href="<?php echo Yii::app()->createUrl('/install/index')?>">
     <img style="margin-top: 5px;margin-left: 20px;" class="logo" src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/logo.png"?>">     
  </a>       
</div>
</nav>
  
<form method="POST" action="<?php echo Yii::app()->createUrl('install/step3')?>">
<div class="container">
   <div style="margin-top:30px;">
     
   <div class="card">
      
  <nav class="teal lighten-1">
    <div class="nav-wrapper">
      <div class="col s12" style="padding-left:20px;">
        <a href="<?php echo Yii::app()->createUrl('install')?>" class="breadcrumb">Checking requirements</a>
        <a href="#!" class="breadcrumb">Database Tables</a>
        <!--<a href="#!" class="breadcrumb">Finish</a>-->
      </div>
    </div>
  </nav>            
   
    <div class="card-content">
 
    <h5>Creating database tables...</h5>
    
    <p>
    <?php $x=1;?>
    <?php foreach ($tbl as $key=>$val) {
    	echo "Creating table $key [OK]<br/>";
    	$DbExt->qry($val);
    	$x++;
    }?>
    </p>
    
    </div>  <!--card-content-->
   
     <?php if($_SESSION['kt_install']==1):?>
	 <div class="card-action" style="margin-top:20px;">
	 <button class="btn waves-effect waves-light" type="submit" name="action">
	   Next
	 </button>
	 </div>
	 <?php endif;?>
	 
  </div> <!--card-->
   
  </div>	 
</div> <!--container-->
</form>