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
/////////////////////////////////////////////
// Control: Dialog Box functionality
/////////////////////////////////////////////

	qcodo.registerDialogBox = function(mixControl, strMatteColor, intMatteOpacity, blnMatteClickable, blnAnyKeyCloses) {
		// Initialize the Event Handler
		qcodo.handleEvent();

		// Get Control/Wrapper
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
		var objWrapper = objControl.wrapper;

		// Ensure we have only one
		objWrapper.id = objControl.id + "_ctls";
		while (objOldDialogWrapper = document.getElementById(objControl.id + "_ctl")) {
			objOldDialogWrapper.parentNode.removeChild(objOldDialogWrapper);
		};
		objWrapper.id = objControl.id + "_ctl";

		// DialogBox MUST be at the "top level" in the DOM, a direct child of the FORM
		document.getElementById(document.getElementById("Qform__FormId").value).appendChild(objWrapper);

		// Setup the DialogBoxBackground (DbBg) if applicable
		objWrapper.dbBg = document.getElementById(objWrapper.id + "dbbg");
		var objDbBg = objWrapper.dbBg;

		if (!objDbBg) {
			var objDbBg = document.createElement("div");
			objDbBg.id = objWrapper.id + "dbbg";
			document.getElementById(document.getElementById("Qform__FormId").value).appendChild(objDbBg);

			// Setup the Object Links
			objWrapper.dbBg = objDbBg;
			objDbBg.wrapper = objWrapper;

			if (qcodo.isBrowser(qcodo.IE)) {
				var objIframe = document.createElement("iframe");
				objIframe.id = objWrapper.id + "dbbgframe";
				objIframe.style.left = "0px";
				objIframe.style.top = "0px";
				objIframe.style.position = "absolute";
				objIframe.style.filter = "alpha(opacity=0)";
				objIframe.src = "javascript: false;";
				objIframe.frameBorder = 0;
				objIframe.scrolling = "no";
				objIframe.style.zIndex = 990;
				objIframe.display = "none";
				document.getElementById(document.getElementById("Qform__FormId").value).appendChild(objIframe);
				objWrapper.dbBgFrame = objIframe;
			};
		};

		objWrapper.handleResize = function(objEvent) {
			objEvent = qcodo.handleEvent(objEvent);
			if (objEvent.target && objEvent.target.nodeName) {
				if ((objEvent.target.nodeName.toLowerCase() == 'div') || (objEvent.target.nodeName.toLowerCase() == 'span'))
					return;
			};

			// Restore from Link
			var objWrapper = qcodo.activeDialogBox;
			var objDbBg = objWrapper.dbBg;
			var objDbBgFrame = objWrapper.dbBgFrame;

			// Hide Everything
			objWrapper.style.display = "none";
			objDbBg.style.display = "none";
			if (objDbBgFrame) objDbBgFrame.style.display = "none";

			// Setup Events
			qcodo.handleEvent(objEvent);

			// Show Everything
			objWrapper.style.display = "inline";
			objDbBg.style.display = "block";
			if (objDbBgFrame) objDbBgFrame.style.display = "block";

			// DbBg Re-Setup
			objDbBg.style.width = Math.max(qcodo.page.width, qcodo.client.width) + "px";
			objDbBg.style.height = Math.max(qcodo.page.height, qcodo.client.height) + "px";
			if (objDbBgFrame) {
				objDbBgFrame.style.width = Math.max(qcodo.page.width, qcodo.client.width) + "px";
				objDbBgFrame.style.height = Math.max(qcodo.page.height, qcodo.client.height) + "px";
			};

			// Wrapper Re-Setup
			var intWidth = objWrapper.offsetWidth;
			var intHeight = objWrapper.offsetHeight;
			var intTop = Math.round((qcodo.client.height - intHeight) / 2) + qcodo.scroll.y;
			var intLeft = Math.round((qcodo.client.width - intWidth) / 2) + qcodo.scroll.x;
			objWrapper.setAbsolutePosition(intLeft, intTop);

			return true;
		};

		objWrapper.handleKeyPress = function(objEvent) {
			objEvent = qcodo.handleEvent(objEvent);
			qcodo.terminateEvent(objEvent);
			var objWrapper = qcodo.activeDialogBox;
			objWrapper.hideDialogBox();

			return false;
		};

		objWrapper.showDialogBox = function() {
			// Restore from Object Link
			var objDbBg = this.dbBg;
			var objDbBgFrame = this.dbBgFrame;

			// Hide Everything
			objWrapper.style.display = "none";
			objDbBg.style.display = "none";
			if (objDbBgFrame) objDbBgFrame.style.display = "none";

			// Setup Events
			qcodo.handleEvent();

			// Show Everything
			objDbBg.style.display = "block";
			if (objDbBgFrame) objDbBgFrame.style.display = "block";
			this.toggleDisplay("show");

			// DbBg Re-Setup
			objDbBg.style.width = Math.max(qcodo.page.width, qcodo.client.width) + "px";
			objDbBg.style.height = Math.max(qcodo.page.height, qcodo.client.height) + "px";
			if (objDbBgFrame) {
				objDbBgFrame.style.width = Math.max(qcodo.page.width, qcodo.client.width) + "px";
				objDbBgFrame.style.height = Math.max(qcodo.page.height, qcodo.client.height) + "px";
			};

			// Wrapper Re-Setup
			var intWidth = objWrapper.offsetWidth;
			var intHeight = objWrapper.offsetHeight;
			var intTop = Math.round((qcodo.client.height - intHeight) / 2) + qcodo.scroll.y;
			var intLeft = Math.round((qcodo.client.width - intWidth) / 2) + qcodo.scroll.x;
			objWrapper.setAbsolutePosition(intLeft, intTop);

			// Set Window OnResize Handling
			if((navigator.userAgent.match(/iPhone/i)) ||
			(navigator.userAgent.match(/iPod/i)) ||
			(navigator.userAgent.match(/iPad/i))) {
			return ;
			} else {
			window.onresize = this.handleResize;
			window.onscroll = this.handleResize;
			qcodo.activeDialogBox = this;
			};

			// If we have blnMatteClickable and blnAnyKeyCloses
			if (objWrapper.anyKeyCloses) {
				document.body.onkeypress = this.handleKeyPress;
				objWrapper.control.focus();
			};
		};

		objWrapper.hideDialogBox = function() {
			var objWrapper = this;
			if (this.id.indexOf("_ctldbbg") > 0)
				objWrapper = this.wrapper;
			objWrapper.dbBg.style.display = "none";
			if (objWrapper.dbBgFrame) objWrapper.dbBgFrame.style.display = "none";
			objWrapper.toggleDisplay("hide");

			// Unsetup OnResize Handling
			window.onresize = null;
			window.onscroll = null;

			// Unsetup KeyPress Closing
			document.body.onkeypress = null;

			// Unsetup ActiveDialogBox
			qcodo.activeDialogBox = null;
		};

		// Initial Wrapper Setup
		objWrapper.style.zIndex = 999;
		objWrapper.position = "absolute";
		objWrapper.anyKeyCloses = blnAnyKeyCloses;

		// Initial DbBg Setup
		objDbBg.style.position = "absolute";
		objDbBg.style.zIndex = 998;
		objDbBg.style.top = "0px";
		objDbBg.style.left = "0px";
		if (qcodo.isBrowser(qcodo.IE))
			objDbBg.style.overflow = "auto";
		else
			objDbBg.style.overflow = "hidden";

		if (blnMatteClickable) {
			objDbBg.style.cursor = "pointer";
			objDbBg.onclick = objWrapper.hideDialogBox;
		} else {
			objDbBg.style.cursor = "url(" + qc.imageAssets + "/_core/move_nodrop.cur), auto";
			objDbBg.onclick = null;
		};

		// Background Color and Opacity
		objDbBg.style.backgroundColor = strMatteColor;
		if (qcodo.isBrowser(qcodo.IE))
			objDbBg.style.filter = "alpha(opacity=" + intMatteOpacity + ")";
		else
			objDbBg.style.opacity = intMatteOpacity / 100.0;

		// Other Random Stuff
		objDbBg.style.fontSize = "1px";
		objDbBg.innerHTML = "&nbsp;";

		// Perform a Show or Hide (depending on state)
		if (objWrapper.style.display == 'none')
			objWrapper.hideDialogBox();
		else
			objWrapper.showDialogBox();
	};


//////////////////
// Qcodo Shortcuts
//////////////////

	qc.regDB = qcodo.registerDialogBox;