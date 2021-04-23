(function ($, Drupal) {
  Drupal.behaviors.consumUserRegister = {
    attach: function (context, settings) {
      $("#edit-socio-cliente-options-new").click(function () {
        if (this.checked) {
          $("#edit-socio-cliente-options-current").prop('checked', false);
          $(".form-item-numero-socio").hide();
          $(".form-item-documento-principal-numero").hide();
        }
      });
      $("#edit-socio-cliente-options-current").click(function () {
        if (this.checked) {
          $("#edit-socio-cliente-options-new").prop('checked', false);
          $(".form-item-numero-socio").show();
          $(".form-item-documento-principal-numero").show();
        }
      });
      if ($(".password-strength__indicator").hasClass("is-strong")){
        $(this).parent().parent().find(".password-strength__title").addClass("is-strong");
      }
    }
  };
  $(".form-cf .password-suggestions").addClass("password-suggestions-display");
  $("<span class='ci-view'></span>").insertBefore(".form-cf input[type='password']");
  $(".form-cf .ci-view").click(function(){
    if ($(this).parent().find("input").prop("type") == "password"){
      $(this).parent().find("input").prop("type","text");
    }else{
      $(this).parent().find("input").prop("type","password");
    }  
  });
  var suggestions = false;
  $(".form-cf #edit-password").on("input",function(){
    if ($(".password-strength__indicator").hasClass("is-strong")){
      $(this).parent().parent().find(".password-strength__title").attr("data-security","is-strong");
      $(this).parent().parent().find(".password-strength__title").addClass("is-security");
    }else if ($(".password-strength__indicator").hasClass("is-good")){
      $(this).parent().parent().find(".password-strength__title").attr("data-security","is-good");
      $(this).parent().parent().find(".password-strength__title").addClass("is-security");
    }else if ($(".password-strength__indicator").hasClass("is-fair")){
      $(this).parent().parent().find(".password-strength__title").attr("data-security","is-fair");
      $(this).parent().parent().find(".password-strength__title").addClass("is-security");
    }else if ($(".password-strength__indicator").hasClass("is-weak")){
      $(this).parent().parent().find(".password-strength__title").attr("data-security","is-weak");
      $(this).parent().parent().find(".password-strength__title").addClass("is-security");
    }else{
      $(this).parent().parent().find(".password-strength__title").removeClass("is-security");
      $(this).parent().parent().find(".password-strength__title").removeAttr("data-security");
    }
    if (jQuery(".form-cf #edit-password .password-field").val() && !suggestions){
      $("<span class='ci-info'>").insertAfter(".form-cf #edit-password>label");
      $(".password-suggestions").insertAfter(".form-cf #edit-password>label");
      $("<div class='arrow'></div>").insertBefore(".form-cf .password-suggestions ul");
      suggestions = true;
    }
    if (jQuery(".form-cf #edit-password .password-confirm").val()){
      if (jQuery(".form-cf #edit-password .password-confirm").val() == jQuery(".form-cf #edit-password .password-field").val()){
        $(".form-cf #edit-password input").attr("data-password","password-equals"); 
        $(".form-cf #edit-password div.password-confirm").attr("data-confirm","password-equals"); 
      }else{
        $(".form-cf #edit-password input").attr("data-password","password-no-equals");
        $(".form-cf #edit-password div.password-confirm").attr("data-confirm","password-no-equals");
      }
    }else{
      $(".form-cf #edit-password input").removeAttr("data-password");
      $(".form-cf #edit-password div.password-confirm").removeAttr("data-confirm");
    }
    $(".form-cf #edit-password .ci-info").on("mouseenter", function(){
      $(".password-suggestions").addClass("password-suggestions-display");
    });
    $(".form-cf #edit-password .ci-info").on("mouseleave", function(){
      $(".password-suggestions").removeClass("password-suggestions-display");
    });
  });
})(jQuery, Drupal);
