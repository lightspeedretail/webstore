<?php

class m150408_134939_WS_448_add_en_to_default_language_list extends CDbMigration
{
	public function up()
	{
		$sql = "SELECT key_value FROM xlsws_configuration WHERE key_name = 'LANGUAGES';";
		$queryResult = $this->dbConnection->createCommand($sql)->queryRow();
		if (empty($queryResult) == false)
		{
			$arrLanguages = explode(',', $queryResult['key_value']);
			if (in_array('en', $arrLanguages) == false)
			{
				array_unshift($arrLanguages, 'en');
				$languages = implode(',', $arrLanguages);

				$this->update(
					'xlsws_configuration',
					array('key_value' => $languages),
					"key_name = 'LANGUAGES'"
				);
			}
		}
	}

	public function down()
	{
		echo "m150408_134939_WS_448_add_en_to_default_language_list does not support migration down.\n";
		return false;
	}
}