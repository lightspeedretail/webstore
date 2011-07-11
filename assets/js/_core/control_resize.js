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
// Block Control: Resize Handle functionality
/////////////////////////////////////////////

	qcodo.registerControlResizeHandle = function(mixControl, blnVertical) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
		var objWrapper = objControl.wrapper;

		objWrapper.resizeHandle = true;
		objWrapper.resizeDirectionVertical = blnVertical;
		objWrapper.resizeUpperControls = new Array();
		objWrapper.resizeLowerControls = new Array();

		if (!objWrapper.handle) {
			if (qcodo.isBrowser(qcodo.SAFARI))
				qcodo.registerControlHandle(objControl, 'move');
			else if (qcodo.isBrowser(qcodo.IE)) {
				if (objWrapper.resizeDirectionVertical)
					qcodo.registerControlHandle(objControl, 'row-resize');
				else
					qcodo.registerControlHandle(objControl, 'col-resize');
			} else {
				if (objWrapper.resizeDirectionVertical)
					qcodo.registerControlHandle(objControl, 'ns-resize');
				else
					qcodo.registerControlHandle(objControl, 'ew-resize');
			};

			// Assign Event Handlers
			qcodo.enableMouseDrag();

			objWrapper.handleMouseDown = function(objEvent, objHandle) {
				this.startUpperSizes = new Array();
				this.startLowerSizes = new Array();
				this.startLowerPositions = new Array();
	
				if (this.resizeDirectionVertical) {
					this.offsetY = qcodo.page.y - this.getAbsolutePosition().y;
					this.startDragY = qcodo.page.y;
	
					for (var intIndex = 0; intIndex < this.resizeUpperControls.length; intIndex++) {
						var objUpperControl = this.resizeUpperControls[intIndex];
						this.startUpperSizes[intIndex] = eval(objUpperControl.control.style.height.replace(/px/, ""));
					};
	
					for (var intIndex = 0; intIndex < this.resizeLowerControls.length; intIndex++) {
						var objLowerControl = this.resizeLowerControls[intIndex];
						this.startLowerPositions[intIndex] = objLowerControl.getAbsolutePosition().y;
						this.startLowerSizes[intIndex] = eval(objLowerControl.control.style.height.replace(/px/, ""));
					};
	
					if (this.resizeMinimum != null)
						this.resizeMinimumY = this.getAbsolutePosition().y - (this.offsetTop - this.resizeMinimum);
					else
						this.resizeMinimumY = null;

					if (this.resizeMaximum != null)
						this.resizeMaximumY = this.getAbsolutePosition().y - (this.offsetTop - this.resizeMaximum);
					else
						this.resizeMaximumY = null;
				} else {
					this.offsetX = qcodo.page.x - this.getAbsolutePosition().x;
					this.startDragX = qcodo.page.x;
	
					for (var intIndex = 0; intIndex < this.resizeUpperControls.length; intIndex++) {
						var objUpperControl = this.resizeUpperControls[intIndex];
						this.startUpperSizes[intIndex] = eval(objUpperControl.control.style.width.replace(/px/, ""));
					};

					for (var intIndex = 0; intIndex < this.resizeLowerControls.length; intIndex++) {
						var objLowerControl = this.resizeLowerControls[intIndex];
						this.startLowerPositions[intIndex] = objLowerControl.getAbsolutePosition().x;
						this.startLowerSizes[intIndex] = eval(objLowerControl.control.style.width.replace(/px/, ""));
					};

					if (this.resizeMinimum != null)
						this.resizeMinimumX = this.getAbsolutePosition().x - (this.offsetLeft - this.resizeMinimum);
					else
						this.resizeMinimumX = null;

					if (this.resizeMaximum != null)
						this.resizeMaximumX = this.getAbsolutePosition().x - (this.offsetLeft - this.resizeMaximum);
					else
						this.resizeMaximumX = null;
				};

				return qcodo.terminateEvent(objEvent);
			};
	
			objWrapper.handleMouseMove = function(objEvent, objHandle) {
				if (this.resizeDirectionVertical) {
					var intNewY = qcodo.page.y - this.offsetY;
	
					if (this.resizeMinimumY != null)
						intNewY = Math.max(intNewY, this.resizeMinimumY);
					if (this.resizeMaximumY != null)
						intNewY = Math.min(intNewY, this.resizeMaximumY);
					var intDeltaY = intNewY - this.startDragY + this.offsetY;
	
					// Update ResizeHandle's Position
					this.setAbsolutePosition(this.getAbsolutePosition().x, intNewY);
	
					// Resize Upper Controls
					for (var intIndex = 0; intIndex < this.resizeUpperControls.length; intIndex++) {
						var objUpperControl = this.resizeUpperControls[intIndex];
						objUpperControl.updateStyle("height", this.startUpperSizes[intIndex] + intDeltaY + "px");
					};
	
					// Reposition Lower Controls
					for (var intIndex = 0; intIndex < this.resizeLowerControls.length; intIndex++) {
						var objLowerControl = this.resizeLowerControls[intIndex];
						objLowerControl.setAbsolutePosition(
							objLowerControl.getAbsolutePosition().x,
							this.startLowerPositions[intIndex] + intDeltaY);
						objLowerControl.updateStyle("height", this.startLowerSizes[intIndex] - intDeltaY + "px");
					};
				} else {
					var intNewX = qcodo.page.x - this.offsetX;
	
					if (this.resizeMinimumX != null)
						intNewX = Math.max(intNewX, this.resizeMinimumX);
					if (this.resizeMaximumX != null)
						intNewX = Math.min(intNewX, this.resizeMaximumX);
					var intDeltaX = intNewX - this.startDragX + this.offsetX;
	
					// Update ResizeHandle's Position
					this.setAbsolutePosition(intNewX, this.getAbsolutePosition().y);
	
					// Resize Upper Controls
					for (var intIndex = 0; intIndex < this.resizeUpperControls.length; intIndex++) {
						var objUpperControl = this.resizeUpperControls[intIndex];
						objUpperControl.updateStyle("width", this.startUpperSizes[intIndex] + intDeltaX + "px");
					};
	
					// Reposition Lower Controls
					for (var intIndex = 0; intIndex < this.resizeLowerControls.length; intIndex++) {
						var objLowerControl = this.resizeLowerControls[intIndex];
						objLowerControl.setAbsolutePosition(
							this.startLowerPositions[intIndex] + intDeltaX,
							objLowerControl.getAbsolutePosition().y);
						objLowerControl.updateStyle("width", this.startLowerSizes[intIndex] - intDeltaX + "px");
					};
				};
	
				// Update Handle Position
				this.updateHandle(false);
	
				return qcodo.terminateEvent(objEvent);
			};
	
			objWrapper.handleMouseUp = function(objEvent, objHandle) {
				// See if we've even resized at all
				var blnResized = true;
				if (this.resizeDirectionVertical) {
					if (this.startDragY == qcodo.page.y)
						blnResized = false;
				} else {
					if (this.startDragX == qcodo.page.x)
						blnResized = false;
				};

				if (blnResized) {
					this.updateHandle(true);

					// Setup OnResize (if applicable)
					if (this.control.getAttribute("onqcodoresize")) {
							this.control.qcodoresize = function(strOnResizeCommand) {
								eval(strOnResizeCommand);
							};

							this.control.qcodoresize(this.control.getAttribute("onqcodoresize"));
					};

					return qcodo.terminateEvent(objEvent);
				} else {
					// If we haven't resized at all, go ahead and run the control's onclick method
					// (if applicable) or just propogate the click up
					if (this.control.onclick)
						return this.control.onclick(objEvent);
					else
						return true;
				};
			};

			objWrapper.setUpperControl = function(mixControl) {
				var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
				var objWrapper = objControl.wrapper;
	
				this.resizeUpperControls[this.resizeUpperControls.length] = objWrapper;
			};
	
			objWrapper.setLowerControl = function(mixControl) {
				var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
				var objWrapper = objControl.wrapper;
	
				this.resizeLowerControls[this.resizeLowerControls.length] = objWrapper;
			};
	
			objWrapper.resizeMinimum = null;
			objWrapper.resizeMaximum = null;
	
			objWrapper.setResizeMinimum = function(intMinimum) {
				this.resizeMinimum = intMinimum;
			};
	
			objWrapper.setResizeMaximum = function(intMaximum) {
				this.resizeMaximum = intMaximum;
			};
	
			// Wrapper Shortcuts
			objWrapper.setUC = objWrapper.setUpperControl;
			objWrapper.setLC = objWrapper.setLowerControl;
			objWrapper.setReMi = objWrapper.setResizeMinimum;
			objWrapper.setReMa = objWrapper.setResizeMaximum;
		} else {
			objWrapper.updateHandle();
		};
	};



//////////////////
// Qcodo Shortcuts
//////////////////

	qc.regCRH = qcodo.registerControlResizeHandle;
