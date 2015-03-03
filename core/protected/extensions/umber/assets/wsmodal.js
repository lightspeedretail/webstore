'use strict';
/* globals $:false, History:false */
/* exported showEditCartModal */
function sleep(millis, callback) {
    setTimeout(function()
        { callback(); },
        millis);
}

function mobilecheck() {
    var check = false;
    (function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
}

var clickevent = mobilecheck() ? 'touchstart' : 'click';


function clearErrors(form) {
    form
        .removeClass('error').end()
        .find('.form-error').remove();
}

function renderError(form,error_message) {
    // Clear errors
    clearErrors(form);

    var error_dom = $('<div class="form-error">').append($('<p>').append(error_message));
    error_dom.hide();
    form.addClass('error').prepend(error_dom);

    error_dom.fadeIn();

    // Shake like crazy, then let us do it again!
    $('.webstore-modal > section').addClass('animated shake');
    setTimeout(function() {
        $('.webstore-modal > section').removeClass('animated shake');
    },500);
}

function hideModal() {
    // Only try to hide if it's visible. If a user keeps on pressing
    // the ESC key the modal won't show up
    if ($('.webstore-modal').hasClass('show') === true)
    {
        $('.webstore-modal').fadeOut();
        setTimeout(function() {
            $('.webstore-modal')
                .removeClass('show')
                .show();
        }, 1000);
    }

    // Restore the body's scrollbar when hiding a modal
    $('body').css('overflow', 'inherit');
    $('#viewport, .btn-navbar, #menubar, #topbar, #footer').removeClass('mobile-hide');
}

/**
 * Show the edit cart modal.
 * @param {string} [editCartUrl] The URL to add to the browser history. If no
 * URL is provided, then no URL is added to the history.
 */
function showEditCartModalAndPushState(editCartUrl) {
    // Remove the body's scrollbar when opening a modal
    $('body').css('overflow', 'hidden');

    $('.webstore-modal-cart:first').addClass('show');
    setTimeout(function() { $('.webstore-modal-cart').find('input[autofocus]').focus(); }, 500);

    if (typeof History !== 'undefined' && typeof editCartUrl === 'string') {
        History.pushState({key:'editcart'}, null, editCartUrl);
    }

    $('#viewport, .btn-navbar, #menubar, #topbar, #footer').addClass('mobile-hide');
}

/**
 * Show the edit cart modal.
 * @param {boolean} maintainPushState Set this to true to leave the browser
 * history state unmodified. Defaults to false.
 * @deprecated This is left in for compatibility with Brooklyn2014 3.2.2
 * themes. Use showEditCartModalAndPushState instead. This was deprecated in
 * Web Store 3.2.4 because it uses a hard-coded URL instead of going through
 * Yii::app()->createUrl.
 */
function showEditCartModal(maintainPushState) {
    // This warning is on purpose: we want to store owners to update their
    // theme copies.
    /* globals console:false */
    console.warn('showEditCartModal is deprecated. Use showEditCartModalAndPushState instead.');
    var editCartUrl = '/editcart';

    if (maintainPushState === true) {
        showEditCartModalAndPushState();
    } else {
        showEditCartModalAndPushState(editCartUrl);
    }
}


function showModal() {
    // Remove the body's scrollbar when opening a modal
    $('body').css('overflow', 'hidden');
    $('.webstore-modal-cart-confirm:first').addClass('show');
    setTimeout(function() { $('.webstore-modal-cart-confirm').find('input[autofocus]').focus(); }, 500);
    $('#viewport, .btn-navbar, #menubar, #topbar, #footer').addClass('mobile-hide');
}

$('.webstore-modal, .webstore-modal-close').on(clickevent, function(e) {
    if (e.target === $('.webstore-modal').get(0) || e.target === $('.webstore-modal-close').get(0)) {
        hideModal();
    }
});

$('#modal-trigger').on('click', function() { showModal(); });


/**
 * When the ESC key pressed is captured by the document, we check
 * that there's a modal visible to close and also do a back if it's
 * the edit cart modal that is open
 */
$(document).on('keydown', function(e) {
    if (e.keyCode === 27)
    {
        hideModal();

        if ($('.editcartmodal').first().hasClass('show') === true)
        {
            History.back();
        }
    }
});

/**
 * When the add to cart modal or the edit cart it open it takes up the full
 * screen, so we check it a click event it captured on it to close the modal
 * and if it's the edit cart modal we go back in history.
 */
$(document).on(clickevent, function(e) {
    var target = $(e.target);

    // If the clicked element has the .webstore-modal class, then hide the modal.
    // (Only the modal's parent has the .webstore-modal class. The modal's active window don't have the .webstore-modal class).
    if (target.hasClass('webstore-modal')) {
        hideModal();
    }

    // We only want to send the customer back if he has the editcart modal open
    if (target.hasClass("editcartmodal") &&
        target.hasClass("webstore-modal"))
    {
        History.back();
    }
});

$(document).on('click', ".webstore-modal-close, .continue-shopping", function()  {
    hideModal();
});

$(document).on('click', ".webstore-change-item", function()  {
    hideModal();
    var editCartUrl = $(this).data('editcarturl');
    setTimeout(function(){
        showEditCartModalAndPushState(editCartUrl);
    }, 1125);
});

// This ensures that back and forward buttons work correctly with the editcart modal
$(function() {
    if (typeof History !== 'undefined' && typeof History.pushState !== 'undefined') {

        History.Adapter.bind(window, 'statechange', function(e) {
            var State = History.getState();

            // When a user clicks "forward" to the edit cart page, we want to
            // show the edit cart modal but we do not need to add to the push
            // state since forward already does that.
            //
            // Note that showEditCartModal is actually called twice when the
            // user clicks on the "Cart" link. The first time with
            // doPushSate=true, and the second time in this callback when the
            // push state changes.
            if(State.data.key === 'editcart'){
                showEditCartModalAndPushState();
            }
            else {
                hideModal();
            }
        });
    }
});

