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
function treenavToggleImage(strControlId) {
	var blnShow = treenavToggleDisplay(strControlId + "_sub", null, "block");
	
	var objImage = document.getElementById(strControlId + "_image");
	var strPath = qc.imageAssets + "/treenav_expanded.png";
	var strPathNotExpanded = qc.imageAssets + "/treenav_not_expanded.png";

	if (blnShow)
		objImage.src = strPath;
	else
		objImage.src = strPathNotExpanded;

	strActualControlId = strControlId.substr(0, strControlId.indexOf('_'));
	qcodo.recordControlModification(strActualControlId, 'ItemExpanded',  strControlId + ((blnShow) ? ' 1' : ' 0'));
};

function treenavToggleDisplay(mixControl, strShowOrHide, strDisplayStyle) {
	// Toggles the display/hiding of the entire control (including any design/wrapper HTML)
	// If ShowOrHide is blank, then we toggle
	// Otherwise, we'll execute a "show" or a "hide"
	var objControl; if (!(objControl = qcodo.getControl(mixControl))) return;

	if (strShowOrHide) {
		if (strShowOrHide == "show") {
			objControl.style.display = strDisplayStyle;
			return true;
		} else {
			objControl.style.display = "none";
			return false;
		};
	} else {
		if (objControl.style.display == "none") {
			objControl.style.display = strDisplayStyle;
			return true;
		} else {
			objControl.style.display = "none";
			return false;
		};
	};
};

function treenavItemUnselect(strControlId, strStyleName) {
	var objControl = document.getElementById(strControlId);
	objControl.className = strStyleName;
	objControl.onmouseout = function() {treenavItemSetStyle(strControlId, strStyleName);};
};

function treenavItemSetStyle(strControlId, strStyleName) {
	var objControl = document.getElementById(strControlId);
	objControl.className = strStyleName;
};

function treenavRedrawElement(strElementId, strHtml) {
	document.getElementById(strElementId).innerHTML = strHtml;
};