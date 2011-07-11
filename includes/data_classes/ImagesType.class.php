<?php

    abstract class ImagesType extends QBaseClass {
        const normal = 0;
        const small = 1;
        const pdetail = 2;
        const mini = 3;
        const listing = 4;

        const MaxId = 4;

        public static $NameArray = array(
            0 => 'image',
            1 => 'smallimage',
            2 => 'pdetailimage',
            3 => 'miniimage',
            4 => 'listingimage',
        );

        public static $SizeArray = array(
            0 => array(0, 0), // Don't resize image
            1 => array(100, 80),
            2 => array(100, 80),
            3 => array(30, 30),
            4 => array(50, 40),
        );

        public static $ConfigKeyArray = array(
            0 => null,
            1 => 'LISTING_IMAGE',
            2 => 'DETAIL_IMAGE',
            3 => 'MINI_IMAGE',
            4 => 'LISTING_IMAGE'
        );

        public static $TokenArray = array(
            0 => 'NORMAL',
            1 => 'SMALL',
            2 => 'PDETAIL',
            3 => 'MINI',
            4 => 'LISTING',
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

?>
