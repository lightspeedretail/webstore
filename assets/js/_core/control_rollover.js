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

	qcodo.registerImageRollover = function(mixControl, strStandardImageSource, strHoverImageSource, blnLinkFlag) {
		// Initialize the Event Handler
		qcodo.handleEvent();

		// Get Control/Wrapper
		var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;
		var objWrapper = objControl.wrapper;

		objWrapper.standardImageSource = strStandardImageSource;
		objWrapper.hoverImageSource = strHoverImageSource;

		// Pull out the Image Element
		if (blnLinkFlag)
			objWrapper.imageElement = qcodo.getControl(objControl.id + "_img");
		else
			objWrapper.imageElement = objControl;

		// Setup the DialogBoxBackground (DbBg) if applicable
		objWrapper.handleMouseOver = function(objEvent) {
			objEvent = qcodo.handleEvent(objEvent);
			var objControl = this;
			var objWrapper = objControl.wrapper;
			var objImage = objWrapper.imageElement;

			var intWidth = objImage.width;
			var intHeight = objImage.height;

			objImage.src = objWrapper.hoverImageSource;
			objImage.width = intWidth;
			objImage.height = intHeight;
		};

		objWrapper.handleMouseOut = function(objEvent) {
			objEvent = qcodo.handleEvent(objEvent);
			var objControl = this;
			var objWrapper = objControl.wrapper;
			var objImage = objWrapper.imageElement;

			objImage.src = objWrapper.standardImageSource;
		};

		// Preload
		var objHoverImage = document.createElement("img");
		objHoverImage.src = strHoverImageSource;

		// Setup Event Handlers
		objControl.onmouseover = objWrapper.handleMouseOver;
		objControl.onmouseout = objWrapper.handleMouseOut;
	};



//////////////////
// Qcodo Shortcuts
//////////////////

	qc.regIR = qcodo.registerImageRollover;