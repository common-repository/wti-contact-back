jQuery(document).ready(function(){
     jQuery("#wti_contact_back_form").submit(function(e){
          e.preventDefault();
          jQuery('#wti_contact_back_form_submit').val('Processing...').attr('disabled', 'disabled');
          
          jQuery.ajax({
               type: "POST",
               url: "wp-content/plugins/wti-contact-back/wti-contact.php",
               data: jQuery(this).serialize(),
               success: function(data){
                    if(data.error == 1){
                         add_class = 'wti-contact-back-error';
                         remove_class = 'wti-contact-back-success';
                    }else{
                         add_class = 'wti-contact-back-success';
                         remove_class = 'wti-contact-back-error';
                         
                         jQuery('#contact_name').val('');
                         jQuery('#contact_value').val('');
                    }
                    
                    jQuery('#wti_contact_back_form_submit').val('Contact me back').removeAttr('disabled');
                    jQuery("#wti_contact_back_form_result").removeClass(remove_class).addClass(add_class).empty().html(data.msg);
	       },
               dataType: "json"
          });
     });
});