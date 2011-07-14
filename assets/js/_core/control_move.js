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
// Control: Moveable functionality
/////////////////////////////////////////////

	qcodo.registerControlMoveable = function(mixControl) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
		var objWrapper = objControl.wrapper;

		objWrapper.moveable = true;
		
		// Control Handle and Mask
		objWrapper.mask = qcodo.getControl(objWrapper.id + "mask");
		if (!objWrapper.mask) {
			var objSpanElement = document.createElement('span');
			objSpanElement.id = objWrapper.id + "mask";
			
			// PAT MOD -- appending thhe mask span element to the body, rather than some random location
			// while defaulting to old location under the Qform__FormId on failure.
			// Note: must wait until page is fully loaded to do this, so ensure it's called on ready
			jQuery(document).ready(function(){

				objSpanElement.style.position = "absolute";
				var bodyEl = jQuery('body');
				if (! bodyEl) {
					bodyEl = jQuery('#' + document.getElementById("Qform__FormId").value);
				}

				objSpanJEl = jQuery(objSpanElement);

				bodyEl.append(objSpanJEl);
			 });

			objWrapper.mask = objSpanElement;
		};
		objWrapper.mask.wrapper = objWrapper;

		// Setup Mask
		objMask = jQuery(objWrapper.mask);
		objMask.css('position', 'absolute');
		objMask.css('z-index', '998');
		objMask.css('overflow', 'visible');
		objMask.attr('class', 'hohoho');
		if (qcodo.isBrowser(qcodo.IE))
		{
			objMask.css('filter', "alpha(opacity=50)");
		} else {
			objMask.css('opacity', "0.5");
		}
		objMask.hide();
		objMask.html('');

		objMask.handleAnimateComplete = function(mixControl) {
			jQuery(this).hide();
		};
	};

		// Update Absolutely-positioned children on Scroller (if applicable)
		// to fix Firefox b/c firefox uses position:absolute incorrectly
/*			if (qcodo.isBrowser(qcodo.FIREFOX) && (objControl.style.overflow == "auto"))
			objControl.onscroll = function(objEvent) {
				objEvent = qcodo.handleEvent(objEvent);
				for (var intIndex = 0; intIndex < this.childNodes.length; intIndex++) {
					if ((this.childNodes[intIndex].style) && (this.childNodes[intIndex].style.position == "absolute")) {
						if (!this.childNodes[intIndex].originalX) {
							this.childNodes[intIndex].originalX = this.childNodes[intIndex].offsetLeft;
							this.childNodes[intIndex].originalY = this.childNodes[intIndex].offsetTop;
						};

						this.childNodes[intIndex].style.left = this.childNodes[intIndex].originalX - this.scrollLeft + "px";
						this.childNodes[intIndex].style.top = this.childNodes[intIndex].originalY - this.scrollTop + "px";
					};
				};
			};*/



