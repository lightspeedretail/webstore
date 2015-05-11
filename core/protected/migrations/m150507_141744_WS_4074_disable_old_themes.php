<?php

class m150507_141744_WS_4074_disable_old_themes extends CDbMigration
{
	public function up()
	{
		$installed = $this->getDbConnection()->createCommand()
			->select('key_value')
			->from('xlsws_configuration')
			->where('key_name = :key', array(':key' => 'INSTALLED'))
			->queryScalar();

		$this->insert(
			'xlsws_configuration',
			array(
				'title' => 'Display Legacy Themes',
				'key_name' => 'ALLOW_LEGACY_THEMES',
				'key_value' => intval($installed),
				'configuration_type_id' => 0,
				'sort_order' => 0,
				'template_specific' => 0,
				'required' => 0
			)
		);

	}

	public function down()
	{
		$this->delete(
			'xlsws_configuration',
			'key_name = :key',
			array('key' => 'ALLOW_LEGACY_THEMES')
		);
	}
}
