<?php $this->renderPartial('/layouts/header');?>

<body class="<?php echo isset($this->body_class)?$this->body_class:'';?>">


<?php if (AdminFunctions::islogin()):?>
<?php $this->renderPartial('/admin/menu',array(
 'language'=>getOptionA('language_list'),
 'current_lang'=>Yii::app()->language
));?>
<?php endif;?>


<?php if (AdminFunctions::islogin()):?>
<div class="container">
  <div class="section">
    <?php echo $content;?>
  </div>
</div>
<?php else :?>
<?php echo $content;?>
<?php endif;?>

</body>

<?php $this->renderPartial('/layouts/admin_footer');?>