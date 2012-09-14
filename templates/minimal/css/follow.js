
window.onload = function() {


    function getScrollTop() {
        if (typeof window.pageYOffset !== 'undefined' ) {
            // Most browsers
            return window.pageYOffset;
        }

        var d = document.documentElement;
        if (d.clientHeight) {
            // IE in standards mode
            return d.scrollTop;
        }

        // IE in quirks mode
        return document.body.scrollTop;
    }

    window.onscroll = function() {
        var box = document.getElementById('shoppingcart_ctl'),
            scroll = getScrollTop();
        box.style.display = "absolute";
        if (scroll <= 180) {
            box.style.top = "180px";
        }
        else {
            box.style.top = (scroll + 2) + "px";
        }
    };

};
