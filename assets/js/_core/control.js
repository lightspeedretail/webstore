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
/////////////////////////////////
// Controls-related functionality
/////////////////////////////////

	qcodo.getControl = function(mixControl) {
		if (typeof(mixControl) == 'string')
			return document.getElementById(mixControl);
		else
			return mixControl;
	};

	qcodo.getWrapper = function(mixControl) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

		if (objControl)
			return this.getControl(objControl.id + "_ctl");			
		else
			return null;
	};



/////////////////////////////
// Register Control - General
/////////////////////////////
	
	qcodo.controlModifications = new Array();
	qcodo.javascriptStyleToQcodo = new Array();
	qcodo.javascriptStyleToQcodo["backgroundColor"] = "BackColor";
	qcodo.javascriptStyleToQcodo["borderColor"] = "BorderColor";
	qcodo.javascriptStyleToQcodo["borderStyle"] = "BorderStyle";
	qcodo.javascriptStyleToQcodo["border"] = "BorderWidth";
	qcodo.javascriptStyleToQcodo["height"] = "Height";
	qcodo.javascriptStyleToQcodo["width"] = "Width";
	qcodo.javascriptStyleToQcodo["text"] = "Text";

	qcodo.javascriptWrapperStyleToQcodo = new Array();
	qcodo.javascriptWrapperStyleToQcodo["position"] = "Position";
	qcodo.javascriptWrapperStyleToQcodo["top"] = "Top";
	qcodo.javascriptWrapperStyleToQcodo["left"] = "Left";

	qcodo.recordControlModification = function(strControlId, strProperty, strNewValue) {
		if (!qcodo.controlModifications[strControlId])
			qcodo.controlModifications[strControlId] = new Array();
		qcodo.controlModifications[strControlId][strProperty] = strNewValue;	
	};

	qcodo.registerControl = function(mixControl) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

		// Link the Wrapper and the Control together
		var objWrapper = this.getWrapper(objControl);
		objControl.wrapper = objWrapper;
		objWrapper.control = objControl;

		// Add the wrapper to the global qcodo wrappers array
		qcodo.wrappers[objWrapper.id] = objWrapper;


		// Create New Methods, etc.
		// Like: objWrapper.something = xyz;

		// Updating Style-related Things
		objWrapper.updateStyle = function(strStyleName, strNewValue) {
			var objControl = this.control;
			
			switch (strStyleName) {
				case "className":
					objControl.className = strNewValue;
					qcodo.recordControlModification(objControl.id, "CssClass", strNewValue);
					break;
					
				case "parent":
					if (strNewValue) {
						var objNewParentControl = qcodo.getControl(strNewValue);
						objNewParentControl.appendChild(this);
						qcodo.recordControlModification(objControl.id, "Parent", strNewValue);
					} else {
						var objParentControl = this.parentNode;
						objParentControl.removeChild(this);
						qcodo.recordControlModification(objControl.id, "Parent", "");
					};
					break;
				
				case "displayStyle":
					objControl.style.display = strNewValue;
					qcodo.recordControlModification(objControl.id, "DisplayStyle", strNewValue);
					break;

				case "display":
					if (strNewValue) {
						objWrapper.style.display = "inline";
						qcodo.recordControlModification(objControl.id, "Display", "1");
					} else {
						objWrapper.style.display = "none";
						qcodo.recordControlModification(objControl.id, "Display", "0");
					};
					break;

				case "enabled":
					if (strNewValue) {
						objWrapper.control.disabled = false;
						qcodo.recordControlModification(objControl.id, "Enabled", "1");
					} else {
						objWrapper.control.disabled = true;
						qcodo.recordControlModification(objControl.id, "Enabled", "0");
					};
					break;
					
				case "width":
				case "height":
					objControl.style[strStyleName] = strNewValue;
					if (qcodo.javascriptStyleToQcodo[strStyleName])
						qcodo.recordControlModification(objControl.id, qcodo.javascriptStyleToQcodo[strStyleName], strNewValue);
					if (objWrapper.handle)
						objWrapper.updateHandle();
					break;

				case "text":
					objControl.innerHTML = strNewValue;
					qcodo.recordControlModification(objControl.id, "Text", strNewValue);
					break;

				default:
					if (qcodo.javascriptWrapperStyleToQcodo[strStyleName]) {
						this.style[strStyleName] = strNewValue;
						qcodo.recordControlModification(objControl.id, qcodo.javascriptWrapperStyleToQcodo[strStyleName], strNewValue);
					} else {
						objControl.style[strStyleName] = strNewValue;
						if (qcodo.javascriptStyleToQcodo[strStyleName])
							qcodo.recordControlModification(objControl.id, qcodo.javascriptStyleToQcodo[strStyleName], strNewValue);
					};
					break;
			};
		};

		// Positioning-related functions

		objWrapper.getAbsolutePosition = function() {
			var intOffsetLeft = 0;
			var intOffsetTop = 0;

			var objControl = this.control;

			while (objControl) {
				// If we are IE, we don't want to include calculating
				// controls who's wrappers are position:relative
				if ((objControl.wrapper) && (objControl.wrapper.style.position == "relative")) {
					
				} else {
					intOffsetLeft += objControl.offsetLeft;
					intOffsetTop += objControl.offsetTop;
				};
				objControl = objControl.offsetParent;
			};

			return {x:intOffsetLeft, y:intOffsetTop};
		};

		objWrapper.setAbsolutePosition = function(intNewX, intNewY, blnBindToParent) {
			var objControl = this.offsetParent;

			while (objControl) {
				intNewX -= objControl.offsetLeft;
				intNewY -= objControl.offsetTop;
				objControl = objControl.offsetParent;
			};

			if (blnBindToParent) {
				if (this.parentNode.nodeName.toLowerCase() != 'form') {
					// intNewX and intNewY must be within the parent's control
					intNewX = Math.max(intNewX, 0);
					intNewY = Math.max(intNewY, 0);

					intNewX = Math.min(intNewX, this.offsetParent.offsetWidth - this.offsetWidth);
					intNewY = Math.min(intNewY, this.offsetParent.offsetHeight - this.offsetHeight);
				};
			};

			this.updateStyle("left", intNewX + "px");
			this.updateStyle("top", intNewY + "px");
		};

		objWrapper.setDropZoneMaskAbsolutePosition = function(intNewX, intNewY, blnBindToParent) {
/*
			var objControl = this.offsetParent;

			while (objControl) {
				intNewX -= objControl.offsetLeft;
				intNewY -= objControl.offsetTop;
				objControl = objControl.offsetParent;
			};

			if (blnBindToParent) {
				if (this.parentNode.nodeName.toLowerCase() != 'form') {
					// intNewX and intNewY must be within the parent's control
					intNewX = Math.max(intNewX, 0);
					intNewY = Math.max(intNewY, 0);

					intNewX = Math.min(intNewX, this.offsetParent.offsetWidth - this.offsetWidth);
					intNewY = Math.min(intNewY, this.offsetParent.offsetHeight - this.offsetHeight);
				};
			};
			
			qc.logObject(intNewX + " x " + intNewY);
*/
			this.dropZoneMask.style.left = intNewX + "px";
			this.dropZoneMask.style.top = intNewY + "px";
		};

		objWrapper.setMaskOffset = function(intDeltaX, intDeltaY) {
			var objAbsolutePosition = this.getAbsolutePosition();
			this.mask.style.left = (objAbsolutePosition.x + intDeltaX)  + "px";
			this.mask.style.top = (objAbsolutePosition.y + intDeltaY) + "px";
		};

		objWrapper.containsPoint = function(intX, intY) {
			var objAbsolutePosition = this.getAbsolutePosition();
			if ((intX >= objAbsolutePosition.x) && (intX <= objAbsolutePosition.x + this.control.offsetWidth) &&
				(intY >= objAbsolutePosition.y) && (intY <= objAbsolutePosition.y + this.control.offsetHeight))
				return true;
			else
				return false;
		};

		// Toggle Display / Enabled
		objWrapper.toggleDisplay = function(strShowOrHide) {
			// Toggles the display/hiding of the entire control (including any design/wrapper HTML)
			// If ShowOrHide is blank, then we toggle
			// Otherwise, we'll execute a "show" or a "hide"
			if (strShowOrHide) {
				if (strShowOrHide == "show")
					this.updateStyle("display", true);
				else
					this.updateStyle("display", false);
			} else
				this.updateStyle("display", (this.style.display == "none") ? true : false);
		};

		objWrapper.toggleEnabled = function(strEnableOrDisable) {
			if (strEnableOrDisable) {
				if (strEnableOrDisable == "enable")
					this.updateStyle("enabled", true);
				else
					this.updateStyle("enabled", false);
			} else
				this.updateStyle("enabled", (this.control.disabled) ? true : false);
		};

		objWrapper.registerClickPosition = function(objEvent) {
			objEvent = (objEvent) ? objEvent : ((typeof(event) == "object") ? event : null);
			qcodo.handleEvent(objEvent);

			var intX = qcodo.mouse.x - this.getAbsolutePosition().x + qcodo.scroll.x;
			var intY = qcodo.mouse.y - this.getAbsolutePosition().y + qcodo.scroll.y;

			// Random IE Check
			if (qcodo.isBrowser(qcodo.IE)) {
				intX = intX - 2;
				intY = intY - 2;
			};

			document.getElementById(this.control.id + "_x").value = intX;
			document.getElementById(this.control.id + "_y").value = intY;
		};

		// Focus
		objWrapper.focus = function() {
			if (this.control.focus) {
				if (qcodo.isBrowser(qcodo.IE) && (typeof (this.control.focus) == "object"))
					this.control.focus();
				else if (typeof (this.control.focus) == "function")
					this.control.focus();
			};
		};

		// Blur
		objWrapper.blur = function() {
			if (this.control.blur) {
				if (qcodo.isBrowser(qcodo.IE) && (typeof (this.control.blur) == "object"))
					this.control.blur();
				else if (typeof (this.control.blur) == "function")
					this.control.blur();
			};
		};

		// Select All (will only work for textboxes only)
		objWrapper.select = function() {
			if (this.control.select)
				this.control.select();
		};

		// Blink
		objWrapper.blink = function(strFromColor, strToColor) {
			objWrapper.defaultBackgroundColor = objWrapper.control.style.backgroundColor;

			objWrapper.blinkStart = qcodo.colorRgbValues(strFromColor);
			objWrapper.blinkEnd = qcodo.colorRgbValues(strToColor);
			objWrapper.blinkStep = new Array(
				Math.round((objWrapper.blinkEnd[0] - objWrapper.blinkStart[0]) / 12.5),
				Math.round((objWrapper.blinkEnd[1] - objWrapper.blinkStart[1]) / 12.5),
				Math.round((objWrapper.blinkEnd[2] - objWrapper.blinkStart[2]) / 12.5)
			);
			objWrapper.blinkDown = new Array(
				(objWrapper.blinkStep[0] < 0) ? true : false,
				(objWrapper.blinkStep[1] < 0) ? true : false,
				(objWrapper.blinkStep[2] < 0) ? true : false
			);

			objWrapper.blinkCurrent = objWrapper.blinkStart;
			this.control.style.backgroundColor = qcodo.colorRgbString(objWrapper.blinkCurrent);
			qcodo.setTimeout(objWrapper.id, "qc.getC('" + objWrapper.id + "').blinkHelper()", 20);
		};

		objWrapper.blinkHelper = function() {
			objWrapper.blinkCurrent[0] += objWrapper.blinkStep[0];
			objWrapper.blinkCurrent[1] += objWrapper.blinkStep[1];
			objWrapper.blinkCurrent[2] += objWrapper.blinkStep[2];
			if (((objWrapper.blinkDown[0]) && (objWrapper.blinkCurrent[0] < objWrapper.blinkEnd[0])) ||
				((!objWrapper.blinkDown[0]) && (objWrapper.blinkCurrent[0] > objWrapper.blinkEnd[0])))
				objWrapper.blinkCurrent[0] = objWrapper.blinkEnd[0];
			if (((objWrapper.blinkDown[1]) && (objWrapper.blinkCurrent[1] < objWrapper.blinkEnd[1])) ||
				((!objWrapper.blinkDown[1]) && (objWrapper.blinkCurrent[1] > objWrapper.blinkEnd[1])))
				objWrapper.blinkCurrent[1] = objWrapper.blinkEnd[1];
			if (((objWrapper.blinkDown[2]) && (objWrapper.blinkCurrent[2] < objWrapper.blinkEnd[2])) ||
				((!objWrapper.blinkDown[2]) && (objWrapper.blinkCurrent[2] > objWrapper.blinkEnd[2])))
				objWrapper.blinkCurrent[2] = objWrapper.blinkEnd[2];

			this.control.style.backgroundColor = qcodo.colorRgbString(objWrapper.blinkCurrent);

			if ((objWrapper.blinkCurrent[0] == objWrapper.blinkEnd[0]) &&
				(objWrapper.blinkCurrent[1] == objWrapper.blinkEnd[1]) &&
				(objWrapper.blinkCurrent[2] == objWrapper.blinkEnd[2])) {
				// Done with Blink!
				this.control.style.backgroundColor = objWrapper.defaultBackgroundColor;
			} else {
				qcodo.setTimeout(objWrapper.id, "qc.getC('" + objWrapper.id + "').blinkHelper()", 20);
			};
		};
	};

	qcodo.registerControlArray = function(mixControlArray) {
		var intLength = mixControlArray.length;
		for (var intIndex = 0; intIndex < intLength; intIndex++)
			qcodo.registerControl(mixControlArray[intIndex]);
	};



//////////////////
// Qcodo Shortcuts
//////////////////

	qc.getC = qcodo.getControl;
	qc.getW = qcodo.getWrapper;
	qc.regC = qcodo.registerControl;
	qc.regCA = qcodo.registerControlArray;