///////////////////////////////////////////////
// Block Control: DropZone Target Functionality
///////////////////////////////////////////////

	qcodo.registerControlDropZoneTarget = function(mixControl) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
		var objWrapper = objControl.wrapper;

		// Control Handle and Mask
		objWrapper.dropZoneMask = qcodo.getControl(objWrapper.id + "dzmask");
		if (!objWrapper.dropZoneMask) {
			//<span id="%s_ctldzmask" style="position:absolute;"><span style="font-size: 1px">&nbsp;</span></span>
			var objSpanElement = document.createElement("span");
			objSpanElement.id = objWrapper.id + "dzmask";
			objSpanElement.style.position = "absolute";

			var objInnerSpanElement = document.createElement("span");
			objInnerSpanElement.style.fontSize = "1px";
			objInnerSpanElement.innerHTML = "&nbsp;";

			objSpanElement.appendChild(objInnerSpanElement);
			
			document.getElementById(document.getElementById("Qform__FormId").value).appendChild(objSpanElement);
			objWrapper.dropZoneMask = objSpanElement;

			objWrapper.dropZoneMask.wrapper = objWrapper;

			// Setup Mask
			objMask = objWrapper.dropZoneMask;
			objMask.style.position = "absolute";
			objMask.style.top = "0px";
			objMask.style.left = "0px";
			objMask.style.borderColor = "#bb3399";
			objMask.style.borderStyle = "solid";
			objMask.style.borderWidth = "3px";
			objMask.style.display = "none";
		};
		
		objWrapper.addToDropZoneGrouping = function(strGroupingId, blnAllowSelf, blnAllowSelfParent) {
			if (!qcodo.dropZoneGrouping[strGroupingId])
				qcodo.dropZoneGrouping[strGroupingId] = new Array();
			qcodo.dropZoneGrouping[strGroupingId][this.control.id] = this;
			qcodo.dropZoneGrouping[strGroupingId]["__allowSelf"] = (blnAllowSelf) ? true : false;
			qcodo.dropZoneGrouping[strGroupingId]["__allowSelfParent"] = (blnAllowSelfParent) ? true : false;

			qcodo.registerControlDropZoneTarget(this.control);
		};

		objWrapper.removeFromDropZoneGrouping = function(strGroupingId) {
			if (!qcodo.dropZoneGrouping[strGroupingId])
				qcodo.dropZoneGrouping[strGroupingId] = new Array();
			else
				qcodo.dropZoneGrouping[strGroupingId][this.control.id] = false;
		};

		// Qcodo Shortcuts
		objWrapper.a2DZG = objWrapper.addToDropZoneGrouping;
		objWrapper.rfDZG = objWrapper.removeFromDropZoneGrouping;
	};



///////////////////////////////////
// Block Control: DropZone Grouping
///////////////////////////////////

	qcodo.dropZoneGrouping = new Array();



