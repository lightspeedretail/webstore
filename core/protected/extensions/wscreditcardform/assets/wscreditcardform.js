function showCardNameInputField() {
    $('.cardholder').hide();
    $('.cardholder-field').fadeIn();
    setTimeout(function() {
       $('.cardholder-field input').focus();
     }, 200);
};
