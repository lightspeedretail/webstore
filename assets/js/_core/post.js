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
////////////////////////////////////////////
// PostBack and AjaxPostBack
////////////////////////////////////////////

	qcodo.postBack = function(strForm, strControl, strEvent, strParameter) {
		var objForm = document.getElementById(strForm);
		objForm.Qform__FormControl.value = strControl;
		objForm.Qform__FormEvent.value = strEvent;
		objForm.Qform__FormParameter.value = strParameter;
		objForm.Qform__FormCallType.value = "Server";
		objForm.Qform__FormUpdates.value = qcodo.formUpdates();
		objForm.Qform__FormCheckableControls.value = qcodo.formCheckableControls(strForm, "Server");
		objForm.submit();
	};

	qcodo.formUpdates = function() {
		var strToReturn = "";
		for (var strControlId in qcodo.controlModifications)
			for (var strProperty in qcodo.controlModifications[strControlId])
				strToReturn += strControlId + " " + strProperty + " " + qcodo.controlModifications[strControlId][strProperty] + "\n";
		qcodo.controlModifications = new Array();
		return strToReturn;
	};

	qcodo.formCheckableControls = function(strForm, strCallType) {
		var objForm = document.getElementById(strForm);
		var strToReturn = "";

		for (var intIndex = 0; intIndex < objForm.elements.length; intIndex++) {
			if (((objForm.elements[intIndex].type == "checkbox") ||
				 (objForm.elements[intIndex].type == "radio")) &&
				((strCallType == "Ajax") ||
				(!objForm.elements[intIndex].disabled))) {

				// CheckBoxList
				if (objForm.elements[intIndex].id.indexOf('[') >= 0) {
					if (objForm.elements[intIndex].id.indexOf('[0]') >= 0)
						strToReturn += " " + objForm.elements[intIndex].id.substring(0, objForm.elements[intIndex].id.length - 3);

				// RadioButtonList
				} else if (objForm.elements[intIndex].id.indexOf('_') >= 0) {
					if (objForm.elements[intIndex].id.indexOf('_0') >= 0)
						strToReturn += " " + objForm.elements[intIndex].id.substring(0, objForm.elements[intIndex].id.length - 2);

				// Standard Radio or Checkbox
				} else {
					strToReturn += " " + objForm.elements[intIndex].id;
				};
			};
		};

		if (strToReturn.length > 0)
			return strToReturn.substring(1);
		else
			return "";
	};

	qcodo.ajaxQueue = new Array();

	qcodo.postAjax = function(strForm, strControl, strEvent, strParameter, strWaitIconControlId) {
		// Only add if we're not unloaded
		if (!qc.unloadFlag) {
			if (qc.beforeUnloadFlag) {
				qc.beforeUnloadFlag = false;
			};

			// Figure out if Queue is Empty
			var blnQueueEmpty = false;
			if (qcodo.ajaxQueue.length == 0)
				blnQueueEmpty = true;

			// Enqueue the AJAX Request
			qcodo.ajaxQueue.push(new Array(strForm, strControl, strEvent, strParameter, strWaitIconControlId));

			// If the Queue was originally empty, call the Dequeue
			if (blnQueueEmpty)
				qcodo.dequeueAjaxQueue();
		};
	};
	
	qcodo.clearAjaxQueue = function() {
		qcodo.ajaxQueue = new Array();
	};

	qcodo.objAjaxWaitIcon = null;
	qcodo.ajaxRequest = null;

	qcodo.handleAjaxResponse = function(objEvent, objIframeResponse) {
		var objRequest;

		if (objIframeResponse || (qcodo.ajaxRequest.readyState == 4)) {
			if (objIframeResponse)
				objRequest = objIframeResponse;
			else
				objRequest = qcodo.ajaxRequest;

			if (!qcodo.beforeUnloadFlag) {
				try {
					var objXmlDoc = objRequest.responseXML;
//					qcodo.logMessage(objRequest.responseText, true);
					// alert('AJAX Response Received:' + objXmlDoc);

					if (!objXmlDoc) {
						alert("An error occurred during AJAX Response parsing.\r\n\r\nThe error response will appear in a new popup.");
						var objErrorWindow = window.open('about:blank', 'qcodo_error','menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=700,left=50,top=50');
						if (! objErrorWindow) {
							alert("Could not open a new window to display the error... are you, perchance, using a popup blocker?");
							return;
						}
						objErrorWindow.focus();
						objErrorWindow.document.write(objRequest.responseText);
						return;
					} else {
						var intLength = 0;

						// Go through Controls
						var objXmlControls = objXmlDoc.getElementsByTagName('control');
						intLength = objXmlControls.length;

						for (var intIndex = 0; intIndex < intLength; intIndex++) {
							var strControlId = objXmlControls[intIndex].attributes.getNamedItem('id').nodeValue;

							var strControlHtml = "";
							if (objXmlControls[intIndex].textContent)
								strControlHtml = objXmlControls[intIndex].textContent;
							else if (objXmlControls[intIndex].firstChild)
								strControlHtml = objXmlControls[intIndex].firstChild.nodeValue;

							// Perform Callback Responsibility
							if (strControlId == "Qform__FormState") {
								var objFormState = document.getElementById(strControlId);
								objFormState.value = strControlHtml;							
							} else {
								var objSpan = document.getElementById(strControlId + "_ctl");
								if (objSpan)
									objSpan.innerHTML = strControlHtml;
							};
						};

						// Go through Commands
						var objXmlCommands = objXmlDoc.getElementsByTagName('command');
						intLength = objXmlCommands.length;

						for (var intIndex = 0; intIndex < intLength; intIndex++) {
							if (objXmlCommands[intIndex] && objXmlCommands[intIndex].firstChild) {
								var strCommand = "";
								intChildLength = objXmlCommands[intIndex].childNodes.length;
								for (var intChildIndex = 0; intChildIndex < intChildLength; intChildIndex++)
									strCommand += objXmlCommands[intIndex].childNodes[intChildIndex].nodeValue;
								eval(strCommand);
							};
						};
					};
				} catch (objExc) {
					alert(objExc.message + "\r\non line number " + objExc.lineNumber + "\r\nin file " + objExc.fileName);
					alert("An error occurred during AJAX Response handling.\r\n\r\nThe error response will appear in a new popup.");
					var objErrorWindow = window.open('about:blank', 'qcodo_error','menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=700,left=50,top=50');
					objErrorWindow.focus();
					objErrorWindow.document.write(objRequest.responseText);
					return;
				};
			};

			// Perform the Dequeue
			qcodo.ajaxQueue.shift();

			// Hid the WaitIcon (if applicable)
			if (qcodo.objAjaxWaitIcon)
				qcodo.objAjaxWaitIcon.style.display = 'none';

			// If there are still AjaxEvents in the queue, go ahead and process/dequeue them
			if (qcodo.ajaxQueue.length > 0)
				qcodo.dequeueAjaxQueue();
		};
	};

	qcodo.dequeueAjaxQueue = function() {
		if (qcodo.ajaxQueue.length > 0) {
			strForm = this.ajaxQueue[0][0];
			strControl = this.ajaxQueue[0][1];
			strEvent = this.ajaxQueue[0][2];
			strParameter = this.ajaxQueue[0][3];
			strWaitIconControlId = this.ajaxQueue[0][4];

			// Display WaitIcon (if applicable)
			if (strWaitIconControlId) {
				this.objAjaxWaitIcon = this.getWrapper(strWaitIconControlId);
				if (this.objAjaxWaitIcon)
					this.objAjaxWaitIcon.style.display = 'inline';
			};

			var objForm = document.getElementById(strForm);
			objForm.Qform__FormControl.value = strControl;
			objForm.Qform__FormEvent.value = strEvent;
			objForm.Qform__FormParameter.value = strParameter;
			objForm.Qform__FormCallType.value = "Ajax";
			objForm.Qform__FormUpdates.value = qcodo.formUpdates();
			objForm.Qform__FormCheckableControls.value = this.formCheckableControls(strForm, "Ajax");

			var strPostData = "";
			for (var i = 0; i < objForm.elements.length; i++) {
				switch (objForm.elements[i].type) {
					case "checkbox":
					case "radio":
						if (objForm.elements[i].checked) {
							var strTestName = objForm.elements[i].name + "_";
							if (objForm.elements[i].id.substring(0, strTestName.length) == strTestName)
								strPostData += "&" + objForm.elements[i].name + "=" + objForm.elements[i].id.substring(strTestName.length);
							else
//								strPostData += "&" + objForm.elements[i].id + "=" + "1";
								strPostData += "&" + objForm.elements[i].id + "=" + objForm.elements[i].value;
						};
						break;

					case "select-multiple":
						var blnOneSelected = false;
						for (var intIndex = 0; intIndex < objForm.elements[i].options.length; intIndex++)
							if (objForm.elements[i].options[intIndex].selected) {
								strPostData += "&" + objForm.elements[i].name + "=";
								strPostData += objForm.elements[i].options[intIndex].value;
							};
						break;

					default:
						strPostData += "&" + objForm.elements[i].id + "=";

						// For Internationalization -- we must escape the element's value properly
						var strPostValue = objForm.elements[i].value;
						if (strPostValue) {
							strPostValue = strPostValue.replace(/\%/g, "%25");
							strPostValue = strPostValue.replace(/&/g, escape('&'));
							strPostValue = strPostValue.replace(/\+/g, "%2B");
						};
						strPostData += strPostValue;
						break;
				};
			};

			var strUri = objForm.action;

			var objRequest;
			if (window.XMLHttpRequest) {
				objRequest = new XMLHttpRequest();
			} else if (typeof ActiveXObject != "undefined") {
				objRequest = new ActiveXObject("Microsoft.XMLHTTP");
			};

			if (objRequest) {
				objRequest.open("POST", strUri, true);
				objRequest.setRequestHeader("Method", "POST " + strUri + " HTTP/1.1");
				objRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

				objRequest.onreadystatechange = qcodo.handleAjaxResponse;
				qcodo.ajaxRequest = objRequest;
				objRequest.send(strPostData);
			};
		};
	};



//////////////////
// Qcodo Shortcuts
//////////////////

	qc.pB = qcodo.postBack;
	qc.pA = qcodo.postAjax;
