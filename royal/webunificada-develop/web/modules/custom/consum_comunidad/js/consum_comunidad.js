jQuery(document).ready(function(){
    jQuery("#modalEditProfile .webform-term-checkboxes #edit-intereses .form-check").each(function(){
        if (jQuery(this).children('input').is(':checked')) {
            jQuery(this).addClass("checkbox-checked");
        } 
    });
    jQuery("body").on("click","#modalEditProfile .webform-term-checkboxes #edit-intereses .form-check",function(){
        if (jQuery(this).children('input').is(':checked')) {
          jQuery(this).addClass("checkbox-checked");
        }else{
          jQuery(this).removeClass("checkbox-checked");
        }  
    });
    jQuery(".picture_user_message img").on("mouseenter", function(){
      jQuery(this).parents(".label-container").find(".medallas_notificacion").toggle("slide");
    });
    jQuery(".picture_user_message img").on("mouseleave", function(){
      jQuery(this).parents(".label-container").find(".medallas_notificacion").toggle("slide");
    });
});