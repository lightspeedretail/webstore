<?php

class m150421_202650_WS_4071_disable_php_customization extends CDbMigration
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
				'title' => 'Show Customization Button',
				'key_name' => 'SHOW_CUSTOMIZATION_BUTTON',
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
			array('key' => 'SHOW_CUSTOMIZATION_BUTTON')
		);
	}
}