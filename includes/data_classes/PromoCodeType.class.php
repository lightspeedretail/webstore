<?php

    abstract class PromoCodeType extends QBaseClass {
        const Flat = 0;
        const Percent = 1;

		const MaxId = 1;

        public static $NameArray = array(
            0 => '%',
            1 => '$',
        );

        public static $TokenArray = array(
            0 => '%',
            1 => '$',
        );

        public static function Display($intPromoCodeTypeId, $intAmount) {
            switch ($intPromoCodeTypeId) {
                case 0:
                    return _xls_currency($intAmount);
                case 1:
                    return sprintf('%s%s', $intAmount, '%');
				default:
                    throw new QCallerException(
                        sprintf(_sp('Invalid intPromoCodeTypeId') . 
                            ': %s', $intPromoCodeTypeId));
            }
        }

		public static function ToString($intPromoCodeTypeId) {
			switch ($intPromoCodeTypeId) {
                case 0:
                    $locale = localeconv();
                    return $locale['currency_symbol'];
                case 1: return '%';
				default:
                    throw new QCallerException(
                        sprintf(_sp('Invalid intPromoCodeTypeId') . 
                            ': %s', $intPromoCodeTypeId));
			}
		}

		public static function ToToken($intPromoCodeTypeId) {
			switch ($intPromoCodeTypeId) {
                case 0:
                    $locale = localeconv();
                    return $locale['currency_symbol'];
                case 1: return 'Percent';
				default:
                    throw new QCallerException(
                        sprintf(_sp('Invalid intPromoCodeTypeId') . 
                            ': %s', $intPromoCodeTypeId));
			}
		}

	}
?>
