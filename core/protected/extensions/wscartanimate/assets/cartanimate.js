/*
 Add to cart fly effect with jQuery. - May 05, 2013
 (c) 2013 @ElmahdiMahmoud - fikra-masri.by
 license: http://www.opensource.org/licenses/mit-license.php
 */

function animateAddToCart(target,image) {

    if(target==undefined) target='#shoppingcart';
    if(image==undefined) image='#zoomPrimary';
    var cart = $(target);
    var imgtodrag = $(image);
    if (imgtodrag) {
        var imgclone = imgtodrag.clone()
            .offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            })
            .css({
                'opacity': '0.5',
                'position': 'absolute',
                'height': '150px',
                'width': '150px',
                'z-index': '100'
            })
            .appendTo($('body'))
            .animate({
                'top': cart.offset().top + 10,
                'left': cart.offset().left + 10,
                'width': 75,
                'height': 75
            }, 500, 'easeInOutExpo');

        imgclone.animate({
            'width': 0,
            'height': 0
        }, function () {
            $(this).detach()
        });
    }
}