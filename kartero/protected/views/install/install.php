<?php 
$failed=0;
$_SESSION['kt_install']=2;
$path=Yii::getPathOfAlias('webroot');
?>

<nav class="teal lighten-1">
<div class="nav-wrapper">
  <a href="<?php echo Yii::app()->createUrl('/install/index')?>">
     <img style="margin-top: 5px;margin-left: 20px;" class="logo" src="<?php echo Yii::app()->getBaseUrl(true)."/assets/images-front/logo.png"?>">     
  </a>       
</div>
</nav>
  
<form method="POST" action="<?php echo Yii::app()->createUrl('install/step2')?>">
<div class="container">
   <div style="margin-top:30px;">
   
   <div class="card">
   
   <nav class="teal lighten-1">
    <div class="nav-wrapper">
      <div class="col s12" style="padding-left:20px;">
        <a href="<?php echo Yii::app()->createUrl('install')?>" class="breadcrumb">Checking requirements</a>        
      </div>
    </div>
   </nav>    
   
   <div class="card-content">
    <p>
	   <?php
		try {
	        echo 'Connecting to database...<br/>';
	        $connection = Yii::app()->db;  // (*)
	        echo ($connection ? 'Database Successful [OK]' : 'Database Failed');
		}
		catch(Exception $ex) {
		    echo $ex->getMessage();
		    $failed++;
		}
	   ?>
    </p>
    
    <p>
    <?php 
    if (!defined('PDO::ATTR_DRIVER_NAME')){    	
    	echo "PDO is not installed";
    	$failed++;
    } else echo "PDO installed [OK]";
    ?>
    </p>
    
    <p>
    <?php 
    $_SESSION['test']='test';
    if (!empty($_SESSION['test'])){
    	echo "Session [OK]";
    } else {
    	echo "Session not supported";
    	$failed++;
    }
    ?>
    </p>
    
    <p>
    <?php 
    if ( !function_exists( 'mail' ) ) { 
    	echo "mail() has been disabled";
    	$failed++;
    } else echo "mail() is available [OK]"	;
    ?>
    </p>
    
    <p>
    <?php 
    if ( function_exists('curl_version') ){
    	echo "CURL is enabled [OK]";
    } else {
    	echo "CURL is disabled";
    	$failed++;
    }
    ?>
    </p>
    
    <p>
    <?php 
    if ( @file_get_contents(__FILE__) ){
    	echo "file_get_contents is enabled [OK]";
    } else {
    	echo "file_get_contents is disabled";
    	$failed++;
    }
    ?>
    </p>
    
    <p>    
    <?php 
    $isEnabled = in_array('mod_rewrite', apache_get_modules());
    echo ($isEnabled) ? 'mod_rewrite Enabled' : 'mod_rewrite Not enabled';
    if(!$isEnabled){
    	$failed++;
    }
    ?>
    </p>
    
    <p>
    <?php 
    $t_path=explode("/",$path);        
    $host=dirname($_SERVER['REQUEST_URI']);
    $host=$host=="/"?"":$host;
    $current_dir_folder=$host;
    $ht_file=$path."/.htaccess";    
    if(!file_exists($ht_file)){
    	echo 'Creating .htaccess file<br/>';    
    	if ( $host=="htdocs" || $host=="public_html" || $host==""){
$htaccess="<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>";  	
} else {  
$htaccess="<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $current_dir_folder/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $current_dir_folder [L]
</IfModule>";
}
		InstallHelper::dump($htaccess);
		InstallHelper::createFile($ht_file,$htaccess);
    }
    ?>    
    </p>
    
    
    <p>
    <?php 
    if ( $failed<=0){
    	$_SESSION['kt_install']=1;
    	echo '<h5>Everything seems to be ok. Proceed to next steps</h5>';
    } else echo '<h5 style="color:red;font-size:16px;">There seems to be error in checking your server. Please fixed the following issue and try again.</h5>';
    ?>
    </p>
    
    </div>  <!--card-content-->
   
     <?php if($_SESSION['kt_install']==1):?>
	 <div class="card-action" style="margin-top:20px;">
	 <button class="btn waves-effect waves-light" type="submit" name="action">
	   Install
	 </button>
	 </div>
	 <?php endif;?>
	 
  </div> <!--card-->
   
  </div>	 
</div> <!--container-->
</form>