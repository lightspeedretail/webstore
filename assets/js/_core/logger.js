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
////////////////////////////////
// Logging-related functionality
////////////////////////////////

	qcodo.logMessage = function(strMessage, blnReset, blnNonEscape) {
		var objLogger = qcodo.getControl("Qform_Logger");

		if (!objLogger) {
			var objLogger = document.createElement("div");
			objLogger.id = "Qform_Logger";
			objLogger.style.display = "none";
			objLogger.style.width = "400px";
			objLogger.style.backgroundColor = "#dddddd";
			objLogger.style.fontSize = "10px";
			objLogger.style.fontFamily = "lucida console, courier, monospaced";
			objLogger.style.padding = "6px";
			objLogger.style.overflow = "auto";

			if (qcodo.isBrowser(qcodo.IE))
				objLogger.style.filter = "alpha(opacity=50)";
			else
				objLogger.style.opacity = 0.5;

			document.body.appendChild(objLogger);
		};

		if (!blnNonEscape)
			if (strMessage.replace)
				strMessage = strMessage.replace(/</g, '&lt;');

		var strPosition = "fixed";
		var strTop = "0px";
		var strLeft = "0px";
		if (qcodo.isBrowser(qcodo.IE)) {
			// IE doesn't support position:fixed, so manually set positioning
			strPosition = "absolute";
			strTop = qcodo.scroll.y + "px";
			strLeft = qcodo.scroll.x + "px";
		};

		objLogger.style.position = strPosition;
		objLogger.style.top = strTop;
		objLogger.style.left = strLeft;
		objLogger.style.height = (qcodo.client.height - 100) + "px";
		objLogger.style.display = 'inline';

		var strHeader = '<a href="javascript:qcodo.logRemove()">Remove</a><br/><br/>';

		if (blnReset)
			objLogger.innerHTML = strHeader + strMessage + "<br/>";
		else if (objLogger.innerHTML == "")
			objLogger.innerHTML = strHeader + strMessage + "<br/>";
		else
			objLogger.innerHTML += strMessage + "<br/>";
	};

	qcodo.logRemove = function() {
		var objLogger = qcodo.getControl('Qform_Logger');
		if (objLogger)
			objLogger.style.display = 'none';
	};

	qcodo.logEventStats = function(objEvent) {
		objEvent = qcodo.handleEvent(objEvent);

		var strMessage = "";
		strMessage += "scroll (x, y): " + qcodo.scroll.x + ", " + qcodo.scroll.y + "<br/>";
		strMessage += "scroll (width, height): " + qcodo.scroll.width + ", " + qcodo.scroll.height + "<br/>";
		strMessage += "client (x, y): " + qcodo.client.x + ", " + qcodo.client.y + "<br/>";
		strMessage += "client (width, height): " + qcodo.client.width + ", " + qcodo.client.height + "<br/>";
		strMessage += "page (x, y): " + qcodo.page.x + ", " + qcodo.page.y + "<br/>";
		strMessage += "page (width, height): " + qcodo.page.width + ", " + qcodo.page.height + "<br/>";
		strMessage += "mouse (x, y): " + qcodo.mouse.x + ", " + qcodo.mouse.y + "<br/>";
		strMessage += "mouse (left, middle, right): " + qcodo.mouse.left + ", " + qcodo.mouse.middle + ", " + qcodo.mouse.right + "<br/>";
		strMessage += "key (alt, shift, control, code): " + qcodo.key.alt + ", " + qcodo.key.shift + ", " +
			qcodo.key.control + ", " + qcodo.key.code;

		qcodo.logMessage("Event Stats", true);
		qcodo.logMessage(strMessage, false, true);
	};

	qcodo.logObject = function(objObject) {
		var strDump = "";

		for (var strKey in objObject) {
			var strData = objObject[strKey];

			strDump += strKey + ": ";
			if (typeof strData == 'function')
				strDump += "&lt;FUNCTION&gt;";
			else if (typeof strData == 'object')
				strDump += "&lt;OBJECT&gt;";
			else if ((strKey == 'outerText') || (strKey == 'innerText') || (strKey == 'outerHTML') || (strKey == 'innerHTML'))
				strDump += "&lt;TEXT&gt;";
			else
				strDump += strData;
			strDump += "<br/>";
		};

		qcodo.logMessage("Object Stats", true);
		qcodo.logMessage(strDump, false, true);
	};