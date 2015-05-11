<?php

class m150429_161521_WS_4070_disable_advanced_payment extends CDbMigration
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
				'title' => 'Display Advanced Payment Methods',
				'key_name' => 'ALLOW_ADVANCED_PAY_METHODS',
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
			array('key' => 'ALLOW_ADVANCED_PAY_METHODS')
		);
	}
}
