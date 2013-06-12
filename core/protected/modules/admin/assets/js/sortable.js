
jQuery(function ($) {
    var sorting = {
        message: null,
        init: function () {


        $('#sortable').sortable({
            update: function(event, ui) {
                var newOrder = $(this).sortable('toArray').toString();
                var controller = $("#controller").val();
               $.post(yii.urls.base + "/admin/" + controller + "/order", {order:newOrder});
            }
        });




        }

    }

    sorting.init();

});

$("#sortable").sortable();
$("#sortable").disableSelection();