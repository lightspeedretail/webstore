<?php

class m150304_201446_WS_3110_remove_schema_config_key extends CDbMigration
{
	public function up()
	{
		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array('key' => 'DATABASE_SCHEMA_VERSION')
		);
	}

	public function down()
	{
		$now = date('Y-m-d H:i:s');

		$this->insert(
			'xlsws_configuration',
			array(
				'title' => 'Database Schema Version',
				'key_name' => 'DATABASE_SCHEMA_VERSION',
				'key_value' => '447',
				'helper_text' => 'Used for tracking schema changes (pre 3.1.6)',
				'sort_order' => 0,
				'modified' => $now,
				'created' => $now,
				'param' => 301,
				'required' => 0
			)
		);

		echo "DATABASE_SCHEMA_VERSION config key replaced";
	}
}