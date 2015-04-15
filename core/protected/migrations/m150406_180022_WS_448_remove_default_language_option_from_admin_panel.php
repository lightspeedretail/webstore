<?php

class m150406_180022_WS_448_remove_default_language_option_from_admin_panel extends CDbMigration
{
	public function up()
	{
		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array('key' => 'LANG_CODE')
		);
	}

	public function down()
	{
		$now = date('Y-m-d H:i:s');

		$this->insert(
			'xlsws_configuration',
			array(
				'title' => 'Default Locale (Language) Code',
				'key_name' => 'LANG_CODE',
				'key_value' => 'en',
				'configuration_type_id' => 15,
				'sort_order' => 1,
				'modified' => $now,
				'created' => $now,
				'param' => 1
			)
		);

		echo "LANG_CODE config key replaced";
	}
}