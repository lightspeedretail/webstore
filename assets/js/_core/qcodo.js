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
///////////////////////////////////////////////////
// The Qcodo Object is used for everything in Qcodo
///////////////////////////////////////////////////

function xsilvadebug(msg)
{
	
	var el = document.getElementById('footer');
	el.innerHTML = el.innerHTML + msg + "<br />";
}

	var qcodo = {
		initialize: function() {

		////////////////////////////////
		// Browser-related functionality
		////////////////////////////////

			this.isBrowser = function(intBrowserType) {
				return (intBrowserType & qcodo._intBrowserType);
			};
			this.IE = 1;
			this.IE_6_0 = 2;
			this.IE_7_0 = 4;
			this.IE_8_0 = 8;

			this.FIREFOX = 16;
			this.FIREFOX_1_0 = 32;
			this.FIREFOX_1_5 = 64;
			this.FIREFOX_2_0 = 128;
			this.FIREFOX_3_0 = 256;
			this.FIREFOX_3_5 = 512;

			this.SAFARI = 1024;
			this.SAFARI_2_0 = 2048;
			this.SAFARI_3_0 = 4096;
			this.SAFARI_4_0 = 8192;

			this.CHROME = 16384;
			this.CHROME_2_0 = 32768;
			this.CHROME_3_0 = 65536;
			this.CHROME_4_0 = 131072;

			this.MACINTOSH = 262144;

			this.UNSUPPORTED = 524288;

			// INTERNET EXPLORER (supporting versions 6.0, 7.0 and 8.0)
			if (navigator.userAgent.toLowerCase().indexOf("msie") >= 0) {
				this._intBrowserType = this.IE;

				if (navigator.userAgent.toLowerCase().indexOf("msie 6.0") >= 0)
					this._intBrowserType = this._intBrowserType | this.IE_6_0;
				else if (navigator.userAgent.toLowerCase().indexOf("msie 7.0") >= 0)
					this._intBrowserType = this._intBrowserType | this.IE_7_0;
				else if (navigator.userAgent.toLowerCase().indexOf("msie 8.0") >= 0)
					this._intBrowserType = this._intBrowserType | this.IE_7_0;
				else
					this._intBrowserType = this._intBrowserType | this.UNSUPPORTED;

			// FIREFOX (supporting versions 1.0, 1.5, 2.0, 3.0 and 3.5)
			} else if ((navigator.userAgent.toLowerCase().indexOf("firefox") >= 0) || (navigator.userAgent.toLowerCase().indexOf("iceweasel") >= 0)) {
				this._intBrowserType = this.FIREFOX;
				var strUserAgent = navigator.userAgent.toLowerCase();
				strUserAgent = strUserAgent.replace('iceweasel/', 'firefox/');

				if (strUserAgent.indexOf("firefox/1.0") >= 0)
					this._intBrowserType = this._intBrowserType | this.FIREFOX_1_0;
				else if (strUserAgent.indexOf("firefox/1.5") >= 0)
					this._intBrowserType = this._intBrowserType | this.FIREFOX_1_5;
				else if (strUserAgent.indexOf("firefox/2.0") >= 0)
					this._intBrowserType = this._intBrowserType | this.FIREFOX_2_0;
				else if (strUserAgent.indexOf("firefox/3.0") >= 0)
					this._intBrowserType = this._intBrowserType | this.FIREFOX_3_0;
				else if (strUserAgent.indexOf("firefox/3.5") >= 0)
					this._intBrowserType = this._intBrowserType | this.FIREFOX_3_5;
				else
					this._intBrowserType = this._intBrowserType | this.UNSUPPORTED;

			// CHROME (not yet supported)
			} else if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
				this._intBrowserType = this.CHROME;
				this._intBrowserType = this._intBrowserType | this.UNSUPPORTED;

				if (navigator.userAgent.toLowerCase().indexOf("chrome/2.") >= 0)
					this._intBrowserType = this._intBrowserType | this.CHROME_2_0;
				else if (navigator.userAgent.toLowerCase().indexOf("chrome/3.") >= 0)
					this._intBrowserType = this._intBrowserType | this.CHROME_3_0;
				else if (navigator.userAgent.toLowerCase().indexOf("chrome/4.") >= 0)
					this._intBrowserType = this._intBrowserType | this.CHROME_4_0;
				else
					this._intBrowserType = this._intBrowserType | this.UNSUPPORTED;

			// SAFARI (supporting version 2.0, 3.0 and 4.0)
			} else if (navigator.userAgent.toLowerCase().indexOf("safari") >= 0) {
				this._intBrowserType = this.SAFARI;
				
				if (navigator.userAgent.toLowerCase().indexOf("safari/41") >= 0)
					this._intBrowserType = this._intBrowserType | this.SAFARI_2_0;
				else if (navigator.userAgent.toLowerCase().indexOf("version/3.") >= 0)
					this._intBrowserType = this._intBrowserType | this.SAFARI_3_0;
				else if (navigator.userAgent.toLowerCase().indexOf("version/4.") >= 0)
					this._intBrowserType = this._intBrowserType | this.SAFARI_4_0;
				else
					this._intBrowserType = this._intBrowserType | this.UNSUPPORTED;

			// COMPLETELY UNSUPPORTED
			} else
				this._intBrowserType = this.UNSUPPORTED;

			// MACINTOSH?
			if (navigator.userAgent.toLowerCase().indexOf("macintosh") >= 0)
				this._intBrowserType = this._intBrowserType | this.MACINTOSH;



		////////////////////////////////
		// Browser-related functionality
		////////////////////////////////

			this.loadJavaScriptFile = function(strScript, objCallback) {
				strScript = qc.jsAssets + "/" + strScript;
				var objNewScriptInclude = document.createElement("script");
				objNewScriptInclude.setAttribute("type", "text/javascript");
				objNewScriptInclude.setAttribute("src", strScript);
				document.getElementById(document.getElementById("Qform__FormId").value).appendChild(objNewScriptInclude);

				// IE does things differently...
				if (qc.isBrowser(qcodo.IE)) {
					objNewScriptInclude.callOnLoad = objCallback;
					objNewScriptInclude.onreadystatechange = function() {
						if ((this.readyState == "complete") || (this.readyState == "loaded"))
							if (this.callOnLoad)
								this.callOnLoad();
					};

				// ... than everyone else
				} else {
					objNewScriptInclude.onload = objCallback;
				};
			};

			this.loadStyleSheetFile = function(strStyleSheetFile, strMediaType) {
				strStyleSheetFile = qc.cssAssets + "/" + strStyleSheetFile;

				// IE does things differently...
				if (qc.isBrowser(qcodo.IE)) {
					var objNewScriptInclude = document.createStyleSheet(strStyleSheetFile);

				// ...than everyone else
				} else {
					var objNewScriptInclude = document.createElement("style");
					objNewScriptInclude.setAttribute("type", "text/css");
					objNewScriptInclude.setAttribute("media", strMediaType);
					objNewScriptInclude.innerHTML = '@import "' + strStyleSheetFile + '";';
					document.body.appendChild(objNewScriptInclude);
				};
			};



		/////////////////////////////
		// QForm-related functionality
		/////////////////////////////

			this.registerForm = function() {
				// "Lookup" the QForm's FormId
				var strFormId = document.getElementById("Qform__FormId").value;

				// Register the Various Hidden Form Elements needed for QForms
				this.registerFormHiddenElement("Qform__FormControl", strFormId);
				this.registerFormHiddenElement("Qform__FormEvent", strFormId);
				this.registerFormHiddenElement("Qform__FormParameter", strFormId);
				this.registerFormHiddenElement("Qform__FormCallType", strFormId);
				this.registerFormHiddenElement("Qform__FormUpdates", strFormId);
				this.registerFormHiddenElement("Qform__FormCheckableControls", strFormId);
			};

			this.registerFormHiddenElement = function(strId, mixForm) {
				var objForm;
				if (typeof(mixForm) == 'string')
					objForm = document.getElementById(mixForm);
				else
					objForm = mixForm;

				var objHiddenElement = document.createElement("input");
				objHiddenElement.type = "hidden";
				objHiddenElement.id = strId;
				objHiddenElement.name = strId;
				objForm.appendChild(objHiddenElement);
			};

			this.wrappers = new Array();



		////////////////////////////////////
		// URL Hash Processing
		////////////////////////////////////
			this.processHashCurrent = null;
			this.processHash = function(strControlId) {
				// Get the Hash Value
				var strUrl = new String(document.location);

				// Only Proceed if it's different than before
				if (qc.processHashCurrent != strUrl.toString()) {
					// Update the stored current hash stuff
					qc.processHashCurrent = strUrl.toString();

					// Get Info Needed for the Control Proxy call
					var strFormId = document.getElementById("Qform__FormId").value;

					// Figure out the Hash data
					var intPosition = strUrl.indexOf('#');
					var strHashData = "";

					if (intPosition > 0)
						strHashData = strUrl.substring(intPosition + 1);

					// Make the callback
					qc.pA(strFormId, strControlId, 'QClickEvent', strHashData, null);
				};
			};



		////////////////////////////////////
		// Mouse Drag Handling Functionality
		////////////////////////////////////

			this.enableMouseDrag = function() {
				document.onmousedown = qcodo.handleMouseDown;
				document.onmousemove = qcodo.handleMouseMove;
				document.onmouseup = qcodo.handleMouseUp;
			};

			this.handleMouseDown = function(objEvent) {
				objEvent = qcodo.handleEvent(objEvent);

				var objHandle = qcodo.target;
				if (!objHandle) return true;

				var objWrapper = objHandle.wrapper;
				if (!objWrapper) return true;

				// Qcodo-Wide Mouse Handling Functions only operate on the Left Mouse Button
				// (Control-specific events can respond to QRightMouse-based Events)
				if (qcodo.mouse.left) {
					qcodo.mouse.left = true;
					if (objWrapper.handleMouseDown) {
						// Specifically for Microsoft IE
						if (objHandle.setCapture)
							objHandle.setCapture();

						// Ensure the Cleanliness of Dragging
						objHandle.onmouseout = null;
						if (document.selection)
							document.selection.empty();

						qcodo.currentMouseHandleControl = objWrapper;
						return objWrapper.handleMouseDown(objEvent, objHandle);
					};
				};

				qcodo.currentMouseHandleControl = null;
				return true;
			};

			this.handleMouseMove = function(objEvent) {
				objEvent = qcodo.handleEvent(objEvent);

				if (qcodo.currentMouseHandleControl) {
					var objWrapper = qcodo.currentMouseHandleControl;
					var objHandle = objWrapper.handle;

					// In case IE accidentally marks a selection...
					if (document.selection)
						document.selection.empty();

					if (objWrapper.handleMouseMove)
						return objWrapper.handleMouseMove(objEvent, objHandle);
				};

				return true;
			};

			this.handleMouseUp = function(objEvent) {
				objEvent = qcodo.handleEvent(objEvent);

				if (qcodo.currentMouseHandleControl) {
					var objWrapper = qcodo.currentMouseHandleControl;
					var objHandle = objWrapper.handle;

					// In case IE accidentally marks a selection...
					if (document.selection)
						document.selection.empty();

					// For IE to release release/setCapture
					if (objHandle.releaseCapture) {
						objHandle.releaseCapture();
						objHandle.onmouseout = function() {this.releaseCapture()};
					};

					qcodo.currentMouseHandleControl = null;

					if (objWrapper.handleMouseUp)
						return objWrapper.handleMouseUp(objEvent, objHandle);
				};

				return true;
			};



		////////////////////////////////////
		// Window Unloading
		////////////////////////////////////

			this.unloadFlag = false;
			this.handleUnload = function() {
				qcodo.unloadFlag = true;
			};
			window.onunload= this.handleUnload;

			this.beforeUnloadFlag = false;
			this.handleBeforeUnload = function() {
				qcodo.beforeUnloadFlag = true;
			};
			window.onbeforeunload= this.handleBeforeUnload;



		////////////////////////////////////
		// Color Handling Functionality
		////////////////////////////////////

			this.colorRgbValues = function(strColor) {
				strColor = strColor.replace("#", "");

				try {
					if (strColor.length == 3)
						return new Array(
							eval("0x" + strColor.substring(0, 1)),
							eval("0x" + strColor.substring(1, 2)),
							eval("0x" + strColor.substring(2, 3))
						);
					else if (strColor.length == 6)
						return new Array(
							eval("0x" + strColor.substring(0, 2)),
							eval("0x" + strColor.substring(2, 4)),
							eval("0x" + strColor.substring(4, 6))
						);
				} catch (Exception) {};

				return new Array(0, 0, 0);
			};

			this.hexFromInt = function(intNumber) {
				intNumber = (intNumber > 255) ? 255 : ((intNumber < 0) ? 0 : intNumber);
				intFirst = Math.floor(intNumber / 16);
				intSecond = intNumber % 16;
				return intFirst.toString(16) + intSecond.toString(16);
			};

			this.colorRgbString = function(intRgbArray) {
				return "#" + qcodo.hexFromInt(intRgbArray[0]) + qcodo.hexFromInt(intRgbArray[1]) + qcodo.hexFromInt(intRgbArray[2]);
			};
		}
	};



////////////////////////////////
// Qcodo Shortcut and Initialize
////////////////////////////////

	var qc = qcodo;
	qc.initialize();
