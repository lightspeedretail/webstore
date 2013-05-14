
function selectall()
{
    var inputs = document.getElementsByTagName("input");
    for(var i = 0; i < inputs.length; i++) {
        if(inputs[i].type == "checkbox" && inputs[i].id.indexOf("offerservices")>0) {
            inputs[i].checked = true;
        }

    }
    return false;
}

function selectnone()
{
    var inputs = document.getElementsByTagName("input");
    for(var i = 0; i < inputs.length; i++) {
        if(inputs[i].type == "checkbox" && inputs[i].id.indexOf("offerservices")>0) {
            inputs[i].checked = false;
        }

    }
    return false;
}
