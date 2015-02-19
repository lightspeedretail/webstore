<?php

class m140919_173004_WS_2076_shipping_estimator extends CDbMigration
{
	private static function _pathToMain()
	{
		return YiiBase::getPathOfAlias('application').
			DIRECTORY_SEPARATOR . '..' .
			DIRECTORY_SEPARATOR . '..' .
			DIRECTORY_SEPARATOR . 'config/main.php';
	}

	public function up()
	{
		$mainConfig = file_get_contents(self::_pathToMain());

		// Remove DOS-style line endings if they exist.
		$strMain = str_replace("\r", '', $mainConfig);

		$textToInsert = "\t\t'application.extensions.wsshippingestimator.WsShippingEstimator',\n";

		if (strpos($strMain, $textToInsert) === false)
		{
			echo "Adding WsShippingEstimator component to config/main.php...\n";
			$needle = "\t\t'application.extensions.wspayment.WsPayment',\n";

			$strMain = str_replace(
				$needle,
				$needle . $textToInsert,
				$strMain,
				$count
			);

			file_put_contents(
				self::_pathToMain(),
				$strMain
			);
		} else {
			echo "Nothing to do with config/main.php...\n";
		}
	}

	public function down()
	{
		// The migration is idempotent so we we can go down.
		// It won't remove the component though.
		echo "Not removing WsShippingEstimator component.\n";
		return true;
	}
}
