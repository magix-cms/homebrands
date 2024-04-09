/* Add this block to the global.js file of your theme */
if($(".owl-brands").length > 0 && $.fn.owlCarousel !== undefined) {
    $(".owl-brands").owlCarousel(Object.assign({},owlOptions,{
        margin: 30,
        dots: false,
        nav: true,
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 5000,
        responsive:{
            0:{
                items:2,
                margin: 10,
                autoplayTimeout: 3000
            },
            768:{
                items:3
            },
            992:{
                items:5
            },
            1200:{
                items:6
            },
            1500:{
                items:8
            }
        }
    }));
}