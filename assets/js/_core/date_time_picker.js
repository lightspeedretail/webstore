/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */
	function Qcodo__DateTimePicker_Change(strControlId, objListbox) {
		var objMonth = document.getElementById(strControlId + "_lstMonth");
		var objDay = document.getElementById(strControlId + "_lstDay");
		var objYear = document.getElementById(strControlId + "_lstYear");

		if (objListbox.options[objListbox.selectedIndex].value == "") {
			objMonth.selectedIndex = 0;
			objYear.selectedIndex = 0;
			while(objDay.options.length)
				objDay.options[objDay.options.length - 1] = null;
			objDay.options[0] = new Option("--", "");
			objDay.selectedIndex = 0;
		} else {
			if ((objListbox == objMonth) || ((objListbox == objYear) && (objMonth.options[objMonth.selectedIndex].value == 2))) {
				var intCurrentDay = objDay.options[objDay.selectedIndex].value;
				var intCurrentMaxDay = objDay.options[objDay.options.length - 1].value;
				
				// Calculate new Max Day
				var intNewMaxDay = 0;
				var intSelectedMonth = objMonth.options[objMonth.selectedIndex].value;
				var intSelectedYear = new Number(objYear.options[objYear.selectedIndex].value);

				if (!intSelectedYear)
					intSelectedYear = 2000;

				switch (intSelectedMonth) {
					case "1":
					case "3":
					case "5":
					case "7":
					case "8":
					case "10":
					case "12":
						intNewMaxDay = 31;
						break;
					case "4":
					case "6":
					case "9":
					case "11":
						intNewMaxDay = 30;
						break;
					case "2":
						if ((intSelectedYear % 4) != 0)
							intNewMaxDay = 28;
						else if ((intSelectedYear % 1000) == 0)
							intNewMaxDay = 29;
						else if ((intSelectedYear % 100) == 0)
							intNewMaxDay = 28;
						else
							intNewMaxDay = 29;
						break;
				};

				if (intNewMaxDay != intCurrentMaxDay) {
					// Redo the Days Dropdown
					var blnRequired = true;
					if (objDay.options[0].value == "")
						blnRequired = false;

					while (objDay.options.length)
						objDay.options[objDay.options.length - 1] = null;
					if (!blnRequired)
						objDay.options[0] = new Option("--", "");
					for (var intDay = 1; intDay <= intNewMaxDay; intDay++) {
						objDay.options[objDay.options.length] = new Option(intDay, intDay);
					};
					
					intCurrentDay = Math.min(intCurrentDay, intNewMaxDay);
					
					if (blnRequired)
						objDay.options[intCurrentDay - 1].selected = true;
					else
						objDay.options[intCurrentDay].selected = true;
				};
			};
		};
	};