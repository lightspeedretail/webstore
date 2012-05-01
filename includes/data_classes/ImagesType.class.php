<?php
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

abstract class ImagesType extends QBaseClass {
	const normal = 0;
	const small = 1;
	const pdetail = 2;
	const mini = 3;
	const listing = 4;
	const preview = 5;
	const slider = 6;

	const MaxId = 6;

	public static $NameArray = array(
		0 => 'image',
		1 => 'smallimage',
		2 => 'pdetailimage',
		3 => 'miniimage',
		4 => 'listingimage',
		5 => 'previewimage',
		6 => 'sliderimage'
	);

	public static $SizeArray = array(
		0 => array(0, 0), // Don't resize image
		1 => array(100, 80),
		2 => array(100, 80),
		3 => array(30, 30),
		4 => array(50, 40),
		5 => array(30, 30),
		6 => array(100, 80),
	);

	public static $ConfigKeyArray = array(
		0 => null,
		1 => 'LISTING_IMAGE',
		2 => 'DETAIL_IMAGE',
		3 => 'MINI_IMAGE',
		4 => 'LISTING_IMAGE',
		5 => 'PREVIEW_IMAGE',
		6 => 'SLIDER_IMAGE'
	);

	public static $TokenArray = array(
		0 => 'NORMAL',
		1 => 'SMALL',
		2 => 'PDETAIL',
		3 => 'MINI',
		4 => 'LISTING',
		5 => 'PREVIEW',
		6 => 'SLIDER'
		
	);

	public static function ToString($intImageTypeId) {
		return ImagesType::$NameArray[$intImageTypeId];
	}

	public static function GetSize($intImageTypeId) {
		list($intDefWidth, $intDefHeight) = 
			ImagesType::$SizeArray[$intImageTypeId];

		$strCfg = ImagesType::GetConfigKey($intImageTypeId);

		$strCfgWidth = $strCfg . '_WIDTH';
		$strCfgHeight = $strCfg . '_HEIGHT';

		$intWidth = _xls_get_conf($strCfgWidth, $intDefWidth);
		$intHeight = _xls_get_conf($strCfgHeight, $intDefHeight);

		return array($intWidth, $intHeight);
	}

	public static function GetConfigKey($intImageTypeId) {
		return ImagesType::$ConfigKeyArray[$intImageTypeId];
	}

	public static function ToToken($strImageType) {
		return array_search($strImageType, ImagesType::$NameArray);
	}
}
