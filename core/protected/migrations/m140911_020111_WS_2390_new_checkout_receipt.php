<?php

class m140911_020111_WS_2390_new_checkout_receipt extends CDbMigration
{
	private static function _pathToMain()
	{
		return
			YiiBase::getPathOfAlias('application').
			DIRECTORY_SEPARATOR . '..' .
			DIRECTORY_SEPARATOR . '..' .
			DIRECTORY_SEPARATOR . 'config/main.php';
	}

	public function up()
	{
		// add new route rules to main.php

		$strMain = file_get_contents(self::_pathToMain());
		$strMain = str_replace("\r", '', $strMain);
		$insert = "'checkout/thankyou/<linkid:[\w\d\-_\.()]+>' => 'checkout/thankyou',";

		if (strpos($strMain, $insert) === false)
		{
			$needle = "'cart/receipt/<getuid:";
			$pos = strpos($strMain, $needle);
			$insert = $insert . "\n\t\t\t\t\t\t";
			$strMain = substr_replace($strMain, $insert, $pos, 0);
			file_put_contents(self::_pathToMain(), $strMain);
		}
	}

	public function down()
	{
		// Idempotent migration. We can go down but the routes are not removed
		echo "Not removing new checkout routes.\n";
		return true;
	}
}