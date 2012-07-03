<script>
    var arrOldValues;

    function SelectAllList(CONTROL) {
        for (var i = 0; i < CONTROL.length; i++) {
            CONTROL.options[i].selected = true;
        }
    }

    function DeselectAllList(CONTROL) {
        for (var i = 0; i < CONTROL.length; i++) {
            CONTROL.options[i].selected = false;
        }
    }


    function FillListValues(CONTROL) {
        var arrNewValues;
        var intNewPos = -1;
        var strTemp = GetSelectValues(CONTROL);
        arrNewValues = strTemp.split(",");
        for (var i = 0; i < arrNewValues.length - 1; i++) {
            if (arrNewValues[i] == 1) {
                intNewPos = i;
            }
        }

        if (intNewPos > -1)
         for (var i = 0; i < arrOldValues.length - 1; i++) {
            if (arrOldValues[i] == 1 && i != intNewPos) {
                CONTROL.options[i].selected = true;
            } else if (arrOldValues[i] == 0 && i != intNewPos) {
                CONTROL.options[i].selected = false;
            }

            if (arrOldValues[intNewPos] == 1) {
                CONTROL.options[intNewPos].selected = false;
            } else {
                CONTROL.options[intNewPos].selected = true;
            }
        }
    }


    function GetSelectValues(CONTROL) {
        var strTemp = "";
        for (var i = 0; i < CONTROL.length -1; i++) {
            if (CONTROL.options[i].selected == true) {
                strTemp += "1,";
            } else {
                strTemp += "0,";
            }
        }
        return strTemp;
    }

    function GetCurrentListValues(CONTROL) {
        var strValues = "";
        strValues = GetSelectValues(CONTROL);
        arrOldValues = strValues.split(",")
    }
</script>
<table>
<td class="label">Set restrictions for to apply when</td>
	<td><select name="matchwhen"><option>products match the following criteria</option><option>matches everything BUT the following criteria</option></select></td>
</table>

	


