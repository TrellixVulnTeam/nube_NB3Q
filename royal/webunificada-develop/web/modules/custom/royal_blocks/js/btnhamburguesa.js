jQuery(document).ready(function() {
    /* Sidebar */
    jQuery('.dismiss, .overlay').on('click', function() {
        jQuery('.sidebar').removeClass('active');
        jQuery('.overlay').removeClass('active');
        jQuery('body').removeClass("overflow-hidden");
        if (jQuery('#collapseUser').hasClass("show")){
            jQuery('.ci-user').click();
        }
    });

    jQuery('.open-menu').on('click', function(e) {
        e.preventDefault();
        jQuery('body').addClass("overflow-hidden");
        jQuery('.sidebar').addClass('active');
        jQuery('.overlay').addClass('active');
        // close opened sub-menus
        jQuery('.collapse.show').toggleClass('show');
        jQuery('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    jQuery(".block--usuario .ci-user").on('click', function() {
        if(!jQuery("#collapseUser").hasClass("show")){
            jQuery('body').addClass("overflow-hidden");
            jQuery('.overlay').addClass('active');
        }else{
            jQuery('.overlay').removeClass('active');
            jQuery('body').removeClass("overflow-hidden");
        }
    });
});