///////////////////////////////////////////
// Block Control: Move Handle Functionality
///////////////////////////////////////////

	qcodo.registerControlMoveHandle = function(mixControl) {				
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
		var objWrapper = objControl.wrapper;

		if (!objWrapper.handle) {
			qcodo.registerControlHandle(objControl, 'move');

			// Assign Event Handlers
			qcodo.enableMouseDrag();

			objWrapper.handleMouseDown = function(objEvent, objHandle) {
				// Set the Handle's MoveControls Bounding Box
				this.setupBoundingBox();

				// Calculate the offset (the top-left page coordinates of the bounding box vs. where the mouse is on the page)
				this.offsetX = qcodo.page.x - this.boundingBox.x;
				this.offsetY = qcodo.page.y - this.boundingBox.y;
				this.startDragX = qcodo.page.x;
				this.startDragY = qcodo.page.y;

				// Clear MaskReturn Timeout (if applicable)
				if (qcodo.moveHandleReset)
					qcodo.moveHandleReset.resetMasksCancel();

				// Make the Masks appear (if applicable)
				for (var strKey in this.moveControls) {
					var objMoveControl = this.moveControls[strKey];
					if (qcodo.isBrowser(qcodo.IE))
					{
						// PAT MOD for IE stupidity... forcing the class name to change, gets rid of the product_image_cell margin 'auto' setting that 
						// was causing so many issues with positioning...
						objMoveControl.updateStyle("className", 'xsilva_moving');
					}
					var objMask = jQuery(objMoveControl.mask);

					var objAbsolutePosition = objMoveControl.getAbsolutePosition();

					objMask.css('top', "" + objAbsolutePosition.y + "px");
					objMask.css('left', "" + objAbsolutePosition.x + "px");
					objMask.html('');
					objMask.fadeIn('fast');
				};

				return qcodo.terminateEvent(objEvent);
			};


			objWrapper.handleMouseMove = function(objEvent, objHandle) {
				// Do We Scroll?
				if ((qcodo.client.x <= 30) || (qcodo.client.y >= (qcodo.client.height - 30)) ||
					(qcodo.client.y <= 30) || (qcodo.client.x >= (qcodo.client.width - 30))) {
					qcodo.scrollMoveHandle = this;
					qcodo.handleScroll();
				} else {
					// Clear Handle Timeout
					qcodo.clearTimeout(objWrapper.id);

					this.moveMasks();
				};

				return qcodo.terminateEvent(objEvent);
			};


			objWrapper.handleMouseUp = function(objEvent, objHandle) {
				// Calculate Move Delta
				var objMoveDelta = this.calculateMoveDelta();
				var intDeltaX = objMoveDelta.x;
				var intDeltaY = objMoveDelta.y;

				// Stop Scrolling
				qcodo.clearTimeout(this.id);

				// Validate Drop Zone
				var objDropControl;

				if ((intDeltaX == 0) && (intDeltaY == 0)) {
					// Nothing Moved!
					objDropControl = null;
				} else {
					objDropControl = this.getDropTarget();
				};

				if (objDropControl) {
					// Update everything that's moving (e.g. all controls in qcodo.moveControls)
					for (var strKey in this.moveControls) {
						var objWrapper = this.moveControls[strKey];
						var objMask = objWrapper.mask;

						objMask.style.display = "none";
						objMask.style.cursor = null;
//						qcodo.moveControls[strKey] = null;

						objWrapper.updateStyle("position", "absolute");

						// Get Control's Position
						var objAbsolutePosition = objWrapper.getAbsolutePosition();

						// Update Parent -- Wrapper now belongs to a new DropControl
						if (objDropControl.nodeName.toLowerCase() == 'form') {
							if (objWrapper.parentNode != objDropControl)
								objWrapper.updateStyle("parent", objDropControl.id);
						} else {
							if (objDropControl.id != objWrapper.parentNode.parentNode.id)
								objWrapper.updateStyle("parent", objDropControl.control.id);
						};

						// Update Control's Position
						objWrapper.setAbsolutePosition(objAbsolutePosition.x + intDeltaX, objAbsolutePosition.y + intDeltaY, true);

						if (objWrapper.updateHandle)
							objWrapper.updateHandle(true, "move");

						// Setup OnMove (if applicable)
						if (objWrapper.control.getAttribute("onqcodomove")) {
							objWrapper.control.qcodomove = function(strOnMoveCommand) {
								eval(strOnMoveCommand);
							};
							objWrapper.control.qcodomove(objWrapper.control.getAttribute("onqcodomove"));
						};
					};
				} else {
					// Rejected
					for (var strKey in this.moveControls) {
						var objWrapper = this.moveControls[strKey];
						var objMask = objWrapper.mask;

						objMask.style.cursor = null;
					};

					if (objWrapper.updateHandle)
						objWrapper.updateHandle(false, "move");

					if (qcodo.isBrowser(qcodo.IE))
						this.resetMasks(intDeltaX, intDeltaY, 25);
					else
						this.resetMasks(intDeltaX, intDeltaY, 50);
				};

				// If we haven't moved at all, go ahead and run the control's onclick method
				// (if applicable) or just propogate the click up
				if ((intDeltaX == 0) && (intDeltaY == 0)) {
					if (this.control.onclick)
						return this.control.onclick(objEvent);
					else
						return true;
				} else {
					return qcodo.terminateEvent(objEvent);
				};
			};

			// Setup Move Targets
			objWrapper.moveControls = new Object();

			objWrapper.registerMoveTarget = function(mixControl) {
				// If they pass in null, then register itself as the move target
				if (mixControl == null) mixControl = this.control;

				var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
				var objTargetWrapper = objControl.wrapper;

				if (objTargetWrapper)
					this.moveControls[objControl.id] = objTargetWrapper;
//				this.registerDropZone(objTargetWrapper.parentNode);
			};

			objWrapper.unregisterMoveTarget = function(mixControl) {
				var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

				if (objControl.id)
					this.moveControls[objControl.id] = null;
			};

			objWrapper.clearMoveTargets = function() {
				this.moveControls = new Object();
			};

			// Setup Drop Zones
			objWrapper.registerDropZone = function(mixControl) {
				var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

				if (objControl.wrapper) {
					qcodo.registerControlDropZoneTarget(objControl);
					this.dropControls[objControl.id] = objControl.wrapper;
				} else
					this.dropControls[objControl.id] = objControl;
			};

			objWrapper.unregisterDropZone = function(mixControl) {
				var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

				this.dropControls[objControl.id] = null;
			};

			objWrapper.clearDropZones = function() {
				this.dropControls = new Object();
			};

			objWrapper.clearDropZones();
			
			objWrapper.registerDropZoneGrouping = function(strGroupingId) {
				if (!qcodo.dropZoneGrouping[strGroupingId])
					qcodo.dropZoneGrouping[strGroupingId] = new Array();
				this.dropGroupings[strGroupingId] = true;
			};

			objWrapper.clearDropZoneGroupings = function() {
				this.dropGroupings = new Object();
			};
			objWrapper.clearDropZoneGroupings();

			// Mouse Delta Calculator
			objWrapper.calculateMoveDelta = function() {
				// Calculate Move Delta
				var intDeltaX = (qcodo.page.x - this.startDragX);
				var intDeltaY = qcodo.page.y - this.startDragY;


				// PAT MOD ... for some reason, we need to blowup mouse movement for IE or else the drag
				// does not track in the x-plane.
				if (0 && qcodo.isBrowser(qcodo.IE))
				{
					// intDeltaX = intDeltaX * 2;
				} else {
					// this code also fails with IE, so it's here for other more decent browsers...
					intDeltaX = Math.min(Math.max(intDeltaX, -1 * this.boundingBox.x), qcodo.page.width - this.boundingBox.boundX);
					intDeltaY = Math.min(Math.max(intDeltaY, -1 * this.boundingBox.y), qcodo.page.height - this.boundingBox.boundY);
				}
				return {x: intDeltaX, y: intDeltaY};
			};

			objWrapper.setupBoundingBox = function() {
				// Calculate moveControls aggregate bounding box (x,y,width,height,boundX,boundY)
				// Note that boundX is just (x + width), and boundY is just (y + height)
				var intMinX = null;
				var intMinY = null;
				var intMaxX = null;
				var intMaxY = null;
				for (var strKey in this.moveControls) {
					var objMoveControl = this.moveControls[strKey];
					var objAbsolutePosition = objMoveControl.getAbsolutePosition();
					if (intMinX == null) {
						intMinX = objAbsolutePosition.x;
						intMinY = objAbsolutePosition.y;
						intMaxX = objAbsolutePosition.x + objMoveControl.offsetWidth;
						intMaxY = objAbsolutePosition.y + objMoveControl.offsetHeight;
					} else {
						intMinX = Math.min(intMinX, objAbsolutePosition.x);
						intMinY = Math.min(intMinY, objAbsolutePosition.y);
						intMaxX = Math.max(intMaxX, objAbsolutePosition.x + objMoveControl.offsetWidth);
						intMaxY = Math.max(intMaxY, objAbsolutePosition.y + objMoveControl.offsetHeight);
					};
				};

				if (!this.boundingBox)
					this.boundingBox = new Object();

				this.boundingBox.x = intMinX;
				this.boundingBox.y = intMinY;
				this.boundingBox.boundX = intMaxX;
				this.boundingBox.boundY = intMaxY;
				this.boundingBox.width = intMaxX - intMinX;
				this.boundingBox.height = intMaxY - intMinY;
			};

			objWrapper.updateBoundingBox = function() {
				// Just like SETUP BoundingBox, except now we're using the MASKS instead of the Controls
				// (in case, becuase of hte move, the size of the control may have changed/been altered)
				var intMinX = null;
				var intMinY = null;
				var intMaxX = null;
				var intMaxY = null;
				for (var strKey in this.moveControls) {
					var objMoveControl = this.moveControls[strKey];
					var objAbsolutePosition = objMoveControl.getAbsolutePosition();

					var offsetWidth = objMoveControl.mask.offsetWidth;
					// PAT MOD -- using the mask offsetWidth with IE causes unhappiness, perhaps because it is invisible but I dunno... but the move control's offset works ok.
					if (qcodo.isBrowser(qcodo.IE))
					{
						offsetWidth = objMoveControl.offsetWidth;
					}
						
					
					if (intMinX == null) {
						intMinX = objAbsolutePosition.x;
						intMinY = objAbsolutePosition.y;
						intMaxX = objAbsolutePosition.x + offsetWidth;
						intMaxY = objAbsolutePosition.y + objMoveControl.mask.offsetHeight;
					} else {
						intMinX = Math.min(intMinX, objAbsolutePosition.x);
						intMinY = Math.min(intMinY, objAbsolutePosition.y);
						intMaxX = Math.max(intMaxX, objAbsolutePosition.x + offsetWidth);
						intMaxY = Math.max(intMaxY, objAbsolutePosition.y + objMoveControl.mask.offsetHeight);
					};
				};

				this.boundingBox.x = intMinX;
				this.boundingBox.y = intMinY;
				this.boundingBox.boundX = intMaxX;
				this.boundingBox.boundY = intMaxY;
				this.boundingBox.width = intMaxX - intMinX;
				this.boundingBox.height = intMaxY - intMinY;
			};

			objWrapper.moveMasks = function() {
				// Calculate Move Delta
				var objMoveDelta = this.calculateMoveDelta();
				var intDeltaX = objMoveDelta.x;
				var intDeltaY = objMoveDelta.y;

				var blnValidDropZone = this.validateDropZone();
				if (blnValidDropZone)
					this.handle.style.cursor = "url(" + qc.imageAssets + "/_core/move_drop.cur), auto";
				else
					this.handle.style.cursor = "url(" + qc.imageAssets + "/_core/move_nodrop.cur), auto";

				// Update Everything that's Moving (e.g. all controls in qcodo.moveControls)
				for (var strKey in this.moveControls) {
					var objWrapper = this.moveControls[strKey];
					var objMask = objWrapper.mask;

					// Fixes a weird Firefox bug
					if (objMask.innerHTML == "")
						objMask.innerHTML = ".";
					if (objMask.innerHTML == ".")
						objMask.innerHTML = objWrapper.innerHTML.replace(' id="', ' id="invalid_mask_');

					objMaskJEl = jQuery(objMask);
					// Recalculate Widths
					this.updateBoundingBox();

					// Move this control's mask
					objWrapper.setMaskOffset(intDeltaX, intDeltaY);

					if (blnValidDropZone) {
						objMask.style.cursor = "url(" + qc.imageAssets + "/_core/move_drop.cur), auto";
					} else {
						objMask.style.cursor = "url(" + qc.imageAssets + "/_core/move_nodrop.cur), auto";
					};
				};
			};

			objWrapper.getDropZoneControlWrappers = function() {
				var arrayToReturn = new Array();
				
				for (var strDropKey in this.dropControls) {
					var objDropWrapper = this.dropControls[strDropKey];
					if (objDropWrapper)
						arrayToReturn[strDropKey] = objDropWrapper;
				};
				
				for (var strGroupingId in this.dropGroupings) {
					if (this.dropGroupings[strGroupingId]) for (var strControlId in qcodo.dropZoneGrouping[strGroupingId]) {
						if (strControlId.substring(0, 1) != "_") {
							var objDropWrapper = qcodo.dropZoneGrouping[strGroupingId][strControlId];
							if (objDropWrapper) {
								if (objDropWrapper.control.id == objWrapper.control.id) {
									if (qcodo.dropZoneGrouping[strGroupingId]["__allowSelf"])
										arrayToReturn[strControlId] = objDropWrapper;
								} else if (objDropWrapper.control.id == objWrapper.parentNode.id) {
									if (qcodo.dropZoneGrouping[strGroupingId]["__allowSelfParent"])
										arrayToReturn[strControlId] = objDropWrapper;
								} else {
									arrayToReturn[strControlId] = objDropWrapper;
								};
							};
						};
					};
				};
				return arrayToReturn;
			};

			objWrapper.validateDropZone = function() {
				var blnFoundTarget = false;
				var blnFormOkay = false;
				var dropControls = this.getDropZoneControlWrappers();

				for (var strDropKey in dropControls) {
					var objDropWrapper = dropControls[strDropKey];
					if (objDropWrapper) {
						if (objDropWrapper.nodeName.toLowerCase() == 'form') {
							blnFormOkay = true;
						} else if (objDropWrapper.containsPoint(qcodo.page.x, qcodo.page.y)) {
							if (blnFoundTarget) {
								objDropWrapper.dropZoneMask.style.display = "none";
							} else {
								objDropWrapper.dropZoneMask.style.display = "block";
								var objAbsolutePosition = objDropWrapper.getAbsolutePosition();
								if (qcodo.isBrowser(qcodo.IE) && (window.document.compatMode == "BackCompat")) {
									objDropWrapper.dropZoneMask.style.width = Math.max(7, objDropWrapper.control.offsetWidth) + "px";
									objDropWrapper.dropZoneMask.style.height = Math.max(7, objDropWrapper.control.offsetHeight) + "px";

//										if (objDropWrapper.style.position == 'absolute') {
										var objAbsolutePosition = objDropWrapper.getAbsolutePosition();
//											objDropWrapper.setDropZoneMaskAbsolutePosition(objAbsolutePosition.x + 10, objAbsolutePosition.y + 10);
										objDropWrapper.setDropZoneMaskAbsolutePosition(objAbsolutePosition.x, objAbsolutePosition.y);
//										};
								} else {
									objDropWrapper.dropZoneMask.style.width = Math.max(1, objDropWrapper.control.offsetWidth - 6) + "px";
									objDropWrapper.dropZoneMask.style.height = Math.max(1, objDropWrapper.control.offsetHeight - 6) + "px";

//										if (objDropWrapper.style.position != 'absolute') {
										var objAbsolutePosition = objDropWrapper.getAbsolutePosition();
										objDropWrapper.setDropZoneMaskAbsolutePosition(objAbsolutePosition.x, objAbsolutePosition.y);
//										};
								};
								blnFoundTarget = true;
							};
						} else {
							objDropWrapper.dropZoneMask.style.display = "none";
						};
					};
				};

				return (blnFoundTarget || blnFormOkay);
			};

			// Will return "NULL" if there was no target found
			// Could also return the Form if not dropped on any valid target BUT tbe Form is still a drop zone
			objWrapper.getDropTarget = function() {
				var objForm = null;
				var objToReturn = null;
				
				var dropControls = this.getDropZoneControlWrappers();
				
				for (var strDropKey in dropControls) {
					var objDropWrapper = dropControls[strDropKey];
					if (objDropWrapper) {
						if (objDropWrapper.nodeName.toLowerCase() == 'form')
							objForm = objDropWrapper;
						else if (objDropWrapper.containsPoint(qcodo.page.x, qcodo.page.y)) {
							objDropWrapper.dropZoneMask.style.display = "none";
							if (!objToReturn)
								objToReturn = objDropWrapper;
						};
					};
				};

				if (objToReturn)
					return objToReturn;

				if (objForm)
					return objForm;

				return null;
			};

			objWrapper.resetMasks = function(intDeltaX, intDeltaY, intSpeed) {
				qcodo.moveHandleReset = this;

				if (intDeltaX || intDeltaY) {
					this.resetCurrentOffsetX = intDeltaX * 1.0;
					this.resetCurrentOffsetY = intDeltaY * 1.0;

					var fltTotalMove = Math.sqrt(Math.pow(intDeltaX, 2) + Math.pow(intDeltaY, 2));
					var fltRatio = (intSpeed * 1.0) / fltTotalMove;
					this.resetStepX = fltRatio * intDeltaX;
					this.resetStepY = fltRatio * intDeltaY;
					
					qcodo.setTimeout("move_mask_return", "qcodo.wrappers['" + this.id + "'].resetMaskHelper()", 10);
				};
			};

			objWrapper.resetMaskHelper = function() {
				if (this.resetCurrentOffsetX < 0)
					this.resetCurrentOffsetX = Math.min(this.resetCurrentOffsetX - this.resetStepX, 0);
				else
					this.resetCurrentOffsetX = Math.max(this.resetCurrentOffsetX - this.resetStepX, 0);

				if (this.resetCurrentOffsetY < 0)
					this.resetCurrentOffsetY = Math.min(this.resetCurrentOffsetY - this.resetStepY, 0);
				else
					this.resetCurrentOffsetY = Math.max(this.resetCurrentOffsetY - this.resetStepY, 0);

				for (var strKey in this.moveControls) {
					var objWrapper = this.moveControls[strKey];
					objWrapper.setMaskOffset(this.resetCurrentOffsetX, this.resetCurrentOffsetY);

					if ((this.resetCurrentOffsetX == 0) && (this.resetCurrentOffsetY == 0)) {
						objWrapper.mask.style.display = "none";
					};
				};

				if ((this.resetCurrentOffsetX != 0) || (this.resetCurrentOffsetY != 0))
					qcodo.setTimeout("move_mask_return", "qcodo.wrappers['" + this.id + "'].resetMaskHelper()", 10);
				else
					qcodo.moveHandleReset = null;
			};

			objWrapper.resetMasksCancel = function() {
				qcodo.clearTimeout("move_mask_return");
				qcodo.moveHandleReset = null;
				for (var strKey in this.moveControls) {
					var objWrapper = this.moveControls[strKey];
					objWrapper.mask.style.display = "none";
				};
			};
			
			// Wrapper Shortcuts
			objWrapper.regMT = objWrapper.registerMoveTarget;
			objWrapper.regDZ = objWrapper.registerDropZone;			
			objWrapper.regDZG = objWrapper.registerDropZoneGrouping;
		} else {
			objWrapper.updateHandle();
		};
	};

	qcodo.animateMove = function(mixControl, intDestinationX, intDestinationY, intSpeed) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

		// Record Destination Coordinates
		objControl.destinationX = intDestinationX;
		objControl.destinationY = intDestinationY;

		// Get Starting Coordinates
		var objAbsolutePosition = qcodo.getAbsolutePosition(objControl);
		objControl.currentX = objAbsolutePosition.x * 1.0;
		objControl.currentY = objAbsolutePosition.y * 1.0;

		// Calculate the amount to move in the X- and Y- direction per step
		var fltTotalMove = Math.sqrt(Math.pow(objControl.destinationY - objControl.currentY, 2) + Math.pow(objControl.destinationX - objControl.currentX, 2));
		var fltTotalMoveX = (objControl.destinationX * 1.0) - objControl.currentX;
		var fltTotalMoveY = (objControl.destinationY * 1.0) - objControl.currentY;
		objControl.stepMoveX = ((intSpeed * 1.0) / fltTotalMove) * fltTotalMoveX;
		objControl.stepMoveY = ((intSpeed * 1.0) / fltTotalMove) * fltTotalMoveY;

		qcodo.setTimeout(objControl, "qcodo.handleAnimateMove('" + objControl.id + "');", 10);
	};

	qcodo.handleAnimateMove = function(mixControl) {
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

		// Update Current Coordinates
		if (objControl.stepMoveX < 0)
			objControl.currentX = Math.max(objControl.destinationX, objControl.currentX + objControl.stepMoveX);
		else
			objControl.currentX = Math.min(objControl.destinationX, objControl.currentX + objControl.stepMoveX);

		if (objControl.stepMoveY < 0)
			objControl.currentY = Math.max(objControl.destinationY, objControl.currentY + objControl.stepMoveY);
		else
			objControl.currentY = Math.min(objControl.destinationY, objControl.currentY + objControl.stepMoveY);

		qcodo.setAbsolutePosition(objControl, Math.round(objControl.currentX), Math.round(objControl.currentY));
		
		if ((Math.round(objControl.currentX) == objControl.destinationX) &&
			(Math.round(objControl.currentY) == objControl.destinationY)) {
			// We are done
			
			if (objControl.handleAnimateComplete)
				objControl.handleAnimateComplete(objControl);
		} else {
			// Do it again
			qcodo.setTimeout(objControl, "qcodo.handleAnimateMove('" + objControl.id + "');", 10);
		};
	};

	qcodo.handleScroll = function() {
		var objHandle = qcodo.scrollMoveHandle;

		// Clear Timeout
		qcodo.clearTimeout(objHandle.id);

		// How much to scroll by
		var intScrollByX = 0;
		var intScrollByY = 0;

		// Calculate our ScrollByY amount
		if (qcodo.client.y <= 30) {
			var intDivisor = (qcodo.isBrowser(qcodo.IE)) ? 1.5 : 3;
			intScrollByY = Math.round((qcodo.client.y - 30) / intDivisor);
		} else if (qcodo.client.y >= (qcodo.client.height - 30)) {
			var intDivisor = (qcodo.isBrowser(qcodo.IE)) ? 1.5 : 3;
			intScrollByY = Math.round((qcodo.client.y - (qcodo.client.height - 30)) / intDivisor);
		};

		// Calculate our ScrollByX amount
		if (qcodo.client.x <= 30) {
			var intDivisor = (qcodo.isBrowser(qcodo.IE)) ? 1 : 2;
			intScrollByX = Math.round((qcodo.client.x - 30) / intDivisor);
		} else if (qcodo.client.x >= (qcodo.client.width - 30)) {
			var intDivisor = (qcodo.isBrowser(qcodo.IE)) ? 1 : 2;
			intScrollByX = Math.round((qcodo.client.x - (qcodo.client.width - 30)) / intDivisor);
		};

		// Limit ScrollBy amounts (dependent on current scroll and scroll.max's)
		if (intScrollByX < 0) {
			// Scroll to Left
			intScrollByX = Math.max(intScrollByX, 0 - qcodo.scroll.x);
		} else if (intScrollByX > 0) {
			// Scroll to Right
			intScrollByX = Math.min(intScrollByX, qcodo.scroll.width - qcodo.scroll.x);
		};
		if (intScrollByY < 0) {
			// Scroll to Left
			intScrollByY = Math.max(intScrollByY, 0 - qcodo.scroll.y);
		} else if (intScrollByY > 0) {
			// Scroll to Right
			intScrollByY = Math.min(intScrollByY, qcodo.scroll.height - qcodo.scroll.y);
		};

		// Perform the Scroll
		window.scrollBy(intScrollByX, intScrollByY);

		// Update Event Stats
		qcodo.handleEvent(null);

		// Update Handle Offset
		objHandle.offsetX -= intScrollByX;
		objHandle.offsetY -= intScrollByY;

		objHandle.moveMasks();
		if (intScrollByX || intScrollByY)
			qcodo.setTimeout(objHandle.id, "qcodo.handleScroll()", 25);
	};



//////////////////
// Qcodo Shortcuts
//////////////////

	qc.regCM = qcodo.registerControlMoveable;
	qc.regCMH = qcodo.registerControlMoveHandle;
