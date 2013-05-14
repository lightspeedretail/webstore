<?php

/**
 * ImageType definitions
 *
 * @category   Helpers
 * @package    Images
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @copyright  Copyright &copy; 2013 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @version    3.0
 * @since      2013-05-14

 */


class ImagesType {
	const normal = 0;
	const small = 1;
	const pdetail = 2;
	const mini = 3;
	const listing = 4;
	const category = 5;
	const preview = 6;
	const slider = 7;

	const MaxId = 7;

	public static $NameArray = array(
		0 => 'image',
		1 => 'smallimage',
		2 => 'pdetailimage',
		3 => 'miniimage',
		4 => 'listingimage',
		5 => 'categoryimage',
		6 => 'previewimage',
		7 => 'sliderimage'
	);

	public static $SizeArray = array(
		0 => array(0, 0), // Don't resize image
		1 => array(100, 80),
		2 => array(100, 80),
		3 => array(30, 30),
		4 => array(50, 40),
		5 => array(180, 180),
		6 => array(30, 30),
		7 => array(120, 120),
	);

	public static $ConfigKeyArray = array(
		0 => null,
		1 => 'LISTING_IMAGE',
		2 => 'DETAIL_IMAGE',
		3 => 'MINI_IMAGE',
		4 => 'LISTING_IMAGE',
		5 => 'CATEGORY_IMAGE',
		6 => 'PREVIEW_IMAGE',
		7 => 'SLIDER_IMAGE'
	);

	public static $TokenArray = array(
		0 => 'NORMAL',
		1 => 'SMALL',
		2 => 'PDETAIL',
		3 => 'MINI',
		4 => 'LISTING',
		5 => 'CATEGORY',
		6 => 'PREVIEW',
		7 => 'SLIDER'

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
