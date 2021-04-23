jQuery(document).ready(function(){
    jQuery(".custom-row-region-footer-fluid h2").click(function(){
        if (jQuery(this).hasClass("icon-up")){
            jQuery(this).parent().find("ul").slideUp();
            jQuery(this).removeClass("icon-up");
        }else{
            jQuery(".custom-row-region-footer-fluid ul").slideUp();
            jQuery(".custom-row-region-footer-fluid h2").removeClass("icon-up");
            jQuery(this).parent().find("ul").slideDown();
            jQuery(this).addClass("icon-up");
        }
    });
    
    jQuery('body').on("click", ".custom-row-region-navbar-accessibility #dropdown-language",function(){
        var mainNodeLanguage = document.getElementById('dropdown-language');
        function callback(mutationsList) {
            mutationsList.forEach(mutation => {
                if (mutation.attributeName === 'class') {
                    if (jQuery("#dropdown-language").hasClass("show")){
                        jQuery("#dropdown-language .arrow").removeClass("ci-down");
                        jQuery("#dropdown-language .arrow").addClass("ci-up");
                    }else{
                        jQuery("#dropdown-language .arrow").addClass("ci-down");
                        jQuery("#dropdown-language .arrow").removeClass("ci-up");
                    }
                }
            })
            /*mutationObserverLanguage.disconnect();*/
        }
        var mutationObserverLanguage = new MutationObserver(callback);
        mutationObserverLanguage.observe(mainNodeLanguage, { attributes: true });
    });

    jQuery(".node--type-guia-general .row-1 .col--1 p").click(function(){
        jQuery(".node--type-guia-general .row-1 .col--1 p").removeClass("active");
        jQuery(this).addClass("active");
        jQuery("html, body").animate({ scrollTop: (jQuery('#c-'+jQuery(this).attr("id")).offset().top) }, 600);
    });

    jQuery(".custom-row-region-menu-personalizado .submenu3").on("mouseleave",function(){
        jQuery(".submenu3").addClass("submenu-hover-disabled");
    });

    jQuery(".custom-row-region-navbar-principal").on("mouseenter",function(){
        jQuery(".submenu3").addClass("submenu-hover-disabled");
    });

    jQuery(".custom-row-region-menu-personalizado .submenu2>ul>li>.dropdown-menu>ul>li").on("mouseenter",function(){
        jQuery(".custom-row-region-menu-personalizado .submenu3>ul>li>.dropdown-menu>ul>li").removeClass("active");
        jQuery(".custom-row-region-menu-personalizado .submenu3>ul>li>.dropdown-menu>ul>li[data-node='"+jQuery(this).data("node")+"']").addClass("active");
        if (jQuery(this).find("dropdown-menu")){
            jQuery(".submenu3").removeClass("submenu-hover-disabled");
        }else{
            jQuery(".submenu3").addClass("submenu-hover-disabled");
        }
    });

    /* START MENU TREE */
    /* LEVEL 2 */
    jQuery('body .block--navegacionprincipal>ul>.dropdown>.div-toggle').click(function(){
        if (jQuery(".block--navegacionprincipal").hasClass("level-2")){
            jQuery(this).attr("data-toggle","dropdown");
            jQuery(".block--navegacionprincipal").removeClass("level-2");
            jQuery(this).parent().removeClass("show-level-2");
            jQuery("body .block--navegacionprincipal .dropdown").removeAttr("id");
        }else{
            jQuery(".block--navegacionprincipal").addClass("level-2");
            jQuery(this).parent().addClass("show-level-2");
            jQuery(this).parent().attr("id","mutation-active");
            var mainNode = document.getElementById('mutation-active');
            function callback(mutationsList) {
                mutationsList.forEach(mutation => {
                    if (mutation.attributeName === 'class') {
                        if ((jQuery(".block--navegacionprincipal").hasClass("level-2")) && (jQuery("#mutation-active").hasClass("show"))){
                            jQuery("body #mutation-active>.div-toggle").removeAttr("data-toggle");
                            jQuery("body .block--navegacionprincipal .dropdown").removeAttr("id");
                        }
                    }
                })
                mutationObserver.disconnect();
            }
            var mutationObserver = new MutationObserver(callback);
            mutationObserver.observe(mainNode, { attributes: true });
        } 
    });

    /* LEVEL 3 */
    jQuery(".block--navegacionprincipal>ul>li>.dropdown-menu>ul>li>.div-toggle").click(function(){
        if (jQuery(".block--navegacionprincipal").hasClass("level-3")){
            jQuery(this).attr("data-toggle","dropdown");
            jQuery(".block--navegacionprincipal").removeClass("level-3");
            jQuery(this).parent().removeClass("show-level-3");
            jQuery(".block--navegacionprincipal").addClass("level-2");
            jQuery("body .block--navegacionprincipal .dropdown").removeAttr("id");
        }else{
            jQuery(".block--navegacionprincipal").removeClass("level-2");
            jQuery(".block--navegacionprincipal").addClass("level-3");
            jQuery(this).parent().addClass("show-level-3");
            jQuery(this).parent().attr("id","mutation-active");
            var mainNode = document.getElementById('mutation-active');
            function callback(mutationsList) {
                mutationsList.forEach(mutation => {
                    if (mutation.attributeName === 'class') {
                        if ((jQuery(".block--navegacionprincipal").hasClass("level-3")) && (jQuery("#mutation-active").hasClass("show"))){
                            jQuery("body #mutation-active>.div-toggle").removeAttr("data-toggle");
                            jQuery("body .block--navegacionprincipal .dropdown").removeAttr("id");
                        }
                    }
                })
                mutationObserver.disconnect();
            }
            var mutationObserver = new MutationObserver(callback);
            mutationObserver.observe(mainNode, { attributes: true });
        }  
    });
    /* END MENU TREE */

    jQuery(".js-carousel").each(function(){
        /*var itemActive = 0;*/
        var $carousel = jQuery(this),
            $carouselContainer = $carousel.find(".js-carousel-container"),
            $carouselList = $carousel.find(".js-carousel-list"),
            $carouselItem = $carousel.find(".js-carousel-item"),
            $carouselButton = $carousel.find(".js-carousel-button"),
            setItemWidth = function(){
                $carouselList.removeAttr("style");
                var curWidth = 0;
                for (var i = 0; i < $carouselItem.length; i++){
                    curWidth += jQuery($carouselItem[i]).outerWidth() + 10; //10 es el margen que tienen los items.
                }
                curWidth += 1; //Desviación redondeo decimal.
                $carouselList.css("width", curWidth);
            },
            
            slide = function(){
                var $button = jQuery(this),
                    dir = $button.data("dir"),
                    curPos = parseInt($carouselList.css("left")) || 0,
                    moveto = 0,
                    /*widthItems = 0,
                    widthItemsPrev = 0,*/
                    containerWidth = $carouselContainer.outerWidth(),
                    listWidth = $carouselList.outerWidth(),
                    before = (curPos + containerWidth),
                    after = listWidth + (curPos - containerWidth);
                if(dir=="next"){
                    /*for (var i = itemActive; i < $carouselItem.length; i++){
                        widthItemsPrev += jQuery($carouselItem[i]).outerWidth() + 10; //10 es el margen que tienen los items.
                        if (widthItemsPrev <= containerWidth){
                            jQuery($carouselItem).removeClass("last-view");
                            jQuery($carouselItem[i]).addClass("last-view");
                            widthItems = widthItemsPrev;
                            itemActive = i;
                            console.log(widthItems);
                        }
                    }*/
                    moveto = (after < containerWidth) ? curPos - after : curPos - containerWidth;
                } else {
                    moveto = (before >= 0) ? 0 : curPos + containerWidth;
                }
                
                $carouselList.animate({
                    left: moveto
                });
            };
        jQuery(window).resize(function(){
            setItemWidth();
        });
        setItemWidth();
        
        $carouselButton.on("click", slide);
    });

    /*jQuery(document).on("dragover drop", function(e) {
        e.preventDefault();  // allow dropping and don't navigate to file on drop
    }).on("drop", function(e) {
        console.log("Entro");
    jQuery(".node--type-viaje .subir-fotos input[type='file']").prop("files", e.originalEvent.dataTransfer.files);
    });*/
});

jQuery(window).scroll(function(){
    if (jQuery(window).scrollTop() > jQuery(".global-head").height()){
        jQuery(".page").css("padding-top",jQuery(".global-head").height()+"px");
        jQuery(".global-head").css("top",jQuery("body").css("padding-top"));
        jQuery(".global-head").addClass("menu-sticky");
        jQuery(".custom-container-region-navbar-accessibility").css("display","none");
    }else{
        jQuery(".page").css("padding-top","0px");
        jQuery(".custom-container-region-navbar-accessibility").css("display","block");
        jQuery(".global-head").css("top",jQuery("body").css("padding-top"));
        jQuery(".global-head").removeClass("menu-sticky");
    }
});