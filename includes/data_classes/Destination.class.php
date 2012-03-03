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

require(__DATAGEN_CLASSES__ . '/DestinationGen.class.php');

/**
 * The Destination class defined here contains any customized code for the
 * Destination class in the Object Relational Model. It represents the
 * "xlsws_destination" table in the database.
 */
class Destination extends DestinationGen {
	// String representation of the object
	public function __toString() {
		return sprintf('Destination Object %s', $this->intRowid);
	}

	public static function GetDefaultOrdering($sql = false) {
		if ($sql)
			return 'ORDER BY `state` DESC, `zipcode1` DESC';
		else
			return QQ::Clause(
				QQ::OrderBy(
					QQN::Destination()->State, false,
					QQN::Destination()->Zipcode1, false
				)
			);
	}

    public static function LoadDefault() {
        return Destination::QuerySingle(
            QQ::AndCondition(
                QQ::Equal(QQN::Destination()->Country, '*'),
                QQ::Equal(QQN::Destination()->State, '*'), 
                QQ::IsNull(QQN::Destination()->Zipcode1),
                QQ::IsNull(QQN::Destination()->Zipcode2)
            )
        );
    }

	public static function LoadByCountry($strCountry, $blnRestrict = false) {
		if ($blnRestrict) {
			if (Destination::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Destination()->Country, $strCountry),
					QQ::Equal(QQN::Destination()->State, '*')
				))) {
					$arrStates = State::QueryArray(
						QQ::AndCondition(
							QQ::Equal(
								QQN::State()->CountryCode, $strCountry),
							QQ::Equal(
								QQN::State()->Avail, 'Y')
						),
						State::GetDefaultOrdering()
					);

					return Destination::ConvertStatesToDestinations(
						$strCountry, $arrStates
					);
			}
		}

		return Destination::QueryArray(
			QQ::AndCondition(
				QQ::Equal(QQN::Destination()->Country,
				$strCountry)
			),
			Destination::GetDefaultOrdering()
		);
	}

	public static function ConvertStatesToDestinations($country,$arrStates) {
		$arrDests = array();
		foreach($arrStates as $state)
		{
			$dest = new Destination();
			$dest->Country = $country;
			$dest->State = $state->Code;
			$dest->Name = $state->Code;
			$dest->Zipcode1 = "*";
			$dest->Zipcode2 = "*";
			$dest->Taxcode = 0;
			$arrDests[] = $dest;
		}
		return $arrDests;
	}

	/**
	 * Match a given address to the most accurate Destination
	 * @param string $country
	 * @param string $state
	 * @param string $zip
	 * @return object :: The matching destination
	 */
	public static function LoadMatching($country, $state, $zip) {
		$arrDestinations = Destination::LoadByCountry($country);
		$zip = preg_replace('/[^A-Z0-9]/', '',strtoupper($zip));

		foreach ($arrDestinations as $objDestination) {
			if (($objDestination->State == $state) ||
				($objDestination->State == '*')) {

				$zipStart = $objDestination->Zipcode1;
				$zipEnd = $objDestination->Zipcode2;

				if (($zipStart <= $zip && $zipEnd >= $zip) ||
					$zipStart=='' ||
					$zipStart=='*' ||
					$zip=='')
						return $objDestination;
			}
		}
		return false;
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Zipcode1':
				return preg_replace('/[^A-Z0-9]/', '',strtoupper($this->strZipcode1));

			case 'Zipcode2':
				return preg_replace('/[^A-Z0-9]/', '',strtoupper($this->strZipcode2));

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}
}
