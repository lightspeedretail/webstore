<?php
	/**
	 * The ImagesType class defined here contains
	 * code for the ImagesType enumerated type.  It represents
	 * the enumerated values found in the "xlsws_images_type" table
	 * in the database.
	 * 
	 * To use, you should use the ImagesType subclass which
	 * extends this ImagesTypeGen class.
	 * 
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the ImagesType class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 */
	abstract class ImagesTypeGen extends QBaseClass {
		const image = 1;
		const smallimage = 2;
		const pdetailimage = 3;
		const miniimage = 4;
		const listingimage = 5;
		const categoryimage = 6;
		const previewimage = 7;
		const sliderimage = 8;

		const MaxId = 8;

		public static $NameArray = array(
			1 => 'image',
			2 => 'smallimage',
			3 => 'pdetailimage',
			4 => 'miniimage',
			5 => 'listingimage',
			6 => 'categoryimage',
			7 => 'previewimage',
			8 => 'sliderimage');

		public static $TokenArray = array(
			1 => 'image',
			2 => 'smallimage',
			3 => 'pdetailimage',
			4 => 'miniimage',
			5 => 'listingimage',
			6 => 'categoryimage',
			7 => 'previewimage',
			8 => 'sliderimage');

		public static $ExtraColumnNamesArray = array(
			'Width',
			'Height');

		public static $ExtraColumnValuesArray = array(
			1 => array (
						'Width' => '0',
						'Height' => '0'),
			2 => array (
						'Width' => '90',
						'Height' => '90'),
			3 => array (
						'Width' => '256',
						'Height' => '256'),
			4 => array (
						'Width' => '30',
						'Height' => '30'),
			5 => array (
						'Width' => '40',
						'Height' => '40'),
			6 => array (
						'Width' => '180',
						'Height' => '180'),
			7 => array (
						'Width' => '30',
						'Height' => '30'),
			8 => array (
						'Width' => '90',
						'Height' => '90'));


		public static function ToString($intImagesTypeId) {
			switch ($intImagesTypeId) {
				case 1: return 'image';
				case 2: return 'smallimage';
				case 3: return 'pdetailimage';
				case 4: return 'miniimage';
				case 5: return 'listingimage';
				case 6: return 'categoryimage';
				case 7: return 'previewimage';
				case 8: return 'sliderimage';
				default:
					throw new QCallerException(sprintf('Invalid intImagesTypeId: %s', $intImagesTypeId));
			}
		}

		public static function ToToken($intImagesTypeId) {
			switch ($intImagesTypeId) {
				case 1: return 'image';
				case 2: return 'smallimage';
				case 3: return 'pdetailimage';
				case 4: return 'miniimage';
				case 5: return 'listingimage';
				case 6: return 'categoryimage';
				case 7: return 'previewimage';
				case 8: return 'sliderimage';
				default:
					throw new QCallerException(sprintf('Invalid intImagesTypeId: %s', $intImagesTypeId));
			}
		}

		public static function ToWidth($intImagesTypeId) {
			if (array_key_exists($intImagesTypeId, ImagesType::$ExtraColumnValuesArray))
				return ImagesType::$ExtraColumnValuesArray[$intImagesTypeId]['Width'];
			else
				throw new QCallerException(sprintf('Invalid intImagesTypeId: %s', $intImagesTypeId));
		}

		public static function ToHeight($intImagesTypeId) {
			if (array_key_exists($intImagesTypeId, ImagesType::$ExtraColumnValuesArray))
				return ImagesType::$ExtraColumnValuesArray[$intImagesTypeId]['Height'];
			else
				throw new QCallerException(sprintf('Invalid intImagesTypeId: %s', $intImagesTypeId));
		}

	}
?>