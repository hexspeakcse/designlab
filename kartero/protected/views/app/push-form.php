
<div class="modal fade push-form-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
    
      <div class="modal-header">
         <button aria-label="Close" data-dismiss="modal" class="close" type="button">
           <span aria-hidden="true"><i class="ion-android-close"></i></span>
         </button> 
        <h4 id="mySmallModalLabel" >
        <?php echo t("Send Push")?>
        </h4> 
      </div>  
      
      <div class="modal-body">
      
      <form id="frm-send-push" class="frm" method="POST" onsubmit="return false;">
      <?php echo CHtml::hiddenField('action','sendPush')?>
      <?php echo CHtml::hiddenField('driver_id_push','')?>
      <div class="inner">
      
        <div class="row">
          <div class="col-md-12 ">
            <?php echo CHtml::textField('x_push_title','',array(
              'placeholder'=>t("Push Title"),
              'required'=>true
            ))?>
          </div>          
        </div> <!--row-->        
        
        <div class="row top10">
        <div class="col-md-12 ">
            <?php echo CHtml::textArea('x_push_message','',array(
              'placeholder'=>t("Push Message"),
               'required'=>true
            ))?>
          </div>
        </div> <!--row-->          
        
        <div class="row top20">
        <div class="col-md-5 col-md-offset-7">
        <button type="submit" class="orange-button medium rounded"><?php echo t("Submit")?></button>
        <button type="button" data-id=".push-form-modal" 
            class="close-modal green-button medium rounded"><?php echo t("Cancel")?></button>
        </div>
        </div>        
        
        
      </div> <!--inner-->  
      </form>  
      
      </div> <!--body-->
    
    </div>
  </div>
</div>