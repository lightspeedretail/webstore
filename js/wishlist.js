
function wishlistedit(data) {
        if (data.status=="success") {
            $("#qty-"+data.id).html(data.qty);
            $("#WishitemEdit").dialog("close");
            if (data.reload) location.reload();
        } else alert(data.errormsg);
}

